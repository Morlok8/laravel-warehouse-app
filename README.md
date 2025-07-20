<h2>Установка</h2>
<ol>
    <li> Скопировать URL репозитория; </li>
    <li> Выбрав нужную директорию локального сервера в командной строке, ввести git clone; </li>
    <li> Создать базу в MySQL; </li>
    <li> Отредактировать файл env (или env.example и убрать example), добавив туда название новой базы и заменив sqlite на mysql в строке DB_CONNECTION; </li>
    <li> Выполнить команду composer install; </li>
    <li> Выполнить команду php artisan migrate; </li>
    <li> Выполнить команду php artisan db:seed; </li>
    <li> Выполнить команду php artisan key:generate; </li>
    <li> Запустить сервер, используя команду php artisan serve; </li>    
</ol>

<h3>Маршруты API</h3>
get: host/api/products --- получить список товаров
get: host/api/warehouse --- получить список складов

get: host/api/orders --- получить список заказов
get: host/api/stock-movements --- получить историю изменения количества товаров на складах

post: host/api/orders  --- добавить заказ

patch: host/api/orders/{id} --- изменить заказ
patch: host/api/orders/cancel/{id} --- отменить заказ
patch: host/api/orders/complete/{id} --- завершить заказ
patch: host/api/orders/restore/{id} --- восстановить заказ
