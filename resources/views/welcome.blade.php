<!doctype html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Welcome</title>

		<!-- Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
		<!-- style -->
		<link href="/css/app.css" rel="stylesheet" type="text/css">

	<!-- FontAwesome -->
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<!-- jquery -->
	<script src="http://code.jquery.com/jquery-3.2.1.min.js"></script>
	<!-- My js -->
	<script src="/js/all.js?{{date('now')}}"></script>

	</head>
	<body>
		<header>
			<nav>
				<ul>
					<li><a href="/back" class="ajax_item"><i class="fa fa-arrow-left" aria-hidden="true"></i></a></li>
					<li><a href="/category" class="ajax_item home"><i class="fa fa-home" aria-hidden="true"></i></a></li>
					<li><a href="/search" class="ajax_item"><i class="fa fa-search" aria-hidden="true"></i></a></li>
					<li><a href="/card" class="ajax_item"><i class="fa fa-credit-card" aria-hidden="true"></i></a></li>
					<li><a href="/refresh" class="ajax_item"><i class="fa fa-refresh" aria-hidden="true"></i></a></li>

					<li><a href="/cart" class="cart ajax_item"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="count"></span></a></li>
				</ul>
			</nav>
		</header>

		<main>			
		</main>

		<aside>
			<button class="up"><i class="fa fa-arrow-up" aria-hidden="true"></i></button>
			<button class="down"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
		</aside>

		<footer>
			<img src="/img/banner.png" alt="">
		</footer>
	</body>


</html>
