
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

		<form action="{{ url('/user/auth/addadmin') }}" method="post">
			
			<!-- CSRF 欄位 -->
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<label>
				暱稱:
				<input type="text" name="uName" placeholder="暱稱" value="{{ old('uName') }}">
			</label>

			<label>
				Email:
				<input type="text" name="uEmail" placeholder="Email" value="{{ old('uEmail') }}">
			</label>

			<label>
				密碼:
				<input type="password" name="uPassword" placeholder="密碼" >
			</label>

			<label>
				確認密碼:
				<input type="password" name="password_confirmation" placeholder="確認密碼">
			</label>

			<label>
				帳號類型:
				<select name="type">
					<option value="G">一般會員</option>
					<option value="A">管理者</option>
				</select>
			</label>

			<button type="submit">註冊</button>
		</form>
	</div>
@endsection