<?php

namespace App\Http\Middleware;

use Closure;
use App\Shop\Entity\User;

class AuthUserAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $is_allow_access = false;       //預設不允許請求

        $user_id = session()->get('user_id');       //取得會員編號

        if(!is_null($user_id)){
            //會員編號取得會員資料
            $User = User::findOrFail($user_id);
            if($User->type == 'A'){
                //是管理者，允許存取
                $is_allow_access = true;
            }
        }

        //若不允許，導回頁
        if(!$is_allow_access)   return redirect()->back()->withErrors("你沒有權限存取!!"); 

        //若允許，繼續下個請求
        return $next($request);         
    }
}
