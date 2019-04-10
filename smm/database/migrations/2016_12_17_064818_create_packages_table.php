<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->decimal('price_per_item', 11, 5);
            $table->unsignedInteger('minimum_quantity');
            $table->unsignedInteger('maximum_quantity');
            $table->text('description');
            $table->enum('status', ['ACTIVE', 'INACTIVE']);
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('preferred_api_id')->nullable();
            $table->boolean('custom_comments')->default(0);
            $table->timestamps();

            $table->foreign('service_id')
                ->references('id')->on('services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
}
