<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateExistingResourcesApprovalStatusSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Update all existing resources to have approved status
        // This ensures existing resources remain visible on your site
        DB::table('resources')
            ->whereNull('approval_status')
            ->update([
                'approval_status' => 'approved',
                'approved_at' => Carbon::now(),
                'submitted_at' => Carbon::now()
            ]);

        $this->command->info('All existing resources have been set to approved status.');
    }
}