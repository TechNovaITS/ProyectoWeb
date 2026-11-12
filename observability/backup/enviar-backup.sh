#!/bin/sh
# Detiene el proceso ante errores de sintaxis o variables obligatorias ausentes.
set -eu

: "${BACKUP_IIS_URL:?Define BACKUP_IIS_URL con la URL HTTPS de la carpeta WebDAV de IIS}"
: "${BACKUP_IIS_USERNAME:?Define BACKUP_IIS_USERNAME para la cuenta de carga de IIS}"
: "${BACKUP_IIS_PASSWORD:?Define BACKUP_IIS_PASSWORD para la cuenta de carga de IIS}"
: "${BACKUP_INTERVAL_SECONDS:=86400}"

# Rechaza HTTP para que las credenciales WebDAV no viajen sin cifrar.
case "$BACKUP_IIS_URL" in
  https://*) ;;
  *)
    echo "Error: BACKUP_IIS_URL debe usar HTTPS para proteger las credenciales." >&2
    exit 1
    ;;
esac

# Evita que un intervalo inválido cause un ciclo de carga excesivo o sin espera.
case "$BACKUP_INTERVAL_SECONDS" in
  ''|*[!0-9]*|0)
    echo "Error: BACKUP_INTERVAL_SECONDS debe ser un entero positivo." >&2
    exit 1
    ;;
esac

# Cada ejecución usa un nombre UTC único para evitar sobrescribir copias anteriores.
# Los errores se informan y se reintentan en el siguiente intervalo.
umask 077

while :
do
  fecha=$(date -u +%Y%m%dT%H%M%SZ)
  archivo="/tmp/observabilidad-${fecha}.tar.gz"
  destino="${BACKUP_IIS_URL%/}/$(basename "$archivo")"

  echo "Creando copia de observabilidad ${fecha}..."
  # Los directorios de datos y la configuración se archivan como grupos separados.
  if tar -czf "$archivo" \
    -C /backup-data grafana prometheus loki alertmanager alloy \
      suricata-config suricata-data suricata-logs \
    -C /backup-config observability
  then
    # --fail convierte respuestas HTTP de error en fallos visibles del comando.
    if curl --fail --silent --show-error \
      --retry 3 \
      --retry-delay 5 \
      --user "${BACKUP_IIS_USERNAME}:${BACKUP_IIS_PASSWORD}" \
      --upload-file "$archivo" \
      "$destino"
    then
      echo "Copia enviada correctamente a ${destino}."
    else
      echo "Error: no se pudo enviar la copia a IIS; se reintentará en el próximo intervalo." >&2
    fi
  else
    echo "Error: no se pudo crear el archivo de copia; se reintentará en el próximo intervalo." >&2
  fi

  # Borra el temporal local incluso si la transferencia falló; IIS conserva la copia.
  rm -f "$archivo"
  sleep "$BACKUP_INTERVAL_SECONDS"
done
