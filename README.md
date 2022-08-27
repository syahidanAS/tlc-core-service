# Laravel JWT Authentication Boilerplate

## Overview
This repository is used to speed up Restful API development using JWT authentication

## Stack Architechture
1. PHP 9, Laravel
2. Mysql or MariaDB
3. Firebase JWT

## Local development quickstart
Clone the repository
```
$ git clone github.com/syahidanAS/laravel-jwt-auth-boilerplate.git
```

Enter into the `src` directory
```
$ cd src
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
