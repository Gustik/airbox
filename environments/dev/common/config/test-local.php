<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=mysql;dbname=airbox_test',
            'username' => 'root',
            'password' => $_ENV['MYSQL_ROOT_PASSWORD'],
            'charset' => 'utf8',
        ],
    ],
];
