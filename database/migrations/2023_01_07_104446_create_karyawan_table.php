<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_karyawan');
            $table->integer('nik_karyawan')->unique();
            $table->tinyInteger('status_kerja')->comment('0 = Kontrak, 1 = Tetap');
            $table->unsignedBigInteger('divisi_id');
            $table->unsignedBigInteger('pt_id');
            $table->string('foto')->nullable();
            $table->date('tanggal_masuk');
            $table->date('tanggal_kontrak')->nullable();
            $table->date('akhir_kontrak')->nullable();
            $table->string('no_kk');
            $table->string('nik_penduduk')->unique();
            $table->string('grade');
            $table->unsignedBigInteger('jabatan_id');
            $table->string('no_hp');
            $table->string('no_wa');
            $table->string('no_bpjs_kesehatan')->nullable();
            $table->string('no_bpjs_ketenagakerjaan')->nullable();
            $table->tinyInteger('gol_darah')->comment('0 = A, 1 = B, 2 = O, 3 = AB')->nullable();
            $table->string('email');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->integer('umur')->nullable();
            $table->text('alamat_ktp');
            $table->text('alamat_domisili');
            $table->tinyInteger('jenis_kelamin')->comment('0 = Laki-laki, 1 = Perempuan');
            $table->tinyInteger('status_pernikahan')->comment('0 = Belum Menikah, 1 = Sudah Menikah');
            $table->tinyInteger('pendidikan')->comment('0 = SD, 1 = SMP, 2 = SMA, 3 = S1, 4 = S2, 5 = S3');
            $table->string('nama_sekolah');
            $table->string('kab_penugasan');
            $table->integer('rekening')->nullable();
            $table->tinyInteger('ukuran_baju')->comment('0 = S, 1 = M, 2 = L, 3 = XL, 4 = XXL, 5 = Jumbo');
            $table->string('no_sdr');
            $table->string('hubungan');
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
        Schema::dropIfExists('karyawan');
    }
}
