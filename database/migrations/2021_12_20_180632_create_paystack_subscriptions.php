<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaystackSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * Add columns to user table
         */
        
        Schema::table('users', function ($table) {
            // $table->string('paystack_id')->nullable();
            // $table->string('paystack_code')->nullable();
            // $table->string('card_brand')->nullable();
            // $table->string('card_last_four', 4)->nullable();

            // // added by air
            // $table->string('paystack_authorization')->nullable();
            // $table->string('paystack_email_token')->nullable();
            // $table->timestamp('trial_ends_at')->nullable();
        });



        /*
         * Create subscriptions table
         */


        Schema::create('paystack_subscriptions', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('name');
            $table->string('subscription_id')->nullable()->unique();
            $table->string('subscription_code')->nullable();

           
            $table->integer('quantity')->nullable();
            $table->integer('payments_count')->nullable();
            $table->string('email_token')->nullable();

            $table->string('paystack_id')->nullable();
            $table->string('paystack_status');
            $table->mediumText('paystack_plan')->nullable();
            $table->mediumText('authorization')->nullable();
            $table->mediumText('most_recent_invoice')->nullable();
            $table->mediumText('meta')->nullable();
            $table->mediumText('customer')->nullable();

            $table->timestamp('ends_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('next_payment_date')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'paystack_status']);

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists(config('paystacksubscription.user_table', 'users'));
        Schema::dropIfExists(config('paystacksubscription.subscription_table', 'paystack_subscriptions'));
    }
}
