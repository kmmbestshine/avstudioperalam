<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerInvoiceNosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ser_invoice_nos', function (Blueprint $table) {
            $table->string('service_id');
            $table->string('customer_id');
            $table->string('invoice_no');
            $table->string('company_id');
            $table->double('subtotal');
            $table->double('gst_total')->nullable();
            $table->double('grand_total')->nullable();
            $table->double('discount_amt')->nullable();
            $table->double('paid_amt');
            $table->double('due_amt');
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
        Schema::dropIfExists('ser_invoice_nos');
    }
}
