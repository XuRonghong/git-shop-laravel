<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title> @yield('title') - Shop Laravel </title>
		<script src="js/jquery-3.3.1.min.js" defer=""></script>
		<script src="js/bootstrap/bootstrap.min.js" defer=""></script>
		<link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">		
		<link rel="stylesheet" href="css/fontawesome-free-5.0.8/web-fonts-with-css/css/fontawesome.min.css">
	</head>
	<body>
		<header>		
			<ul class="nav">
				@if(session()->has('user_id'))
					<li><a href="{{ url('/user/auth/sign-out') }}">登出</a></li>
				@else
					<li><a href="{{ url('/user/auth/sign-in') }}">登入</a></li>
					<li><a href="{{ url('/user/auth/sign-up') }}">註冊</a></li>
				@endif
			</ul>
		</header>

		<div class="container">
			@yield('content')
		</div>

		<footer>
			<a href="#">聯絡我們</a>
		</footer>

	</body>
</html>