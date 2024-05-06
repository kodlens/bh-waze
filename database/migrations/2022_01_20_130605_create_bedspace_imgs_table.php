<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBedspaceImgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bedspace_imgs', function (Blueprint $table) {
            $table->id('bedspace_img_id');
            $table->unsignedBigInteger('bedspace_id');
            $table->foreign('bedspace_id')->references('bedspace_id')->on('bedspaces')->onDelete('cascade');
            $table->string('bedspace_img_path')->nullable();
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
        Schema::dropIfExists('bedspace_imgs');
    }
}
