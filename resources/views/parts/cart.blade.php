@extends('welcome')

@section('main') 

@if(count($products) <=0 )
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<h2>А корзина то пуста</h2>
		</div>
	</div>
</div>
@else
@php
$summ = 0;
@endphp
<table class="table table-striped">
	<tbody>
		<tr>
			<th>ИД</th>
			<th>Имя</th>
			<th>Кол-во</th>
			<th>Цена</th>			
			<th>Удалить</th>			
		</tr>
@foreach ($products as $item)
@php
$summ += $item->price;
@endphp
		<tr>
			<td>{{ $item->id }}</td>
			<td>{{ $item->product->name }}</td>
			<td>{{ $item->count }} {{ $item->unit }}</td>
			<td>{{ $item->price }} р.</td>		
			<td><a href="#" data-id="{{ $item->id }}" class="removeFromCart"><i class="fa fa-times" aria-hidden="true"></i></a></td>
		</tr>	
@endforeach
	</tbody>

</table>
<hr>

<span>Итого: {{ $summ }} р.</span>
<div class="cart_btns_wrapper">
	<button class="btn btn-primary btn-lg" id="sendCart"><i class="fa fa-check" aria-hidden="true"></i>Заказать</button>
	<button class="btn btn-danger btn-lg" id="cancelCart"><i class="fa fa-times" aria-hidden="true"></i>Отмена</button>
</div>
<input type="text" hidden="hidden" id="summ" value="{{ $summ }}">
<input type="text" hidden="hidden" id="name" value="Имя магазина">
<input type="text" hidden="hidden" id="nomer" value="00001">
@endif

@endsection