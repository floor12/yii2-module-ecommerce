# yii2-module-ecommerce

Установка
------------

#### Ставим модуль

Выполняем команду
```bash
$ composer require floor12/yii2-module-ecommerce
```

иди добавляем в секцию "requred" файла composer.json
```json
"floor12/yii2-module-ecommerce": "dev-master"
```


###Выполняем миграцию для созданию необходимых таблиц
```bash
$ ./yii migrate --migrationPath=@vendor/floor12/yii2-module-ecommerce/src/migrations
```

###Добавляем модуль в конфиг приложения
```php  
'modules' => [
        'shop' => [
            'class' => 'floor12\ecommerce\Module',
            'editRole' => '@',
        ],
    ]
    ...
```

Параметры:

1. `editRole` - роль пользователей, которым доступно управление. Можно использовать "@".

Использование
-----
@todo

