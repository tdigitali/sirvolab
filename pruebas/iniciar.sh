#!/usr/bin/env bash
# Levanta el entorno de pruebas de SIRVOLAB con Podman (sin root).
#   ./iniciar.sh            construye, levanta y (la primera vez) carga datos inventados
#   ./iniciar.sh --reiniciar-datos   borra las bases y vuelve a generar los datos
#   ./iniciar.sh --sin-construir     levanta con las imágenes ya construidas
set -euo pipefail
REINICIAR=0; CONSTRUIR=1
for opcion in "$@"; do
    case "$opcion" in
        --reiniciar-datos) REINICIAR=1 ;;
        --sin-construir) CONSTRUIR=0 ;;
        *) echo "Opción desconocida: $opcion" >&2; exit 1 ;;
    esac
done
cd "$(dirname "$0")"
REPO="$(git rev-parse --show-toplevel)"
# Commit con el código original (Laravel 4.2), para comparar contra el migrado.
COMMIT_LEGADO=a9826c7

# La salida de podman-compose (incluye contraseñas) va a .iniciar.log.
compose() { podman-compose -f compose.yaml "$@" >>.iniciar.log 2>&1 || { tail -40 .iniciar.log; exit 1; }; }
contenedor() { podman ps -q --filter label=com.docker.compose.project=sirvolab-pruebas --filter "label=com.docker.compose.service=$1" | head -1; }
en() { local c; c="$(contenedor "$1")"; shift; podman exec -i "$c" "$@"; }
aleatorio() { tr -dc 'A-Za-z0-9' </dev/urandom | head -c "$1" || true; }
paso() { printf '\n==> %s\n' "$*"; }

# 1. Configuración con contraseñas y llave aleatorias (solo la primera vez).
if [ ! -f .env ]; then
    paso "Creando pruebas/.env"
    sed -e "s|^APP_KEY=.*|APP_KEY=$(aleatorio 32)|" \
        -e "s|^DB_ROOT_PASSWORD=.*|DB_ROOT_PASSWORD=$(aleatorio 24)|" \
        -e "s|^DB_PASSWORD=.*|DB_PASSWORD=$(aleatorio 24)|" \
        .env.example > .env
    chmod 600 .env
fi
set -a; . ./.env; set +a

# 2. Código original extraído del historial de git.
if [ ! -d viejo/codigo ]; then
    paso "Extrayendo el código original (commit $COMMIT_LEGADO)"
    mkdir -p viejo/codigo
    git -C "$REPO" archive "$COMMIT_LEGADO" | tar -x -C viejo/codigo
fi

# 3. Imágenes.
if [ "$CONSTRUIR" = 1 ]; then
    paso "Construyendo imagen del sistema original (PHP 5.6)"
    podman build -t localhost/sirvolab-viejo -f viejo/Containerfile viejo
    paso "Construyendo imagen del sistema migrado (PHP 8.3)"
    podman build -t localhost/sirvolab-nuevo -f nuevo/Containerfile "$REPO"
fi

# 4. Contenedores.
if [ "$REINICIAR" = 1 ]; then
    paso "Borrando bases de datos y archivos"
    compose down -v
fi
paso "Levantando contenedores"
: > .iniciar.log
compose up -d

paso "Esperando a MariaDB"
for _ in $(seq 1 60); do
    en db mariadb-admin ping -h 127.0.0.1 -uroot -p"$DB_ROOT_PASSWORD" --silent >/dev/null 2>&1 && break
    sleep 2
done
sql() { en db mariadb -uroot -p"$DB_ROOT_PASSWORD" -N -e "$1"; }
for _ in $(seq 1 30); do
    [ "$(sql "SELECT COUNT(*) FROM information_schema.schemata WHERE schema_name IN ('sirvolab_viejo','sirvolab_nuevo')" 2>/dev/null | tr -d '\r')" = "2" ] && break
    sleep 2
done

# 5. Esquema y datos inventados (solo si la base está vacía).
if [ "$(sql "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='sirvolab_nuevo' AND table_name='usuario'" | tr -d '\r')" = "0" ]; then
    if [ -f datos/esquema.sql ]; then
        paso "Cargando el esquema real (datos/esquema.sql)"
        en db mariadb -uroot -p"$DB_ROOT_PASSWORD" sirvolab_nuevo < datos/esquema.sql
    else
        paso "Creando el esquema inferido del código (no hay datos/esquema.sql)"
        en nuevo php artisan migrate --path=tests/database/migrations --force
    fi

    paso "Generando datos inventados"
    en nuevo php artisan db:seed --class=DatosDePruebaSeeder --force

    paso "Copiando los mismos datos a la base del sistema original"
    en db sh -c 'mariadb-dump -uroot -p"$MARIADB_ROOT_PASSWORD" sirvolab_nuevo | mariadb -uroot -p"$MARIADB_ROOT_PASSWORD" sirvolab_viejo'
    # Cada sistema arma los enlaces de sus correos con su propia dirección.
    sql "UPDATE sirvolab_viejo.ambiente SET nombre='${URL_VIEJO%/}/' WHERE id=1"
    sql "UPDATE sirvolab_nuevo.ambiente SET nombre='${URL_NUEVO%/}/' WHERE id=1"
fi

cat <<EOF

Listo. Desde su computadora abra un túnel SSH al servidor:

    ssh -L 8080:localhost:8080 -L 8081:localhost:8081 -L 8025:localhost:8025 usuario@servidor

y entre a:
    Sistema original (PHP 5.6):  ${URL_VIEJO}
    Sistema migrado  (PHP 8.3):  ${URL_NUEVO}
    Correos (Mailpit):           http://localhost:8025

Usuarios (contraseña Pruebas2026!):
    admin@pruebas.test               Administrador SIRVOLAB
    cN.procesos@pruebas.test         Administrador de procesos de la convocatoria N
    cN.comite1@pruebas.test          Comité
    cN.validador1@pruebas.test       Validador
  (N = 1..7; la convocatoria N está en la fase indicada en su nombre)
EOF
