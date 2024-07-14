<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
  public function up()
  {
    Schema::create('settings', function (Blueprint $table) {
      $table->id();
      $table->double('price_max');
      $table->unsignedBigInteger('bank_account_bankily');
      $table->unsignedBigInteger('bank_account_sedad');
      $table->unsignedBigInteger('bank_account_bimbank');
      $table->unsignedBigInteger('bank_account_masrfy');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down()
  {
    Schema::dropIfExists('settings');
  }
}
