<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('wilaya_id');
            $table->string('name');
            $table->string('display_name');
            $table->double('longitude');
            $table->double('latitude');
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('wilaya_id')->references('id')->on('wilayas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('districts');
    }
};
