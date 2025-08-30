<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHargaModalToMasterProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_produk', function (Blueprint $table) {
            $table->integer('harga_modal')->nullable()->after('outlet_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_produk', function (Blueprint $table) {
            $table->dropColumn('harga_modal');
        });
    }
}
