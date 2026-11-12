#!/bin/sh
# Falla explícitamente si las credenciales requeridas no fueron configuradas.
set -eu

: "${MYSQL_EXPORTER_USER:?Debes definir MYSQL_EXPORTER_USER}"
: "${MYSQL_EXPORTER_PASSWORD:?Debes definir MYSQL_EXPORTER_PASSWORD}"

# Mantiene las credenciales fuera de argumentos visibles y limita permisos del archivo.
# El archivo temporal solo existe dentro del contenedor y se protege con umask 077.
umask 077
printf '[client]\nuser=%s\npassword=%s\n' \
  "$MYSQL_EXPORTER_USER" \
  "$MYSQL_EXPORTER_PASSWORD" \
  > /tmp/.mysqld_exporter.cnf

# Ejecuta el exporter en el puerto interno consultado por Prometheus.
# "$@" conserva opciones adicionales sin dividirlas ni reinterpretarlas.
exec /bin/mysqld_exporter \
  --config.my-cnf=/tmp/.mysqld_exporter.cnf \
  --web.listen-address=:9104 \
  "$@"
