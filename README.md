# SIRVOLAB

Sistema Remoto de Votación Laboral (registro de padrón, validación de
votantes, consulta en línea y actas de resultados).

Esta rama contiene la **migración de Laravel 4.2 / PHP 5 a Laravel 13 /
PHP 8.3**. El detalle de qué cambió, por qué y qué falta revisar está en
[`MIGRACION.md`](MIGRACION.md).

## Requisitos

- PHP **8.3 o superior** con extensiones: `pdo_mysql`, `mbstring`, `curl`,
  `zip`, `gd`, `xml`, `fileinfo`, `openssl`.
- Composer 2.
- MySQL / MariaDB con la base de datos existente de SIRVOLAB.

## Instalación

```bash
composer install --no-dev --optimize-autoloader
```

```bash
cp .env.example .env
```

Edite `.env`:

- `APP_KEY`: **use la misma llave** que tenía `app/config/app.php` en el
  servidor actual. Si se cambia, los enlaces de voto/registro ya enviados por
  correo y los datos cifrados dejan de funcionar.
- `DB_*`, `MAIL_*` (SendGrid) y `RENAPO_TOKEN`.

Copie del servidor actual las carpetas de archivos (no están en git porque
contienen datos personales):

- `public/archivos/` (INE, selfies y `aviso_privacidad_SIRVOLAB.pdf`)
- `public/archivosPadron/`
- `public/actaFirmada/`
- `public/acta/` (incluye las plantillas `Acta_de_Resultados.docx` y
  `Acta_de_Resultados_Cargos.docx`)

El servidor web debe apuntar a `public/` y poder escribir en `storage/`,
`bootstrap/cache/` y las carpetas anteriores.

```bash
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## Desarrollo y pruebas

```bash
composer install
```

```bash
php artisan test
```

Las pruebas usan SQLite en memoria con un esquema **inferido del código**
(`tests/database/migrations`), simulan RENAPO y no envían correos reales.
Ese esquema no es el oficial: no lo ejecute contra la base de producción.
