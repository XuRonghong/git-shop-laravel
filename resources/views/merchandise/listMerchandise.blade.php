
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


		<table class="table">
			<tr>
				<th>名稱</th>
				<th>圖片</th>
				<th>價格</th>
				<th>剩餘數量</th>
			</tr>
			@foreach( $MerchandisePaginate as $Merchandise )
				<tr>					
					<td> 
						<a href="{{ url('/merchandise/'. $Merchandise->id) }}">
							{{ $Merchandise->name }}
						</a>
					</td>
					<td> 
						<a href="{{ url('/merchandise/'. $Merchandise->id) }}">
							<img src="{{ $Merchandise->photo or '/assets/images/default-merchandise.jpg' }}" />
						</a>
					</td>
					<td> {{ $Merchandise->price }} </td>
					<td> {{ $Merchandise->remain_count }} </td>
					
				</tr>

			@endforeach
		</table>

		{{-- 分頁頁數按鈕 --}}
		{{ $MerchandisePaginate->links() }}		<!--Laravel很貼心的對分頁物件提供links方法很快建立分頁按鈕HTML-->
		
	</div>
@endsection