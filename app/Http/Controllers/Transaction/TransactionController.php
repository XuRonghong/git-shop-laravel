<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shop\Entity\Transaction;


class TransactionController extends Controller
{

    //----------------------- 交易紀錄頁面 --------------------------------------------
    public function transactionListPage(){

    	// 取得登入會員資料
        $user_id = session()->get('user_id');

        $row_per_page = 10;     //每頁資料量

        //撈取商品分頁資料
        $TransactionPaginate = Transaction::where('user_id', $user_id)
                ->OrderBy('created_at','desc')
                ->with('Merchandise')
                ->paginate($row_per_page);

        //設定商品圖片網址
        foreach ($TransactionPaginate as $Transaction ) {
            if( !is_null($Transaction->Merchandise->photo) ){
                $Transaction->Merchandise->photo = url($Transaction->Merchandise->photo);
            }
        }

        $binding = [
            'title'=>'交易紀錄', 
            'TransactionPaginate'=> $TransactionPaginate, 
        ];


        return view('transaction.listUserTransaction', $binding);
    }
}
