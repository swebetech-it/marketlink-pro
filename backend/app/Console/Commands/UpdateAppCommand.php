<?php

namespace App\Console\Commands;

use App\Services\TenantManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UpdateAppCommand extends Command
{
    protected $signature = 'app:update {--force : Force execution in production}';
    protected $description = 'Automate full platform update: migrations, tenant databases, and cache optimization';

    public function handle(TenantManager $tenantManager): int
    {
        $this->info("=================================================");
        $this->info("       LINKORA AUTOMATED SERVER UPDATE           ");
        $this->info("=================================================");

        // 1. Run Central Migrations
        $this->info("\n[1/4] Running central database migrations...");
        Artisan::call('migrate', ['--force' => true], $this->output);

        // 2. Provision & Migrate All Tenant Databases
        $this->info("\n[2/4] Provisioning & migrating dedicated tenant databases...");
        $tenants = $tenantManager->provisionAllSeededTenants();
        $this->info("Successfully provisioned/verified " . count($tenants) . " tenant databases.");

        // 3. Clear and Optimize Caches
        $this->info("\n[3/4] Rebuilding application caches...");
        Artisan::call('optimize:clear', [], $this->output);
        Artisan::call('config:cache', [], $this->output);
        Artisan::call('route:cache', [], $this->output);
        Artisan::call('view:cache', [], $this->output);

        // 4. Restart Queues
        $this->info("\n[4/4] Restarting background queue workers...");
        Artisan::call('queue:restart', [], $this->output);

        $this->info("\n=================================================");
        $this->info("       LINKORA UPDATE COMPLETED SUCCESSFULLY     ");
        $this->info("=================================================");

        return Command::SUCCESS;
    }
}
