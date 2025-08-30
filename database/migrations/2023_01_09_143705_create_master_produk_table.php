<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->decimal('harga_jual', 15, 2)->default(0); 
            $table->unsignedBigInteger('kategori_produk_id')->nullable();
            $table->foreign('kategori_produk_id')->references('id')->on('kategori_produk')->nullOnDelete();
            $table->unsignedBigInteger('tipe_outlet_id')->nullable();
            $table->foreign('tipe_outlet_id')->references('id')->on('tipe_outlet')->nullOnDelete();
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
        Schema::dropIfExists('master_produk');
    }
}
