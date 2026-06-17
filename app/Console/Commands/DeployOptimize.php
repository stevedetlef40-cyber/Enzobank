<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DeployOptimize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run EnzoBank professional post-deployment performance optimization.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🚀 Starting EnzoBank Post-Deployment Optimization Protocol...');

        // 1. Clear All Caches
        $this->warn('--- Clearing existing caches...');
        Artisan::call('optimize:clear');
        $this->info('✓ Caches cleared successfully.');

        // 2. Cache Configuration
        $this->warn('--- Caching application configuration...');
        Artisan::call('config:cache');
        $this->info('✓ Configuration cached.');

        // 3. Cache Routes
        $this->warn('--- Caching application routes...');
        try {
            Artisan::call('route:cache');
            $this->info('✓ Routes cached.');
        } catch (\Exception $e) {
            $this->error('⚠️ Failed to cache routes. Ensure all routes use controller classes (not closures).');
        }

        // 4. Cache Views
        $this->warn('--- Caching application views...');
        Artisan::call('view:cache');
        $this->info('✓ Views cached.');

        // 5. Generate Storage Symlink
        $this->warn('--- Verifying storage symlink...');
        Artisan::call('storage:link');
        $this->info('✓ Storage symlink verified.');

        $this->info('✅ EnzoBank Optimization Complete! Your site is now running at peak performance.');

        return 0;
    }
}
