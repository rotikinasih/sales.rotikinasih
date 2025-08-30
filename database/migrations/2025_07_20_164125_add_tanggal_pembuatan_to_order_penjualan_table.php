<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalPembuatanToOrderPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('order_penjualan', function (Blueprint $table) {
        $table->date('tanggal_pembuatan')->nullable()->after('status');
    });
}

public function down()
{
    Schema::table('order_penjualan', function (Blueprint $table) {
        $table->dropColumn('tanggal_pembuatan');
    });
}

}
