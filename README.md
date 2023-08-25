# final_web
Финальный проект по веб-программированию.</br>
Тема: составление расписания и учёт работы менджеров автосалона.</br>

## Запуск приложения:
```bash
docker compose up -d --force-recreate
```

## Вход в контейнер с php-fpm:
```bash
docker compose exec php81-service sh
```

## Установка зависимостей (внутри контейнера с php-fpm):
```bash
composer install
```

## Выполнение последней миграции (внутри контейнера с php-fpm):
```bash
php bin/console doctrine:migrations:migrate
```
Приложение запускается на http://127.0.0.1:8080
