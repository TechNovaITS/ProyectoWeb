#!/bin/sh
# Genera la configuración inicial usando las credenciales del entorno.
# umask restringe el archivo, ya que contiene las credenciales administrativas.
set -eu
umask 077
cat > /etc/proxysql.cnf <<EOF
datadir="/var/lib/proxysql"

admin_variables=
{
  admin_credentials="${PROXYSQL_ADMIN_USER}:${PROXYSQL_ADMIN_PASSWORD}"
  mysql_ifaces="0.0.0.0:6032"
}

mysql_variables=
{
  interfaces="0.0.0.0:6033"
  threads=4
  max_connections=2048
  connect_timeout_server=3000
}
EOF

# Ejecuta ProxySQL en primer plano para que Docker supervise el proceso.
# exec sustituye el shell y entrega señales de apagado directamente a ProxySQL.
exec proxysql -f -c /etc/proxysql.cnf -D /var/lib/proxysql
