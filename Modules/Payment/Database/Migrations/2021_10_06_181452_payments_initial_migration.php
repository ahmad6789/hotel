<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PaymentsInitialMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
			$table->string('context');
			$table->unsignedBigInteger('contextid');
			$table->unsignedBigInteger('payeeid');
			$table->unsignedBigInteger('receivedby');
            $table->timestamps();
            $table->softDeletes();
        });
		
		Schema::create('payment_line', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('paymentid');
			$table->string('description');
			$table->unsignedInteger('cost');
			$table->unsignedInteger('quantity');
			$table->foreign('paymentid')->references('id')->on('payment');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
