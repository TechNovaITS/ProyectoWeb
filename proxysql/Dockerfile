# Usa la imagen oficial de ProxySQL 2.7.
FROM proxysql/proxysql:2.7.3

# Inicia ProxySQL con una configuración inicial generada desde el entorno.
# Normaliza CRLF para que el script también se pueda editar desde Windows.
COPY iniciar-proxysql.sh /usr/local/bin/iniciar-proxysql.sh
RUN sed -i 's/\r$//' /usr/local/bin/iniciar-proxysql.sh \
    && chmod +x /usr/local/bin/iniciar-proxysql.sh
ENTRYPOINT ["/usr/local/bin/iniciar-proxysql.sh"]
