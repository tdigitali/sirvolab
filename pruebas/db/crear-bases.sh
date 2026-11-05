#!/bin/sh
# Crea las dos bases (una por sistema) con el mismo juego de caracteres
# que producción. Se ejecuta solo la primera vez que se crea el volumen.
mariadb -uroot -p"$MARIADB_ROOT_PASSWORD" <<SQL
CREATE DATABASE IF NOT EXISTS sirvolab_viejo CHARACTER SET utf8 COLLATE utf8_unicode_ci;
CREATE DATABASE IF NOT EXISTS sirvolab_nuevo CHARACTER SET utf8 COLLATE utf8_unicode_ci;
GRANT ALL PRIVILEGES ON sirvolab_viejo.* TO '$MARIADB_USER'@'%';
GRANT ALL PRIVILEGES ON sirvolab_nuevo.* TO '$MARIADB_USER'@'%';
FLUSH PRIVILEGES;
SQL
