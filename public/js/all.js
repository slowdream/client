$(document).ready(function() {
	$('.addToCart').click(function(e) {
		e.preventDefault();
		var id = $(this).data('id');
		var count = 1;
		AddToCart(id, count);		
	});	
	$('.removeFromCart').click(function(e) {
		e.preventDefault();
		var id = $(this).data('id');		
		RemoveFromCart(id);
	});		
	$('#cancelCart').click(function(e) {
		e.preventDefault();				
		CancelCart();		
	});	
	$('a[href="/refresh"]').click(function(e) {
		e.preventDefault();
		//location.reload();
		RefreshData();
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
		GetCartCount();
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
		location.reload();
		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
function CancelCart(){
	$.ajax({
		url: '/cart/cancel',
		type: 'POST',
		//dataType: 'json',
		data: {},
	})
	.done(function() {
		location.reload();
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
function RefreshData(){
	$.ajax({
		url: '/refresh',
		type: 'POST',
		//dataType: 'json',
		data: {},
		success: function(data){
    		console.log(data);
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