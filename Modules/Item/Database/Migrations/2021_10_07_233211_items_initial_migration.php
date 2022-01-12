<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ItemsInitialMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description');
             $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('room_items', function (Blueprint $table) {
             $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('roomid');
            $table->unsignedBigInteger('itemid');
            $table->timestamps();
            $table->softDeletes();
            $table->primary(['roomid', 'itemid']);
            $table->foreign('itemid')->references('id')->on('items');
            $table->foreign('roomid')->references('id')->on('rooms');


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
