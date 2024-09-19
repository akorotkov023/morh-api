# Mobile operator registry handler (MORH)

## Описание
Сервис занимается обработкой рееестров платежей получаемых по почте.

## Требования
* PHP 7.2
* Postgres 11.2

## Принцип работы
* Реестр забирается из почтового ящика
* Реестр читается обработчиком и заносит транзакции в таблицу registries_transactions
* Обработчик транзакций забирает транзакции из базы данных
* Task Manager выполняет задания для каждый транакзции
* Загрзука транзакции в platbox_gate
* Обновление комиссий
* Отправка транзакций в банк


## Установка

### Окружение для разрабоичика
```
# sudo docker-compose up
```

### Продуктовое окружение
В настройках файла окружения нужно прописать параметры для доступа к боевым серверам и указать окружение.

```
# cp ./env/.env.dist ./env/.env
# vi ./env/.env
# sudo docker-compose up -d
# sudo docker exec -it morh-php-fpm sh 
# php bin/console cache:clear
```

#### Настройки окружения
* APP_ENV       - окружение (prod/dev/test)
* APP_SECRET    - ключ для слоирования сессий и внутренних данных фреймворка
* APP_DEBUG     - (boolean) Включить режим отладки

* MORH_HOST         - хост базы данных проекта morh
* MORH_PORT         - порт базы данных проекта morh
* MORH_DATABASE     - название базы данных проекта morh
* MORH_USER         - пользователь базы данных проекта morh
* MORH_PASSWORD     - пароль к базе данных проекта morh

* PLATBOX_GATEWAY_HOST      - хост pgmaster (platbox_gateway) базы данных проекта
* PLATBOX_GATEWAY_PORT      - порт pgmaster (platbox_gateway) базы данных проекта
* PLATBOX_GATEWAY_DATABASE  - название pgmaster (platbox_gateway) базы данных
* PLATBOX_GATEWAY_USER      - пользователь pgmaster (platbox_gateway) базы данных
* PLATBOX_GATEWAY_PASSWORD  - пароль pgmaster (platbox_gateway) к базе данных

* MAILBOX_USER      - пользователь почтового ящика
* MAILBOX_PASSWORD  - пароль от почтового ящика
* MAILBOX_SERVER    - адрес сервера

#### Настройка данных почты для забора реестра
```
# vi app/config/packages/runa_inbox_config.yaml
```

## Команды
```
# (1) morh:registries:obtain
# (2) morh:registries:parse
# (3) morh:transactions:handle
```

> (1) Команда получения реестра  
> (2) Запуск обработки реестра   
> (3) Запуск процесса обработки транзакций*

\* Может быть запущено N процеассов параллельно. Устойчиво к параллельной обработке.

## Command for dev:
1. php bin/console morh:registries:parse -vvv
2. while true; do php bin/console morh:transactions:handle -vvv; sleep 1; done

## TODO
* Автоматический забор реестра с почты
* Параллельный парсинг N реестров
* Перенести все логику работы с базами данных в репозитории
* Покрыть тестами
