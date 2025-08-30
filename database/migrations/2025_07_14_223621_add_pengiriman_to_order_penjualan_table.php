<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPengirimanToOrderPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('order_penjualan', function (Blueprint $table) {
        $table->date('tanggal_pengiriman')->nullable();
        $table->enum('jam_pengiriman', ['Pagi', 'Siang', 'Sore', 'Malam'])->nullable();
    });
}

public function down()
{
    Schema::table('order_penjualan', function (Blueprint $table) {
        $table->dropColumn(['tanggal_pengiriman', 'jam_pengiriman']);
    });
}

}
