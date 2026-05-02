# Manual de instalación - ManagerGob

Este documento describe cómo instalar y poner en marcha el proyecto `managergob` sin usar contenedores (sin Docker), en un servidor Linux (o entorno local) con PHP y Node instalados.

**Resumen**
- Requisitos: PHP, extensiones, Composer, Node.js, npm, MySQL/MariaDB, Git.
- Pasos: clonar repo → instalar dependencias PHP y Node → crear `.env` → generar `APP_KEY` → crear base de datos → ejecutar migraciones → build de assets → permisos → ejecutar la app.

**Requisitos mínimos**
- **Laravel**: 12.x (`v12.58.0` en el build actual).
- **PHP**: 8.2 o superior (el build registra `8.5.5`).
- **Extensiones PHP necesarias**: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pcre`, `pdo`, `session`, `tokenizer`, `xml`, `pdo_mysql`.
- **Composer**: 2.x (el build registra `2.9.7`).
- **Node.js**: se usó `22.x` en el build (por ejemplo `22.22.2`).
- **npm**: `10.x` compatible con Node 22 (el build registra `10.9.7`).
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
- PHP 8.2+ con PHP-FPM (se recomienda 8.5).
- Extensiones PHP: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pcre`, `pdo`, `session`, `tokenizer`, `xml`, `pdo_mysql`.
- Composer 2.x disponible en `PATH`.
- Node.js 22.x y npm 10.x (sólo para compilar assets, no para ejecutar Laravel).
- Servidor de base de datos MySQL/MariaDB accesible desde la máquina.
- Git para clonar el repositorio.

## Preparar paquetes del sistema (ejemplo minimal)

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y git curl unzip zip mysql-client
sudo apt install -y apache2 libapache2-mod-php8.5
sudo apt install -y php8.5-cli php8.5-mbstring php8.5-xml php8.5-mysql php8.5-curl php8.5-zip php8.5-bcmath
sudo apt install -y build-essential
sudo a2enmod rewrite
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

## Configurar VirtualHost en Apache

Instala Apache y el módulo PHP si aún no los tienes:

```bash
sudo apt install -y apache2 libapache2-mod-php8.2
sudo a2enmod rewrite
```

Crea el archivo VirtualHost (ajusta `ServerName` y la ruta al proyecto):

```bash
sudo nano /etc/apache2/sites-available/managergob.conf
```

Contenido del archivo:

```apache
<VirtualHost *:80>
    ServerName ejemplo.com

    DocumentRoot /var/www/html/managergob/public

    <Directory /var/www/html/managergob/public>
        AllowOverride All
        Require all granted
    </Directory>

    DirectoryIndex index.php index.html

    ErrorLog /var/log/apache2/managergob_error.log
    CustomLog /var/log/apache2/managergob_access.log combined
</VirtualHost>
```

Activa el sitio y reinicia Apache:

```bash
sudo a2ensite managergob.conf
sudo systemctl restart apache2
```

> **Nota:** `AllowOverride All` es necesario para que el `.htaccess` de Laravel funcione correctamente.

## Notas finales
- En producción se ejecuta `npm run build` para compilar los assets a `public/build`. **No se ejecuta `npm run dev`** (eso es solo para desarrollo local). Una vez compilados, Node ya no es necesario en runtime.
- Laravel se sirve íntegramente a través de Apache/PHP. Node/npm solo se necesita durante el despliegue para compilar assets.
- No se incluyen en este documento pasos relacionados con colas, cron o SSL: solo lo mínimo necesario para que la aplicación funcione.

## Despliegue sin Git

Si el servidor de producción no tiene Git, compila en tu máquina y transfiere los archivos manualmente (FTP, SCP, rsync, ZIP, etc.).

### Archivos a transferir

```
app/
bootstrap/
config/
database/
lang/
public/          ← incluye public/build/ ya compilado
resources/
routes/
storage/         ← solo la estructura, sin logs ni cache
artisan
composer.json
composer.lock
.env.example
```

### No transferir

| Carpeta/archivo | Motivo |
|---|---|
| `vendor/` | Se regenera con `composer install` en el servidor |
| `node_modules/` | No necesario si `public/build/` ya va compilado |
| `.git/` | No aplica |
| `.env` | Se crea nuevo en el servidor |
| `bootstrap/cache/*` | Se genera con `php artisan config:cache` |
| `storage/logs/*` | Deben quedar vacíos |

> Si transfieres `public/build/` ya compilado **no necesitas instalar Node en el servidor**. Solo PHP, Composer y Apache.

### Pasos en el servidor tras transferir

```bash
# 1. Instalar dependencias PHP
composer install --optimize-autoloader --no-dev

# 2. Configurar entorno
cp .env.example .env
# editar .env con datos reales (APP_URL, DB_*, etc.)
php artisan key:generate

# 3. Base de datos
php artisan migrate --force

# 4. Permisos
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
php artisan storage:link

# 5. Optimización
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
