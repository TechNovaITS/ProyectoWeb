# ProxySQL

ProxySQL es el punto de conexión entre la aplicación y el clúster. La
aplicación se conecta a `proxy:6033`; la interfaz administrativa `6032` solo se
usa en la red interna de Compose. Centralizar el enrutamiento evita que PHP
tenga que conocer qué nodo es el primario.

## Archivos

| Archivo | Función |
| --- | --- |
| `Dockerfile` | Prepara la imagen del servicio ProxySQL y su script de inicio. |
| `iniciar-proxysql.sh` | Genera una configuración inicial privada usando las variables de entorno e inicia el proceso. |
| `Dockerfile.configurar` | Prepara la imagen temporal que aplica la configuración SQL. |
| `configurar-proxy.sh` | Espera al administrador, define monitoreo, grupos, servidores y usuario de aplicación. |

La tarea `configurar-proxy` termina cuando carga y guarda la configuración.
La aplicación web depende de esa tarea completada para no aceptar tráfico
antes de que exista una ruta SQL configurada.

Los hostgroups separan el destino de escritura del de lectura y el grupo de
respaldo. La tabla de miembros de Group Replication permite que ProxySQL
actualice las funciones de los servidores según el estado que observa.

## Variables y validación

Las credenciales se reciben desde `.env`; no las escribas en estos scripts.
Un cambio de contraseña debe coordinarse con MySQL porque los volúmenes ya
inicializados conservan sus cuentas.

```powershell
docker compose config --quiet
docker compose logs --tail 100 proxy configurar-proxy
docker compose exec proxy mysql -h 127.0.0.1 -P 6032 -u clusteradmin -p
```

El último comando es para diagnóstico administrativo: usa el usuario y
contraseña de ProxySQL definidos en `.env`. No publiques el puerto `6032`.
