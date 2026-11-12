# Prometheus

Prometheus recopila series temporales de los servicios, evalúa reglas y envía
las alertas resultantes a Alertmanager. Separar los objetivos (`prometheus.yml`)
de las expresiones de alerta (`alerts.yml`) facilita revisar y validar cada
tipo de cambio.

## Archivos

| Archivo | Función |
| --- | --- |
| `prometheus.yml` | Intervalos de recopilación, destino de Alertmanager, carga de reglas y objetivos. |
| `alerts.yml` | Expresiones PromQL y tiempos durante los que una condición debe cumplirse antes de alertar. |

El almacenamiento se mantiene en `prometheus_data`; la retención está limitada
por tiempo y tamaño en `compose.yaml`. Las URL scrape apuntan a servicios
internos Compose y no deben cambiarse a `localhost`.

## Validación y diagnóstico

Abre <http://localhost:9090/targets> y comprueba los tres objetivos MySQL, más
los servicios de observabilidad. En **Alerts** puedes ver reglas pendientes y
activas.

```powershell
docker compose logs --tail 100 prometheus alertmanager
docker compose config --quiet
```

Para comprobar Group Replication directamente, sigue la
[guía de salud de MySQL](../../README.md#comprobar-los-tres-nodos-mysql).
