<?php

namespace App\Console\Commands;

use App\Models\CompanyNotification;
use Illuminate\Console\Command;

class CleanupOldCompanyNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-old-company-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup old company notifications that have been read for more than 7 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = CompanyNotification::where('created_at', '<', now()->subDays(7))
            ->where('is_read', true)
            ->delete();

        $this->info("Deleted {$count} old company notifications.");

        return Command::SUCCESS;
    }
}
