<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('recipient_id')->nullable();
            $table->string('phone')->nullable();
            $table->enum('status', ['start','tshirt_buy','tshirt_size', 'phone_given', 'done'])->default("start")->nullable();
            $table->unsignedBigInteger('apparel_id')->nullable();
            $table->foreign('apparel_id')->references('id')->on('apparel')->nullable();
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
        Schema::dropIfExists('order');
    }
}
