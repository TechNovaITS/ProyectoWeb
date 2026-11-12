# Proveedor de dashboards

Esta carpeta define cómo Grafana descubre los dashboards JSON del repositorio.
Separar el proveedor de los documentos permite cambiar la política de carga
sin modificar el contenido de los paneles.

## Archivo

| Archivo | Función |
| --- | --- |
| `dashboards.yml` | Declara un proveedor basado en archivos y la ruta de dashboards dentro del contenedor. |

La ruta del proveedor debe coincidir con el montaje de
`observability/grafana/dashboards` en Compose. Si no aparece un panel, valida
esa ruta, la sintaxis YAML/JSON y los logs de Grafana.

```powershell
docker compose logs --tail 100 grafana
```

El dashboard de infraestructura está descrito en
[`../../dashboards/README.md`](../../dashboards/README.md).
