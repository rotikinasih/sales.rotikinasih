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
             //data pribadi
            $table->string('nama_lengkap');
            $table->string('nama_panggilan')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->integer('umur')->nullable();
            $table->tinyInteger('agama')->comment('islam')->nullable();
            $table->tinyInteger('jenis_kelamin')->comment('0 = Laki-laki, 1 = Perempuan')->nullable();
            $table->tinyInteger('gol_darah')->comment('0 = A, 1 = B, 2 = O, 3 = AB')->nullable();
            $table->string('riwayat_penyakit')->nullable();
            $table->string('no_kk')->nullable();
            $table->string('nik_penduduk')->unique()->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->text('alamat_domisili')->nullable();
            $table->tinyInteger('pendidikan')->comment('0 = SD, 1 = SMP, 2 = SMA, 3 = S1, 4 = S2, 5 = S3')->nullable();
            $table->string('nama_sekolah')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('email_pribadi')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('no_wa')->nullable();
            $table->string('no_keluarga')->nullable();
            $table->string('hubungan_keluarga')->nullable();
            $table->tinyInteger('status_pernikahan')->comment('0 = Belum Menikah, 1 = Sudah Menikah')->nullable();
            $table->tinyInteger('status_keluarga')->comment('0 = Ayah, 1 = Ibu, 2 = Anak ke 1, 3 = Anak ke 2, 4 = Anak ke 3, 5 = Lainya')->nullable();
            $table->tinyInteger('jenis_sosmed')->comment('0 = IG, 1 = FB, 2 = tiktok, 3 = youtube, 4 = Lainya')->nullable();            
            $table->string('nama_sosmed')->nullable();
            
            //data di perusahaan
            $table->integer('nik_karyawan')->nullable();
            $table->unsignedBigInteger('divisi_id')->nullable();
            $table->unsignedBigInteger('pt_id')->nullable();
            $table->unsignedBigInteger('jabatan_id')->nullable();
            $table->string('grade')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->tinyInteger('status_kerja')->comment('0 = Kontrak, 1 = Tetap, 2 = Training')->nullable();
            $table->tinyInteger('komposisi_peran')->comment('0 = Support, 1 = Core')->nullable();
            $table->date('tanggal_kontrak')->nullable();
            $table->date('akhir_kontrak')->nullable();
            $table->string('masa_kontrak')->nullable();
            $table->string('kota_rekuitmen')->nullable();
            $table->string('kota_penugasan')->nullable();
            $table->string('email_internal')->nullable();
            $table->string('no_npwp')->nullable();
            $table->string('no_bpjs_kesehatan')->nullable();
            $table->string('no_bpjs_ketenagakerjaan')->nullable();
            $table->integer('rekening')->nullable();
            $table->tinyInteger('ukuran_baju')->comment('0 = S, 1 = M, 2 = L, 3 = XL, 4 = XXL, 5 = Jumbo')->nullable();
            $table->string('pengalaman_kerja_terakhir')->nullable();
            $table->string('foto')->nullable();
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
