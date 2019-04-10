<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->enum('source', ['WEB', 'API'])->default('WEB');
            $table->enum('status', [
                'COMPLETED',
                'PROCESSING',
                'INPROGRESS',
                'PENDING',
                'PARTIAL',
                'CANCELLED',
                'REFUNDED'
            ])->default('PENDING');
            $table->decimal('price', 11, 2);
            $table->string('link', 300);
            $table->string('start_counter')->nullable();
            $table->string('remains')->nullable();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('package_id');
            $table->unsignedInteger('api_id')->nullable();
            $table->string('api_order_id')->nullable();
            $table->text('custom_comments')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->foreign('package_id')
                ->references('id')->on('packages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
