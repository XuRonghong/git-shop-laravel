<?php

namespace App\Shop\Entity;

use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{
    //
    protected $table = 'merchandise';

    protected $primaryKey = 'id';

    // 可填寫，可能是給mod直接create一筆資料
    protected $fillable = [
    	"id",
    	"status",
    	"name",
    	"name_en",
    	"introduction",
    	"introduction_en",
    	"photo",
    	"price",
    	"remain_count",
    ];
}
