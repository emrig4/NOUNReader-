<?php

namespace App\Console\Commands;

use App\Services\VerificationService;
use Illuminate\Console\Command;

class CleanupExpiredVerificationCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verification:cleanup-expired {--dry-run : Show what would be cleaned up without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired verification code records';

    protected $verificationService;

    /**
     * Create a new command instance.
     */
    public function __construct(VerificationService $verificationService)
    {
        parent::__construct();
        $this->verificationService = $verificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of expired verification codes...');

        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No records will actually be deleted');
        }

        $expiredCount = $this->verificationService->cleanupExpiredRecords();

        if ($isDryRun) {
            $this->info("Found {$expiredCount} expired verification records that would be deleted.");
        } else {
            $this->info("Cleaned up {$expiredCount} expired verification records.");
        }

        // Also clear rate limiting entries
        $this->clearRateLimitEntries();

        $this->info('Cleanup completed successfully.');

        return Command::SUCCESS;
    }

    /**
     * Clear rate limiting entries for verification
     */
    private function clearRateLimitEntries()
    {
        try {
            // Get all rate limiting keys related to verification
            $pattern = 'verification-*';
            
            // Since RateLimiter doesn't have a direct method to clear by pattern,
            // we'll use the Redis facade directly if using Redis cache
            if (config('cache.default') === 'redis') {
                $redis = app('redis')->connection();
                $keys = $redis->keys($pattern);
                
                if (!empty($keys)) {
                    $deletedCount = $redis->del($keys);
                    $this->info("Cleared {$deletedCount} rate limiting entries.");
                }
            } else {
                $this->info('Rate limit cleanup skipped - Redis not configured as cache driver.');
            }
        } catch (\Exception $e) {
            $this->error('Failed to clear rate limiting entries: ' . $e->getMessage());
        }
    }
}