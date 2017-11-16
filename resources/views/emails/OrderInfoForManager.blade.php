На терминале {{ $idterm }} создан заказ номер {{ $orderId }} на {{ count($products) }} позиций. <br>
Контакты заказчика {{ $name }} +7{{ $telnumber }} <br>
Адрес доставки {{ $address }} <br>
Дата и время доставки {{ $orderDate }} {{ $timeRange }} <br>
Общая сумма заказа {{ $summ }}р + доставка {{ $delivery }}р <br>
Было оплачено на месте {{ $pay }}р <br>
@if($pay > ($summ + $delivery))
Выдать сдачу в размере {{ $pay  - ($summ + $delivery) }}р <br>
@elseif($pay < ($summ + $delivery))
Получить с покупателя {{ ($summ + $delivery) - $pay }}р <br>
@endif
Позиции: <br>
<table>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>
                {{ $product['guid'] }}
            </td>
            <td>
                {{ $product['name'] }}
            </td>
            <td>
                {{ $product['count'] }}
            </td>
            <td>
                {{ $product['unit'] }}
            </td>
            <td>
                {{ $product['price'] }}р
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
