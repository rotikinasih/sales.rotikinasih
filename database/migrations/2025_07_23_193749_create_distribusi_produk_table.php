<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistribusiProdukTable extends Migration
{
    public function up()
    {
        Schema::create('distribusi_produk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monitoring_order_id');
            $table->foreign('monitoring_order_id')->references('id')->on('monitoring_order')->cascadeOnDelete();

            $table->unsignedBigInteger('order_penjualan_id');
            $table->foreign('order_penjualan_id')->references('id')->on('order_penjualan')->cascadeOnDelete();

            $table->tinyInteger('status_distribusi')->comment('1 = Sedang Distribusi, 2 = Selesai Distribusi')->default(1);
            $table->time('jam_pengiriman')->nullable();
            $table->tinyInteger('created_id')->nullable();
            $table->unsignedBigInteger('master_kendaraan_id')->nullable();
            $table->foreign('master_kendaraan_id')->references('id')->on('master_kendaraan')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('distribusi_produk');
    }
}
