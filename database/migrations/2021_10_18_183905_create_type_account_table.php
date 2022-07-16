<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_account', function (Blueprint $table) {
            $table->id('id');
            $table->string("name");
            $table->integer("total_screens");
            $table->integer("available_screens");
            $table->integer("extraordinary_available_screens");
            $table->unsignedBigInteger('price_day');
            $table->string("picture");
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
        Schema::dropIfExists('type_account');
    }
}
