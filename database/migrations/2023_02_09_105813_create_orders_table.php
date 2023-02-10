<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('apt_suite_number')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->bigInteger('room_id')->nullable();
            $table->bigInteger('bathroom_id')->nullable();
            $table->bigInteger('discount_id')->nullable();
            $table->date('date')->nullable();
            $table->bigInteger('time_slot_id')->nullable();
            $table->bigInteger('clean_type_id')->nullable();
            $table->string('contact_with_covid_person')->nullable();
            $table->string('inform_maid')->nullable();
            $table->string('total_bill')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
