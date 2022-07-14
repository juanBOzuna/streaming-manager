<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLinkBotToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('cms_users')) {
            Schema::table('cms_users', function (Blueprint $table) {
                $table->string('link_bot')->nullable();
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

        if (Schema::hasTable('cms_users')) {
            Schema::table('cms_users', function (Blueprint $table) {
                $table->dropColumn('link_bot');
            });
        }
    }
}
