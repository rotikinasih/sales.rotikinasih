<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProduksiKasirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_produksi_kasir', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('outlet_id');
        $table->string('kode_distribusi')->unique();
        $table->tinyInteger('status')->default(1);
        $table->tinyInteger('created_id')->nullable();
        $table->timestamps();

        $table->foreign('outlet_id')->references('id')->on('master_outlet')->cascadeOnDelete();
    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_produksi_kasir');
    }
}
