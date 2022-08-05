<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRevendedorIdToScreensAndOthers extends Migration
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
                $table->unsignedBigInteger('revendedor_id')->nullable();
                $table->boolean('is_sold_revendedor')->default(0);
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
                $table->dropColumn('revendedor_id');
                $table->dropColumn('is_sold_revendedor');
            });
        }
    }
}
