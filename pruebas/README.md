# Servidor de pruebas de SIRVOLAB (Podman)

Levanta, en un solo servidor y sin permisos de root, el **sistema original**
(Laravel 4.2 / PHP 5.6) y el **sistema migrado** (Laravel 13 / PHP 8.3) lado a
lado, con **datos inventados**, para comparar que se comporten igual.

| Contenedor | Qué es | Dirección (por túnel SSH) |
|---|---|---|
| `viejo` | Sistema original, PHP 5.6 | http://localhost:8080 |
| `nuevo` | Sistema migrado, PHP 8.3 | http://localhost:8081 |
| `mailpit` | Bandeja que atrapa **todos** los correos | http://localhost:8025 |
| `db` | MariaDB 10.5 (igual que producción), una base por sistema | — |
| `renapo` | Simulador del servicio de CURP | — |

- Ningún correo sale a internet: ambos sistemas envían a Mailpit.
- No se usa RENAPO real: el simulador inventa nombres a partir de la CURP.
  Para probar errores use CURPs que empiecen con `BAJA` (dada de baja),
  `DEFU` (defunción), `NOEX` (no encontrada) o `VACI` (servicio caído).
- Ambos sistemas comparten la misma llave (`APP_KEY`), así que un enlace de
  voto generado por uno sirve en el otro (se verificó: un enlace generado por
  el sistema migrado abre y vota en el original).
- Los puertos solo escuchan en `127.0.0.1`: desde internet no se ve nada.

## 1. Preparar el servidor (una sola vez)

Ubuntu Server 24.04 LTS. Como administrador:

```bash
sudo apt update && sudo apt install -y podman podman-compose git
```

Cree un usuario **sin sudo** para las pruebas (ese usuario no podrá elevar
privilegios aunque controle los contenedores):

```bash
sudo adduser pruebas
```

Para que sus contenedores sigan corriendo cuando cierre la sesión SSH:

```bash
sudo loginctl enable-linger pruebas
```

Firewall con solo SSH abierto:

```bash
sudo ufw allow OpenSSH && sudo ufw enable
```

## 2. Levantar el entorno

Entre como el usuario `pruebas` y clone el repositorio:

```bash
git clone https://github.com/tdigitali/sirvolab.git
```

```bash
cd sirvolab/pruebas && ./iniciar.sh
```

La primera vez tarda varios minutos (descarga imágenes y dependencias). La
salida detallada de Podman queda en `pruebas/.iniciar.log`. El script:

1. Crea `pruebas/.env` con contraseñas y llave aleatorias.
2. Extrae el código original del historial de git.
3. Construye las imágenes y levanta los contenedores.
4. Crea el esquema, genera los datos inventados y copia los mismos datos a la
   base del sistema original.

## 3. Entrar desde su computadora

Abra un túnel SSH (déjelo abierto mientras prueba):

```bash
ssh -L 8080:localhost:8080 -L 8081:localhost:8081 -L 8025:localhost:8025 pruebas@IP-DEL-SERVIDOR
```

Usuarios (contraseña `Pruebas2026!`):

| Correo | Rol |
|---|---|
| `admin@pruebas.test` | Administrador SIRVOLAB |
| `cN.procesos@pruebas.test` | Administrador de procesos de la convocatoria N |
| `cN.comite1@pruebas.test`, `cN.comite2@…` | Comité |
| `cN.validador1@pruebas.test`, `cN.validador2@…` | Validador |

Hay 7 convocatorias, una en cada fase: `Prueba 1 - Configuración` …
`Prueba 6 - Cerrada`, y `Prueba 7`, una consulta de elección de directiva
(se eligen dos candidatos). La convocatoria 6 está cerrada, así que sus
usuarios están desactivados, igual que en el sistema real.

Para votar como trabajador: entre a Mailpit después de enviar un enlace de
voto desde el sistema, o copie el enlace desde la lista del padrón.

## 4. Operación diaria

| Para… | Comando (en `sirvolab/pruebas`) |
|---|---|
| Ver el estado | `podman-compose ps` |
| Ver errores del sistema migrado | `podman-compose logs -f nuevo` |
| Ver errores del sistema original | `podman-compose exec viejo tail -f app/storage/logs/laravel.log` |
| Actualizar al último código | `git pull && ./iniciar.sh` |
| Empezar de cero con datos nuevos | `./iniciar.sh --reiniciar-datos` |
| Reiniciar sin reconstruir imágenes | `./iniciar.sh --sin-construir` |
| Apagar todo | `podman-compose down` |

## Usar el esquema real (recomendado)

Las tablas se crean con un esquema **deducido del código**. Para probar con la
estructura exacta de producción, exporte solo la estructura (sin datos
personales) y los catálogos:

```bash
mysqldump -u USUARIO -p --no-data sirvolab > esquema.sql
```

```bash
mysqldump -u USUARIO -p --no-create-info sirvolab rol convocatoria_estatus entidad_federativa motivo_rechazo >> esquema.sql
```

Revise que el archivo no tenga datos personales, cópielo a
`pruebas/datos/esquema.sql` (git lo ignora) y ejecute
`./iniciar.sh --reiniciar-datos`.

## Notas de seguridad

- El contenedor `viejo` usa PHP 5.6, que ya no recibe parches de seguridad.
  Por eso nada se publica a internet; se entra solo por túnel SSH.
- `pruebas/.env` tiene las contraseñas del entorno de pruebas. No use aquí la
  `APP_KEY` ni las contraseñas de producción.

## Plantilla para otros sistemas

Esta carpeta sirve de modelo para los demás sistemas en PHP 5 y 7.4: una
imagen por versión de PHP (cambie `php:5.6-apache` por `php:7.4-apache`, por
ejemplo), MariaDB con la versión de producción, Mailpit para los correos, y
simuladores para los servicios externos. Use otros puertos (por ejemplo 8090,
8091) para que no choquen.
