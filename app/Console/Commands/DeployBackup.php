<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DeployBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:backup {--rollback : Whether to perform a rollback instead of a backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform a database backup before migration or rollback after failure.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('rollback')) {
            return $this->rollback();
        }

        return $this->backup();
    }

    protected function backup()
    {
        $this->info("Starting Database Backup for Deployment...");
        
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host     = config('database.connections.mysql.host');
        
        $filename = "backup_" . date('Y-m-d_H-i-s') . ".sql";
        $path = storage_path('app/backups/' . $filename);
        
        if (!File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'), 0755, true);
        }

        // Using mysqldump if available, otherwise fallback to Laravel's DB export logic
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($database),
            escapeshellarg($path)
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error("Backup failed using mysqldump. Ensure it is installed and in your PATH.");
            return 1;
        }

        $this->info("Backup successfully created at: " . $path);
        return 0;
    }

    protected function rollback()
    {
        $this->info("Starting Database Rollback...");
        
        $backups = File::files(storage_path('app/backups'));
        if (empty($backups)) {
            $this->error("No backups found in storage/app/backups.");
            return 1;
        }

        // Sort by modification time to get the latest
        usort($backups, function($a, $b) {
            return $b->getMTime() - $a->getMTime();
        });

        $latestBackup = $backups[0];
        $this->info("Rolling back to: " . $latestBackup->getFilename());

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host     = config('database.connections.mysql.host');

        $command = sprintf(
            'mysql --user=%s --password=%s --host=%s %s < %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($database),
            escapeshellarg($latestBackup->getPathname())
        );

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error("Rollback failed. Check database credentials and mysql binary.");
            return 1;
        }

        $this->info("Rollback successful.");
        return 0;
    }
}
