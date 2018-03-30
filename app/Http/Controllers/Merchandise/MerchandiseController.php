<?php

namespace App\Http\Controllers\Merchandise;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Shop\Entity\Merchandise;
use App\Shop\Entity\Transaction;
use App\Shop\Entity\User;
use Validator;
use Image;
use DB;
use Exception;

class MerchandiseController extends Controller
{

    //----------------------- 商品頁面 --------------------------------------------
    public function merchandiseListPage(){
        //顯示會員資料
        $user_nickname=null;
        if(!is_null(session()->get('user_id')))
        {
            $User = User::where('id', session()->get('user_id') )->first();            
            $user_nickname=$User->nickname;
        }



        $row_per_page = 2;     //每頁資料量

        //撈取商品分頁資料
        $MerchandisePaginate = Merchandise::OrderBy('created_at','desc')
                ->where('status', 's')
                ->paginate($row_per_page);

        //設定商品圖片網址
        foreach ($MerchandisePaginate as $Merchandise ) {
            if( !is_null($Merchandise->photo) ){
                $Merchandise->photo = url($Merchandise->photo);
            }
        }

        $binding = [
            'title'=>'商品列表', 
            'MerchandisePaginate'=> $MerchandisePaginate, 
            'user_nickname'=> $user_nickname ,
        ];


        return view('merchandise.listMerchandise', $binding);
    }


    //----------------------- 新增商品頁面 --------------------------------------------
    public function merchandiseCreatePage(){

        $binding = [
            'title' => '增加商品',
            'mes'   => '',
        ];

        return view('merchandise.createMerchandise', $binding);
    }


    //----------------------- 增加商品 --------------------------------------------
    public function merchandiseCreateProcess(){

        $input = request()->all();

        //定義驗證規則        
    	$rules = [
    		'status' 		  => ['required','in:c,s'],
    		'name' 			  => ['required','min:1'  ,'max:39'],
    		'name_en' 		  => ['required','min:1'  ,'max:79'],
    		'introduction' 	  => ['required','min:0'  ,'max:999'],
    		'introduction_en' => ['required','min:0'  ,'max:999'],
    		'photo' 		  => ['file'    ,'image'  ,'max:10240'],
    		'price' 		  => ['required','integer','min:0'],
    		'remain_count' 	  => ['required','integer','min:0'],
    	];

        //驗證資料
        $validator = validator($input, $rules);

        if($validator->fails()){
            //驗證資料錯誤
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }


        if( isset($input['photo']) ){    //有上傳圖片
            $photo = $input['photo'];

            $file_extension = $photo->getClientOriginalExtension();   //檔案附檔名

            $file_name = uniqid() . '.' . $file_extension;            //產生自並隨機檔案名稱


            //Lets create path for post_id if it doesn't exist yet e.g `public\blogpost\23`
            $file_dir = 'assets/images/merchandise/';
            if(!File::exists( $file_dir )) File::makeDirectory( $file_dir , 775);   //創造有權限的目錄


            $file_relative_path = $file_dir . $file_name;     //檔案相對路徑

            $file_path = public_path($file_relative_path);                //產生該檔案實體路徑

            $image = Image::make( $photo )
                ->resize(450,300)->save($file_path); //裁切圖片(需packagist安裝 intervention/image)

            $input['photo'] = $file_relative_path;            //設定圖片檔案相對位置
        }


    	$Merchandise = Merchandise::create($input);


    	return redirect('/merchandise/manage')->withErrors("Create Success~");
    }



    //----------------------- 商品頁 --------------------------------------------
    public function merchandiseItemPage($merchandise_id){
        //顯示會員資料
        $user_nickname=null;
        if(!is_null(session()->get('user_id')))
        {
            $User = User::where('id', session()->get('user_id') )->first();            
            $user_nickname=$User->nickname;
        }




        $Merchandise = Merchandise::findOrFail($merchandise_id);   //撈取商品資料

        if(!is_null($Merchandise->photo)){
            $Merchandise->photo = url($Merchandise->photo);       //設定商品照片網址
        }

        $binding=[  'title' => $Merchandise->name , 
                    'Merchandise' => $Merchandise , 
                    'user_nickname'=> $user_nickname ,
                ];

        return view('merchandise.showMerchandise',$binding);        
    }



