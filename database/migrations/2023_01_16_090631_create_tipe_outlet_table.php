<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipeOutletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipe_outlet', function (Blueprint $table) {
            $table->id();
            $table->string('tipe');
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
        Schema::dropIfExists('tipe_outlet');
    }
}
