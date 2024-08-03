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
        Schema::create('purchase_items', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('purchase_id');
          $table->foreign('purchase_id')->references('id')->on('purchases');
          $table->unsignedBigInteger('product_id');
          $table->foreign('product_id')->references('id')->on('products');
          $table->string('name')->nullable()->default(null);
          $table->double('price')->default(0);
          $table->integer('quantity')->default(0);
          $table->double('amount')->default(0);
          $table->timestamps();
          $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_items');
    }
};
