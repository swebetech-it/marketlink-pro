<?php

namespace App\Console\Commands;

use App\Services\TenantManager;
use Illuminate\Console\Command;

class ProvisionTenantsCommand extends Command
{
    protected $signature = 'tenants:provision';
    protected $description = 'Provision and migrate dedicated databases for all registered organizations';

    public function handle(TenantManager $tenantManager): int
    {
        $this->info("Starting tenant database provisioning...");

        $results = $tenantManager->provisionAllSeededTenants();

        if (empty($results)) {
            $this->warn("No unprovisioned organizations found.");
            return Command::SUCCESS;
        }

        $this->table(['Tenant ID', 'Organization Name', 'Dedicated Database'], array_map(function ($r) {
            return [
                $r['tenant_id'],
                $r['org_name'],
                $r['database'],
            ];
        }, $results));

        $this->info("All " . count($results) . " tenant databases provisioned and migrated successfully!");
        return Command::SUCCESS;
    }
}
