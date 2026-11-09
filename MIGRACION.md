# Migración de SIRVOLAB: Laravel 4.2 / PHP 5 → Laravel 13 / PHP 8.3

## Resumen

| | Antes | Ahora |
|---|---|---|
| PHP | 5.x | 8.3+ |
| Framework | Laravel 4.2 (sin soporte desde 2015) | Laravel 13 |
| Excel | PHPExcel copiado en `app/libraries` (abandonado, no corre en PHP 8) | PhpSpreadsheet 5 vía Composer |
| Formularios | `Form::` de illuminate/html | `App\Support\Html\FormBuilder` (réplica compatible) |
| Credenciales | Escritas en `app/config/*.php` y en controladores | Variables de entorno (`.env`) |
| Pruebas | Ninguna | 80 pruebas automatizadas |

El objetivo fue **conservar el comportamiento**: mismas rutas, mismas
pantallas (escritorio y móvil), mismos mensajes y mismas reglas de negocio.
Los cambios de comportamiento son intencionales y se listan abajo.

### Historial de commits

1. **Código legado original** con credenciales redactadas (punto de partida).
2. **Mover archivos** a la estructura de Laravel 13 y normalizar fin de línea
   (sin cambios de contenido): permite ver la migración como diff real.
3. **Migración**: todos los cambios de código.

Para revisar el diff de la migración:

```bash
git diff HEAD~1 HEAD -M -- app resources routes
```

## Qué se cambió

### Estructura
- `app/controllers` → `app/Http/Controllers`, `app/models` → `app/Models`,
  `app/views` → `resources/views`, `app/routes.php` → `routes/web.php`.
- Configuración, arranque y `public/index.php` de Laravel 13.

### Controladores
- Namespace `App\Http\Controllers` e importación de modelos y fachadas.
- `Input::all()/get()/file()/hasFile()` → `Request::...`.
- `str_random()` → `Str::random()` (también en vistas).
- La detección de móvil (copiada ~70 veces) quedó en
  `BaseController::detectarMovil()`; regresa el mismo `0/1` que `$detector`.
- `ini_set('max_execution_time')` al inicio del archivo → constructor.
- RENAPO: el código duplicado en dos controladores quedó en
  `App\Services\Renapo` con los mismos códigos de retorno; URL y token salen
  de `.env`.
- Descargas de Excel: se regresa una respuesta de descarga (antes `header()`
  + `exit`). El archivo sigue siendo `.xls` (Excel 97-2003).
- Lectura del padrón con PhpSpreadsheet. Ojo: PhpSpreadsheet numera columnas
  desde 1 (PHPExcel desde 0); se ajustó.
- Se eliminó `PHPExcelController` (demo sin ruta).

### Modelos
- Namespace `App\Models`, `SoftDeletes` en lugar de `SoftDeletingTrait`, sin
  las interfaces de autenticación que no usaban. `Usuario` extiende el
  `Authenticatable` de Laravel y sigue siendo el modelo de inicio de sesión.

### Vistas (Blade)
- En Laravel 4 `{{ }}` imprimía **sin escapar**; en Laravel 5+ escapa.
  - Lo que genera HTML (`Form::`, `->links()`, textos con etiquetas) se
    cambió a `{!! !!}`: se ve igual que antes.
  - Los datos (nombres, CURP, correos…) quedan en `{{ }}`: se ven igual y
    ahora están protegidos contra XSS.
- Los modelos usados en vistas se califican como `\App\Models\...`.
- Paginación con el marcado de Bootstrap 3, igual que Laravel 4.
- Se borraron los respaldos `menu/*.blade.php_31012025`.

### Configuración
- Zona horaria `America/Mexico_City` (como antes).
- MySQL con `utf8` / `utf8_unicode_ci` y **sin modo estricto** (Laravel 4 no
  lo activaba; Laravel 13 sí por defecto). Configurable con `DB_STRICT`.
- Sesión y caché en archivos: no requieren tablas nuevas.
- Se desactivaron los middleware `TrimStrings` y `ConvertEmptyStringsToNull`
  porque en Laravel 4 no existían y las validaciones (p. ej. `between` en campos
  opcionales) dependen de recibir `''`.
- El filtro `auth` es ahora el middleware `App\Http\Middleware\Autenticado`
  (misma redirección y mismo mensaje).

## Compatibilidad con producción (verificada con pruebas)

- **Enlaces ya enviados por correo y datos cifrados**: Laravel 4.2 cifraba
  con mcrypt RIJNDAEL-128/CBC; el formato es idéntico a AES-256-CBC de
  Laravel 13. Con la **misma `APP_KEY`** los enlaces antiguos siguen
  funcionando (`CompatibilidadLaravel4Test`).
- **Contraseñas**: los hashes bcrypt de Laravel 4 se aceptan sin cambios.
- **Sesiones**: al cambiar de versión, los usuarios deberán iniciar sesión de
  nuevo (una sola vez).

## Errores encontrados

