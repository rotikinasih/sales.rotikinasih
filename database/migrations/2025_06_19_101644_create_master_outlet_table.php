<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterOutletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_outlet', function (Blueprint $table) {
            $table->id();
            $table->string('lokasi');
            $table->string('alamat');
            $table->decimal('lat_pen', 10, 8)->nullable();
            $table->decimal('long_pen', 11, 8)->nullable();
            $table->integer('radius')->default(0);
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
        Schema::dropIfExists('master_outlet');
    }
}
