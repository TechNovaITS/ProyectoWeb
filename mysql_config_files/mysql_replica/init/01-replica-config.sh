#!/usr/bin/env bash
set -euo pipefail

until mysqladmin ping -h "mysql_db" -uroot -p"rootpassword" --silent; do
  echo "Esperando a nodo principal..."
  sleep 2
done

mysql -u root -p"rootpassword" <<-EOSQL
  CHANGE REPLICATION SOURCE TO
    SOURCE_HOST='mysql_db',
    SOURCE_USER='replicacion',
    SOURCE_PASSWORD='ReplicacionPassword123',
    SOURCE_AUTO_POSITION=1,
    GET_SOURCE_PUBLIC_KEY=1;

  START REPLICA;
EOSQL