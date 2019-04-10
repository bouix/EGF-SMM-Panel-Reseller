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
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name');
            $table->string('email',191)->unique();
            $table->decimal('funds',11,2)->default(0);
            $table->string('password');
            $table->enum('status',[
                'ACTIVE',
                'DEACTIVATED',
                'DELETED',
            ])->default('ACTIVE');
            $table->enum('role',['ADMIN','USER'])->default('USER');
            $table->string('api_token',191)->unique()->nullable();
            $table->string('enabled_payment_methods')->nullable();
            $table->string('skype_id')->nullable();
            $table->string('timezone')->default('America/Chicago');
            $table->timestamp('last_login')->nullable();
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
