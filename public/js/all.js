$(document).ready(function() {
	$('.addToCart').click(function(e) {
		e.preventDefault();
		var id = $(this).data('id');
		var count = 1;
		AddToCart(id, count);
		GetCartCount();
	});	
	$('.removeFromCart').click(function(e) {
		e.preventDefault();
		var id = $(this).data('id');		
		RemoveFromCart(id);
		location.reload();
	});
});

$(window).on('load', function() {
	GetCartCount();
});

function AddToCart(id, count = 1){
	$.ajax({
		url: '/cart/add',
		type: 'POST',
		//dataType: 'json',
		data: {
			id: id,
			count: count
		},
	})
	.done(function() {
		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
function RemoveFromCart(id){
	$.ajax({
		url: '/cart/remove',
		type: 'POST',
		//dataType: 'json',
		data: {
			id: id
		},
	})
	.done(function() {
		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}

function GetCartCount(){
	$.ajax({
		url: '/cart/count',
		type: 'POST',
		//dataType: 'json',
		data: {},
		success: function(data){
    		$('.count').text(data);
  		}
	})
	.done(function() {
		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
}