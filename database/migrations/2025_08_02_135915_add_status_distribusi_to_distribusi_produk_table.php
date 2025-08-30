<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusDistribusiToDistribusiProdukTable extends Migration
{
    public function up()
    {
        Schema::table('distribusi_produk', function (Blueprint $table) {
            $table->tinyInteger('status_distribusi')->default(1)->comment('1=Dikirim, 2=Diterima, 3=Ditolak');
        });
    }

    public function down()
    {
        Schema::table('distribusi_produk', function (Blueprint $table) {
            $table->dropColumn('status_distribusi');
        });
    }
}
