# Exportador de métricas MySQL

`mysqld_exporter` convierte estadísticas del servidor MySQL en métricas que
Prometheus puede recopilar. Prometheus consulta las tres instancias por
separado, así se puede distinguir la salud de un nodo de la del clúster.

## Archivos

| Archivo | Función |
| --- | --- |
| `Dockerfile` | Construye la imagen oficial del exporter con el script de inicio del proyecto. |
| `iniciar-exporter.sh` | Valida la contraseña, crea un archivo temporal protegido y ejecuta el exporter. |

La contraseña llega por variable de entorno, no se incorpora en la imagen ni
se deja en los argumentos visibles del proceso. La cuenta `prometheus_exporter`
la crea el inicializador MySQL y recibe permisos de lectura para consultar
estadísticas.

## Comprobación

Abre <http://localhost:9090/targets> y confirma tres objetivos `mysql` en
estado `UP`. Para detalles:

```powershell
docker compose logs --tail 100 mysql-exporter
docker compose logs --tail 100 inicializar-cluster
```

Si se cambia `MYSQL_EXPORTER_PASSWORD`, hay que actualizar la cuenta en MySQL
y el servicio exporter; cambiar únicamente `.env` no altera una cuenta ya
existente.
