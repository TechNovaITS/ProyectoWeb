# Alertmanager

Esta carpeta guarda la política de agrupación y entrega de alertas. Alertmanager
recibe las alertas evaluadas por Prometheus y las agrupa para evitar repetir
notificaciones idénticas.

## Archivo

| Archivo | Función |
| --- | --- |
| `alertmanager.yml` | Define el agrupamiento de rutas y el receptor local inicial. |

Por seguridad y para que el stack funcione sin servicios externos, el receptor
no envía correo, mensajes ni webhooks. Ver una alerta en la interfaz no
significa que alguien vaya a recibir una notificación.

## Operación

Abre <http://localhost:9093> o revisa Prometheus → **Alerts** para comprobar el
flujo. Los datos de silencios se conservan en el volumen `alertmanager_data`.
Para agregar un destino, configura primero credenciales fuera del repositorio,
ajusta `route` y `receivers` y valida el YAML antes de reiniciar.

```powershell
docker compose logs --tail 100 alertmanager
docker compose config --quiet
```

Consulta [`../README.md`](../README.md) para las reglas de Prometheus y
[`../../README.md`](../../README.md) para el contexto del stack completo.
