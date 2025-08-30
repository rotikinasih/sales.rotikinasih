<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitoringOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitoring_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_penjualan_id');
            $table->foreign('order_penjualan_id')->references('id')->on('order_penjualan')->cascadeOnDelete();
            $table->tinyInteger('status_produksi')->comment('1 = Sedang Diproduksi, 2 = Selesai Diproduksi')->default(1);
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
        Schema::dropIfExists('monitoring_order');
    }
}
