<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add verification code expiration timestamp
            $table->timestamp('verification_code_expires_at')->nullable()->after('has_set_permanent_password');
            
            // Add verification attempts counter
            $table->integer('verification_code_attempts')->default(0)->after('verification_code_expires_at');
            
            // Add indexes for better performance
            $table->index(['email', 'has_set_permanent_password']);
            $table->index('verification_code_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email', 'has_set_permanent_password']);
            $table->dropIndex(['verification_code_expires_at']);
            $table->dropColumn(['verification_code_expires_at', 'verification_code_attempts']);
        });
    }
};