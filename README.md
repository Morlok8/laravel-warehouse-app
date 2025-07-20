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
<p>get: host/api/products --- получить список товаров </p>
<p>get: host/api/warehouse --- получить список складов </p>

<p>get: host/api/orders --- получить список заказов </p>
<p>get: host/api/stock-movements --- получить историю изменения количества товаров на складах </p>

<p>post: host/api/orders  --- добавить заказ </p>
<p>Тело запроса:</p>
{
    "customer": "name",
    "warehouse_id": number, //id склада
    "products":{
        [
            "id": product_id, //id товара
            "count": 1 //количество товара
        ],
    }
}
<p>patch: host/api/orders/{id} --- изменить заказ </p>
<p>Тело запроса:</p>
{
    "customer": "name",
    "warehouse_id": number, //id склада
    "products":{
        [
            "id": product_id, //id товара
            "count": 1 //количество товара
        ],
    }
}
<p>При обновлении, список товаров создается заново</p>

<p>patch: host/api/orders/cancel/{id} --- отменить заказ </p>
<p>patch: host/api/orders/complete/{id} --- завершить заказ </p>
<p>patch: host/api/orders/restore/{id} --- восстановить заказ </p>
