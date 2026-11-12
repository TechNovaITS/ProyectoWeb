# Imagen de MySQL Shell

Esta carpeta construye la herramienta administrativa `mysqlsh`, usada para
crear y recuperar InnoDB Cluster. Se mantiene separada de las imágenes de los
servidores: Shell es una tarea de administración puntual y no necesita residir
en cada nodo de base de datos.

## Archivo

| Archivo | Función |
| --- | --- |
| `Dockerfile` | Parte de Oracle Linux, configura el repositorio oficial de MySQL e instala MySQL Shell. |

La imagen se crea como servicio `inicializar-cluster` en `compose.yaml`. Ese
servicio monta `mysql/inicializar-cluster.js` en solo lectura, recibe secretos
por variables de entorno y sale con código cero únicamente si terminó su
trabajo.

## Reconstrucción y diagnóstico

Desde la raíz del proyecto:

```powershell
docker compose build inicializar-cluster
docker compose run --rm inicializar-cluster
```

La segunda orden ejecuta el procedimiento real y por eso depende de que los
tres nodos estén iniciados y sanos. No ejecutes `docker compose down -v` para
reparar un error de Shell: eso borraría datos persistentes.

El Dockerfile descarga un paquete de repositorio y una clave del proveedor
MySQL; cambiar la versión base requiere confirmar que ambos sigan siendo
compatibles. El script de inicialización se explica en
[`../mysql/README.md`](../mysql/README.md).
