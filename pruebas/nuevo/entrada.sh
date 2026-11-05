#!/bin/sh
# Carpetas de archivos compartidas con el sistema original (volumen /datos).
set -e
for d in archivos archivosPadron actaFirmada acta; do
    mkdir -p "/datos/$d"
    [ -e "/var/www/html/public/$d" ] || ln -s "/datos/$d" "/var/www/html/public/$d"
done
chown -R www-data:www-data /datos
# Apache no hereda el entorno del contenedor en $_SERVER; Laravel lee el .env.
env | grep -E '^(APP_|DB_|MAIL_|LOG_|SESSION_|CACHE_|QUEUE_|RENAPO_)' | sed 's/=\(.*\)/="\1"/' > /var/www/html/.env
chown www-data:www-data /var/www/html/.env
exec apache2-foreground
