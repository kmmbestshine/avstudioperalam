<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_bills', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_id');
            $table->integer('customer_id');
            $table->integer('company_id');
            $table->integer('invoice_no');
            $table->integer('quantity');
            $table->double('price');
            $table->double('amount');
            $table->double('particulars');
            $table->double('delivery_date')->nullable();
            $table->string('units');
            $table->date('bill_date');
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
        Schema::dropIfExists('service_bills');
    }
}
