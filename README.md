# Laravel-Vue-SPA

This repository is the result of a tutorial about creating a SPA (Single Page Application) with Laravel and Vue.

## Update Laravel 6
The project has been updated for Laravel 6. Just switch to the 'Laravel-6.1' branch.

## Prerequiries

- PHP 7
- Composer
- NodeJs
- MySQL

## Installation

- Clone the repository -> git clone git@github.com:alekslauda/transactions.git
- Installer back dependencies with `composer install`
- Install front dependencies with `npm install`
- Copy file `.env.example` in `.env` and add following informations (command: `cp .env.example .env`):
    - Database credentials (`DB_DATABASE`, `DB_USERNAME`,`DB_PASSWORD` ...)
    - Application url (`APP_URL`). Either virtual host address if you configure one, either address form the command `php artisan serve`
- Generate application key with `php artisan key:generate`
- Generate JWT key with `php artisan jwt:secret`
- Launch migrations with `php artisan migrate`
- Seed the Database with `php artisan db:seed`
- Build front with `npm run dev`

## Usage

`admin credentials`:
- username: admin
- password: admin

`user credentials`:
- username: user
- password: secret
