Laravel database for mysql 扩展
==============================

这是一个为了兼容老的项目对数据库的操作，比如getRow,getAll等开发的laravel扩展，对项目有一定的针对性，不一定适合所有项目

Requirements
------------

 - PHP 5.5.9+
 - [PDO]
 - Laravel 5.2

Installation
-------------

Add the dependency to `composer.json`:

```
"require": {
    "very/laravel-database": "1.*"
}
```

Add the `DatabaseServiceProvider` to `config/app.php` (comment out built-in `DatabaseServiceProvider`):

```
...
'providers' => array(
    ...
    Very\Database\DatabaseServiceProvider::class,
    ...
),
...
```