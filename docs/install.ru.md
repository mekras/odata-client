# Установка

Установка производится с помощью [Composer](https://getcomposer.org/). Однако перед установкой надо
добавить в проект некоторые зависимости.

Для работы с HTTP используются библиотеки [PHP-HTTP](http://php-http.org/). Поэтому надо
подключить пакет, предоставляющий
[php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation),
например [php-http/curl-client](https://packagist.org/packages/php-http/curl-client). Для этого
в папке проекта выполните:

    composer require php-http/curl-client

Подробнее о доступных клиентах можно прочитать на сайте
[PHP-HTTP](http://php-http.org/en/latest/clients.html). 

Теперь можно установить `odata-client`:

    composer require mekras/odata-client
