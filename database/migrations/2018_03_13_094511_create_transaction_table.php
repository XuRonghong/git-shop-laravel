<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {

            $table->increments('id');   //交易編號

            $table->integer('user_id');

            $table->integer('merchandise_id');
            
            $table->integer('price');   //當時購買價格
            
            $table->integer('buy_count');
            
            $table->integer('total_price');
            
            $table->timestamps();       //時間戳記


            $table->index(['user_id'], 'user_transaction_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction');
    }
}
