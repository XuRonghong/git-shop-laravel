<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title> @yield('title') - Shop Laravel </title>

		<!-- Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

		<script src="{{asset('js/jquery-3.3.1.min.js')}}" defer=""></script>
		<script src="{{asset('js/bootstrap/bootstrap.min.js')}}" defer=""></script>
		<link rel="stylesheet" href="{{asset('css/bootstrap/bootstrap.min.css')}}">		
		<link rel="stylesheet" href="{{asset('css/fontawesome-free-5.0.8/web-fonts-with-css/css/fontawesome.min.css')}}">

		<link rel="stylesheet" href="{{asset('css/welcome.css')}}">
		@yield('css')
	</head>
	<body>
		<div class="alert alert-dark" role="alert">
				<ul class="nav" style="align-content: right; padding: 0px;">
					<li>
						<a href="{{ url('/') }}">
							<img src="{{asset('assets/images/home.png')}}" style="width: 5%;">
						</a>
					</li>
					{{--<li><a href="{{ url('/transaction') }}">交易紀錄&nbsp;&nbsp;</a></li>--}}
					{{--<li>&nbsp;</li>--}}
					{{--<li><a href="{{ url('/merchandise/manage') }}">編輯列表&nbsp;&nbsp;</a></li>--}}
					{{--<li><a href="{{ url('/merchandise/create') }}">新增商品&nbsp;&nbsp;</a></li>--}}
					{{--<li>&nbsp;</li>--}}
				</ul>
				<div class="top-right links">
					<a href="{{ url('/merchandise') }}">商品列表</a>
					<a href="{{ url('/transaction') }}">交易紀錄</a>
					<a href="{{ url('/merchandise/manage') }}">編輯列表</a>
					<a href="{{ url('/merchandise/create') }}">新增商品</a>
					@if(is_null( session()->get('user_id') ))
						<a href="{{ url('/user/auth/signin') }}">Login</a>
						<a href="{{ url('/user/auth/signup') }}">註冊</a>
					@else
						<a href="{{ url('/user/auth/signout') }}">Logout</a>
					@endif
				</div>

				{{--@if(session()->has('user_id'))--}}
					{{--<li>--}}
						{{--&nbsp;&nbsp;{{ $user_nickname or ''  }}&nbsp;--}}
						{{--<a href="{{ url('/user/auth/sign-out') }}">登出&nbsp;</a>--}}
					{{--</li>--}}
				{{--@else--}}
					{{--<li>&nbsp;&nbsp;<a href="{{ url('/user/auth/sign-in') }}">登入&nbsp;</a></li>--}}
					{{--<li>&nbsp;&nbsp;<a href="{{ url('/user/auth/sign-up') }}">註冊&nbsp;</a></li>--}}
				{{--@endif--}}

			{{--@if (Route::has('login'))--}}
				{{--<div class="top-right links">--}}
					{{--@auth--}}
						{{--<a href="{{ url('/home') }}">Home</a>--}}
					{{--@else--}}
						{{--<a href="{{ route('login') }}">Login</a>--}}
						{{--<a href="{{ route('register') }}">Register</a>--}}
					{{--@endauth--}}
				{{--</div>--}}
			{{--@endif--}}
			</div>

		<div class="container">
			@yield('content')
		</div>

		<footer>
			<div class="footer">
				@yield('footer')
				<!-- 使用社群API模板元件 -->
				@include('components.socialButtons');
				<a href="#">聯絡我們</a>
			</div>
		</footer>
	</body>
</html>