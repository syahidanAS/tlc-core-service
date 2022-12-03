# The Bright Learning Center Blog Service Core


## Stack Architechture
1. PHP 8, Laravel
2. Mysql or MariaDB
3. Firebase JWT

## Local development quickstart
Clone the repository
```
$ git clone github.com/syahidanAS/laravel-jwt-auth-boilerplate.git
```
Copy the config file and adjust the needs
```
$ copy .env-example .env
```
Generate the APP_KEY
```
$ php artisan key:generate
```
App dependencies using composer
```
$ composer install
```
DB migration
```
$ php artisan migrate
```

Run the local server:
```
$ php artisan serve
```

Your playground already to use:
```
Open on the browser: {APP_URL}/api/auth
```