### Corregidos (fallaban o fallarían en PHP 8)
- `compact("x", $x)` en 36 lugares: en PHP 8 lanza error. Ahora `compact("x")`.
- `RegistroValidacionController::validacion()` usaba `compact("convocatoria")`
  sin definir la variable → error al terminar el periodo de registro.
- `registrarseUpdate()` (móvil): usaba `compact("convocatoriaPadronMovil")`
  (variable inexistente) y la vista de escritorio.
- Varios métodos usaban `$detector` o `$mensaje` sin definirlos en ramas de
  error (`asignarComiteUpdate`, `cambiaPermiso`, `eliminaAsignacion`,
  `cargaPadronUpdate`, `enviarTokenVoto…`).
- El mensaje de error de la carga de padrón podía fallar al concatenar un
  arreglo.
- `new ZipArchive` y `catch (Exception)` dentro de un namespace (se importan).
- El correo `envioEnlaceUsuarioInterno` fallaba siempre al dar de alta
  administradores y validadores (la plantilla pedía `$convocatoria` y no se
  le enviaba). **Esto ya fallaba en la versión anterior**: se mostraba la
  pantalla de falla de correo.
- Las carpetas de carga ahora se crean de forma recursiva (antes fallaba si
  no existía la carpeta padre, p. ej. en una instalación nueva).

### Detectados pero NO cambiados (decisión de negocio)
- `if(!$convocatoria->convocatoria_estatus_id == 4)` (y variantes con 2) en
  `UsuarioController` y `cargaPadron`: por precedencia de operadores nunca
  bloquea. En la práctica se pueden enviar enlaces de voto/edición fuera de
  la fase correspondiente. Probablemente se quería `!=`.
- `cambiarContrasenaSave` y `signIn`: si el usuario está desactivado se le
  muestra el mensaje, **pero la sesión queda iniciada**.
- La ruta `/preguntas/` apunta a un método que no existe.
- La vista `rowquery.blade.php` envía consultas a `/admin/executeRowQuery/`
  (sin ruta). Conviene borrarla.

## Recomendaciones de seguridad (pendientes)

1. **Rotar credenciales**: la contraseña de MySQL, las llaves de SendGrid,
   la contraseña de aplicación de Gmail y el token de RENAPO estaban en el
   código fuente. Considérelas expuestas.
2. Las fotos de INE y selfies se guardan en `public/archivos/`: cualquiera
   con la URL puede verlas. Moverlas a `storage/` y servirlas con permiso.
3. Las actas generadas también quedan en `public/acta/`.
4. Acciones que modifican datos por GET (eliminar votante, cerrar
   convocatoria, cambiar permisos…): deberían ser POST con CSRF.
5. Faltan validaciones de pertenencia: p. ej. `/usuario/edit/{id}`,
   `/convocatoria/eliminaValidador/{id}` o `/validacion/rechazo/{id}`
   aceptan IDs de otras convocatorias.
6. Se guarda la contraseña inicial cifrada de forma reversible
   (`usuario.metadata`) y los administradores nuevos reciben una contraseña
   fija escrita en el código.
7. `/convocatoria/pruebaRENAPO/` es un endpoint de depuración con una CURP
   real fija, accesible a cualquier usuario con sesión. Conviene eliminarlo.

## Pruebas automatizadas

`php artisan test` (83 pruebas, ~6 s). Las pruebas **siempre** usan SQLite en
memoria: `phpunit.xml` fuerza esos valores aunque el sistema tenga otras
variables de entorno, y `tests/TestCase.php` se niega a reiniciar cualquier
otra base. Sin esto, correr las pruebas en un contenedor o servidor con
`DB_CONNECTION=mysql` en el entorno borraba las tablas de esa base.

- `RecorridoDePantallasTest`: todas las pantallas GET con los 4 roles, en
  las 5 fases de la convocatoria, en escritorio y móvil, sin errores 500.
- `AdministracionTest`: convocatorias, validaciones y mensajes, alta de
  usuarios y correo, comité con RENAPO, carga de padrón desde Excel,
  descarga de Excel, autorizaciones con token, cierre.
- `RegistroYVotacionTest`: registro con imágenes, validación y rechazo,
  voto, voto nulo, voto por dos cargos, fuera de periodo, acta en Word.
- `CompatibilidadLaravel4Test`: cifrado y contraseñas de Laravel 4, escape
  de HTML, CSRF y el HTML que genera `Form::`.

## Qué no se pudo verificar aquí

- **Contra la base de datos real**: no se tenía su esquema. Las pruebas usan
  un esquema inferido del código. Antes de salir a producción conviene probar
  contra una copia de la base de MySQL.
- **Envío real de correo (SendGrid) y consulta real a RENAPO**.
- **Plantillas de actas** (`public/acta/*.docx`) y aviso de privacidad: no
  venían en el paquete.

Plan sugerido: montar esta versión en un servidor de pruebas con una copia
de la base y de `public/archivos`, recorrer un ciclo completo de
convocatoria y después cambiar en producción conservando la `APP_KEY`.
