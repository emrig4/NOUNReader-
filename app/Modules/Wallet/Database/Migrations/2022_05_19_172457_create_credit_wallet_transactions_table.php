<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_wallet_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('credit_wallet_id');
            $table->string('reference')->unique()->index();
            $table->text('remark')->nullable();
            $table->decimal('amount', 20, 2)->default(0.00);
            $table->string('currency')->default('NGN');

            $table->enum('type', array('earning', 'withdrawal', 'credit'))->nullable();
            // $table->string('type')->nullable(); // 'earning', 'withdrawal'
            
            // $table->string('status')->nullable(); // processed, pending, rejected
            $table->enum('status', array('processed', 'pending', 'rejected', 'cancelled'))->nullable();
            
            $table->decimal('ranc', 20, 2)->default(0.00);
            $table->boolean('active')->default(1);
            $table->nullableTimestamps();
            
            $table->foreign('credit_wallet_id')->references('id')->on('credit_wallets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_wallet_transactions');
    }
}
