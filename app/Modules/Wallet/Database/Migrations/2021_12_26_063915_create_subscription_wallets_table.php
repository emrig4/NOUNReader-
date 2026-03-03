<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_wallets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference')->unique()->index();
            $table->foreignId('user_id');
            $table->timestamp('expiring')->nullable();
            $table->decimal('ranc', 20, 2)->default(0.00);
            $table->boolean('active')->default(1);
            $table->nullableTimestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_wallets');
    }
}
