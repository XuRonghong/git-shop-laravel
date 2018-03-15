<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Testing\Fakes\Mail;
use App\Http\Controllers\Controller;
use App\Shop\Entity\User;
use Validator;

class UserAuthController extends Controller
{

    //------------------頁面 login --------------------------------------------
    public function signInPage(){

    	$binding = [
    		'title' => '登入',
            'mes'   => session()->get('message'),
    	];

    	return view('auth.signIn', $binding);
    }



    //------------------處理登入資料----------------------------------------
    public function signInProcess(){

    	$input = request()->all();     //接收輸入資料

        //定義驗證規則
    	$rules = [
    		'email'   =>['required','max:150','email'],
    		'password'=>['required','min:6'],
    	];

        //驗證資料
    	$validator = validator($input, $rules);

    	if($validator->fails()){
            //驗證資料錯誤
    		return redirect('/user/auth/sign-in')
    					->withErrors($validator)
    					->withInput();
    	}

        //從資料庫撈取使用者資料 sql * form users where email= input['email'] limit 1
    	$User = User::where('email',$input['email'])->firstOrFail();

        //密碼是否一樣
    	$is_password_correct=Hash::check($input['password'], $User->password);
    	if(!$is_password_correct){
            //錯誤訊息陣列
    		$error_message=['msg'=> ['密碼驗證錯誤',] ,];
            //滾回去
    		return redirect('/user/auth/sign-in')
    					->withErrors($error_message)
    					->withInput();
    	}

    	session()->put('user_id',$User->id);   //驗證成功，紀錄會員編號


        return redirect('/merchandise/');
        //重新導向到原先造訪頁面，沒有嘗試造訪頁則導向首頁
    	//return redirect()->intended('/');
    }



    //------------------logout------------------------------------------
    public function signOut(){
        //清除session
    	session()->forget('user_id');

    	return redirect('/');
    }



    //------------------註冊頁------------------------------------------
    public function signUpPage(){

        $binding = [
            'title' => '註冊'
        ];
        return view('auth.signUp',$binding);
    }



    //------------------處理註冊資料----------------------------------------------
    public function signUpProcess(){

        $input = request()->all();
        //var_dump($input);
        //exit();

        $rules = [
            'nickname'              =>['required','max:50'],
            'email'                 =>['required','max:150','email'],
            'password'              =>['required','same:password_confirmation','min:6'],
            'password_confirmation' =>['required','min:6'],          
            'type'                  =>['required','in:G,A'],
        ];

        $validator = validator($input, $rules);

        if($validator->fails()){
            return redirect('/user/auth/sign-up')
                        ->withErrors($validator)
                        ->withInput();
        }


        //從資料庫撈取所有使用者信箱，是否重複
        $UserEmail = User::all();
        foreach ($UserEmail as $key ) {
            if($key->email==$input['email'])
                return redirect('/user/auth/sign-up')
                        ->withErrors('信箱已存在')
                        ->withInput();
        }

        $input['password'] = Hash::make($input['password']);

        $Users = User::create($input);

        /*$mail_binding = ['nickname'=>$input['nickname']];
        Mail::send('email.signUpEmailNotification', $mail_binding, function($mail) use($input){
            $mail->to($input['email']);
            $mail->from('hello080810@gmail.com');
            $mail->subject('註冊成功-Shop Laravel');
        });*/


        return redirect('/user/auth/sign-in')
                        ->with(['message'=> ['signUpSuccess'=>'註冊成功',] ,] );
    }

}
