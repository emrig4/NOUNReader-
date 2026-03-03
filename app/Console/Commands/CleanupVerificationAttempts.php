<?php

namespace App\Console\Commands;

use App\Services\VerificationService;
use Illuminate\Console\Command;

class CleanupVerificationAttempts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verification:cleanup {--force : Run without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired and failed verification attempts';

    protected $verificationService;

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
        if (!$this->option('force') && !$this->confirm('Do you wish to clean up expired verification attempts?')) {
            $this->info('Cleanup cancelled.');
            return Command::SUCCESS;
        }

        try {
            $this->verificationService->cleanupExpiredVerificationAttempts();
            
            $this->info('Successfully cleaned up expired verification attempts.');
            $this->line('You can run this command periodically (e.g., daily) to keep your database clean.');
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to clean up verification attempts: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}