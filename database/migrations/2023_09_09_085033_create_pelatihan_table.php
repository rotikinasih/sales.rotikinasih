<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelatihanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelatihan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karyawan_id');
            $table->foreign('karyawan_id')->references('id')->on('karyawan')->onDelete('cascade');
            $table->tinyInteger('kategori_pelatihan')->comment('1 = Internal Perusahaan, 2 = Personal(Individu), 3 = Pemerintah');
            $table->string('nama_pelatihan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('durasi_pelatihan')->nullable();
            $table->tinyInteger('created_id')->nullable();
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
        Schema::dropIfExists('pelatihan');
    }
}
