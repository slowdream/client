Заказ № {{ $id }}
Сумма: {{ $summ + $delivery }}р
Оплачено: {{ $cash }}р
Доставка: {{ $orderDate }} Время: {{ $timeRange }}
@if ($reason == 'canceled')
  ВНИМАНИЕ! Ваш заказ не будет обработан, свяжитесь с оператором.
@endif