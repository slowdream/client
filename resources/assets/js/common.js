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
		CancelCart('UserCancel');
		location.reload();
	});		
	$('#sendCart').click(function(e) {
		e.preventDefault();
		SendCart();
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
function CancelCart(reason = ''){
	$.ajax({
		url: '/cart/cancel',
		type: 'POST',
		//dataType: 'json',
		data: {
			reason: reason
		},
		success: function () {
    		GetCartCount();
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
		location.replace('/1c');
		console.log("success");
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
}

function SendCart() {
	$.ajax({
		url: '/cart/complete',
		type: 'POST',
		//dataType: 'json',
		data: {
			name: $('#name').val(),
			order_num: $('#nomer').val(),
			summ: $('#summ').val()
		},
		success: function(data){
			$('main').html(data);
    		console.log(data);
    		CancelCart('UserCancel');
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