<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingEmailVerificationFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('email_verified')->default(false)->after('email_verified_at');
            $table->string('email_verification_token')->nullable()->after('email_verified');
            $table->timestamp('last_login_at')->nullable()->after('updated_at');
            $table->string('login_token', 100)->nullable()->after('last_login_at');
            $table->timestamp('token_expires_at')->nullable()->after('login_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_verified', 'email_verification_token', 'last_login_at', 'login_token', 'token_expires_at']);
        });
    }
}