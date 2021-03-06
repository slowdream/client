На терминале {{ $idterm }} создан заказ номер {{ $orderId }} на {{ count($products) }} позиций.
Контакты заказчика {{ $name }} +7{{ $telnumber }}
Адрес доставки {{ $address }}
Дата и время доставки {{ $orderDate }} {{ $timeRange }}
Общая сумма заказа {{ $summ }}р + доставка {{ $delivery }}р
Было оплачено на месте {{ $pay }}р
@if($pay > ($summ + $delivery))
Выдать сдачу в размере {{ $pay  - ($summ + $delivery) }}р
@elseif($pay < ($summ + $delivery))
Получить с покупателя {{ ($summ + $delivery) - $pay }}р
@endif
Позиции:
@foreach($products as $product)
    {{ $product['guid'] }} {{ $product['name'] }} {{ $product['count'] }}{{ $product['unit'] }} {{ $product['price'] }}р
@endforeach