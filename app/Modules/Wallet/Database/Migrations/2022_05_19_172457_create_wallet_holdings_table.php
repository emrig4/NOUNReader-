<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletHoldingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('credit_wallet_holdings', function (Blueprint $table) {
            $table->bigIncrements('id');

            // $table->string('status')->default('pending'); // refunded, pending, completed$table->string('reference');

            $table->enum('status', array('completed', 'pending', 'cancelled'))->default('pending');

            $table->string('reference')->nullable()->index();

            $table->foreignId('credit_wallet_id');
            $table->foreignId('user_id');

            $table->decimal('ranc', 20, 2);

            $table->string('description')->nullable()->default('Withdrawal');
            
            $table->timestamps();

            $table->foreign('credit_wallet_id')->references('id')->on('credit_wallets');
            $table->foreign('reference')->references('reference')->on('credit_wallet_transactions')->onDelete(null);
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
        Schema::dropIfExists('credit_wallet_holdings');
    }
}
