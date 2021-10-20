#### Запуск тестов
```
docker-compose run --rm backend vendor/bin/codecept run unit services -c common
```

#### Запуск всего
```
docker-compose up
```

#### Частичный запуск
```
docker-compose up mysql api
docker-compose up mysql backend
docker-compose up modbus
```