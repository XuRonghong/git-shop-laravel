<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\Hash;
use App\Shop\Entity\User;

class CreateUsersTable extends Migration
{
    protected $table = 'users';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //if ( !Schema::hasTable( $this->table )) {
            Schema::create($this->table, function (Blueprint $table) {
                $table->increments('uId');
                $table->string('uName',20);
                $table->string('uEmail',64)->unique();
                $table->string('uPassword',64);
                $table->string('uCreateIP',255);
                $table->string('uType',1)->default('G');
                $table->string('uStatus',3);
                $table->rememberToken();
                $table->timestamps();
            });
            /*
             * 創建會員資料表時，先造一個god帳號
             */
            $Dao = new User ();
            $Dao->uName = 'rh';
            $Dao->uEmail = "rh@rh.com";
            $Dao->uPassword = hash::make( '123456' );
            $Dao->uCreateIP = Request::ip();
            //$Dao->iCreateTime = $Dao->iUpdateTime = time();
            //$Dao->bActive = 1;
            $Dao->uType = 'A';
            $Dao->uStatus = 1;
            $Dao->save();

       /* } else {
            if ( !Schema::hasColumn( $this->table, 'uId' )) {
                Schema::table( $this->table, function( $table ) {
                    $table->increments( 'uId' );
                } );
            } else {
            }
        }*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
