<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFrakturPembayaranDpKeteranganPenginputLokasiToOrderPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_penjualan', function (Blueprint $table) {
            $table->string('no_fraktur')->unique()->nullable();
            $table->integer('jumlah_bayar')->default(0);
            $table->integer('total_bayar')->default(0);
            $table->integer('kurang_bayar')->default(0);
            $table->enum('metode_pembayaran', ['Tunai', 'Qris', 'Debit', 'Transfer'])->nullable();
            $table->date('tanggal_dp')->nullable();
            $table->text('keterangan_staf')->nullable();
            $table->string('penginput')->nullable();
            $table->string('lokasi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_penjualan', function (Blueprint $table) {
            $table->dropColumn('no_fraktur');
            $table->dropColumn('jumlah_bayar');
            $table->dropColumn('total_bayar');
            $table->dropColumn('kurang_bayar');
            $table->dropColumn('metode_pembayaran');
            $table->dropColumn('tanggal_dp');
            $table->dropColumn('keterangan_staf');
            $table->dropColumn('penginput');
            $table->dropColumn('lokasi');
        });
    }
}
