<?php

namespace App\Http\Middleware;

use Closure;
use App\Shop\Entity\User;

class AuthUserMiddleware
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
            //有會員編號可允許
            $is_allow_access = true;            
        }

        //若不允許，導回至登入畫面
        if(!$is_allow_access)   return redirect()->to('/user/auth/sign-in');    

        //若允許，繼續下個請求
        return $next($request);
    }
}
