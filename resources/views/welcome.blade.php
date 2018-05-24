
<!-- 繼承母模板 -->
    @extends('layout.master')

<!-- 傳送資料到母模板，變數為title -->
    @section('title',$title)

<!-- 此頁面需要的style -->
    @section('css')
        {{--<link rel="stylesheet" href="{{asset('css/welcome.css')}}">--}}
    @endsection

<!-- 傳送資料到母模板，變數為content -->
    @section('content')
            <div class="content">
                <img src="{{asset('assets/images/index_01.jpg')}}" class="img-fluid" alt="Responsive image">


                <div class="title m-b-md">
                    Laravel
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Documentation</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
    @endsection
