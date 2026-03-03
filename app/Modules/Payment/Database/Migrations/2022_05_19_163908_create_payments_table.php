<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('amount')->nullable(); // amount paid
            $table->string('currency')->nullable(); // amount paid
            $table->foreignId('user_id')->nullable(); 

            $table->string('status')->nullable()->comment('success, failed, refunded, declined');
            $table->string('paid_at')->nullable()->comment('success, failed, refunded, declined');
            $table->string('reference')->unique(); // from aggregator
            $table->string('channel')->nullable(); // online, offline etc
            $table->string('gateway')->nullable(); // rave, paystack, paypal etc
            $table->string('type')->nullable(); // subscription, resource_purchase etc
            $table->text('meta')->nullable();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('payments');
    }
}
