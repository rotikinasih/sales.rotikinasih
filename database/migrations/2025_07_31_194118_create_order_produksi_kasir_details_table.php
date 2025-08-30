<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProduksiKasirDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_produksi_kasir_details', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('order_produksi_kasir_id');
        $table->unsignedBigInteger('master_produk_id');
        $table->integer('jumlah_beli');
        $table->timestamps();

        $table->foreign('order_produksi_kasir_id')->references('id')->on('order_produksi_kasir')->cascadeOnDelete();
        $table->foreign('master_produk_id')->references('id')->on('master_produk')->restrictOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_produksi_kasir_details');
    }
}
