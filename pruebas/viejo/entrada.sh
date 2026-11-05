#!/bin/sh
# Carpetas de archivos compartidas con el sistema nuevo (volumen /datos).
set -e
for d in archivos archivosPadron actaFirmada acta; do
    mkdir -p "/datos/$d"
    [ -e "/var/www/html/public/$d" ] || ln -s "/datos/$d" "/var/www/html/public/$d"
done
chown -R www-data:www-data /datos
exec apache2-foreground
