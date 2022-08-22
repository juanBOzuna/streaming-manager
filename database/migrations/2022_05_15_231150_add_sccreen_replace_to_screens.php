<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addSccreenReplaceToScreens extends Migration
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
                $table->boolean('is_screen_replace_notified')->default(0);
                $table->string('screen_replace')->nullable();
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
                $table->dropColumn('is_screen_replace_notified');
                $table->dropColumn('screen_replace');
            });
        }
    }
}
