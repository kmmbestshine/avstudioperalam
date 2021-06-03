<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('service_id');
            $table->string('customer_id');
            $table->string('company_id');
            $table->string('event_name');
            $table->string('images');
            $table->boolean('status')->default(0);
            $table->boolean('payment_status')->default(0);
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
        Schema::dropIfExists('customer_photos');
    }
}
