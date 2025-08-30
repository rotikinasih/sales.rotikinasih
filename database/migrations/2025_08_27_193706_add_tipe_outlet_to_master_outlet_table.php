<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipeOutletToMasterOutletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_outlet', function (Blueprint $table) {
            $table->unsignedBigInteger('tipe_outlet_id')->nullable()->after('radius');
            $table->foreign('tipe_outlet_id')->references('id')->on('tipe_outlet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_outlet', function (Blueprint $table) {
            $table->dropForeign(['tipe_outlet_id']);
            $table->dropColumn('tipe_outlet_id');
        });
    }
}
