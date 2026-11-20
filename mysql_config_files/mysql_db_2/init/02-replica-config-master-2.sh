#!/usr/bin/env bash
set -eo pipefail

retries=30
until mysqladmin ping -h "mysql_db" -u"replicacion" -p"ReplicacionPassword123" --silent; do
  retries=$((retries - 1))
  if [ "$retries" -le 0 ]; then
    echo "Timeout esperando a mysql_db" >&2
    exit 1
  fi
  echo "Esperando a nodo principal (mysql_db)..."
  sleep 2
done
 
mysql -u root -p"rootpasswordmaster2" <<-EOSQL
  CHANGE REPLICATION SOURCE TO
    SOURCE_HOST='mysql_db',
    SOURCE_USER='replicacion',
    SOURCE_PASSWORD='ReplicacionPassword123',
    SOURCE_AUTO_POSITION=1,
    GET_SOURCE_PUBLIC_KEY=1;

  START REPLICA;
EOSQL

mysql -h mysql_db -u root -p"rootpassword" <<-EOSQL
  CHANGE REPLICATION SOURCE TO
    SOURCE_HOST='mysql_db_2',
    SOURCE_USER='replicacion',
    SOURCE_PASSWORD='ReplicacionPassword123',
    SOURCE_AUTO_POSITION=1,
    GET_SOURCE_PUBLIC_KEY=1;

  START REPLICA;
EOSQL