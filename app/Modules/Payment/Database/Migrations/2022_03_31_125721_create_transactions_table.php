<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('amount')->nullable(); // amount paid
            $table->string('currency')->nullable(); // amount paid
            $table->string('user_id')->nullable(); 
            $table->string('status')->nullable()->comment('success, failed, refunded, declined');
            $table->string('paid_at')->nullable()->comment('success, failed, refunded, declined');
            $table->string('reference')->unique(); // from aggregator
            $table->string('payment_channel')->nullable(); // online, offline etc
            $table->string('txntype')->nullable(); // subscription, downloads etc
            $table->string('payment_gateway')->nullable(); // rave, paystack, paypal etc
            $table->text('transaction_payload')->nullable();
            $table->text('transaction_meta')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('transactions');
    }
}
