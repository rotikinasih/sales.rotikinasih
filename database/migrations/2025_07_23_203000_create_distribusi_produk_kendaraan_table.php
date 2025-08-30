<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistribusiProdukKendaraanTable extends Migration
{
    public function up()
    {
        Schema::create('distribusi_produk_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('distribusi_produk_id');
            $table->unsignedBigInteger('master_kendaraan_id');
            $table->foreign('distribusi_produk_id')->references('id')->on('distribusi_produk')->cascadeOnDelete();
            $table->foreign('master_kendaraan_id')->references('id')->on('master_kendaraan')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('distribusi_produk_kendaraan');
    }
}