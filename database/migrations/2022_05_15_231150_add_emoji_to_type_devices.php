<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addEmojiToTypeDevices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('type_devices')) {
            Schema::table('type_devices', function (Blueprint $table) {
                $table->string('emoji')->nullable();
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

        if (Schema::hasTable('type_devices')) {
            Schema::table('type_devices', function (Blueprint $table) {
                $table->dropColumn('emoji');
            });
        }
    }
}
