<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentOrderDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('order_details')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->unsignedBigInteger('parent_order_detail')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        if (Schema::hasTable('order_details')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->dropColumn('parent_order_detail');
            });
        }
    }
}
