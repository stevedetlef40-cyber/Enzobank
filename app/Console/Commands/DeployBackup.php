<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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

    /**
     * Get the active database connection config.
     */
    protected function getDbConfig(): array
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        if (! $config) {
            $this->error("Database connection '{$connection}' not found in config.");
            exit(1);
        }

        return $config;
    }

    protected function backup()
    {
        $this->info('Starting Database Backup for Deployment...');

        $config = $this->getDbConfig();
        $driver = $config['driver'] ?? 'mysql';

        $filename = 'backup_'.date('Y-m-d_H-i-s').'.sql';
        $path = storage_path('app/backups/'.$filename);

        if (! File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'), 0755, true);
        }

        if ($driver === 'pgsql') {
            $cmd = sprintf(
                'PGPASSWORD=%s pg_dump --host=%s --port=%s --username=%s --dbname=%s --no-password > %s',
                escapeshellarg($config['password'] ?? ''),
                escapeshellarg($config['host'] ?? '127.0.0.1'),
                escapeshellarg($config['port'] ?? '5432'),
                escapeshellarg($config['username'] ?? ''),
                escapeshellarg($config['database'] ?? ''),
                escapeshellarg($path)
            );
        } else {
            $cmd = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                escapeshellarg($config['username'] ?? ''),
                escapeshellarg($config['password'] ?? ''),
                escapeshellarg($config['host'] ?? '127.0.0.1'),
                escapeshellarg($config['database'] ?? ''),
                escapeshellarg($path)
            );
        }

        exec($cmd, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error('Backup failed using '.($driver === 'pgsql' ? 'pg_dump' : 'mysqldump').'. Ensure it is installed and in your PATH.');

            return 1;
        }

        $this->info('Backup successfully created at: '.$path);

        return 0;
    }

    protected function rollback()
    {
        $this->info('Starting Database Rollback...');

        $backups = File::files(storage_path('app/backups'));
        if (empty($backups)) {
            $this->error('No backups found in storage/app/backups.');

            return 1;
        }

        // Sort by modification time to get the latest
        usort($backups, function ($a, $b) {
            return $b->getMTime() - $a->getMTime();
        });

        $latestBackup = $backups[0];
        $this->info('Rolling back to: '.$latestBackup->getFilename());

        $config = $this->getDbConfig();
        $driver = $config['driver'] ?? 'mysql';

        if ($driver === 'pgsql') {
            $cmd = sprintf(
                'PGPASSWORD=%s psql --host=%s --port=%s --username=%s --dbname=%s --no-password < %s',
                escapeshellarg($config['password'] ?? ''),
                escapeshellarg($config['host'] ?? '127.0.0.1'),
                escapeshellarg($config['port'] ?? '5432'),
                escapeshellarg($config['username'] ?? ''),
                escapeshellarg($config['database'] ?? ''),
                escapeshellarg($latestBackup->getPathname())
            );
        } else {
            $cmd = sprintf(
                'mysql --user=%s --password=%s --host=%s %s < %s',
                escapeshellarg($config['username'] ?? ''),
                escapeshellarg($config['password'] ?? ''),
                escapeshellarg($config['host'] ?? '127.0.0.1'),
                escapeshellarg($config['database'] ?? ''),
                escapeshellarg($latestBackup->getPathname())
            );
        }

        exec($cmd, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error('Rollback failed. Check database credentials and '.($driver === 'pgsql' ? 'psql' : 'mysql').' binary.');

            return 1;
        }

        $this->info('Rollback successful.');

        return 0;
    }
}
