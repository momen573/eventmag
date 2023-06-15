<?php

use App\Models\Artist;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('artists', function (Blueprint $table) {
      $table->id();
      $table->string('full_name');
      $table->json('image')->nullable();
      $table->enum('status', Artist::$statuses);
      $table->text('description')->nullable();
      $table->unsignedBigInteger('language_id');
      $table->timestamps();

      $table->foreign('language_id')
        ->references('id')
        ->on('languages')
        ->onUpdate('CASCADE')
        ->onDelete('CASCADE');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('artists');
  }
};
