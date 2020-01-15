# Quest 1
Установка:
>composer install

Настройки:
>Внести данные для доступа к БД и ключ API в config.php

Создание таблиц:
>vendor/bin/doctrine orm:schema-tool:update --force

Получение данных с API:
>cd cron && php cron.php

Встроенный веб сервер:
>cd public && php -S localhost:8080 index.php

В браузере:
>http://localhost:8080/
