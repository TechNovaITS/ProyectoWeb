# Grafana

Grafana es el punto de consulta visual del stack: conecta métricas de
Prometheus, logs de Loki y estado de alertas de Alertmanager. Las fuentes de
datos y los dashboards se cargan desde archivos versionados para que una
instalación nueva no dependa de pasos manuales.

## Contenido

- `dashboards/`: archivos JSON de los paneles.
- `provisioning/`: proveedores de dashboards y fuentes de datos.

El dashboard está en JSON estricto; agregar comentarios dentro del JSON lo
volvería inválido. Su propósito y sus consultas se explican en
[`dashboards/README.md`](./dashboards/README.md).

## Uso

Abre <http://localhost:3000> y usa las credenciales definidas por
`GRAFANA_ADMIN_USER` y `GRAFANA_ADMIN_PASSWORD` en `.env`. Las fuentes
aprovisionadas apuntan a nombres de servicios internos de Compose, por ejemplo
`http://prometheus:9090`; dentro del contenedor `localhost` sería el propio
Grafana.

El volumen `grafana_data` conserva preferencias y otros datos locales. Los
archivos de aprovisionamiento se montan de solo lectura; edita el repositorio y
reinicia Grafana para aplicar cambios:

```powershell
docker compose logs --tail 100 grafana
docker compose restart grafana
```

No publiques Grafana sin configurar autenticación, TLS y controles de acceso
adecuados para el entorno.
