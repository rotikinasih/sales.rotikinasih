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
            $table->tinyInteger('jenis_kelamin')->comment('01 = Laki-laki, 2 = Perempuan')->nullable();
            $table->tinyInteger('gol_darah')->comment('1 = A, 2 = B, 3 = O, 4 = AB')->nullable();
            $table->string('riwayat_penyakit')->nullable();
            $table->string('no_kk')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('nik_penduduk')->unique()->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->text('alamat_domisili')->nullable();
            $table->tinyInteger('pendidikan')->comment('1 = SD, 2 = SMP, 3 = SMA, 4 = D1, 5 = D2, 6 = D3, 7 = D4, 8 = S1, 9 = S2, 10 = S3')->nullable();
            $table->string('nama_sekolah')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('email_pribadi')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('no_wa')->nullable();
            $table->string('no_keluarga')->nullable();
            $table->tinyInteger('hubungan_keluarga')->comment('1 = Suami/Istri 2 = Ayah, 3 = Ibu, 4 = Kakak/Adik, 5 = Paman/Bibi, 6 = Kakek/Nenek')->nullable();
            $table->tinyInteger('status_pernikahan')->comment('1 = Belum Menikah, 2 = Sudah Menikah, 3 = Janda, 4 = Duda')->nullable();
            $table->tinyInteger('status_keluarga')->comment('1 = Kepala Keluarga, 2 = Ibu, 3 = Anak ke 1, 4 = Anak ke 2, 5 = Anak ke 3, 6 = Lainya')->nullable();
            $table->tinyInteger('jenis_sosmed')->comment('1 = IG, 2 = FB, 3 = tiktok, 4 = youtube, 5 = twitter')->nullable();            
            $table->string('nama_sosmed')->nullable();
            
            //data di perusahaan
            $table->integer('nik_karyawan')->nullable();
            $table->unsignedBigInteger('divisi_id')->nullable();
            $table->unsignedBigInteger('pt_id')->nullable();
            $table->unsignedBigInteger('jabatan_id')->nullable();
            $table->string('grade')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->tinyInteger('status_kerja')->comment('1 = Kontrak, 2 = Tetap, 3 = Training')->nullable();
            $table->tinyInteger('komposisi_karyawan')->comment('1 = Direktor, 2 = Div Head, 3 = Dept Head, 4 = Sect Head, 5 = Head, 6 = Staff, 7 = Non Staff')->nullable();
            $table->tinyInteger('komposisi_peran')->comment('1 = Support, 2 = Core')->nullable();
            $table->string('komposisi_generasi')->nullable();
            $table->date('tanggal_kontrak')->nullable();
            $table->date('tanggal_karyawan_tetap')->nullable();
            $table->date('akhir_kontrak')->nullable();
            $table->string('masa_kontrak')->nullable();
            $table->string('masa_kerja_bulan')->nullable();
            $table->string('masa_kerja_tahun')->nullable();
            $table->string('kota_rekruitmen')->nullable();
            $table->string('kota_penugasan')->nullable();
            $table->string('email_internal')->nullable();
            $table->string('no_npwp')->nullable();
            $table->string('no_bpjs_kesehatan')->nullable();
            $table->string('no_bpjs_ketenagakerjaan')->nullable();
            $table->string('nama_bank')->nullable();
            $table->integer('rekening')->nullable();
            $table->tinyInteger('ukuran_baju')->comment('1 = S, 2 = M, 3 = L, 4 = XL, 5 = XXL, 6 = Jumbo')->nullable();
            $table->string('pengalaman_kerja_terakhir')->nullable();
            $table->string('jabatan_kerja_terakhir')->nullable();
            $table->string('foto')->nullable();
            $table->tinyInteger('status_karyawan')->comment('1 = Aktif, 2 = Resign, 3 = PHK', )->default(0)->nullable();
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
        Schema::dropIfExists('karyawan');
    }
}
