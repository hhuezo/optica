<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('users', 'users_');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('username');
            $table->string('user_name')->nullable();
            $table->decimal('collection_percentage', 4, 2)->nullable();
            $table->decimal('sales_percentage', 4, 2)->nullable();
            $table->string('email')->unique();
            $table->integer('statuses_id')->nullable();
            $table->integer('level')->nullable();
            $table->decimal('comision_percentage', 10, 3)->nullable();
            $table->integer('stores_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
