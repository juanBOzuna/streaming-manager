<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        date_default_timezone_set('America/Bogota');
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string("email");
            $table->string("key_pass");
            $table->boolean('is_renewed')->default(0);
            $table->boolean('is_sold_ordinary')->default(0);
            $table->boolean('is_sold_extraordinary')->default(0);
            $table->boolean('is_active')->default(0);
            $table->boolean('is_expired')->default(0);
            $table->timestamp('date_renewed')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('times_renewed')->default(0);
            $table->integer('screens_sold')->default(0)->nullable();
            $table->unsignedBigInteger("type_account_id");
            $table->timestamps();
        });
    }

    /**0
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
