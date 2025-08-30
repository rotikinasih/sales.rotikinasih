<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterKendaraanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_kendaraan', function (Blueprint $table) {
            $table->id();
            
           $table->string('kode_kendaraan');
           $table->string('merk_kendaraan');
           $table->string('driver');
            $table->decimal('daya_angkut', 8, 2)->nullable();

            $table->tinyInteger('status')->comment('1 = Non Aktif, 2 = Aktif');
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
        Schema::dropIfExists('master_kendaraan');
    }
}
