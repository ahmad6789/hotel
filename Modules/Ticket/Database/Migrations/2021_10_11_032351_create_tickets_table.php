<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('roomid')->unsigned()->nullable()->default(12);
            $table->tinyInteger('status')->default(1)->unsigned();
            $table->unsignedBigInteger('assignedto');
            $table->string('type');
            $table->tinyInteger('priority')->default(5)->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('ticket_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ticketid');
            $table->text('descriptions');
             $table->timestamps();
            $table->softDeletes();
            $table->foreign('ticketid')->references('id')->on('tickets');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
