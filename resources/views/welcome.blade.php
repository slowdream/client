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



	</head>
	<body>
		<header>
			<nav>
				<ul>
					<li><a href="/back"><i class="fa fa-arrow-left" aria-hidden="true"></i></a></li>
					<li><a href="/categorys"><i class="fa fa-home" aria-hidden="true"></i></a></li>
					<li><a href="/search"><i class="fa fa-search" aria-hidden="true"></i></a></li>
					<li><a href="/card"><i class="fa fa-credit-card" aria-hidden="true"></i></a></li>
					<li><a href="/refresh"><i class="fa fa-refresh" aria-hidden="true"></i></a></li>

					<li><a href="/cart" class="cart"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="count">0</span></a></li>
				</ul>
			</nav>
		</header>
		<main>
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
					@yield('main')
					</div>
				</div>
			</div>
		
		</main>
		<aside>
			<button class="up"><i class="fa fa-arrow-up" aria-hidden="true"></i></button>
			<button class="down"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
		</aside>
		<footer>
			
		</footer>
	</body>

	<!-- FontAwesome -->
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<!-- jquery -->
	<script src="http://code.jquery.com/jquery-3.2.1.min.js"></script>
	<!-- My js -->
	<script src="/js/all.js"></script>
</html>
