<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReservationInitialMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employeeid');
			$table->unsignedBigInteger('customerid');
			$table->unsignedBigInteger('roomid');
			$table->unsignedBigInteger('bedid')->nullable();
			$table->date('bookingstart');
			$table->date('bookingend');
			$table->tinyInteger('status')->default(1)->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
		
		Schema::create('customer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname');
			$table->string('lastname');
			$table->date('birthdate');
			$table->string('sex');
			$table->string('idtype');
			$table->string('idnumber');
			$table->string('phone1');
			$table->string('phone2')->nullable();
			$table->string('email');
			$table->string('address1');
			$table->string('address2')->nullable();
			$table->string('city');
			$table->string('country');
			$table->string('nationality');
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
