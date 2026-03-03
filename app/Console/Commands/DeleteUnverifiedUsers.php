<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-unverified {--hours=48 : Number of hours to wait before deleting unverified users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unverified users after a specified period (default: 48 hours)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hours = $this->option('hours');
        
        $this->info("Searching for unverified users older than {$hours} hours...");
        
        $cutoffDate = now()->subHours($hours);
        
        // Find unverified users (status = 'pending') created before the cutoff
        $unverifiedUsers = User::where('status', 'pending')
            ->where('created_at', '<', $cutoffDate)
            ->get();
        
        $count = $unverifiedUsers->count();
        
        if ($count === 0) {
            $this->info('No unverified users found to delete.');
            return 0;
        }
        
        $this->info("Found {$count} unverified user(s) to delete.");
        
        // Display user details
        $this->table(
            ['ID', 'Email', 'Name', 'Created At', 'Hours Ago'],
            $unverifiedUsers->map(function ($user) {
                return [
                    $user->id,
                    $user->email,
                    $user->name,
                    $user->created_at->format('Y-m-d H:i:s'),
                    now()->diffInHours($user->created_at) . ' hours'
                ];
            })->toArray()
        );
        
        // Confirm deletion if running interactively
        if (!$this->output->isQuiet()) {
            $confirm = $this->confirm("Are you sure you want to delete these {$count} unverified user(s)?");
            
            if (!$confirm) {
                $this->info('Deletion cancelled.');
                return 0;
            }
        }
        
        // Delete each user with their related data
        $deletedCount = 0;
        $errorCount = 0;
        
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        
        foreach ($unverifiedUsers as $user) {
            try {
                DB::beginTransaction();
                
                // Log before deletion
                Log::info('Deleting unverified user', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'created_at' => $user->created_at,
                    'hours_since_registration' => now()->diffInHours($user->created_at)
                ]);
                
                // Delete related data first (accounts, wallets, etc.)
                // Account follows
                DB::table('account_follows')
                    ->where('follower_id', $user->id)
                    ->orWhere('followee_id', $user->id)
                    ->delete();
                
                // Account favorites
                DB::table('account_favorites')
                    ->where('user_id', $user->id)
                    ->delete();
                
                // Account settings
                DB::table('account_settings')
                    ->where('user_id', $user->id)
                    ->delete();
                
                // Account (main account table)
                DB::table('accounts')
                    ->where('user_id', $user->id)
                    ->delete();
                
                // Subscription wallets
                DB::table('subscription_wallets')
                    ->where('user_id', $user->id)
                    ->delete();
                
                // Credit wallets
                DB::table('credit_wallets')
                    ->where('user_id', $user->id)
                    ->delete();
                
                // Credit wallet transactions
                DB::table('credit_wallet_transactions')
                    ->where('user_id', $user->id)
                    ->delete();
                
                // Credit wallet holdings
                DB::table('credit_wallet_holdings')
                    ->where('user_id', $user->id)
                    ->delete();
                
                // Purchased resources
                DB::table('purchased_resources')
                    ->where('user_id', $user->id)
                    ->delete();
                
                // Resource reviews
                DB::table('resource_reviews')
                    ->where('user_id', $user->id)
                    ->delete();
                
                // Resource reports
                DB::table('resource_reports')
                    ->where('user_id', $user->id)
                    ->delete();
                
                // Plagiarism checks
                DB::table('plagiarism_checks')
                    ->where('user_id', $user->id)
                    ->delete();
                
                // AI detections
                DB::table('ai_detections')
                    ->where('user_id', $user->id)
                    ->delete();
                
                // Notifications
                DB::table('notifications')
                    ->where('notifiable_id', $user->id)
                    ->where('notifiable_type', 'App\Models\User')
                    ->delete();
                
                // Sessions
                DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->delete();
                
                // Personal access tokens
                DB::table('personal_access_tokens')
                    ->where('tokenable_id', $user->id)
                    ->where('tokenable_type', 'App\Models\User')
                    ->delete();
                
                // Finally, delete the user
                $user->delete();
                
                DB::commit();
                
                Log::info('Successfully deleted unverified user', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
                
                $deletedCount++;
                $bar->advance();
                
            } catch (\Exception $e) {
                DB::rollBack();
                
                Log::error('Failed to delete unverified user', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage()
                ]);
                
                $this->error("\nFailed to delete user {$user->email}: {$e->getMessage()}");
                $errorCount++;
            }
        }
        
        $bar->finish();
        $this->newLine();
        
        // Summary
        $this->info("Deletion complete:");
        $this->info("  - Successfully deleted: {$deletedCount}");
        $this->info("  - Failed to delete: {$errorCount}");
        
        Log::info('Unverified users cleanup completed', [
            'total_found' => $count,
            'successfully_deleted' => $deletedCount,
            'failed' => $errorCount,
            'hours_threshold' => $hours
        ]);
        
        return $errorCount > 0 ? 1 : 0;
    }
}