
<!-- 繼承母模板 -->
@extends('layout.master')

<!-- 傳送資料到母模板，變數為title -->
@section('title',$title)

<!-- 傳送資料到母模板，變數為content -->
@section('content')
	<div class="container">
		{{--<h1>{{ $title }}</h1>--}}

		<!-- 顯示錯誤訊息模板元件 -->
		@include('components.validationErrorMessage');

		<form action="{{ url('/merchandise/create') }}" method="post" enctype="multipart/form-data" id="" >

			<!-- CSRF 欄位 -->
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<li>
				商品狀態:
				<select name="status">
					<option value="c" selected >
						 建立中
					</option>
					<option value="s" >
						 可販售
					</option>
				</select>
			</li>

			<li>
				商品名稱:
				<input type="text" name="name" placeholder="商品名稱" value="{{ old('name') }}">
			</li>

			<li>
				商品英文名稱:
				<input type="text" name="name_en" placeholder="商品英文名稱" value="{{ old('name_en') }}">
			</li>

			<li>
				商品介紹:
				<textarea name="introduction"
						  placeholder="商品介紹"
						  rows="5"
						  cols="30" ></textarea>
			</li>

			<li>
				商品英文介紹:
				<textarea name="introduction_en"
					   placeholder="商品英文介紹"
					   rows="5"
					   cols="30" ></textarea>
			</li>

			<li>
				商品照片:
				<input type="file" name="photo" placeholder="商品照片" accept="image/*" value="{{ old('photo') }}  id="">
				<img src="{{ old('photo') or 'assets/images/default-merchandise.jpg' }}"  id="">
			</li>

			<li>
				商品價格:
				<input type="text" name="price" placeholder="商品價格" value="{{ old('price') }}">
			</li>

			<li>
				商品剩餘數量:
				<input type="text" name="remain_count" placeholder="商品剩餘數量" value="{{ old('remain_count') }}">
			</li>

			<button type="submit" class="btn btn-default">新增商品</button>
		</form>
	</div>
@endsection