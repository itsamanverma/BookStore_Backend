<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->length(50)->unique();
            $table->binary('image')->nullable();
            $table->integer('price')->unsigned();
            $table->boolean('isAvailabil')->defualt('true');
            $table->text('Title');
            $table->integer('noOfBooks')->length(2)->unsigned();
            $table->integer('Ratings')->length(2)->unsigned();
            $table->integer('Reviews')->unsigned();
            $table->string('author_name');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('books');
    }
}
