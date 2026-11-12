# Loki

Loki recibe los logs que envía Alloy y los conserva para consultarlos desde
Grafana. En lugar de indexar todo el contenido de cada línea como texto, usa
etiquetas para localizar flujos y permite analizar las líneas al consultarlas.

## Archivo

| Archivo | Función |
| --- | --- |
| `loki.yml` | Configura el servidor, almacenamiento local, esquema de índice, compactor y retención. |

El volumen `loki_data` conserva índices y bloques entre reinicios. La retención
configurada limita el crecimiento, pero no reemplaza la supervisión del espacio
de disco. Loki está en la red interna sin autenticación; no publiques su puerto
en una red no confiable.

Para verificar el servicio y las consultas:

```powershell
docker compose logs --tail 100 loki alloy
```

En Grafana → **Explore**, selecciona Loki y consulta `{job="docker"}`. La guía
general de operación está en [`../README.md`](../README.md).
