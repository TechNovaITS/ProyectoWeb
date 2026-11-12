// Obtiene las contraseñas desde las variables del servicio de inicialización.
const claveRoot = os.getenv("MYSQL_ROOT_PASSWORD");
const claveMonitor = os.getenv("MYSQL_MONITOR_PASSWORD");

// Crea las opciones de conexión sin incluir credenciales en la línea de comandos.
function conexion(host) {
  return { user: "root", password: claveRoot, host: host, port: 3306 };
}

// Conecta al primer nodo para crear el clúster o recuperar uno ya existente.
shell.connect(conexion("mysql-1"));
const metadataSession = shell.getSession();
// El esquema de metadatos permite distinguir un clúster usado de una instalación nueva.
const metadataRow = metadataSession
  .runSql(
    "SELECT SCHEMA_NAME FROM information_schema.SCHEMATA " +
      "WHERE SCHEMA_NAME = 'mysql_innodb_cluster_metadata'"
  )
  .fetchOne();
let cluster;
if (metadataRow === null) {
  cluster = dba.createCluster("dockerCluster", { communicationStack: "MYSQL" });
  print("Se creó el clúster dockerCluster.");
} else {
  try {
    cluster = dba.getCluster();
    print("Se encontró un clúster existente; se verificará su topología.");
  } catch (error) {
    // La topología guardada evita crear un grupo nuevo sobre datos que ya pertenecen al clúster.
    print(
      "Hay metadatos de un clúster existente, pero no está activo. " +
        "Se intentará recuperarlo tras la caída completa: " +
        error.message
    );
    cluster = dba.rebootClusterFromCompleteOutage();
    print("Se recuperó el clúster existente.");
  }
}

// Agrega los nodos que todavía no aparecen en la topología del clúster.
for (const host of ["mysql-2", "mysql-3"]) {
  // Consulta la topología actual en cada vuelta para no agregar miembros duplicados.
  const topologia = cluster.status().defaultReplicaSet.topology;
  const endpoint = host + ":3306";
  if (!topologia[endpoint]) {
    cluster.addInstance(conexion(host), { recoveryMethod: "clone" });
    print("Se agregó " + endpoint + " al clúster.");
  }
}

// Hace que los nodos vuelvan a unirse automáticamente después de reiniciarse.
for (const host of ["mysql-1", "mysql-2", "mysql-3"]) {
  shell.connect(conexion(host));
  shell.getSession().runSql(
    "SET PERSIST group_replication_start_on_boot = ON"
  );
}

// Crea el usuario de monitoreo que ProxySQL necesita para detectar al primario.
shell.connect(conexion("mysql-1"));
// Escapa comillas y barras porque las contraseñas se interpolan en sentencias SQL.
const claveSql = claveMonitor
  .replace(/\\/g, "\\\\")
  .replace(/'/g, "\\'");
const claveExportador = os.getenv("MYSQL_EXPORTER_PASSWORD");
const claveExportadorSql = claveExportador
  .replace(/\\/g, "\\\\")
  .replace(/'/g, "\\'");
const sesion = shell.getSession();
sesion.runSql(
  "CREATE USER IF NOT EXISTS 'proxysql_monitor'@'%' IDENTIFIED BY '" +
    claveSql +
    "'"
);
sesion.runSql(
  "GRANT REPLICATION CLIENT ON *.* TO 'proxysql_monitor'@'%'"
);
sesion.runSql(
  "GRANT SELECT ON performance_schema.replication_group_members TO 'proxysql_monitor'@'%'"
);
sesion.runSql(
  "GRANT SELECT ON performance_schema.replication_group_member_stats TO 'proxysql_monitor'@'%'"
);

// Crea un usuario de solo lectura con los permisos requeridos por el exporter.
// Prometheus observa métricas; esta cuenta no se comparte con la aplicación.
sesion.runSql(
  "CREATE USER IF NOT EXISTS 'prometheus_exporter'@'%' IDENTIFIED BY '" +
    claveExportadorSql +
    "'"
);
sesion.runSql(
  "ALTER USER 'prometheus_exporter'@'%' IDENTIFIED BY '" +
    claveExportadorSql +
    "'"
);
sesion.runSql(
  "GRANT PROCESS, REPLICATION CLIENT, SELECT ON *.* TO 'prometheus_exporter'@'%'"
);

// Informa el estado final para facilitar la comprobación en los registros.
print(JSON.stringify(cluster.status(), null, 2));
