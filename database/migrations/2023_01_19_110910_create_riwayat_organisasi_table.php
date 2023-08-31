<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatOrganisasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_organisasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karyawan_id');
            $table->foreign('karyawan_id')->references('id')->on('karyawan')->onDelete('cascade');

            $table->unsignedBigInteger('pt_id');
            $table->foreign('pt_id')->references('id')->on('master_perusahaan')->onDelete('cascade');

            $table->unsignedBigInteger('divisi_id');
            $table->foreign('divisi_id')->references('id')->on('master_divisi')->onDelete('cascade');

            $table->unsignedBigInteger('jabatan_id');
            $table->foreign('jabatan_id')->references('id')->on('master_jabatan')->onDelete('cascade');

            // $table->date('tgl_gabung_grup');
            $table->date('tgl_masuk');
            $table->date('tgl_berakhir')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riwayat_organisasi');
    }
}
