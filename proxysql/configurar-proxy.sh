#!/bin/sh
# Reintenta la conexión mientras ProxySQL termina de iniciar.
set -eu
intentos=0
until mysql --protocol=TCP -h proxy -P 6032 \
  -u "$PROXYSQL_ADMIN_USER" -p"$PROXYSQL_ADMIN_PASSWORD" \
  -e "SELECT 1" >/dev/null 2>&1
do
  intentos=$((intentos + 1))
  if [ "$intentos" -ge 60 ]; then
    echo "Error: ProxySQL no respondió luego de 60 intentos." >&2
    exit 1
  fi
  sleep 2
done

# Configura credenciales de monitoreo, enrutamiento por Group Replication,
# nodos MySQL y el usuario de la aplicación.
# ProxySQL separa escritura (10), lectura (20), writer de respaldo (30)
# y nodos fuera de servicio (40); el monitor mueve servidores entre grupos.
mysql --protocol=TCP -h proxy -P 6032 \
  -u "$PROXYSQL_ADMIN_USER" -p"$PROXYSQL_ADMIN_PASSWORD" <<SQL
UPDATE global_variables
SET variable_value = 'proxysql_monitor'
WHERE variable_name = 'mysql-monitor_username';
UPDATE global_variables
SET variable_value = '${MYSQL_MONITOR_PASSWORD}'
WHERE variable_name = 'mysql-monitor_password';

DELETE FROM mysql_group_replication_hostgroups;
INSERT INTO mysql_group_replication_hostgroups
  (writer_hostgroup, reader_hostgroup, backup_writer_hostgroup,
   offline_hostgroup, active, max_writers, writer_is_also_reader,
   max_transactions_behind, comment)
VALUES (10, 20, 30, 40, 1, 1, 0, 100, 'Clúster MySQL de Docker');

# Registra los nodos en el grupo de escritura; el monitor decide su rol real.
DELETE FROM mysql_servers WHERE hostgroup_id IN (10, 20, 30, 40);
INSERT INTO mysql_servers (hostgroup_id, hostname, port, comment)
VALUES
  (10, 'mysql-1', 3306, 'main (miembro inicial)'),
  (10, 'mysql-2', 3306, 'fallback (miembro 2)'),
  (10, 'mysql-3', 3306, 'slave (miembro 3)');

DELETE FROM mysql_users WHERE username = '${MYSQL_USER}';
INSERT INTO mysql_users
  (username, password, default_hostgroup, transaction_persistent, active)
VALUES ('${MYSQL_USER}', '${MYSQL_PASSWORD}', 10, 1, 1);

LOAD MYSQL VARIABLES TO RUNTIME;
SAVE MYSQL VARIABLES TO DISK;
LOAD MYSQL SERVERS TO RUNTIME;
SAVE MYSQL SERVERS TO DISK;
LOAD MYSQL USERS TO RUNTIME;
SAVE MYSQL USERS TO DISK;
SQL

# Confirma que el proxy ya está listo para recibir conexiones de la aplicación.
echo "ProxySQL quedó configurado para el clúster MySQL."
