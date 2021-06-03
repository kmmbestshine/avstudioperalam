<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDelivedStatusToServInvoiceNosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('serv_invoice_nos', function (Blueprint $table) {
            $table->boolean('deliverd_status')->after('inv_dt')->default(0);
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
