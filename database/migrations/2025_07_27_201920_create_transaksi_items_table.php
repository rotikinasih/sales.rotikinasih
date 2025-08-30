<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('transaksi_id')->constrained()->onDelete('cascade');
    $table->foreignId('master_produk_id')->constrained('master_produk')->onDelete('cascade');
    $table->integer('jumlah');
    $table->decimal('harga_jual', 12, 2);
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
        Schema::dropIfExists('transaksi_items');
    }
}
