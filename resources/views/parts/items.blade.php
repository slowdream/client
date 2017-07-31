@extends('welcome')

@section('main') 
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<table class="table table-striped">
				<tbody>
					<tr>
						<th>ИД</th>
						<th>Имя</th>
						<th>На складе</th>
						<th>Цена</th>			
						<th>в корзину</th>			
					</tr>
			@foreach ($products as $item)
					<tr>
						<td>{{ $item->id }}</td>
						<td>{{ $item->name }}</td>
						<td>???</td>
						<td>???</td>			
						<td><a href="#" data-id="{{ $item->id }}" class="addToCart"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a></td>
					</tr>	
			@endforeach
				</tbody>

			</table>
		</div>
	</div>
</div>

@endsection