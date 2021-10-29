#### Начальная инициализация
```
docker-compose build
docker-compose run --rm backend composer install
docker-compose run --rm backend init
cp env.example .env
docker-compose up -d
docker-compose run --rm backend yii migrate
```

#### Подключиться к консоли
```
docker-compose run --rm backend bash
```

#### Запуск тестов
```
docker-compose up -d
docker-compose run --rm mysql mysql -u root -h mysql -p -e "create database airbox_test";
docker-compose run --rm backend yii_test migrate
docker-compose run --rm backend vendor/bin/codecept run unit services -c common
docker-compose run --rm backend vendor/bin/codecept run unit repositories -c common
```

#### Запуск всего
```
docker-compose up -d
```

#### Частичный запуск
```
docker-compose up mysql api
docker-compose up mysql backend
docker-compose up modbus
```