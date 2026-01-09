<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AccountLinkToken;

class CleanupExpiredAccountLinkTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-expired-account-link-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = AccountLinkToken::whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->delete();

        $this->info("Deleted {$count} expired account link tokens.");

        return Command::SUCCESS;
    }
}
