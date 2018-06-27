<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->boolean('email_verified')->default(false);
            $table->bigInteger('phone')->nullable();
            $table->integer('phone_country_code')->nullable();
            $table->boolean('phone_verified')->default(false);
            $table->string('password');
            $table->boolean('set_password_now')->default(false);
            $table->string('picture')->default('admin/images/user.png');
            $table->date('dob')->nullable();
            $table->enum('gender', ["male", "female", "others"])->default("male");
            $table->boolean('tnc_accepted')->default(false);
            $table->timestamp('last_login')->useCurrent();
            $table->unsignedInteger('entity_id');
            $table->rememberToken();
            $table->jsonb('gplus_data')->nullable();
            $table->text('api_access_token')->nullable();
            $table->text('api_refresh_token')->nullable();
            $table->timestamps();

            $table->foreign('entity_id')
                  ->references('id')->on('entities')
                  ->onDelete('cascade');

            $table->unique(['email', 'entity_id']);
            $table->unique(['phone', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
