<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_id');
            $table->string('name');
            $table->string('bankname');
            $table->string('account_no',100)->unique();
            $table->integer('deposit_amt');
            $table->integer('available_amt')->nullable();
            $table->string('branchname');
            $table->string('ifsc')->nullable();
            $table->string('date');
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('deposits');
    }
}
