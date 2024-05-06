<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookBedspaces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_bedspaces', function (Blueprint $table) {
            $table->id('book_bedspace_id');
            $table->unsignedBigInteger('bedspace_id');
            $table->foreign('bedspace_id')->references('bedspace_id')->on('bedspaces');
            $table->unsignedBigInteger('book_user_id');
            $table->foreign('book_user_id')->references('user_id')->on('users');
            $table->date('book_date')->nullable();
            $table->date('occupy_date')->nullable();
            $table->double('rental_price')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->string('approval_status', 20)->default('PENDING');
            $table->string('proof_transaction')->nullable();
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
        Schema::dropIfExists('book_bedspaces');
    }
}
