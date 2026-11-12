FROM alpine:3.21

# curl transfiere por HTTPS/WebDAV y tar+gzip empaquetan datos y configuración.
# No se instala un cliente completo: la tarea solo archiva y carga copias.
RUN apk add --no-cache curl tar gzip
COPY enviar-backup.sh /usr/local/bin/enviar-backup.sh
RUN sed -i 's/\r$//' /usr/local/bin/enviar-backup.sh \
    && chmod 0755 /usr/local/bin/enviar-backup.sh

ENTRYPOINT ["/bin/sh", "/usr/local/bin/enviar-backup.sh"]
