<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchandiseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchandise', function (Blueprint $table) {

            $table->increments('id');
            // -c (create):建立中
            // -s (sell): 可販售
            $table->string('status',1)->default('c');

            $table->string('name',80)->nullable();      //中文名稱
            $table->string('name_en',80)->nullable();   //英文名稱

            $table->text('introduction');
            $table->text('introduction_en');

            $table->string('photo',50)->nullable();

            $table->integer('price')->default(0);

            $table->integer('remain_count')->default(0);    //商品剩餘數量

            $table->timestamps();


            $table->index(['status'], 'merchandise_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchandise');
    }
}
