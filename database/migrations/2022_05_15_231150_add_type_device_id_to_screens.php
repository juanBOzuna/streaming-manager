<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addTypeDeviceIdToScreens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('screens')) {
            Schema::table('screens', function (Blueprint $table) {
                $table->unsignedBigInteger('type_device_id')->nullable();
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

        if (Schema::hasTable('screens')) {
            Schema::table('screens', function (Blueprint $table) {
                $table->dropColumn('type_device_id');
            });
        }
    }
}
