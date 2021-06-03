<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('service_id');
            $table->string('customer_id');
            $table->string('company_id');
            $table->string('invoice_no');
            $table->string('bill_id');
            $table->boolean('payment_status')->default(0);
            $table->integer('quantity');
            $table->double('price');
            $table->double('amount');
            $table->string('particulars');
            $table->date('delivery_date')->nullable();
            $table->string('units');
            $table->date('payment_date');
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
        Schema::dropIfExists('service_payment');
    }
}
