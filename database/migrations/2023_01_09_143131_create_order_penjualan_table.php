<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_telp')->nullable();
            $table->text('alamat_pengiriman')->nullable();
            $table->unsignedBigInteger('master_produk_id')->nullable();
            $table->foreign('master_produk_id')->references('id')->on('master_produk')->nullOnDelete();
            $table->string('jumlah_beli');
            $table->tinyInteger('status_pembayaran')->comment('1 = DP, 2 = Lunas')->nullable();
            $table->text('keterangan')->nullable();
            $table->tinyInteger('status')->comment('1 = Non Aktif, 2 = Aktif');
            $table->tinyInteger('created_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_penjualan');
    }
}
