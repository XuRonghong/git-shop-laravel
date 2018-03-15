<?php

namespace App\Shop\Entity;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $table = 'transaction';

    protected $primaryKey = 'id';

    protected $fillable = [
    	"id",
    	"user_id",
    	"introduction",
    	"merchandise_id",
    	"price",
    	"buy_count",
    	"total_price",
    ];


    public function Merchandise(){
        return $this->hasOne('App\Shop\Entity\Merchandise', 'id','merchandise_id');
    }
}
