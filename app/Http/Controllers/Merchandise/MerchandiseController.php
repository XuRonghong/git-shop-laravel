<?php

namespace App\Http\Controllers\Merchandise;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Shop\Entity\Merchandise;
use Validator;
use Image;
use Illuminate\Support\Facades\File;

class MerchandiseController extends Controller
{

    //----------------------- 商品頁面 --------------------------------------------
    public function merchandiseListPage(){

        $binding = [
            'title' => session()->get('user_id'),
            'mes'   => session()->get('message'),
        ];

        return view('editMerchandise', $binding);
    }


    //----------------------- 新增商品頁面 --------------------------------------------
    public function merchandiseCreatePage(){

        $binding = [
            'title' => '增加商品',
            'mes'   => '',
        ];

        return view('editMerchandise', $binding);
    }


    //----------------------- 增加商品 --------------------------------------------
    public function merchandiseCreateProcess(){
        //建立商品基本資訊
    	$merchandise_data = [
    		'status' 		  => 'C',
    		'name' 			  => '',
    		'name_en' 		  => '',
    		'introduction' 	  => '',
    		'introduction_en' => '',
    		'photo' 		  => 'null',
    		'price' 		  => '0',
    		'remain_count' 	  => '0',
    	];
    	$Merchandise = Merchandise::create($merchandise_data);


    	return redirect('/merchandise/'.$Merchandise->id.'/edit');
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

    	return redirect('/merchandise/' . $Merchandise->id . '/edit');
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
