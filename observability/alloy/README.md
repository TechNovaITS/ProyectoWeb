# Grafana Alloy

Alloy recolecta logs de Docker y los envía a Loki. Cuando el perfil de Suricata
produce un archivo EVE, Alloy también puede seguirlo. Se usa un único agente
para evitar duplicar lecturas y procesamiento con Promtail o Vector.

## Archivo

| Archivo | Función |
| --- | --- |
| `config.alloy` | Descubre contenedores, asigna etiquetas, encuentra EVE y configura el destino Loki. |

El acceso a Docker pasa por `docker-socket-proxy`: Alloy no recibe el socket de
Docker directamente. Las etiquetas `service`, `container` y `job` ayudan a
filtrar logs en Grafana. El volumen `alloy_data` conserva las posiciones de
lectura para evitar volver al inicio de archivos ya procesados.

## Consultas y diagnóstico

En Grafana → **Explore** → **Loki**, prueba `{job="docker"}`. Para Suricata,
usa `{job="suricata"} | json` después de activar el perfil `security` en un
host Linux compatible.

```powershell
docker compose run --rm --no-deps --entrypoint alloy alloy fmt --test /etc/alloy/config.alloy
docker compose logs --tail 100 alloy docker-socket-proxy loki
```

`fmt --test` valida la sintaxis con la misma versión de Alloy que ejecuta el
stack. En Windows, la ausencia del archivo EVE es normal mientras no se use
Suricata; Alloy mantiene la configuración disponible para cuando se cree.
