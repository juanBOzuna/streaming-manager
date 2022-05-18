<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orders_id');
            $table->unsignedBigInteger('type_account_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('number_screens')->nullable();
            $table->unsignedInteger('screen_id');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('membership_days');
            $table->unsignedInteger('price_of_membership_days');
            $table->timestamp('finish_date');
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
        Schema::dropIfExists('order_details');
    }
}
