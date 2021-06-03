<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_transfer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_id');
            $table->string('commision');
            $table->string('accountno');
            $table->string('branchname')->nullable();
            $table->integer('amount');
            $table->string('name')->nullable();
            $table->string('ifsccode')->nullable();
            $table->string('bankname')->nullable();
            $table->string('mobile')->nullable();
            $table->string('from_account_id');
            $table->string('date');
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
        Schema::dropIfExists('fund_transfers');
    }
}
