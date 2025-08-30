<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPenjualanCicilanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_penjualan_cicilan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_penjualan_id');
            $table->integer('nominal')->default(0);
            $table->tinyInteger('cicilan_ke'); // 1, 2, 3
            $table->date('tanggal');
            $table->timestamps();

            $table->foreign('order_penjualan_id')->references('id')->on('order_penjualan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_penjualan_cicilan');
    }
}
