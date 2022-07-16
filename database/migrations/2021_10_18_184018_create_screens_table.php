<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScreensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('screens', function (Blueprint $table) {
            $table->id('id');
            $table->string("account_id");
            $table->string("email");
            $table->string("client_id")->nullable();
            $table->string("name")->default("sin nombre");
            $table->integer("code_screen")->nullable();
            $table->integer('profile_number');
            $table->boolean("is_sold")->default(0);
            $table->timestamp("date_sold")->nullable();
            $table->timestamp("date_expired")->nullable();
            $table->integer("price_of_membership")->nullable();
            $table->string("device")->nullable();
            $table->string("ip")->nullable();
            $table->boolean("is_account_expired")->default(0);
            $table->unsignedBigInteger('type_account_id');

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
        Schema::dropIfExists('screens');
    }
}
