<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class CleanupUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:cleanup-unverified {days=2 : Delete users not verified within X days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unverified users older than specified days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->argument('days', 2);
        $threshold = Carbon::now()->subDays($days);

        $this->info("Looking for unverified users older than {$days} days...");
        $this->info("Threshold date: {$threshold}");

        // Build query for unverified users
        $query = User::where('status', 'pending')
            ->where('created_at', '<', $threshold);

        // Also include users without status field (older accounts)
        if (!Schema::hasColumn('users', 'status')) {
            $query = User::whereNull('email_verified_at')
                ->where('created_at', '<', $threshold);
        }

        $users = $query->get();
        $count = $users->count();

        if ($count > 0) {
            $this->info("Found {$count} unverified users to delete.");
            
            $bar = $this->output->createProgressBar($count);
            $bar->start();

            foreach ($users as $user) {
                $this->info("Deleting user ID {$user->id}: {$user->email}");
                
                // Log before deletion
                \Log::info("Auto-cleanup: Deleting unverified user", [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'status' => $user->status ?? 'N/A',
                    'email_verified_at' => $user->email_verified_at
                ]);
                
                $user->delete();
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info("✅ Successfully deleted {$count} unverified users.");
        } else {
            $this->info("No unverified users found to delete.");
        }

        return Command::SUCCESS;
    }
}
