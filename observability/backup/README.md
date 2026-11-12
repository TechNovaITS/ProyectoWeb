# Copias de observabilidad a IIS

Esta tarea opcional empaqueta configuraciones y volúmenes de observabilidad en
un archivo comprimido y lo envía a una carpeta IIS/WebDAV mediante HTTPS. No
incluye los volúmenes de MySQL ni reemplaza una copia de seguridad de base de
datos.

## Archivos

| Archivo | Función |
| --- | --- |
| `Dockerfile` | Construye un contenedor Alpine pequeño con `curl`, `tar` y `gzip`. |
| `enviar-backup.sh` | Valida variables, genera un archivo comprimido y lo carga por WebDAV en intervalos regulares. |

El servicio se activa con el perfil `backup`, no durante el arranque normal.
Los volúmenes de origen se montan en modo solo lectura para que la tarea no
modifique los datos de los servicios.

## Configuración y uso

Define `BACKUP_IIS_URL` con una URL `https://`, y configura una cuenta WebDAV
dedicada en `.env`. Después inicia:

```powershell
docker compose --profile backup config --quiet
docker compose --profile backup up --build -d
docker compose --profile backup logs -f backup-observabilidad
```

El archivo puede capturar directorios mientras sus servicios siguen
escribiendo; por ello no debe considerarse una copia consistente ni una
estrategia única de recuperación. Define retención y cuotas en IIS y prueba
periódicamente que los archivos se puedan descargar y restaurar.
