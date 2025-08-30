<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryStokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_stok', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_produk_id');
            $table->integer('stok')->default(0);
            $table->timestamps();

            $table->foreign('master_produk_id')->references('id')->on('master_produk')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_stok');
    }
}
