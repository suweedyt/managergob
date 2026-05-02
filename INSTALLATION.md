# Manual de instalación - ManagerGob

Este documento describe cómo instalar y poner en marcha el proyecto `managergob` sin usar contenedores (sin Docker), en un servidor Linux (o entorno local) con PHP y Node instalados.

**Resumen**
- Requisitos: PHP, extensiones, Composer, Node.js, npm, MySQL/MariaDB, Git.
- Pasos: clonar repo → instalar dependencias PHP y Node → crear `.env` → generar `APP_KEY` → crear base de datos → ejecutar migraciones/seeders → build de assets → permisos → ejecutar la app.

**Requisitos mínimos**
- **PHP**: 8.2 o superior (el build registra `8.2.29`).
- **Extensiones PHP necesarias**: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pcre`, `pdo`, `session`, `tokenizer`, `xml`, `pdo_mysql`.
- **Composer**: para instalar dependencias PHP.
- **Node.js**: se usó `22.x` en el build (por ejemplo `22.21.1`).
- **npm**: compatible con Node 22.
- **Base de datos**: MySQL o MariaDB (ejemplo con MySQL).
- **Git**: para clonar el repositorio.

**Clonar el repositorio**

1. En el servidor o máquina local, elige el directorio donde alojar el proyecto y clona:

```
git clone <URL-del-repo> managergob
cd managergob
```

Reemplaza `<URL-del-repo>` por la URL de tu repositorio.

# Manual de despliegue (producción) - ManagerGob

Este documento describe los pasos mínimos para desplegar `managergob` en un servidor Linux nuevo en producción. Está centrado en lo estrictamente necesario para poner Laravel en funcionamiento: instalación de paquetes del sistema, dependencias PHP con Composer, build de assets con Node (si corresponde), configuración de `.env`, migraciones y permisos.

**Resumen**
- Preparar el servidor (paquetes de sistema y PHP-FPM).
- Instalar dependencias PHP vía Composer (Laravel) y construir assets con Node.
- Configurar `.env` y generar `APP_KEY`.
- Crear base de datos y ejecutar migraciones.
- Ajustar permisos y optimizaciones de Laravel.

## Requisitos mínimos del servidor
- Sistema operativo: Debian/Ubuntu (ejemplos con apt). Adapta para otras distros.
- PHP 8.2+ con PHP-FPM.
- Extensiones PHP: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pcre`, `pdo`, `session`, `tokenizer`, `xml`, `pdo_mysql`.
- Composer disponible en `PATH`.
- Node.js 22.x y `npm` (sólo para compilar assets, no para ejecutar Laravel).
- Servidor de base de datos MySQL/MariaDB accesible desde la máquina.
- Git para clonar el repositorio.

## Preparar paquetes del sistema (ejemplo minimal)

```fish
sudo apt update && sudo apt upgrade -y
sudo apt install -y git curl unzip zip nginx mysql-client
sudo apt install -y php8.2-fpm php8.2-cli php8.2-mbstring php8.2-xml php8.2-mysql php8.2-curl php8.2-zip php8.2-bcmath
sudo apt install -y build-essential
```

Instalar Composer (si no existe):

```fish
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

Instalar Node.js 22.x (NodeSource):

```fish
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo bash -
sudo apt install -y nodejs
```

## Clonar el repositorio y preparar el proyecto

```fish
cd /var/www/html
sudo git clone <URL-del-repo> managergob
cd managergob
sudo chown -R $USER:$USER .
```

Reemplaza `<URL-del-repo>` por la URL real.

## Instalar dependencias de Laravel (Composer)

Las dependencias PHP de Laravel se instalan con Composer. Node se usa únicamente para compilar los assets frontend.

```fish
composer install --optimize-autoloader --no-interaction --no-dev
```

Si tu despliegue requiere instalar también dependencias de desarrollo para build, omite `--no-dev` temporalmente, o ejecuta `npm ci` como usuario de despliegue.

## Compilar assets (si aplica)

Si el proyecto incluye frontend que requiere Vite/Node, ejecuta:

```fish
npm ci
npm run build
```

Esto genera los archivos en `public/build`.

## Configurar `.env` y generar `APP_KEY`

```fish
cp .env.example .env
# Edita .env con tus valores de producción (APP_URL, DB_*, etc.)
php artisan key:generate
```

En `.env` asegúrate de:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL` apuntando a la URL pública
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` correctos

## Crear base de datos y usuario (ejemplo MySQL)

Ejecuta en tu servidor de base de datos (o desde la máquina si tienes cliente):

```sql
CREATE DATABASE managergob CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'managergob_user'@'localhost' IDENTIFIED BY 'TU_CONTRASEÑA_SEGURA';
GRANT ALL PRIVILEGES ON managergob.* TO 'managergob_user'@'localhost';
FLUSH PRIVILEGES;
```

Actualiza `.env` con esos valores.

## Ejecutar migraciones en producción

```fish
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder --force
```

Si el proyecto usa `SESSION_DRIVER=database`, crea la tabla de sesiones antes de migrar:

```fish
php artisan session:table
php artisan migrate --force
```

## Permisos y enlaces públicos

```fish
sudo mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
php artisan storage:link
```

Reemplaza `www-data` por el usuario que use tu servidor web si es distinto.

## Optimización para producción

```fish
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## Configuración mínima de Nginx (referencia)

Coloca un bloque de servidor que apunte a `public/` como `root` y pase PHP a PHP-FPM. Ejemplo de referencia (ajusta `server_name` y rutas):

```
server {
  listen 80;
  server_name ejemplo.com;
  root /var/www/html/managergob/public;

  index index.php index.html;

  location / {
    try_files $uri $uri/ /index.php?$query_string;
  }

  location ~ \.php$ {
    include snippets/fastcgi-php.conf;
    fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
  }

  location ~ /\.ht {
    deny all;
  }
}
```

Después de configurar, reinicia PHP-FPM y recarga Nginx:

```fish
sudo systemctl restart php8.2-fpm
sudo systemctl reload nginx
```

## Notas finales
- Laravel se instala y prepara principalmente con `composer` y los comandos `artisan` (migraciones, `key:generate`, `storage:link`, caches). Node/`npm` sólo es necesario si el proyecto incluye assets a compilar (Vite).
- No se incluyen en este documento pasos relacionados con colas, cron o SSL: sólo lo mínimo necesario para que la aplicación funcione en un servidor de producción nuevo.

---

Si quieres que haga el commit de esta versión final de `INSTALLATION.md` al branch `create-editors`, lo hago ahora.
