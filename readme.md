
<div align="center">
  <img src="https://j.gifs.com/6RMq59.gif">
  
 <small>Design credit </small>
</div>

KingTodo is a simple simple application created with Laravel and vujs that allows you to add todos, edit todos, delete todos and also mark todo as completed and dragging and sorting.

> **Application Version:** 1.1.0 
> **Laravel Version:** 5.5 
> **Vuejs Version:** 2.5.7


# Installation

### 1. Grab the project files

Clone this repository first-
```bash
git clone https://github.com/kingRayhan/kingtodo.git
```

Then navigate to **[kingtodo][kingtodourl]** directiry
```bash
cd kingtodo
```
<br/>

### 2. Install dependencies
There are two type of dependencies here, php **composer** and **npm**

Install **composer** dependencies

```bash
composer install
```

Now, install **npm** dependencies
```php
npm install
``` 
<br/>

### 3. Configure Database
You need to put your database connection access information in **`.env`**   file. But you will not find any **`.env`** file in the project directory, you will see a similar file in the direcoty named **`.env.example`**. It's an example boilarplate file for **`.env`**.

Now you need to duplicated this file and rename it to **`.env`**. You can do this menually. 

**Or you can *duplicate* it by terminal-**

Duplicate .env.example file to .env file to create a environment file-
```bash
cp .env.example .env
```
Generate a application key
```
php artisan key:generate
```
Edit **`.env`** file with with your database credential.
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Now push **[kingtodo][kingtodourl]**'s database tables by running this command
```bash
php artisan migrate
```

<br/>

### 4. Import Demo data (optional)

Run seeder command , if you want to import 50 demo todos
```data
php artisan db:seed
```
<br/>

### 5. Run server
```bash
php artisan serve
```
Now you can see the application at [http://localhost:8000](http://localhost:8000/)

To compile all changes , run npm watcher
```
npm run watch
```

<br>
<br>


## Have any question?
Open a [issue][issue] to do any query or submit any bug.

https://github.com/kingRayhan/kingtodo/issues/new



## Credits:
+ [axios v0.17](https://github.com/axios/axios)
+ [bootstrap v4.0.0-beta.3](https://getbootstrap.com/)
+ [font-awesome v4.7.0](http://fontawesome.io/icons/)
+ [jquery v3.2](https://jquery.com/)
+ [jquery.nicescroll v3.7.6](https://github.com/inuyaksa/jquery.nicescroll)
+ [laravel-mix v1.0](https://github.com/JeffreyWay/laravel-mix)
+ [lodash v4.17.4](https://lodash.com)
+ [popper.js v1.12.9](https://popper.js.org)
+ [sweetalert v2.1.0](https://sweetalert.js.org)
+ [vue v2.5.7](https://vuejs.org)
+ [vuedraggable v2.16.0](https://github.com/SortableJS/Vue.Draggable)


[kingtodourl]: https://github.com/kingRayhan/kingtodo "King Todo Repository"
[issue]: https://github.com/kingRayhan/kingtodo/issues/new "Create new issue"
