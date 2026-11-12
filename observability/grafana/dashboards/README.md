# Dashboards de Grafana

Esta carpeta contiene los dashboards serializados como JSON. El proveedor
configurado en `../provisioning/dashboards` busca aquí esos documentos y los
carga en Grafana.

## Archivo

| Archivo | Función |
| --- | --- |
| `infrastructure.json` | Paneles de métricas de infraestructura basados en Prometheus. |

El dashboard no admite comentarios embebidos porque JSON no define esa
sintaxis. Para documentar un panel, explica aquí el objetivo de la métrica y
mantén su título y descripción dentro del propio dashboard.

Después de editarlo, reinicia o espera la recarga configurada por el proveedor
y revisa los logs de Grafana. Las consultas deben usar métricas disponibles en
la versión desplegada de Prometheus y exporters.

```powershell
docker compose logs --tail 100 grafana
```
