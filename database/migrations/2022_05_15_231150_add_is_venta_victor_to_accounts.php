<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addIsVentaVictorToAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('accounts')) {
            Schema::table('accounts', function (Blueprint $table) {
                $table->boolean('is_venta_victor')->default(0);
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

        if (Schema::hasTable('accounts')) {
            Schema::table('accounts', function (Blueprint $table) {
                $table->dropColumn('is_venta_victor');
            });
        }
    }
}
