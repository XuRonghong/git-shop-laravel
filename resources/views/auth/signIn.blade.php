
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

		<form action="{{ url('/user/auth/sign-in') }}" method="post" >

			<!-- CSRF 欄位 -->
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<div class="form-group">
				<label for="exampleInputEmail1">Email</label>
		      	<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email" name="email" value="{{ old('email') }}">
		      	<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
		    </div>

			<div class="form-group">
			    <label for="exampleInputPassword1">密碼:</label>
				    <input type="password" id="exampleInputPassword1" class="form-control" aria-describedby="passwordHelpInline" placeholder="Password" name="password">
				    <small id="passwordHelpInline" class="text-muted">Must be 1-20 characters long.</small>
				</div>
			</div>

			<!--<div class="form-check">
			    <input type="checkbox" class="form-check-input" id="exampleCheck1">
			    <label class="form-check-label" for="exampleCheck1">Check me out</label>
			</div>-->

			<div class="form-group row">
			    <div class="col-sm-10">
			      <button type="submit" class="btn btn-primary">登入</button>
			    </div>

			    <label>				
					<ul>{{ $mes['signUpSuccess'] or ''}}</ul>
				</label>
			</div>

		</form>
	</div>
@endsection