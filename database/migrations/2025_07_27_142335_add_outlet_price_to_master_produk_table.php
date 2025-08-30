<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOutletPriceToMasterProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_produk', function (Blueprint $table) {
            $table->decimal('outlet_price', 15, 2)->nullable()->after('harga_jual');
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
            $table->dropColumn('outlet_price');
        });
    }
}
