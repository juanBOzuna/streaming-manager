<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceFullToTypeAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('type_account')) {
            Schema::table('type_account', function (Blueprint $table) {
                $table->integer('price_full')->nullable();
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

        if (Schema::hasTable('type_account')) {
            Schema::table('type_account', function (Blueprint $table) {
                $table->dropColumn('price_full');
            });
        }
    }
}
