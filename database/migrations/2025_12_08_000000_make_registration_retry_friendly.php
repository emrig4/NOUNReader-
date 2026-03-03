<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add status column safely
        if (!Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('status', ['pending', 'active'])
                      ->default('pending')
                      ->after('has_set_permanent_password')
                      ->index('users_status_index');
            });
        }

        // Add verification code columns if they don't exist
        if (!Schema::hasColumn('users', 'verification_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('verification_code', 6)->nullable()->after('status');
                $table->timestamp('verification_code_expires_at')->nullable()->after('verification_code');
            });
        }

        // Try to remove email unique constraint with multiple fallback attempts
        $constraintNames = ['users_email_unique', 'email', 'users_email_index', 'users_email_unique_index'];
        
        foreach ($constraintNames as $constraint) {
            try {
                // First try to drop as a unique constraint
                DB::statement("ALTER TABLE users DROP INDEX IF EXISTS {$constraint}");
                break;
            } catch (\Exception $e) {
                continue;
            }
        }

        // Create conditional unique index for active users only
        try {
            DB::statement("
                CREATE UNIQUE INDEX unique_active_user_email 
                ON users (email) 
                WHERE status = 'active'
            ");
        } catch (\Exception $e) {
            // Index might already exist, that's fine
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the conditional unique index
        try {
            DB::statement("DROP INDEX IF EXISTS unique_active_user_email ON users");
        } catch (\Exception $e) {
            // Index might not exist, that's fine
        }

        // Remove status column
        if (Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_status_index');
                $table->dropColumn('status');
                $table->dropColumn('verification_code');
                $table->dropColumn('verification_code_expires_at');
            });
        }

        // Recreate basic email unique constraint
        try {
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_email_unique UNIQUE (email)");
        } catch (\Exception $e) {
            // Might fail if column doesn't exist or constraint already exists
        }
    }
};