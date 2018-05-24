<?php

namespace App\Shop\Entity;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //
    protected $table = 'users';

    protected $primaryKey = 'uId';

    // 可填寫，可能是給mod直接create一筆資料
    protected $fillable = [
        "uName",
    	"uEmail",
    	"uPassword",
        "uCreateIP",
        "uStatus"
    ];
}
