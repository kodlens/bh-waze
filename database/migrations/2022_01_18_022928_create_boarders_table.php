<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()
    {
        Schema::create('boarders', function (Blueprint $table) {
            $table->id('boarder_id');
            $table->string('qr_ref', 100)->unique();
            $table->unsignedBigInteger('boarder_user_id');
            $table->foreign('boarder_user_id')->references('user_id')->on('users');
            $table->unsignedBigInteger('bedspace_id');
            $table->unsignedBigInteger('book_bedspace_id');
            $table->foreign('book_bedspace_id')->references('book_bedspace_id')->on('book_bedspaces');
            $table->foreign('bedspace_id')->references('bedspace_id')->on('bedspaces');
            $table->date('date_acceptance');
            $table->double('rental_price');
            $table->tinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('boarders');
    }
}
