<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentModeToServInvoiceNosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('serv_invoice_nos', function (Blueprint $table) {
            $table->string('payment_mode')->after('company_id');
            $table->string('cheq_no')->after('payment_mode');
            $table->string('cheq_dt')->after('cheq_no');
            $table->string('bank_name')->after('cheq_dt');
            $table->string('transaction_no')->after('bank_name');
            $table->string('online_bank_name')->after('transaction_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('serv_invoice_nos', function (Blueprint $table) {
            //
        });
    }
}
