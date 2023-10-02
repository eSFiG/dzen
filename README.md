## About project

This project is a simple web application which main task is ability to add comments

There are some features:
- customizable captcha
- error handling in form
- replies to the comments
- sorting by user name, email and creation time

## Installation

1) Download the project `git clone`
2) Run `composer install` to download all required packages
3) Copy-paste file `.env.example` and rename it `.env`. In this file fill with necessary data: 
   > * __DB_DATABASE__
   > * __DB_USERNAME__ 
   > * __DB_PASSWORD__
   
   Then run command `php artisan key:generate`
4) Run the migrations `php artisan migrate`
5) Start the Laravel server `php artisan serve`
6) Setup nginx server as for standard laravel project 
