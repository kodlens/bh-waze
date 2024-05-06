<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id('payment_detail_id');
            $table->unsignedBigInteger('boarder_id');
            $table->foreign('boarder_id')->references('boarder_id')->on('boarders');
            $table->double('payment_to_pay')->default(0);
            $table->double('payment')->default(0);
            $table->double('balance')->default(0);
            $table->date('date_pay')->nullable();
            $table->string('payment_status', 50)->nullable();
            $table->string('receipt_img')->nullable();
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
        Schema::dropIfExists('payment_details');
    }
}
