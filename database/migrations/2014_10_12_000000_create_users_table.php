<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            //會員編號
            $table->increments('id');
            //Email
            $table->string('email',150)->unique('user_email_uk');

            $table->string('password',60);

            $table->string('type',1)->default('G');

            $table->string('nickname',50);  //別名

            $table->timestamps();


            $table->index(['nickname'], 'user_nickname_idx');
        });
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
