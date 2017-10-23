Заказ № {{ $id }}
@foreach ($products as $product)
{{ $product['guid'] }} {{ $product['name'] }}
  Количество {{ $product['count'] }}
  Цена {{ $product['price'] }}
  Сумма{{ $product['count']*$product['price'] }}
@endforeach
Итого без учета доставки: {{ $summ }}
Оплачено: {{ $cash }}

@if ($reason == 'canceled')
  ВНИМАНИЕ! Ваш заказ не будет обработан, пожалуйста свяжитесь с оператором по телефону указанному на чеке
@endif
