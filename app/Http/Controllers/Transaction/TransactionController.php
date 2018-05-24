<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shop\Entity\User;
use App\Shop\Entity\Merchandise;
use App\Shop\Entity\Transaction;


class TransactionController extends Controller
{

    //----------------------- 交易紀錄頁面 --------------------------------------------
    public function transactionListPage(){
        // 顯示會員資料
        $user_nickname = null;
        // 取得登入會員資料
        $user_id = session()->get('user_id');   //會員primeKey

        if( !is_null( session()->get('user_id') ) )
        {
            $db_User = User::where('uId', $user_id )->first();   //撈取該會員的資料
            $user_nickname = $db_User->uName;       //會員的名稱
        }

        $row_per_page = 10;     //每頁資料量

        // 撈取商品分頁資料
        $TransactionPaginate = Transaction::query()
            ->join('merchandise', function ($join) {
                $join->on('transaction.merchandise_id' , '=' , 'merchandise.id');
            })
            ->where('user_id', $user_id)
            ->OrderBy('created_at','desc')
            ->select('transaction.*',
                            'merchandise.name',
                            'merchandise.photo'
                            )
            ->paginate($row_per_page);


 /*       $Dao = ModProductCategory::query()
            ->join('mod_product_category_rakuten', function ($join) {
                $join->on('mod_product_category_rakuten.iCategoryId', '=', 'mod_product_category.iId');
            })
            ->whereIn('mod_product_category.vCategoryValue', is_array($genreId) ? $genreId : array($genreId))
            ->where($map)
            ->where($params)
            ->orderBy($this->orderBy, $this->sort)
            ->get();  */

        /*
         * 針對每個交易紀錄商品設定
         */
        $price_total = 0;
        $buy_count_total = 0;
        foreach ($TransactionPaginate as $Transaction ) {
            // 設定商品圖片網址
            if( !is_null($Transaction->photo) ){
                $Transaction->photo = url($Transaction->photo);
            }
            // 交易紀錄總金額
            if( !is_null($Transaction->total_price) ){
                $price_total += $Transaction->total_price;
            }
            // 交易紀錄總數量
            if( !is_null($Transaction->buy_count) ){
                $buy_count_total += $Transaction->buy_count;
            }
        }

        $binding = [
            'title' => '交易紀錄',
            'TransactionPaginate' => $TransactionPaginate,
            'user_nickname' => $user_nickname ,
            'price_total' => $price_total ,
            'buy_count_total' => $buy_count_total
        ];

        return view('transaction.listUserTransaction', $binding);
    }
}
