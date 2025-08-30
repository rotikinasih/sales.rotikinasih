<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderProduksiKasirToMonitoringOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monitoring_order', function (Blueprint $table) {
        $table->unsignedBigInteger('order_produksi_kasir_id')->nullable()->after('order_penjualan_id');
        $table->foreign('order_produksi_kasir_id')
              ->references('id')
              ->on('order_produksi_kasir')
              ->cascadeOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monitoring_order', function (Blueprint $table) {
        $table->dropForeign(['order_produksi_kasir_id']);
        $table->dropColumn('order_produksi_kasir_id');
    });
    }
}
