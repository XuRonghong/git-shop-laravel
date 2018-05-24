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
    		'uEmail'   =>['required','max:150'],
    		'uPassword'=>['required','min:1'],
    	];

        //驗證資料
    	$validator = validator($input, $rules);

    	if($validator->fails()){
            //驗證資料錯誤
    		return redirect()->back()//('/user/auth/sign-in')
    					->withErrors($validator)
    					->withInput();
    	}

        //從資料庫撈取使用者資料 sql * form users where email= input['email'] limit 1
    	$dbUser = User::all();
        foreach ($dbUser as $user ) {
            # code...
            if($user['uEmail'] == $input['uEmail'] || $user['uName'] == $input['uEmail'])
            {
                 //密碼是否一樣
                $is_password_correct=Hash::check($input['uPassword'], $user['uPassword']);
                if(!$is_password_correct){
                    //錯誤訊息陣列
                    $error_message=['msg'=> ['密碼驗證錯誤',] ,];
                    //滾回去
                    return redirect()->back()//'/user/auth/signin')
                                ->withErrors($error_message)
                                ->withInput();
                }
                session()->put('user_id',$user['uId']);   //驗證成功，紀錄會員編號
                session()->put('user_type',$user['uType']);

                if($user['uType'] == "G")return redirect('/merchandise/');
                if($user['uType'] == "A")return redirect('/merchandise/manage');
                    
            }
        }
        //滾回去
        $error_message=['msg'=> ['此信箱不存在',] ,];
        return redirect()->back()
                        ->withErrors($error_message)
                        ->withInput();
        //return redirect('/user/auth/sign-in');
        //重新導向到原先造訪頁面，沒有嘗試造訪頁則導向首頁
        //return redirect()->intended('/user/auth/sign-in');


       
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
            'uName'              =>['required','max:50'],
            'uEmail'                 =>['required','max:150','email'],
            'uPassword'              =>['required','same:password_confirmation','min:6'],
            'password_confirmation' =>['required','min:6'],  
        ];

        $validator = validator($input, $rules);

        if($validator->fails()){
            return redirect()->back()//'/user/auth/signup')
                        ->withErrors($validator)
                        ->withInput();
        }


        //從資料庫撈取所有使用者信箱，是否重複
        $UserEmail = User::all();
        foreach ($UserEmail as $key ) {
            if($key->uEmail==$input['uEmail'])
                return redirect()->back()//'/user/auth/signup')
                        ->withErrors('信箱已存在')
                        ->withInput();
        }

        $input['uPassword'] = Hash::make($input['uPassword']);
        $input['uCreateIP'] = $_SERVER['REMOTE_ADDR'];//Request::ip(); //當地ip
        $input['uStatus'] = 1;  //狀態正常

        $Users = User::create($input);

        if($Users) $mes =['signUpSuccess'=>'註冊成功',];
        else       $mes =['signUpSuccess'=>'註冊加入失敗',];

        /*$mail_binding = ['nickname'=>$input['nickname']];
        Mail::send('email.signUpEmailNotification', $mail_binding, function($mail) use($input){
            $mail->to($input['email']);
            $mail->from('hello080810@gmail.com');
            $mail->subject('註冊成功-Shop Laravel');
        });*/


        return redirect()->back()//('/user/auth/sign-in')
                        ->with(['message'=> $mes ]);
    }



   //------------------最高權限增加管理員------------------------------------------
    public function addadmin(){
        $god = session()->get('user_id');

        $binding = [
            'title' => 'God number '. $god
        ];
        return view('auth.signUpAdmin',$binding);
    }


    //------------------最高權限增加管理員處理----------------------------------------------
    public function addadminProcess(){

        $input = request()->all();
        //var_dump($input);
        //exit();

        $rules = [
            'uName'              =>['required','max:50'],
            'uEmail'                 =>['required','max:150','email'],
            'uPassword'              =>['required','same:password_confirmation','min:6'],
            'password_confirmation' =>['required','min:6'],          
            'uType'                  =>['required','in:G,A'],
        ];

        $validator = validator($input, $rules);

        if($validator->fails()){
            return redirect()->back()//'/user/auth/signup')
                        ->withErrors($validator)
                        ->withInput();
        }


        //從資料庫撈取所有使用者信箱，是否重複
        $UserEmail = User::all();
        foreach ($UserEmail as $key ) {
            if($key->uEmail==$input['uEmail'])
                return redirect()->back()//'/user/auth/signup')
                        ->withErrors('信箱已存在')
                        ->withInput();
        }

        $input['uPassword'] = Hash::make($input['uPassword']);
        $input['uCreateIP'] = $_SERVER['REMOTE_ADDR'];//Request::ip(); //當地ip
        $input['uStatus'] = 1;  //狀態正常

        $Users = User::create($input);

        if($Users) $mes =['signUpSuccess'=>'註冊成功',];
        else       $mes =['signUpSuccess'=>'註冊加入失敗',];

        /*$mail_binding = ['nickname'=>$input['nickname']];
        Mail::send('email.signUpEmailNotification', $mail_binding, function($mail) use($input){
            $mail->to($input['email']);
            $mail->from('hello080810@gmail.com');
            $mail->subject('註冊成功-Shop Laravel');
        });*/


        return redirect()->back() //('/user/auth/signin')
            ->with(['message'=> $mes ]);

    }

}
