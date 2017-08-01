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
		<tr>
			<td>{{ $item->id }}</td>
			<td>{{ $item->product->name }}</td>
			<td>???</td>
			<td>{{ $item->price }}</td>			
			<td><a href="#" data-id="{{ $item->id }}" class="removeFromCart"><i class="fa fa-times" aria-hidden="true"></i></a></td>
		</tr>	
@endforeach
	</tbody>

</table>

@endif

@endsection