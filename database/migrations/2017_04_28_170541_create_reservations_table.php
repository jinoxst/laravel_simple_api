<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
        /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reservation_code')->unique();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('book_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('bookusers')->onUpdate('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reservations');
    }
}
