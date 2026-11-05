<?php
// Configuración de pruebas del sistema original (entorno 'local').
return array(
    'debug' => true,
    'url'   => getenv('URL_VIEJO') ?: 'http://localhost:8080',
    // Misma llave que el sistema nuevo: los enlaces cifrados sirven en ambos.
    'key'   => getenv('APP_KEY'),
);