    //----------------------- 商品購買 --------------------------------------------
    public function merchandiseItemBuyProcess($merchandise_id){

        $input = request()->all();     //接收輸入資料

        //定義驗證規則
        $rules = [
            'buy_count'   =>['required','integer','min:1'],
            //'password'=>['required','min:6'],
        ];

        //驗證資料
        $validator = validator($input, $rules);

        if($validator->fails()){
            //驗證資料錯誤
            return redirect('/merchandise/' . $merchandise_id)
                        ->withErrors($validator)
                        ->withInput();
        }



        try{
            // 取得登入會員資料
            $user_id = session()->get('user_id');
            $User = User::findOrFail($user_id);

            // ... 交易開始 ...
            DB::beginTransaction();

            $Merchandise = Merchandise::findOrFail($merchandise_id);   //撈取商品資料


            $buy_count = $input['buy_count'];           //購買數量
            $remain_count_after_buy = $Merchandise->remain_count - $buy_count;           //購買後剩餘數量

            if($remain_count_after_buy < 0 ){
                //購買後剩餘數量小於0，不足以賣
                throw new Exception('商品數量不足，無法購買');
            }

            $Merchandise->remain_count = $remain_count_after_buy;
            $Merchandise->save();

            $total_price = $buy_count * $Merchandise->price ;           //總金額


            $transaction_data = [
                'user_id'       =>      $User->id,
                'merchandise_id'=>      $Merchandise->id,
                'price'         =>      $Merchandise->price,
                'buy_count'     =>      $buy_count,
                'total_price'   =>      $total_price,
            ];

            // ... 建立交易資料 ...
            Transaction::create($transaction_data);


            // ... 處理交易 ...
            // ... 處理交易 ...
            // ... 處理交易 ...


            // ... 交易結束 ...
            DB::commit();

            //回傳購物成功訊息
            $message = [
                'msg' => ['購買成功',] ,
            ];

            return redirect()
                    ->to('/merchandise/' . $Merchandise->id)
                    ->withErrors($message);

        }catch(Exception $e){
            // ... 回復原先交易狀態 ...
            DB::rollBack();

            // ... 處理交易錯誤 ...

            //回傳錯誤訊息
            $message = [
                'msg' => [ $e->getMessage() ,] ,
            ];

            return redirect()
                    ->back()
                    ->withErrors($message)
                    ->withInput();
        }
   
    }



    //----------------------- 編輯商品 --------------------------------------------
    public function merchandiseItemEditPage($merchandise_id){

    	$Merchandise = Merchandise::findOrFail($merchandise_id);   //撈取商品資料

    	if(!is_null($Merchandise->photo)){
    		$Merchandise->photo = url($Merchandise->photo);       //設定商品照片網址
    	}

    	$binding=[ 'title' => '編輯商品' , 'Merchandise' => $Merchandise , ];

    	return view('merchandise.editMerchandise',$binding);    	
    }


    //----------------------- 更新商品 --------------------------------------------
    public function merchandiseItemUpdateProcess($merchandise_id){

    	$Merchandise = Merchandise::findOrFail($merchandise_id);  //撈取商品資料

    	$input = request()->all();
    	//var_dump($input);
    	//exit();
    	$rules = [
    		'status'		 =>['required' ,'in:c,s'],
    		'name'			 =>['required' ,'max:80'],    		
    		'name_en'		 =>['required' ,'max:80'],
    		'introduction'	 =>['required' ,'max:2000'],    		
    		'introduction_en'=>['required' ,'max:2000'],
    		'photo'			 =>['file'	   ,'image'	 ,'max: 10240'],
    		'price'			 =>['required' ,'integer','min:0'],    		
    		'remain_count'	 =>['required' ,'integer','min:0'],
    	];

    	$validator = validator($input, $rules);

    	if($validator->fails()){
    		return redirect('/merchandise/' . $Merchandise->id . '/edit')
    					->withErrors($validator)
    					->withInput();
    	}


    	
    	if( isset($input['photo']) ){    //有上傳圖片
    		$photo = $input['photo'];

    		$file_extension = $photo->getClientOriginalExtension();   //檔案附檔名

    		$file_name = uniqid() . '.' . $file_extension;            //產生自並隨機檔案名稱


            //Lets create path for post_id if it doesn't exist yet e.g `public\blogpost\23`
            $file_dir = 'assets/images/merchandise/';
            if(!File::exists( $file_dir )) File::makeDirectory( $file_dir , 775);   //創造有權限的目錄


    		$file_relative_path = $file_dir . $file_name;     //檔案相對路徑

    		$file_path = public_path($file_relative_path);                //產生該檔案實體路徑

    		$image = Image::make( $photo )
                ->resize(450,300)->save($file_path); //裁切圖片(需packagist安裝 intervention/image)

    		$input['photo'] = $file_relative_path;            //設定圖片檔案相對位置
    	}

    	$Merchandise->update($input);

    	return redirect('/merchandise/manage');// ' . $Merchandise->id . '/edit');
    }



    //-------------------------------管理商品---------------------------------
    public function merchandiseManageListPage(){
        $row_per_page = 10;     //每頁資料量

        //撈取商品分頁資料
        $MerchandisePaginate = Merchandise::OrderBy('created_at','desc')->paginate($row_per_page);

        //設定商品圖片網址
        foreach ($MerchandisePaginate as $Merchandise ) {
            if( !is_null($Merchandise->photo) ){
                $Merchandise->photo = url($Merchandise->photo);
            }
        }

        $binding = [
            'title'=>'管理商品', 
            'MerchandisePaginate'=> $MerchandisePaginate, 
        ];


        return view('merchandise.manageMerchandise', $binding);
    }
}
