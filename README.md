# Docker CUTSA

Entorno local de pruebas que reúne una aplicación PHP, un clúster MySQL de
tres nodos, ProxySQL y herramientas de observabilidad. Incluye un sensor
Suricata opcional para inspección IDS pasiva en un host Linux y una tarea
opcional de copias WebDAV a IIS.

> **Uso previsto:** laboratorio y aprendizaje en una máquina o UTU. Los valores de
> ejemplo y las opciones locales no constituyen una instalación endurecida de
> producción. No publiques los servicios en Internet sin diseñar autenticación,
> TLS, gestión de secretos, almacenamiento y alta disponibilidad.

## Índice

- [Qué incluye](#qué-incluye)
- [Arquitectura](#arquitectura)
- [Requisitos](#requisitos)
- [Inicio rápido](#inicio-rápido)
- [Direcciones y puertos](#direcciones-y-puertos)
- [Configuración del entorno](#configuración-del-entorno)
- [Servicios de Compose](#servicios-de-compose)
- [Uso de la aplicación](#uso-de-la-aplicación)
- [Perfiles opcionales](#perfiles-opcionales)
- [Persistencia y ciclo de vida](#persistencia-y-ciclo-de-vida)
- [Pruebas y mantenimiento](#pruebas-y-mantenimiento)
- [Seguridad y limitaciones](#seguridad-y-limitaciones)
- [Solución de problemas](#solución-de-problemas)
- [Mapa de archivos](#mapa-de-archivos)
- [Guías por carpeta](#guías-por-carpeta)
- [Documentación complementaria](#documentación-complementaria)

## Qué incluye

| Componente | Responsabilidad |
| --- | --- |
| Apache + PHP 8.3 | Sirve la página de estado Docker CUTSA y el formulario de prueba. |
| MySQL 8.0 / InnoDB Cluster | Tres instancias separadas para Group Replication y quórum. |
| ProxySQL 2.7 | Punto único de conexión SQL; detecta el writer y enruta las consultas. |
| MySQL Shell | Crea o recupera el clúster y prepara usuarios de monitorización. |
| Prometheus | Recopila métricas y evalúa reglas de alerta. |
| Alertmanager | Agrupa alertas y ofrece su interfaz local. |
| Grafana | Presenta el dashboard y las fuentes de métricas, logs y alertas. |
| Loki | Conserva y consulta logs locales. |
| Grafana Alloy | Recoge logs Docker y, cuando se activa Suricata, su archivo EVE JSON. |
| cAdvisor | Expone métricas de contenedores del entorno Linux de Docker. |
| MySQL exporter | Expone métricas de las instancias MySQL para Prometheus. |
| Suricata (opcional) | IDS pasivo sobre una interfaz de un host Docker Engine Linux. |
| Backup WebDAV a IIS (opcional) | Empaqueta telemetría/configuración y la envía con HTTP PUT sobre HTTPS. |

### Versiones de imágenes

Las referencias de imagen usan los tags declarados en Compose/Dockerfiles.
Algunos tags siguen una rama (por ejemplo, `8.0`) y pueden recibir revisiones;
no están fijados por digest. Para reproducibilidad estricta, usa digests
verificados y actualízalos conscientemente.

| Imagen/servicio | Versión declarada |
| --- | --- |
| PHP + Apache | `php:8.3-apache` |
| MySQL | `mysql:8.0` |
| ProxySQL | `proxysql/proxysql:2.7.3` |
| Prometheus | `prom/prometheus:v3.2.1` |
| Alertmanager | `prom/alertmanager:v0.28.1` |
| Loki | `grafana/loki:3.5.0` |
| Grafana Alloy | `grafana/alloy:v1.8.2` |
| Grafana | `grafana/grafana:11.6.0` |
| cAdvisor | `gcr.io/cadvisor/cadvisor:v0.52.1` |
| mysqld_exporter | `prom/mysqld-exporter:v0.17.2` |
| Suricata (opcional) | `jasonish/suricata:8.0` |
| Proxy Docker API | `tecnativa/docker-socket-proxy:0.3.0` |

## Arquitectura

```text
Navegador
  ├── http://localhost:8080 ──> Apache/PHP ──> ProxySQL:6033
  │                                            └──> MySQL InnoDB Cluster
  ├── http://localhost:3000 ──> Grafana
  │                               ├── Prometheus ──> cAdvisor
  │                               │              └──> MySQL exporter ──> MySQL
  │                               ├── Loki <── Alloy <── logs de contenedores
  │                               │          └──────── <── Suricata EVE (opcional)
  │                               └── Alertmanager
  ├── http://localhost:9090 ──> Prometheus
  └── http://localhost:9093 ──> Alertmanager

Perfil backup (opcional): volúmenes de observabilidad/Suricata ──HTTPS/WebDAV──> IIS
```

La página prueba PHP/Apache al responder y prueba la base mediante una consulta
SQL real a través de ProxySQL. El envío del formulario prueba también una
escritura y lectura. La página no reemplaza un health check de cada servicio
del stack.

## Requisitos

- Docker Desktop en Windows con motor Linux y Docker Compose v2 o compatible.
- Acceso a Docker Hub para descargar las imágenes base y los servicios.
- Puertos locales disponibles: `8080`, `6033`, `3000`, `9090` y `9093`.
- Para Suricata en una interfaz del host: Docker Engine en **Linux**,
  privilegios para capturar paquetes y una interfaz válida. Docker Desktop en
  Windows no expone al contenedor toda la red física de Windows.
- IIS/WebDAV HTTPS y credenciales de escritura dedicadas, solo si se habilita
  el perfil de copias.

Los ejemplos siguientes se ejecutan desde la raíz de este proyecto. En Windows
se pueden usar en PowerShell; en Linux/macOS, en una terminal compatible.

## Inicio rápido

1. Crea el archivo local de configuración:

   ```powershell
  Copy-Item env.ejemplo .env
  ```

  En Linux/macOS, usa `cp env.ejemplo .env`.

   Edita `.env` y cambia al menos las contraseñas de MySQL, ProxySQL y Grafana.
   No compartas ni subas `.env`.

2. Valida la configuración y construye/inicia los servicios normales:

   ```powershell
   docker compose config --quiet
   docker compose up --build -d
   ```

3. Revisa el estado y espera a que finalicen las tareas de inicialización:

   ```powershell
   docker compose ps -a
   docker compose logs -f inicializar-cluster configurar-proxy
   ```

   Cuando termine la inicialización, abre <http://localhost:8080>. Comprueba
   también Grafana en <http://localhost:3000>.

4. Para detener los contenedores sin eliminar datos:

   ```powershell
   docker compose down
   ```

No agregues `-v` a `docker compose down` si quieres conservar las bases de
datos, los dashboards, los logs y las métricas.

## Comprobar los tres nodos MySQL

`docker compose ps -a` confirma si los contenedores están activos, pero no
demuestra por sí solo que Group Replication tenga a todos los miembros en línea.
Comprueba el estado del clúster y luego consulta cada servidor:

```powershell
docker compose logs --tail 80 inicializar-cluster
docker compose exec -it main mysql -uroot -p
```

Escribe la contraseña de `MYSQL_ROOT_PASSWORD` cuando MySQL la solicite. En el
cliente interactivo, consulta la topología:

```sql
SELECT MEMBER_HOST, MEMBER_ROLE, MEMBER_STATE
FROM performance_schema.replication_group_members
ORDER BY MEMBER_HOST;
```

Los tres miembros deben aparecer como `ONLINE`, con un miembro `PRIMARY` y dos
`SECONDARY`. El nodo primario puede cambiar después de una elección automática.
Sal del cliente con `exit` y repite la conexión para `fallback` y `slave`;
cada nodo debe mostrar la misma membresía. Comprueba además el modo local de
cada uno:

```powershell
docker compose exec -it fallback mysql -uroot -p
docker compose exec -it slave mysql -uroot -p
```

Ejecuta las mismas consultas SQL en cada sesión.

```sql
SELECT @@hostname AS nodo, @@read_only AS solo_lectura,
       @@super_read_only AS solo_lectura_estricta;
```

En el primario ambos indicadores valen `0`; en los secundarios valen `1`.
Finalmente, envía un mensaje normal desde <http://localhost:8080> y ejecuta
`SELECT message, created_at FROM app.demo_messages ORDER BY id DESC LIMIT 5;`
en cada nodo: el mensaje debe aparecer en los tres. No escribas directamente
en un secundario ni elimines volúmenes como método de diagnóstico.

## Direcciones y puertos

| Servicio | Dirección desde el host | Alcance |
| --- | --- | --- |
| Aplicación web | <http://localhost:8080> | Publicada en todas las interfaces por el mapeo `8080:80`; limita el acceso con el firewall si corresponde. |
| ProxySQL / aplicación | `localhost:6033` | Puerto MySQL publicado en interfaces del host; restríngelo con firewall. La aplicación usa el nombre interno `proxy`. |
| Grafana | <http://localhost:3000> | Vinculada a `127.0.0.1`; solo acceso local. |
| Prometheus | <http://localhost:9090> | Vinculado a `127.0.0.1`; incluye `/targets`. |
| Alertmanager | <http://localhost:9093> | Vinculado a `127.0.0.1`. |
| ProxySQL admin | No publicado | `6032`, accesible dentro de la red de Compose. |
| MySQL | No publicado | `3306` y Group Replication `33061`, solo entre servicios. |
| Loki / Alloy / cAdvisor / exporter | No publicados | Endpoints internos del stack. |
| Suricata | Sin puerto HTTP | Captura en modo host Linux; los eventos se consultan en Grafana/Loki. |

Las URLs de las tarjetas de la web apuntan a `localhost`, adecuadas para el
navegador en la misma máquina. Si publicas el panel detrás de un proxy o accedes
desde otra máquina, revisa y cambia esas URLs.

La página también ofrece consolas de logs de solo lectura por servicio, consultadas
desde Loki y limitadas a servicios conocidos, períodos de hasta una hora y 100
líneas. Los logs contienen información operativa: aunque no permiten ejecutar
comandos, restringe el puerto `8080` a una red de confianza y no publiques el
panel en Internet.

## Configuración del entorno

`env.ejemplo` sirve como plantilla. Compose usa valores por defecto locales si
falta `.env`; esos defaults son solo para desarrollo y no deben usarse en una
instalación expuesta.

| Variable | Uso |
| --- | --- |
| `MYSQL_ROOT_PASSWORD` | Contraseña administrativa de los tres nodos. |
| `MYSQL_DATABASE` | Esquema de la aplicación, por defecto `app`. |
| `MYSQL_USER` / `MYSQL_PASSWORD` | Usuario que utiliza PHP y ProxySQL. |
| `MYSQL_MONITOR_PASSWORD` | Usuario `proxysql_monitor` para detectar el estado del clúster. |
| `MYSQL_EXPORTER_PASSWORD` | Usuario de lectura `prometheus_exporter`. |
| `PROXYSQL_ADMIN_USER` / `PROXYSQL_ADMIN_PASSWORD` | Acceso interno a la interfaz administrativa de ProxySQL. |
| `GRAFANA_ADMIN_USER` / `GRAFANA_ADMIN_PASSWORD` | Cuenta administradora local de Grafana. |
| `SURICATA_INTERFACE` | Interfaz Linux que captura Suricata al activar `security`; ejemplo `eth0`. |
| `BACKUP_IIS_URL` | URL **HTTPS** de la carpeta WebDAV receptora. |
| `BACKUP_IIS_USERNAME` / `BACKUP_IIS_PASSWORD` | Cuenta dedicada para cargar copias en IIS. |
| `BACKUP_INTERVAL_SECONDS` | Intervalo de copias; `86400` equivale a 24 horas. |

Usa contraseñas generadas y únicas. Evita comillas simples, comillas dobles,
barras invertidas y saltos de línea en las contraseñas utilizadas por scripts
SQL de inicialización, porque esos valores se interpolan en comandos SQL.

## Servicios de Compose

- **`web`** construye la imagen definida en `Dockerfile`, monta `src/` para
  permitir cambios inmediatos y depende de la configuración de ProxySQL.
- **`main`, `fallback`, `slave`** construyen imágenes derivadas de MySQL 8.0,
  montan una configuración de Group Replication propia y conservan los datos
  en tres volúmenes distintos. Los alias DNS históricos `mysql-1/2/3` se
  mantienen para la metadata existente del clúster y ProxySQL. Los nombres no
  fijan el rol: Group Replication elige el `PRIMARY` y deja los demás en solo
  lectura.
- **`inicializar-cluster`** espera a que estén sanos los tres nodos, conecta
  MySQL Shell, crea el clúster si no hay metadatos o recupera el clúster
  registrado tras una caída completa, y prepara usuarios y permisos. La
  recuperación conserva los volúmenes; no borres metadatos para resolver un
  error de arranque.
- **`proxy`** expone `6033` para consultas de aplicaciones y conserva su estado
  administrativo en `proxysql_data`.
- **`configurar-proxy`** carga hostgroups, nodos, monitor y usuario de la
  aplicación; termina al completar la configuración.
- **`docker-socket-proxy`** limita las consultas al Docker API que Alloy y
  cAdvisor necesitan. No se monta el socket directamente en la interfaz web.
- **`mysql-exporter`** genera en tiempo de ejecución un archivo de credenciales
  de permisos restringidos y publica métricas solo a la red interna.
- **`cadvisor`** lee métricas de contenedores con acceso privilegiado a partes
  del entorno Linux de Docker.
- **`prometheus`**, **`alertmanager`**, **`loki`**, **`alloy`** y **`grafana`**
  forman la tubería de métricas, alertas y logs descrita en
  [`observability/README.md`](./observability/README.md).
- **`suricata-rules`** y **`suricata`** pertenecen al perfil `security`.
- **`backup-observabilidad`** pertenece al perfil `backup`.

Los `depends_on` coordinan el orden y algunas condiciones de inicio, pero no
equivalen a una garantía de salud permanente. Revisa `docker compose ps -a` y
los logs de un servicio cuando la página o un dashboard indiquen un problema.

## Uso de la aplicación

La interfaz está separada por responsabilidad:

- `src/index.html` contiene solo estructura y contenido HTML estático.
- `src/styles.css` controla el diseño adaptable, los colores y animaciones.
- `src/app.js` obtiene el estado y gestiona el formulario en el navegador.
- `src/api.php` es el backend: mantiene la sesión/CSRF y habla con MySQL.
- `src/logo-docker-cutsa.png` es el recurso gráfico de marca.

Al abrir la página:

1. Apache sirve `index.html`, `styles.css`, `app.js` y el logo sin ejecutar PHP
   para renderizar la interfaz.
2. `app.js` solicita `GET /api.php`; la API obtiene configuración SQL de las
   variables de entorno y se conecta a `proxy:6033`.
3. La API consulta versión y nombre del nodo, crea `demo_messages` si aún no
   existe, y devuelve el estado, token CSRF y hasta cinco mensajes como JSON.
4. Al enviar el formulario, JavaScript manda `POST /api.php` con el token CSRF
   y el mensaje. PHP valida longitud, inserta con una consulta preparada y
   devuelve JSON; JavaScript actualiza la vista sin recargar toda la página.

Es una prueba de demostración, no una interfaz de administración de MySQL.
La web no muestra ni modifica la configuración del clúster.

### API de la página

| Solicitud | Resultado |
| --- | --- |
| `GET /api.php` | Estado de conexión, versión de PHP/MySQL, nodo, token CSRF y mensajes recientes. Si ProxySQL/MySQL no responde, devuelve estado `503` y un mensaje seguro para mostrar. |
| `POST /api.php` | Recibe JSON `{"message":"..."}` y el encabezado `X-CSRF-Token`; valida la sesión y guarda el texto de prueba. Devuelve el estado actualizado o un error `403`, `422` o `503`. |

Las respuestas llevan `Cache-Control: no-store`; el detalle de excepciones SQL
se escribe en el log PHP y no se devuelve al navegador.

## Perfiles opcionales

### Suricata IDS en Linux

Suricata se mantiene fuera del inicio normal. En un host con Docker Engine
Linux:

1. Identifica la interfaz con `ip -br link`.
2. Configura `SURICATA_INTERFACE` en `.env`.
3. Inicia reglas e IDS:

   ```sh
   docker compose --profile security up -d suricata
   ```

4. Consulta eventos en Grafana → **Explore** → Loki, usando:

   ```logql
   {job="suricata"} | json | event_type="alert"
   ```

Este sensor inspecciona el tráfico visible en una interfaz del host en modo
pasivo: no es IPS y no bloquea paquetes. Puede requerirse un puerto espejo
SPAN para ver tráfico entre otros equipos. Docker Desktop Windows no puede
capturar toda la red física del host. Detalles, permisos y actualización de
reglas: [`observability/README.md`](./observability/README.md#ids-de-red-con-suricata-linux).

### Copias WebDAV a IIS

Configura IIS con WebDAV y HTTPS, rellena las variables `BACKUP_IIS_*` y
`BACKUP_INTERVAL_SECONDS` en `.env`, y luego ejecuta:

```powershell
docker compose --profile backup up --build -d
docker compose --profile backup logs -f backup-observabilidad
```

La copia incluye datos y configuración de observabilidad y volúmenes
Suricata; **no incluye los volúmenes MySQL**. Al leer los datos mientras los
servicios están activos, el archivo no es una copia transaccional garantizada.
Consulta la guía de IIS para límites de carga, retención y restauración.

## Persistencia y ciclo de vida

| Volumen | Datos persistidos |
| --- | --- |
| `mysql_1_data`, `mysql_2_data`, `mysql_3_data` | Datos de cada instancia MySQL. |
| `proxysql_data` | Configuración y estado persistidos de ProxySQL. |
| `prometheus_data` | Series temporales de Prometheus. |
| `alertmanager_data` | Estado de Alertmanager. |
| `loki_data` | Índices y bloques de logs. |
| `alloy_data` | Posiciones del lector de archivos y estado local de Alloy. |
| `grafana_data` | Estado local de Grafana. |
| `suricata_config`, `suricata_data`, `suricata_logs` | Configuración, reglas/estado y eventos del sensor opcional. |

`docker compose down` conserva los volúmenes. `docker compose down -v` los
elimina y destruye los datos que contengan; úsalo solo si entiendes el efecto y
quieres reiniciar el laboratorio desde cero.

Las imágenes MySQL incorporan su configuración porque en Windows ciertos
montajes directos de archivos de configuración podían ser ignorados por los
permisos del archivo compartido. Los Dockerfiles derivados colocan los archivos
con permisos `0644`.

## Pruebas y mantenimiento

```powershell
# Revisar la sintaxis general, con o sin activar perfiles.
docker compose config --quiet
docker compose --profile security config --quiet
docker compose --profile backup config --quiet
docker compose run --rm --no-deps --entrypoint php web -l /var/www/html/api.php
# Opcional en el host, si Node.js está instalado:
node --check .\src\app.js

# Estado, historial y logs.
docker compose ps -a
docker compose logs --tail 100 web
docker compose logs -f main fallback slave inicializar-cluster
docker compose logs -f proxy configurar-proxy
docker compose logs -f prometheus alertmanager loki alloy grafana

# Reiniciar un servicio normal sin tocar sus volúmenes.
docker compose restart web
```

Para inspeccionar los objetivos de Prometheus, abre
<http://localhost:9090/targets>. Las reglas se encuentran en
`observability/prometheus/alerts.yml`; el estado y el envío se revisan en
Alertmanager y Grafana. Alloy ya usa Grafana Alloy: este proyecto no utiliza
Promtail ni Vector.

## Seguridad y limitaciones

- El panel web y el puerto SQL `6033` están publicados en el host; usa firewall
  y redes confiables. Grafana, Prometheus y Alertmanager se enlazan solo a
  `127.0.0.1`.
- Loki no tiene autenticación dentro de la red privada de Compose. No publiques
  su puerto ni lo hagas accesible desde redes no confiables.
- cAdvisor está en modo `privileged` y lee montajes del host necesarios para
  las métricas. Revisa estos permisos antes de usar el stack fuera de un
  laboratorio.
- El proxy del socket Docker reduce las operaciones disponibles, pero Docker
  sigue siendo una API sensible; limita su red y no publiques el proxy.
- Suricata necesita `network_mode: host` y capacidades de red del host Linux.
  Los eventos pueden contener metadatos sensibles; limita quién puede
  consultar Grafana/Loki.
- La disponibilidad de tres nodos en un único equipo no protege contra la
  pérdida del host. Este clúster de laboratorio no proporciona alta
  disponibilidad geográfica.
- El backup IIS de telemetría no incluye las bases MySQL ni es consistente con
  escrituras concurrentes. Comprueba periódicamente la descarga y la
  restauración; implementa una estrategia MySQL aparte.
- Alertmanager viene sin receptores externos configurados. La vista local de
  alertas no envía correo, SMS ni mensajes hasta configurar un receptor.
- La integración actual de Suricata envía logs a Loki. No configura bloqueo
  IPS, respuestas automáticas ni reglas de notificación de Alertmanager para
  cada evento Suricata.

## Solución de problemas

### `ERR_CONNECTION_REFUSED` en `localhost:8080`

```powershell
docker compose ps -a
docker compose logs --tail 150 web inicializar-cluster proxy configurar-proxy
```

La aplicación espera a `configurar-proxy`, que a su vez necesita que el clúster
y ProxySQL estén listos. Resuelve primero el primer servicio que terminó con
error; después ejecuta `docker compose up --build -d`.

### La web carga, pero MySQL indica desconexión

- Revisa las variables de `.env` y confirma que `MYSQL_USER`,
  `MYSQL_PASSWORD` y `MYSQL_DATABASE` coincidan entre nodos, proxy y web.
- Consulta `docker compose logs inicializar-cluster proxy configurar-proxy`.
- Revisa `http://localhost:9090/targets`: los tres objetivos `mysql` deben
  estar `UP`. Usa la sección [Comprobar los tres nodos MySQL](#comprobar-los-tres-nodos-mysql)
  para verificar el estado y la réplica directamente en cada instancia.
- Si el log dice que ya existe `mysql_innodb_cluster_metadata`, el inicializador
  intenta recuperar el clúster registrado tras la caída completa. Si falla,
  conserva los datos y usa el siguiente error de MySQL Shell para diagnosticarlo.
- No elimines volúmenes para corregir una contraseña sin planificar la
  recuperación: las credenciales iniciales se establecen al crear los datos y
  ProxySQL también conserva estado en un volumen.

### Grafana abre, pero no hay métricas o logs

- Revisa `http://localhost:9090/targets` y los logs de Prometheus/exporter.
- Comprueba los logs de `alloy`, `docker-socket-proxy` y `loki`.
- En Grafana → Explore selecciona Loki y consulta `{job="docker"}`.
- Los eventos de Suricata solo aparecerán después de activar su perfil sobre
  Linux y si hay eventos EVE escritos en el archivo compartido.

### Suricata no puede abrir la interfaz

Confirma que es Docker Engine nativo en Linux, que la interfaz existe dentro
del host, que `SURICATA_INTERFACE` está bien escrito y que el motor permite
`NET_ADMIN`, `NET_RAW` y `SYS_NICE`. En Docker Desktop Windows, el sensor de
red del host no está soportado por esta configuración.

### Las copias a IIS fallan

Revisa `BACKUP_IIS_URL` (debe comenzar con `https://`), credenciales, permisos
de autoría WebDAV, certificado TLS, límites de solicitud y espacio disponible.
Consulta los errores con:

```powershell
docker compose --profile backup logs backup-observabilidad
```

No uses una cuenta administrativa de IIS.

## Mapa de archivos

### Raíz

| Archivo | Función |
| --- | --- |
| `README.md` | Guía general, arquitectura, operación y mapa del proyecto. |
| `compose.yaml` | Servicios, redes implícitas, dependencias, puertos, perfiles y volúmenes. |
| `Dockerfile` | Imagen Apache/PHP de la aplicación. |
| `env.ejemplo` | Plantilla documentada de variables de entorno; copiar como `.env`. |
| `.gitignore` | Evita incorporar las credenciales locales `.env` al control de versiones. |

### MySQL y ProxySQL

| Archivo | Función |
| --- | --- |
| `mysql/Dockerfile.node-1` | Imagen derivada del primer nodo con configuración MySQL propia. |
| `mysql/Dockerfile.node-2` | Imagen derivada del segundo nodo con configuración MySQL propia. |
| `mysql/Dockerfile.node-3` | Imagen derivada del tercer nodo con configuración MySQL propia. |
| `mysql/nodo-1.cnf` | ID, binlog, GTID y Group Replication del nodo 1. |
| `mysql/nodo-1.cnf` | ID, binlog, GTID y Group Replication de `main`; conserva alias interno `mysql-1`. |
| `mysql/nodo-2.cnf` | Configuración de `fallback`; conserva alias interno `mysql-2`. |
| `mysql/nodo-3.cnf` | Configuración de `slave`; conserva alias interno `mysql-3`. |
| `mysql/inicializar-cluster.js` | Crea/recupera InnoDB Cluster y usuarios de monitorización/exporter. |
| `mysql-shell/Dockerfile` | Instala MySQL Shell desde el repositorio comunitario oficial de MySQL. |
| `proxysql/Dockerfile` | Imagen ProxySQL con entrypoint preparado para este stack. |
| `proxysql/iniciar-proxysql.sh` | Genera configuración inicial con credenciales del entorno e inicia ProxySQL. |
| `proxysql/Dockerfile.configurar` | Imagen utilitaria que empaqueta el script SQL de configuración. |
| `proxysql/configurar-proxy.sh` | Espera a ProxySQL y aplica monitorización, hostgroups, servidores y usuario SQL. |

### Aplicación

| Archivo | Función |
| --- | --- |
| `src/index.html` | Estructura y textos estáticos del panel; lo sirve Apache como documento de inicio. |
| `src/app.js` | Consulta `api.php`, actualiza las comprobaciones y envía el formulario asíncronamente. |
| `src/api.php` | API JSON en PHP: sesión, token CSRF, conexión a ProxySQL y acceso preparado a `demo_messages`. |
| `src/styles.css` | Diseño adaptable, paleta, tarjetas y animaciones accesibles del panel. |
| `src/logo-docker-cutsa.png` | Logotipo utilizado en encabezado y pie de página. |

### Observabilidad, Suricata y copias

| Archivo | Función |
| --- | --- |
| `observability/README.md` | Guía detallada de observabilidad, Suricata y backup IIS. |
| `observability/prometheus/prometheus.yml` | Intervalos, Alertmanager, reglas y objetivos scrape. |
| `observability/prometheus/alerts.yml` | Reglas de disponibilidad y uso elevado de memoria. |
| `observability/alertmanager/alertmanager.yml` | Agrupación y receptores de alertas. |
| `observability/loki/loki.yml` | API, almacenamiento local y retención de logs. |
| `observability/alloy/config.alloy` | Descubrimiento de Docker, lectura de EVE JSON y envío a Loki. |
| `observability/grafana/provisioning/datasources/datasources.yml` | Fuentes de datos Prometheus, Loki y Alertmanager. |
| `observability/grafana/provisioning/dashboards/dashboards.yml` | Descubrimiento del dashboard versionado. |
| `observability/grafana/dashboards/infrastructure.json` | Dashboard de infraestructura en formato JSON de Grafana. |
| `observability/mysql-exporter/Dockerfile` | Imagen del exporter con entrypoint personalizado. |
| `observability/mysql-exporter/iniciar-exporter.sh` | Crea configuración de credenciales temporal y ejecuta exporter. |
| `observability/backup/Dockerfile` | Imagen ligera para empaquetar y transferir datos por HTTPS. |
| `observability/backup/enviar-backup.sh` | Valida variables, crea tar.gz y lo envía a IIS por WebDAV. |

El dashboard está en JSON, un formato que no admite comentarios embebidos.
Su propósito, provisión y consultas se documentan en las guías de Grafana en
lugar de modificar el JSON con comentarios inválidos.

## Guías por carpeta

Cada área mantiene una guía junto a sus archivos. Estas guías explican qué
contiene la carpeta, qué hace cada archivo, por qué existe y cómo validar sus
cambios. La guía raíz sigue siendo el punto de entrada y describe la operación
del stack completo.

| Carpeta | Guía |
| --- | --- |
| `mysql/` | [Nodos, Group Replication y recuperación](./mysql/README.md) |
| `mysql-shell/` | [Instalación de MySQL Shell](./mysql-shell/README.md) |
| `proxysql/` | [Enrutamiento SQL y configuración del proxy](./proxysql/README.md) |
| `src/` | [Interfaz, estilos y API](./src/README.md) |
| `observability/` | [Operación del stack de observabilidad](./observability/README.md) |
| `observability/alertmanager/` | [Agrupación y receptores de alertas](./observability/alertmanager/README.md) |
| `observability/alloy/` | [Recolección de logs con Alloy](./observability/alloy/README.md) |
| `observability/backup/` | [Copias de observabilidad a IIS](./observability/backup/README.md) |
| `observability/grafana/` | [Dashboards y aprovisionamiento](./observability/grafana/README.md) |
| `observability/grafana/dashboards/` | [Dashboard de infraestructura](./observability/grafana/dashboards/README.md) |
| `observability/grafana/provisioning/` | [Configuración versionada de Grafana](./observability/grafana/provisioning/README.md) |
| `observability/grafana/provisioning/dashboards/` | [Carga automática de dashboards](./observability/grafana/provisioning/dashboards/README.md) |
| `observability/grafana/provisioning/datasources/` | [Fuentes de métricas, logs y alertas](./observability/grafana/provisioning/datasources/README.md) |
| `observability/loki/` | [Almacenamiento y retención de logs](./observability/loki/README.md) |
| `observability/mysql-exporter/` | [Métricas de los nodos MySQL](./observability/mysql-exporter/README.md) |
| `observability/prometheus/` | [Recolección de métricas y reglas](./observability/prometheus/README.md) |

## Documentación complementaria

Consulta [`observability/README.md`](./observability/README.md) para configurar
scrapes y alertas, consultar Loki, ejecutar Suricata en Linux y preparar IIS
para copias periódicas.
