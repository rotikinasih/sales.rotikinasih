<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatatanPelanggaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catatan_pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karyawan_id');
            $table->foreign('karyawan_id')->references('id')->on('karyawan')->onDelete('cascade');
            $table->date('tanggal');
            $table->text('catatan');
            $table->tinyInteger('tingkatan')->comment('1 = Teguran Lisan, 2 = Teguran Tertulis, 4 = SP 1, 3 = SP 2, 4 = SP 3');
            $table->tinyInteger('status')->comment('1 = Diproses, 2 = Selesai');
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
        Schema::dropIfExists('catatan_pelanggaran');
    }
}
