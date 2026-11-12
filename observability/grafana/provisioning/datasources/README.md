# Fuentes de datos de Grafana

Las fuentes conectan los paneles de Grafana con los servicios internos que
producen métricas, logs y alertas. Se aprovisionan por archivo para que los
entornos se configuren igual al recrear el contenedor.

## Archivo

| Archivo | Función |
| --- | --- |
| `datasources.yml` | Registra Prometheus, Loki y Alertmanager como fuentes del servidor Grafana. |

Las URL usan los nombres DNS de Compose (`prometheus`, `loki` y
`alertmanager`), no `localhost`: cada contenedor tiene su propio espacio de
red. Si se cambia un nombre de servicio o un puerto interno, actualiza ambas
configuraciones y valida el arranque de Grafana.

Compose monta este archivo en solo lectura. Las credenciales no se guardan aquí
porque estos servicios se consultan dentro de la red privada local.
