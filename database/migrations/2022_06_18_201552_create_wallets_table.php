<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->unique();
            $table->float('current_balance')->default(0.00);
            $table->float('minimum_balance')->default(1000.00);
            $table->integer('interest_rate_in_percentage')->default(4);
            $table->integer('user_id');
            $table->string('unique_identifier')->unique();
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
        Schema::dropIfExists('wallets');
    }
};
