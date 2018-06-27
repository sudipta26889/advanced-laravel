<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol');
            $table->string('code');
            $table->string('name');
            $table->unsignedInteger('entity_id');
            $table->timestamps();

            $table->foreign('entity_id')
                  ->references('id')->on('entities')
                  ->onDelete('cascade');

            $table->unique(['code', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
}
