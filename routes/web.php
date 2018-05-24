<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// 預設寫法
//Route::get('/', function () {
//    return view('welcome');
//});

// * * * * * * * * * * * * * * * homepage首頁 * * * * * * * * * * * * * * *
Route::get('/', 'HomeController@indexPage');


// * * * * * * * * * * * * * * * user使用者頁面類 * * * * * * * * * * * * * * *
Route::group(['prefix' => 'user'], function(){
    /*
     * 認 證
     */
    Route::group(['prefix' => 'auth'], function(){
        Route::get ('/signin' , 'User\UserAuthController@signInPage');		//登入
        Route::post('/signin' , 'User\UserAuthController@signInProcess');	//處理登入資料
        Route::get ('/signup' , 'User\UserAuthController@signUpPage');		//註冊
        Route::post('/signup' , 'User\UserAuthController@signUpProcess');	//處理註冊資料
        Route::get ('/signout', 'User\UserAuthController@signOut');			//登出

        Route::get ('/addadmin', 'User\UserAuthController@addadmin')
            ->middleware(['user.auth.admin']);		//加入管理者

        Route::post('/addadmin', 'User\UserAuthController@addadminProcess');		//加入管理者
    });
});


// * * * * * * * * * * * * * * * merchandise商品管理類 * * * * * * * * * * * * * * *
Route::group(['prefix' => 'merchandise'], function(){
    Route::get ('/'		 , 'Merchandise\MerchandiseController@merchandiseListPage');	//商品清單檢視
    Route::get ('/create', 'Merchandise\MerchandiseController@merchandiseCreatePage')
        ->middleware(['user.auth.admin']);	//商品資料新增

    Route::post('/create', 'Merchandise\MerchandiseController@merchandiseCreateProcess') ;
    Route::get ('/manage', 'Merchandise\MerchandiseController@merchandiseManageListPage')
        ->middleware(['user.auth.admin']);

    //指定商品
    Route::group(['prefix' => '{merchandise_id}'], function(){
        Route::get ('/'	   ,'Merchandise\MerchandiseController@merchandiseItemPage');			//商品單品檢視
        Route::get ('/edit','Merchandise\MerchandiseController@merchandiseItemEditPage')
            ->middleware(['user.auth.admin']);	//商品單品編輯

        Route::put ('/edit','Merchandise\MerchandiseController@merchandiseItemUpdateProcess');	//商品單品修改
        Route::post('/buy' ,'Merchandise\MerchandiseController@merchandiseItemBuyProcess')
            ->middleware(['user.auth']);

    });
});


// * * * * * * * * * * * * * * * transaction商品交易類別 * * * * * * * * * * * * * * *
Route::get('/transaction', 'Transaction\TransactionController@transactionListPage')
    ->middleware(['user.auth']);

