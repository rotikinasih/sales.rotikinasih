<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStokToOrderPenjualanTable extends Migration
{
    public function up()
    {
        Schema::table('order_penjualan', function (Blueprint $table) {
            $table->integer('stok')->default(0)->after('status'); // tambahkan setelah status
        });
    }

    public function down()
    {
        Schema::table('order_penjualan', function (Blueprint $table) {
            $table->dropColumn('stok');
        });
    }
}
