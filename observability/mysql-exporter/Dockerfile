FROM prom/mysqld-exporter:v0.17.2

# Copia el entrypoint que genera credenciales temporales sin incluirlas en la imagen.
# Se fija la versión para que un cambio de métricas sea una actualización consciente.
COPY iniciar-exporter.sh /usr/local/bin/iniciar-exporter.sh
USER root
RUN ["/bin/busybox", "sh", "-c", "busybox sed -i 's/\\r$//' /usr/local/bin/iniciar-exporter.sh"]
USER nobody
ENTRYPOINT ["/bin/busybox", "sh", "/usr/local/bin/iniciar-exporter.sh"]
