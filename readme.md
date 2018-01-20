<p align="center">
    <img src="kingtodo.png">
</p>


KingTodo is a simple simple application created with Laravel and vujs that allows you to add todos, edit todos, delete todos and also mark todo as completed and dragging and sorting.

**Application Version:** 1.0.0

**Laravel Version:** 2.5.7


### Installation
Clone this repository first-
```php
git clone https://github.com/kingRayhan/kingtodo.git
```

Now cd into kingtodo
```php
cd kingtodo
```

Install composer dependencies
```php
composer install
```

Now, install dependencies
```php
npm install
``` 

Duplicate .env.example file to .env file to create a environment file-
```
cp .env.example .env
```
Generate a application key
```
php artisan key:generate
```
Edit .env file with with your database credential.

Now create database tables by running this command
```
php artisan migrate
```


Run seeder command , if you want to import 50 demo todos
```php
php artisan db:seed
```

### Run on server
```php
php artisan serve
```
now you can see the application at [http://localhost:8000](http://localhost:8000/)

To compile all changes , run npm watcher
```
npm run watch
```

<br>
<br>
<br>
<br>


## Have any question?
Open a [issue](https://github.com/kingRayhan/kingtodo/issues/new) to do any query or submit any bug.

https://github.com/kingRayhan/kingtodo/issues/new



## Credits:
+ axios v0.17
+ bootstrap v4.0.0-beta.3
+ cross-env v5.1
+ font-awesome v4.7.0
+ jquery v3.2
+ jquery.nicescroll v3.7.6
+ laravel-mix v1.0
+ lodash v4.17.4
+ popper.js v1.12.9
+ sweetalert v2.1.0
+ vue v2.5.7
+ vuedraggable v2.16.0