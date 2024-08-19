<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class CopyDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a copy of the SQLite database';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $sourcePath = config('database.connections.sqlite.database');
        $filename = basename($sourcePath);

        if (! file_exists($sourcePath)) {
            $this->error($sourcePath . ' does not exist.');
            return;
        }

        $targetPath = Storage::disk('local')->path($filename);

        if (copy($sourcePath, $targetPath)) {
            $this->line('Copied to ' . $targetPath . '.');
        } else {
            $this->error('Failed to copy ' . $sourcePath . ' to ' . $targetPath);
        }
    }
}
