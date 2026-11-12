# Nodos MySQL

Esta carpeta contiene las tres configuraciones de servidor y la tarea que
convierte las instancias en un clúster InnoDB. Cada instancia usa su propio
volumen; compartir un directorio de datos entre contenedores dañaría la base.

## Archivos

| Archivo | Responsabilidad y motivo |
| --- | --- |
| `Dockerfile.node-1` | Construye la imagen del primer servidor con su configuración. |
| `Dockerfile.node-1` | Construye la imagen del servicio `main`. |
| `Dockerfile.node-2` | Construye la imagen del servicio `fallback`. |
| `Dockerfile.node-3` | Construye la imagen del servicio `slave`. |
| `nodo-1.cnf` | Define la identidad interna `mysql-1` y parámetros de replicación de `main`. |
| `nodo-2.cnf` | Mantiene la identidad interna `mysql-2` para `fallback`. |
| `nodo-3.cnf` | Mantiene la identidad interna `mysql-3` para `slave`. |
| `inicializar-cluster.js` | Usa MySQL Shell para crear o recuperar el clúster y preparar cuentas de monitoreo. |

Los nodos comparten el nombre del grupo, el formato de binlog y las opciones
GTID para que Group Replication pueda verificar y replicar transacciones. Cada
nodo tiene un `server_id` y una dirección `group_replication_local_address`
`server_id` y una dirección `group_replication_local_address`
distintos; esos valores no se deben copiar de un nodo a otro.
Los servicios Compose se llaman `main`, `fallback` y `slave`; los alias DNS
anteriores `mysql-1/2/3` se conservan para no invalidar metadatos persistidos ni
la configuración guardada en ProxySQL. Los nombres son etiquetas operativas,
no roles fijos: el `PRIMARY` puede ser cualquiera de los tres miembros.
docker compose ps -a main fallback slave inicializar-cluster

## Flujo de inicialización

Compose espera que los tres servidores acepten conexiones antes de ejecutar
`inicializar-cluster.js`. El script:

1. Consulta si existe el esquema de metadatos de InnoDB Cluster.
2. Crea el clúster si es nuevo; si los metadatos existen, intenta obtener el
   clúster activo o recuperarlo de una caída total.
3. Agrega instancias ausentes mediante recuperación por clonación.
4. Habilita el inicio automático de Group Replication tras reinicios.
5. Crea la cuenta de ProxySQL y la cuenta de lectura para Prometheus.
6. Imprime la topología final para que el estado sea visible en los logs.

La recuperación deliberadamente no elimina esquemas ni volúmenes. Si Shell
rechaza una recuperación, conserva los datos y revisa el error exacto antes de
realizar cualquier acción destructiva.

## Comprobar el clúster

Desde la raíz:

```powershell
docker compose ps -a main fallback slave inicializar-cluster
docker compose logs --tail 100 inicializar-cluster
```

En cada servidor, `performance_schema.replication_group_members` debe mostrar
los mismos tres miembros en estado `ONLINE`. Debe existir un `PRIMARY` y dos
`SECONDARY`; el rol primario puede cambiar si Group Replication elige otro
nodo.

Lee la guía raíz, [comprobar los tres nodos](../README.md#comprobar-los-tres-nodos-mysql),
para las consultas SQL de topología, modo de solo lectura y prueba de escritura
replicada.

> Los nombres de archivo `Dockerfile.node-*` son referencias de construcción
> usadas por `compose.yaml`. Cambiarlos requiere actualizar también Compose.
