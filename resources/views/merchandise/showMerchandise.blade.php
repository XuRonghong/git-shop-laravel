
<!-- 繼承母模板 -->
@extends('layout.master')

<!-- 傳送資料到母模板，變數為title -->
@section('title',$title)

<!-- 傳送資料到母模板，變數為content -->
@section('content')
	<div class="container">

		<!-- 顯示錯誤訊息模板元件 -->
		@include('components.validationErrorMessage');


		<table class="table" >
			<tr>
				<th>名稱</th>
				<td>{{ $Merchandise->name }}</td>
			</tr>
			<tr>
				<th>圖片</th>
				<td>
					<img src="{{ $Merchandise->photo or '/assets/images/default-merchandise.jpg' }}" />
				</td>
			</tr>
			<tr>
				<th>價格</th>
				<td> {{ $Merchandise->price }} </td>
			</tr>
			<tr>
				<th>剩餘數量</th>
				<td> {{ $Merchandise->remain_count }} </td>
			</tr>
			<tr>
				<th>介紹</th>
				<td> {{ $Merchandise->introduction }} </td>
			</tr>
			<tr>					
				<td colspan="2"> 
					<form action="{{ url('/merchandise/' . $Merchandise->id . '/buy') }}" method="post"  id="" >
						<!-- CSRF 欄位 -->
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						購買數量
						<select name="buy_count" placeholder="商品數量">
							@for($count=0 ; $count<= $Merchandise->remain_count ; $count++)
							<option value="{{ $count }}">
								{{ $count }}
							</option>
							@endfor
						</select>	

						<button type="submit" class="">購買</button>
					</form>					
				</td>					
			</tr>
		</table>

		
	</div>
@endsection