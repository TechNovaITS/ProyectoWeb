# Aprovisionamiento de Grafana

El aprovisionamiento convierte la configuración repetible de Grafana en
archivos revisables y versionables. Así, al crear el contenedor no hay que
agregar a mano fuentes de datos ni registrar dónde están los dashboards.

## Subcarpetas

- [`dashboards/`](./dashboards/README.md): indica dónde encontrar los
  dashboards JSON.
- [`datasources/`](./datasources/README.md): registra Prometheus, Loki y
  Alertmanager por el DNS privado de Compose.

Compose monta esta carpeta en `/etc/grafana/provisioning` en modo de solo
lectura. Los cambios se preparan aquí y se aplican al reiniciar Grafana.
Consulta la [guía de Grafana](../README.md) para credenciales y operación.
