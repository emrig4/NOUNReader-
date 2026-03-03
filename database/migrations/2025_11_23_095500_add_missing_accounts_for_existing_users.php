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
        // Find users without accounts and create accounts for them
        $usersWithoutAccounts = \App\Models\User::leftJoin('accounts', 'users.id', '=', 'accounts.user_id')
            ->whereNull('accounts.user_id')
            ->get(['users.id', 'users.email', 'users.first_name', 'users.last_name']);
            
        foreach($usersWithoutAccounts as $user) {
            try {
                $account = new \App\Modules\Account\Models\Account();
                $account->user_id = $user->id;
                $account->save();
                
                echo "Created Account for User: {$user->email}\n";
            } catch (\Exception $e) {
                echo "Error creating account for User: {$user->email} - {$e->getMessage()}\n";
            }
        }
        
        echo "Migration completed successfully!\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: Remove accounts that don't belong to existing users
        $orphanedAccounts = \App\Modules\Account\Models\Account::leftJoin('users', 'accounts.user_id', '=', 'users.id')
            ->whereNull('users.id')
            ->get();
            
        foreach($orphanedAccounts as $account) {
            $account->delete();
            echo "Removed orphaned Account ID: {$account->id}\n";
        }
    }
};