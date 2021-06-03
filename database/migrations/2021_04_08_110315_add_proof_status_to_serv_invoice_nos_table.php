<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProofStatusToServInvoiceNosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('serv_invoice_nos', function (Blueprint $table) {
            $table->boolean('proof_status')->after('inv_dt')->default(0);
           $table->string('proof_dt')->after('proof_status')->nullable();
           $table->string('deliverd_dt')->after('proof_dt')->nullable();
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
