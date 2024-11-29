<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogs extends Command
{
    protected $signature = 'logs:clear';

    protected $description = 'Clear Laravel log files';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Clear the log files
        $logFile = storage_path('logs/laravel.log');
        if (File::exists($logFile)) {
            File::delete($logFile);
            $this->info('Log file cleared successfully!');
        } else {
            $this->info('Log file does not exist!');
        }
    }
}
