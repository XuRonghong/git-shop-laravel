
<!-- 繼承母模板 -->
@extends('layout.master')

<!-- 傳送資料到母模板，變數為title -->
@section('title',$title)

<!-- 傳送資料到母模板，變數為content -->
@section('content')
	<div class="container">
		<h1>{{ $title }}</h1>

		<!-- 顯示錯誤訊息模板元件 -->
		@include('components.validationErrorMessage');

		<!-- 使用社群API模板元件 -->
		@include('components.socialButtons');

		<form action="{{ url('/user/auth/sign-in') }}" method="post">

			<!-- CSRF 欄位 -->
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<label>
				Email:
				<input type="text" name="email" placeholder="Email" value="{{ old('email') }}">
			</label>

			<label>
				密碼:
				<input type="password" name="password" placeholder="密碼" >
			</label>

			<button type="submit">登入</button>

			<label>				
				<ul>{{ $mes['signUpSuccess'] or ''}}</ul>
			</label>
		</form>
	</div>
@endsection