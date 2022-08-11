# CONCURSOS

Descripción de la Aplicación y sus detalles

## Instalación y Configuración Inicial

Para ocupar Laravel se deben realizar la siguientes operaciones iniciales:

1-. *Instalar Composer*

Se deben ejecutar los siguentes comando en la termnal:

```php
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
```

```php
php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
```

```php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
```

```php
php -r "unlink('composer-setup.php');"
```

> Es recomendable a la hora de ejecutar estos comandos chequear posibles cambios en la web [Composer Web](https://getcomposer.org/download/)

2-. *Clonar repositorio*

```
git clone https://usuario@bitbucket.org/mdsobrino/consursos.git
```

3-. *Actualizar aplicación*

Una vez clonado el repositorio, se deberá ejecutar `composer update` para actualizar todo el core y las aplicaciones descritas en el composer.json

Y luego ejecutar el vendor:publish de Fortify

```
php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
```

4-. *Modificar el .env*

```php
APP_NAME=Concursos

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=concursos
DB_USERNAME=postgres
DB_PASSWORD=concursos.$
```
5-. *Ejecutar las Migraciones*

En caso de no tener datos en la BD:
```php
php artisan migrate:reset
php artisan migrate
```

Sino solo

```php
php artisan migrate
```

6-. *Ejecutar el Instalador Inicial*

```php
php artisan concursos:install
```

7-. *Ejecutar los Seeders*

```php
php artisan db:seed
```

En ocaciones es necesario realizar una actualización de las rutas

8-. *Ejecutar el limpiador de cache*

```php
php artisan route:clear
php artisan route:cache
```
