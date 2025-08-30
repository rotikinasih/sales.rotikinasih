<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPenjualanDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('order_penjualan_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_penjualan_id');
            $table->unsignedBigInteger('master_produk_id');
            $table->integer('jumlah_beli');
            $table->timestamps();

            // Relasi
            $table->foreign('order_penjualan_id')
                  ->references('id')
                  ->on('order_penjualan')
                  ->onDelete('cascade');

            $table->foreign('master_produk_id')
                  ->references('id')
                  ->on('master_produk')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_penjualan_details');
    }
}
