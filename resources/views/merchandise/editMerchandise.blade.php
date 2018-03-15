
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


		<form action="{{ url('/merchandise/' . $Merchandise->id . '/edit') }}" method="post" enctype="multipart/form-data" id="form0" >

			<!-- CSRF 欄位 -->
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			{{-- 隱藏方法欄位 --}}
			{{ method_field('PUT') }}

			<li>
				商品狀態:
				<select name="status">
					<option value="c" @if( old('status',$Merchandise->status)=='c' )
						selected @endif
						>
						 建立中
					</option>
					<option value="s" @if( old('status',$Merchandise->status)=='s' )
						selected @endif
						>
						 可販售
					</option>
				</select>
			</li>

			<li>
				商品名稱:
				<input type="text" name="name" placeholder="商品名稱" value="{{ old('name',$Merchandise->name) }}">
			</li>

			<li>
				商品英文名稱:
				<input type="text" name="name_en" placeholder="商品英文名稱" value="{{ old('name_en',$Merchandise->name_en) }}">
			</li>

			<li>
				商品介紹:
				<input type="text" name="introduction" placeholder="商品介紹" value="{{ old('introduction',$Merchandise->introduction) }}">
			</li>

			<li>
				商品英文介紹:
				<input type="text" name="introduction_en" placeholder="商品英文介紹" value="{{ old('introduction_en',$Merchandise->introduction_en) }}">
			</li>

			<li>
				商品照片:
				<input type="file" name="photo" placeholder="商品照片" accept="image/*" id="file0">
				<img src="{{ $Merchandise->photo or '/assets/images/default-merchandise.jpg' }}"  id="img0">
			</li>

			<li>
				商品價格:
				<input type="text" name="price" placeholder="商品價格" value="{{ old('price',$Merchandise->price) }}">
			</li>

			<li>
				商品剩餘數量:
				<input type="text" name="remain_count" placeholder="商品剩餘數量" value="{{ old('remain_count',$Merchandise->remain_count) }}">
			</li>

			<button type="submit" class="btn btn-default">更新商品資訊</button>
		</form>


	</div>
@endsection