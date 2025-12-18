<?php

namespace App\Console\Commands;

use App\Models\LoginAttempt;
use Illuminate\Console\Command;

class CleanupLoginAttempts extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'security:cleanup-login-attempts {--days=7 : Number of days to keep login attempts}';

    /**
     * The console command description.
     */
    protected $description = 'Clean up old login attempts from the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        
        $deleted = LoginAttempt::clearOldAttempts($days);
        
        $this->info("Cleaned up {$deleted} login attempts older than {$days} days.");
        
        return Command::SUCCESS;
    }
}
