<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 100)->nullable();
            $table->string('slug')->nullable();
            $table->string('name');
            $table->text('permissions')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'active', 'archive'])->default("active");
            $table->softDeletes();
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
        Schema::dropIfExists('groups');
    }
}
