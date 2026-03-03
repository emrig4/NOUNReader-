<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // $table->string('name'); ;;
            $table->string('first_name');
            $table->string('last_name');
            $table->string('title')->nullable();
            $table->string('username')->unique()->index();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();



            $table->string('paystack_id')->nullable();
            $table->string('paystack_code')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four', 4)->nullable();

            // added by air
            $table->string('paystack_authorization')->nullable();
            $table->string('paystack_email_token')->nullable();
            $table->timestamp('trial_ends_at')->nullable();


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
        Schema::dropIfExists('users');
    }
}
