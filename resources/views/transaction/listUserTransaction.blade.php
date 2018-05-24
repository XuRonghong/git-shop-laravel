
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
				<th>商品名稱</th>
				<th>圖片</th>
				<th>單價</th>
				<th>數量</th>
				<th>總金額</th>
				<th>購買時間</th>
			</tr>
			@foreach($TransactionPaginate as $Transaction)			
			<tr>					
				<td > 
					<a href="{{ url('/merchandise/' . $Transaction->merchandise_id) }}">
						{{-- 交易紀錄商品名稱 --}}
						{{ $Transaction->name or ''}}
					</a>
				</td>
				<td>
					<a href="{{ url('/merchandise/' . $Transaction->merchandise_id) }}">
						{{-- 交易紀錄商品圖片 --}}
						<img style="width: 15%" src="{{ $Transaction->photo or '/assets/images/default-merchandise.jpg' }}" />
					</a>
				</td>
				<td > 
						{{ $Transaction->price or ''}}
				</td>
				<td > 
						{{ $Transaction->buy_count or ''}}
				</td>
				<td > 
						{{ $Transaction->total_price or ''}}
				</td>
				<td > 
						{{ $Transaction->created_at or ''}}
				</td>					
			</tr>
			@endforeach
			<tr>
				<th></th>
				<th></th>
				<th></th>
				<th>{{ $buy_count_total or ''}}</th>
				<th>{{ $price_total or ''}}</th>
				<th></th>
			</tr>
		</table>

		{{-- 分頁頁數按鈕 --}}
		{{ isset($TransactionPaginate)? $TransactionPaginate->links() : '' }}		<!--Laravel很貼心的對分頁物件提供links方法很快建立分頁按鈕HTML-->
	</div>
@endsection