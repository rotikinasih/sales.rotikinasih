<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pelatihan', function (Blueprint $table) {
            $table->tinyInteger('deleted_status')->default(0);
        });
        Schema::table('riwayat_organisasi', function (Blueprint $table) {
            $table->tinyInteger('deleted_status')->default(0);
        });
        Schema::table('catatan_pelanggaran', function (Blueprint $table) {
            $table->tinyInteger('deleted_status')->default(0);
        });
        Schema::table('karyawan_resign', function (Blueprint $table) {
            $table->tinyInteger('deleted_status')->default(0);
        });
        Schema::table('karyawan_phk', function (Blueprint $table) {
            $table->tinyInteger('deleted_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pelatihan', function (Blueprint $table) {
            $table->dropColumn('deleted_status');
        });
        Schema::table('riwayat_organisasi', function (Blueprint $table) {
            $table->dropColumn('deleted_status');
        });
        Schema::table('catatan_pelanggaran', function (Blueprint $table) {
            $table->dropColumn('deleted_status');
        });
        Schema::table('karyawan_resign', function (Blueprint $table) {
            $table->dropColumn('deleted_status');
        });
        Schema::table('karyawan_phk', function (Blueprint $table) {
            $table->dropColumn('deleted_status');
        });
    }
}
