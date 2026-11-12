# Observabilidad y seguridad de red

Guía operativa de Grafana, Prometheus, Alertmanager, Loki, Grafana Alloy,
exportación de métricas MySQL, cAdvisor, Suricata y copias opcionales a IIS.
La guía general, con el mapa de todos los archivos, está en
[`../README.md`](../README.md).
Cada subcarpeta también documenta el propósito, las decisiones y la validación
de sus archivos. Consulta el [índice de guías](../README.md#guías-por-carpeta).

## 1. Componentes y flujo de datos

```text
MySQL (3 nodos) ──> mysqld_exporter ──> Prometheus ──> reglas ──> Alertmanager
cAdvisor ────────────────────────────> Prometheus
Alloy ── logs Docker ──> Loki <── Grafana
Suricata ── EVE JSON ──> Alloy ──> Loki <── Grafana (perfil opcional)
```

- **Prometheus** consulta objetivos cada 15 segundos y evalúa reglas cada
  15 segundos. Mantiene como máximo 15 días o 10 GB, lo que ocurra primero.
- **Alertmanager** agrupa alertas por nombre, tarea e instancia. Su receptor
  está vacío de destinos externos de forma intencional.
- **Loki** conserva logs hasta 14 días en el volumen local `loki_data`.
- **Alloy** descubre contenedores a través de un proxy restringido del Docker
  API; también puede seguir el archivo `eve.json` de Suricata.
- **Grafana** provisiona automáticamente Prometheus, Loki y Alertmanager,
  además del dashboard `Infrastructure`.
- **cAdvisor** y **mysqld_exporter** exponen métricas internas; no publican sus
  puertos al host.
- **Suricata** es un IDS en modo pasivo: registra eventos, no bloquea paquetes.

Alloy es el único agente de recolección de logs del proyecto: mantiene en un
solo punto el descubrimiento de contenedores y la lectura opcional de EVE de
Suricata. No se ejecutan Promtail ni Vector en paralelo.

Esta configuración está pensada para una sola máquina y almacenamiento local.
No es una arquitectura de producción/alta disponibilidad.

## 2. Requisitos y variables

1. Desde la raíz del proyecto, copia la plantilla de entorno y cambia las
   contraseñas antes de iniciar:

   ```powershell
  Copy-Item env.ejemplo .env
   ```

  En Linux/macOS, usa `cp env.ejemplo .env`.

2. Variables relacionadas con esta guía:

| Variable | Obligatoria | Descripción |
| --- | --- | --- |
| `MYSQL_EXPORTER_PASSWORD` | Sí para métricas MySQL | Contraseña del usuario `prometheus_exporter`, creada por el inicializador del clúster. |
| `GRAFANA_ADMIN_USER` | No | Usuario administrador local; default `admin`. |
| `GRAFANA_ADMIN_PASSWORD` | Sí, cambia el ejemplo | Contraseña de administración de Grafana. |
| `SURICATA_INTERFACE` | Solo perfil `security` | Interfaz física/lógica del host Linux para captura; default `eth0`. |
| `BACKUP_IIS_URL` | Solo perfil `backup` | URL HTTPS de la carpeta IIS/WebDAV. |
| `BACKUP_IIS_USERNAME` | Solo perfil `backup` | Cuenta dedicada con permiso de escritura en la carpeta de copias. |
| `BACKUP_IIS_PASSWORD` | Solo perfil `backup` | Contraseña de esa cuenta. |
| `BACKUP_INTERVAL_SECONDS` | No | Periodo en segundos; default 86400 (24 horas). |

Las variables MySQL, ProxySQL y del exporter deben coincidir con los valores
usados al inicializar los volúmenes. Un cambio en `.env` no siempre migra
cuentas ya guardadas en MySQL o ProxySQL. No pegues secretos en tickets,
capturas ni repositorios.

## 3. Arranque y URLs

Inicia la pila normal desde la raíz:

```powershell
docker compose config --quiet
docker compose up --build -d
docker compose ps -a
```

| Aplicación | URL | Uso |
| --- | --- | --- |
| Grafana | <http://localhost:3000> | Dashboard, Explore y alertas. Credenciales según `.env`. |
| Prometheus | <http://localhost:9090> | Consultas PromQL y evaluación de `/targets`. |
| Alertmanager | <http://localhost:9093> | Alertas recibidas, agrupación y silencios. |
| Loki | Solo red interna | Backend de logs consultado por Grafana y Alloy. |
| Alloy | Solo red interna | Recopila logs y expone métricas internas. |
| cAdvisor | Solo red interna | Métricas de contenedores para Prometheus. |
| MySQL exporter | Solo red interna | Endpoint scrape `/probe` para las instancias. |

Grafana es el punto de consulta. Las fuentes de datos provisionadas son de solo
lectura/administradas por archivos; sus endpoints apuntan a nombres de servicios
Compose, no a `localhost`.

## 4. Métricas y alertas

### Objetivos de Prometheus

Abre <http://localhost:9090/targets>. El archivo
`prometheus/prometheus.yml` define estos jobs:

| Job | Objetivo |
| --- | --- |
| `prometheus` | El propio servidor Prometheus. |
| `alertmanager` | Métricas de Alertmanager. |
| `loki` | Métricas del servicio Loki. |
| `alloy` | Métricas de Alloy en `12345`. |
| `cadvisor` | Métricas de cAdvisor en la red Compose. |
| `mysql` | Sondeo de los tres nodos por `mysql-exporter`. |

El job MySQL envía cada host al exporter por el endpoint `/probe`. El usuario
exporter recibe permisos para consultar las estadísticas necesarias; no es el
usuario que usa la aplicación.

Para comprobar la salud real del clúster, abre `/targets` y verifica que las
tres instancias del job `mysql` estén `UP`. Después consulta
`performance_schema.replication_group_members` desde cada nodo: deben coincidir
los tres miembros en estado `ONLINE`, con un `PRIMARY` y dos `SECONDARY`. La
guía raíz incluye los comandos interactivos y la comprobación de una escritura
replicada: [`Comprobar los tres nodos MySQL`](../README.md#comprobar-los-tres-nodos-mysql).

### Reglas incluidas

`prometheus/alerts.yml` contiene:

- **`ScrapeTargetUnavailable`**: cualquier objetivo `up == 0` durante 2 minutos.
- **`MySQLInstanceUnavailable`**: `mysql_up == 0` durante 1 minuto.
- **`ContainerMemoryUsageHigh`**: consumo mayor al 90% del límite durante
  5 minutos (solo cuando hay un límite definido).

Consulta el estado de una alerta en Prometheus → **Alerts** y su agrupación en
Alertmanager. Grafana puede visualizar las reglas/alertas según sus fuentes
aprovisionadas.

### Notificaciones externas

El archivo `alertmanager/alertmanager.yml` agrupa alertas y define el receptor
local sin configuración de correo/webhook. Para enviar notificaciones:

1. Lee la referencia oficial del receptor elegido y prepara credenciales
   seguras fuera del repositorio.
2. Configura el receptor en `route` y `receivers`.
3. Valida la sintaxis de Alertmanager antes de reiniciar el servicio.
4. Prueba el envío con una alerta controlada y revisa sus logs.

No registres tokens o contraseñas en la configuración versionada. Para una
instalación real, usa un gestor de secretos y protege también el acceso al
endpoint local de Alertmanager.

## 5. Consulta de logs con Loki

Alloy recopila stdout/stderr de los contenedores Docker y los etiqueta con
`job="docker"`, `container` y `service`. En Grafana:

1. Abre **Explore**.
2. Selecciona la fuente **Loki**.
3. Usa una consulta como:

   ```logql
   {job="docker"}
   {job="docker", service="web"}
   {job="docker", container=~".*mysql.*"}
   ```

Para ver la salud de la ingesta, revisa los logs de `alloy` y `loki`:

```powershell
docker compose logs --tail 100 alloy loki docker-socket-proxy
```

El volumen `alloy_data` conserva posiciones de lectura. Si Alloy se reinicia,
continúa el seguimiento desde el punto guardado; evita borrar ese volumen salvo
que intencionalmente quieras reiniciar la posición.

## 6. IDS pasivo con Suricata (Linux)

### Plataforma y alcance

Suricata se activa con el perfil `security`. La configuración usa
`network_mode: host`, captura paquetes de una interfaz del host y requiere
`NET_ADMIN`, `NET_RAW` y `SYS_NICE`. Por ello debe ejecutarse en Docker Engine
nativo sobre **Linux**. Docker Desktop para Windows ejecuta contenedores dentro
de una VM Linux y no ofrece a este servicio captura íntegra de la interfaz
física de Windows.

La captura depende del tráfico visible en la interfaz elegida. Una tarjeta de
red conmutada normalmente no observa todo el tráfico unicast de otros equipos;
para eso puede requerirse SPAN/port mirroring en el switch o un TAP. La
configuración es IDS, no IPS: no altera ni bloquea tráfico.

### Configurar e iniciar

1. En el host Linux, identifica interfaces:

   ```sh
   ip -br link
   ```

2. Configura `SURICATA_INTERFACE` en el `.env` ubicado en la raíz, por ejemplo:

   ```dotenv
   SURICATA_INTERFACE=enp1s0
   ```

3. Valida y activa el perfil:

   ```sh
   docker compose --profile security config --quiet
   docker compose --profile security up -d suricata
   docker compose --profile security ps
   docker compose --profile security logs -f suricata
   ```

Compose ejecuta primero `suricata-rules`, que descarga ET Open mediante
`suricata-update`, y luego inicia el sensor en la interfaz configurada.
Suricata escribe eventos en formato EVE JSON a `suricata_logs`; Alloy monta
ese volumen en modo lectura y envía las líneas a Loki.

### Buscar eventos

En Grafana → **Explore** → **Loki**, filtra todas las líneas:

```logql
{job="suricata"}
```

Solo alertas del IDS:

```logql
{job="suricata"} | json | event_type="alert"
```

Por severidad o firma (después de parsear el JSON, según los campos presentes):

```logql
{job="suricata"} | json | event_type="alert" | alert_severity="1"
{job="suricata"} | json | event_type="alert" | alert_signature=~".*ET.*"
```

Los nombres de campos disponibles dependen del registro EVE generado por la
versión/configuración de Suricata. Usa `{job="suricata"} | json` y expande una
línea para inspeccionar los campos concretos antes de crear filtros.

**La integración entrega eventos a Loki; no crea por sí sola reglas de
notificación para cada alerta Suricata ni acciones de bloqueo.** Puedes crear
alertas de Grafana/Loki por encima de estos logs si necesitas notificaciones.

### Actualizar reglas y apagar

Para actualizar ET Open y reiniciar el sensor:

```sh
docker compose --profile security run --rm suricata-rules
docker compose --profile security restart suricata
```

Para detenerlo sin eliminar datos:

```sh
docker compose --profile security stop suricata
```

Elimina el perfil del comando `up` para no iniciarlo en usos normales. No
elimines los volúmenes `suricata_*` si quieres conservar reglas, estado o EVE.

### Seguridad del sensor

- Ejecuta la captura únicamente sobre redes e interfaces para las que tengas
  autorización.
- `network_mode: host` permite observar la red del host Linux y las capacidades
  de red amplían el acceso del contenedor. No añadas `privileged` si no es
  necesario.
- EVE puede contener IPs, DNS, metadatos de protocolos y contexto de alertas.
  Protege el acceso y la retención en Loki/Grafana.
- El volumen de logs se comparte en solo lectura con Alloy; Alloy no necesita
  acceder a la captura de paquetes ni al socket con permisos de escritura.
- En Windows, no uses este perfil esperando monitorizar la NIC del host.

## 7. Copias periódicas a IIS por WebDAV

### Qué se copia y qué no

`backup-observabilidad` crea un archivo `tar.gz` que incluye los datos de
volúmenes:

- Grafana, Prometheus, Loki, Alertmanager y Alloy.
- Configuración, datos/reglas y logs de Suricata.
- El directorio versionado `observability/`.

No incluye `mysql_1_data`, `mysql_2_data`, `mysql_3_data` ni `proxysql_data`.
Tampoco es una copia consistente de Loki/Prometheus/Grafana mientras siguen
escribiendo; trátala como copia de recuperación de laboratorio, no como
estrategia única de backup productivo. Los archivos se acumulan en IIS, así
que hay que configurar retención y cuotas en el destino.

### Preparar IIS

1. Instala/activa WebDAV y HTTPS en IIS.
2. Crea un directorio de destino y una regla de autoría con permiso de
   escritura solo para una cuenta dedicada.
3. Restringe métodos y permisos a lo necesario. Usa autenticación básica solo
   detrás de HTTPS y configura un certificado confiable.
4. Revisa tamaño máximo de solicitud, cuota de almacenamiento y política de
   eliminación de copias antiguas.

### Configurar e iniciar

Rellena estas variables en `.env`:

```dotenv
BACKUP_IIS_URL=https://servidor/observability-backups
BACKUP_IIS_USERNAME=cuenta-backup
BACKUP_IIS_PASSWORD=secreto
BACKUP_INTERVAL_SECONDS=86400
```

La URL debe ser HTTPS. Inicia el perfil:

```powershell
docker compose --profile backup up --build -d
docker compose --profile backup logs -f backup-observabilidad
```

El primer paquete se prepara en el inicio y después se espera el intervalo
configurado. Se crea temporalmente en `/tmp`, se transfiere con `curl` HTTP
PUT, y se elimina del contenedor al finalizar el intento. Los errores se
registran y el proceso vuelve a intentarlo en el siguiente intervalo.

Para detener las copias:

```powershell
docker compose --profile backup stop backup-observabilidad
```

No actives el perfil hasta configurar URL y credenciales: el contenedor valida
las variables obligatorias y sale explícitamente si faltan.

### Restauración y pruebas

El proyecto no automatiza la restauración en IIS. Descarga un archivo concreto,
verifica que se pueda abrir y recuperar su contenido en un entorno aislado.
Restaura los datos de cada producto con instrucciones compatibles con su
versión; no copies directorios activos sobre servicios en producción. Diseña
y prueba aparte copias consistentes del clúster MySQL.

## 8. Persistencia, capacidad y retención

| Volumen | Propósito |
| --- | --- |
| `prometheus_data` | TSDB; retención de 15 días y máximo 10 GB configurados en Compose. |
| `alertmanager_data` | Silencios y estado persistido de alertas. |
| `loki_data` | Bloques/índices con retención configurada de 336 horas. |
| `alloy_data` | Estado del agente y posiciones de logs. |
| `grafana_data` | Configuración interna y estado de Grafana. |
| `suricata_config` | Configuración desplegada de Suricata. |
| `suricata_data` | Reglas ET Open, caché y otros datos runtime. |
| `suricata_logs` | EVE JSON y otros logs generados por el sensor. |

La ingesta de logs y reglas Suricata puede consumir espacio y contener datos
sensibles. Supervisa disco, ajusta retención de Loki y planifica mantenimiento
antes de aumentar el volumen de tráfico. `docker compose down` mantiene datos;
`docker compose down -v` los elimina y no debe utilizarse como limpieza rutinaria.

## 9. Archivos de esta carpeta

| Ruta | Función |
| --- | --- |
| `prometheus/prometheus.yml` | Configuración global, Alertmanager y targets que se scrapean. |
| `prometheus/alerts.yml` | Reglas PromQL para disponibilidad y memoria. |
| `alertmanager/alertmanager.yml` | Agrupación de alertas y receptor local inicial. |
| `loki/loki.yml` | API, esquema TSDB, filesystem, compactor y retención. |
| `alloy/config.alloy` | Descubrimiento de Docker, lectura EVE y escritura hacia Loki. |
| `grafana/provisioning/datasources/datasources.yml` | Datasources de Prometheus, Loki y Alertmanager. |
| `grafana/provisioning/dashboards/dashboards.yml` | Proveedor de dashboards desde archivos. |
| `grafana/dashboards/infrastructure.json` | Dashboard versionado de métricas de infraestructura. |
| `mysql-exporter/Dockerfile` | Imagen personalizada basada en mysqld_exporter. |
| `mysql-exporter/iniciar-exporter.sh` | Genera configuración temporal y ejecuta el exporter. |
| `backup/Dockerfile` | Imagen Alpine con curl, tar y gzip para el job WebDAV. |
| `backup/enviar-backup.sh` | Empaqueta volúmenes, aplica validaciones y envía el archivo a IIS. |

Los formatos YAML y Alloy incluyen comentarios breves en el propio archivo.
El dashboard Grafana es JSON estricto y por eso se explica aquí en vez de
añadir comentarios que invalidarían su formato.

## 10. Diagnóstico

### Revisar estado y logs

```powershell
docker compose ps -a
docker compose logs --tail 150 prometheus alertmanager loki alloy grafana
docker compose logs --tail 150 mysql-exporter cadvisor docker-socket-proxy
```

Para Suricata:

```sh
docker compose --profile security ps
docker compose --profile security logs --tail 150 suricata suricata-rules
```

Para IIS:

```powershell
docker compose --profile backup ps
docker compose --profile backup logs --tail 150 backup-observabilidad
```

### Problemas frecuentes

| Síntoma | Comprobaciones |
| --- | --- |
| Target `DOWN` | Revisa el endpoint, DNS interno Compose, logs del exporter y estado del contenedor objetivo. |
| No hay métricas MySQL | Revisa `MYSQL_EXPORTER_PASSWORD`, el usuario/permisos inicializados y los logs del exporter. |
| No aparecen logs Docker | Revisa Alloy, `docker-socket-proxy` y etiquetas de servicios; consulta `{job="docker"}`. |
| No aparecen logs Suricata | El perfil debe estar activo en Linux; confirma interfaz, captura, salida `eve.json`, montaje y logs de Alloy. |
| Alloy termina con `unrecognized block name "file_match"` | El descubrimiento de archivos se configura con el componente independiente `local.file_match`; conecta sus `targets` a `loki.source.file` y valida con `alloy fmt --test`. |
| Loki rechaza pushes | Revisa resolución `loki:3100`, logs de Loki y configuración `loki.write` en Alloy. |
| El dashboard no aparece | Revisa JSON, proveedor `dashboards.yml`, montaje y logs de Grafana. |
| No llega notificación | El receptor está intencionalmente vacío; configura una integración externa en Alertmanager. |
| IIS devuelve error HTTP | Revisa HTTPS/certificado, PUT/WebDAV, permisos, URL, límites de carga y logs del job. |

### Validaciones útiles

```powershell
docker compose config --quiet
docker compose --profile security config --quiet
docker compose --profile backup config --quiet
docker compose run --rm --no-deps --entrypoint alloy alloy fmt --test /etc/alloy/config.alloy
```

Para validar YAML/JSON o reglas con las herramientas oficiales, ejecuta sus
validadores en contenedores con la misma versión desplegada. Un `config`
correcto solo valida la estructura de Compose: no prueba credenciales remotas,
captura de paquetes ni recepción real de alertas.
