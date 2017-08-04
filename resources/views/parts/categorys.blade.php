@extends('welcome')

@section('main') 
<div class="cat_wrapper">
	@foreach ($categorys as $item)
		<div class="item">
			<a href="/category/{{ $item->guid }}">
				<div class="img_wrapper">
					<!--<img src="/img/obchestroi.png" alt="">-->
				</div>
				<span class="title">{{ $item->name }}</span>
			</a>
		</div>
	@endforeach		
</div>
@endsection