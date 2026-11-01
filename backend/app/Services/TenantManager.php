<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TenantManager
{
    protected ?Tenant $currentTenant = null;

    /**
     * Get a standardized, valid MySQL database name for a tenant.
     */
    public function getTenantDatabaseName(string $identifier): string
    {
        $cleanId = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($identifier));
        return 'tenant_' . $cleanId;
    }

    /**
     * Provision and migrate a dedicated physical database for a tenant/organization.
     */
    public function createTenantDatabase(string $tenantId, string $orgName, string $userId): Tenant
    {
        $dbName = $this->getTenantDatabaseName($tenantId);

        $defaultDriver = Config::get('database.connections.' . Config::get('database.default') . '.driver', 'mysql');
        if ($defaultDriver === 'sqlite') {
            $sqlitePath = database_path("{$dbName}.sqlite");
            if (!file_exists($sqlitePath)) {
                @touch($sqlitePath);
            }
            Config::set('database.connections.tenant.driver', 'sqlite');
            Config::set('database.connections.tenant.database', $sqlitePath);
            DB::purge('tenant');
        } else {
            // 1. Create the physical MySQL database schema if it doesn't already exist
            DB::connection('mysql')->statement(
                "CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
            );
        }

        // 2. Register/update tenant in central tenants registry
        $tenant = Tenant::updateOrCreate(
            ['id' => $tenantId],
            [
                'user_id' => $userId,
                'organization_name' => $orgName,
                'database_name' => $dbName,
                'status' => 'ACTIVE',
                'settings' => [
                    'created_at' => now()->toIso8601String(),
                    'isolation_type' => 'DATABASE_PER_TENANT',
                    'engine' => 'MySQL 8.x',
                ],
            ]
        );

        // 3. Migrate the dedicated tenant database
        $this->migrateTenantDatabase($dbName);

        Log::info("TenantManager: Successfully provisioned isolated database '{$dbName}' for organization '{$orgName}' ({$tenantId})");

        return $tenant;
    }

    /**
     * Execute tenant migrations on the specified tenant database.
     */
    public function migrateTenantDatabase(string $dbName): void
    {
        // Dynamically configure the 'tenant' connection
        $defaultDriver = Config::get('database.connections.' . Config::get('database.default') . '.driver', 'mysql');
        if ($defaultDriver === 'sqlite') {
            $sqlitePath = database_path("{$dbName}.sqlite");
            if (!file_exists($sqlitePath)) {
                @touch($sqlitePath);
            }
            Config::set('database.connections.tenant.driver', 'sqlite');
            Config::set('database.connections.tenant.database', $sqlitePath);
        } else {
            Config::set('database.connections.tenant.database', $dbName);
        }
        DB::purge('tenant');

        // Run migrations inside database/migrations/tenant
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);
    }

    /**
     * Switch current connection context to a tenant's isolated database.
     */
    public function switchToTenant(Tenant|string $tenant): bool
    {
        if (is_string($tenant)) {
            $tenantModel = Tenant::where('id', $tenant)
                ->orWhere('user_id', $tenant)
                ->orWhere('database_name', $tenant)
                ->first();

            if (!$tenantModel) {
                return false;
            }
            $tenant = $tenantModel;
        }

        $defaultDriver = Config::get('database.connections.' . Config::get('database.default') . '.driver', 'mysql');
        if ($defaultDriver === 'sqlite') {
            $sqlitePath = database_path("{$tenant->database_name}.sqlite");
            if (!file_exists($sqlitePath)) {
                @touch($sqlitePath);
            }
            Config::set('database.connections.tenant.driver', 'sqlite');
            Config::set('database.connections.tenant.database', $sqlitePath);
        } else {
            Config::set('database.connections.tenant.database', $tenant->database_name);
        }
        DB::purge('tenant');
        $this->currentTenant = $tenant;

        return true;
    }

    /**
     * Reset tenant connection context back to central database.
     */
    public function disconnectTenant(): void
    {
        $this->currentTenant = null;
        Config::set('database.connections.tenant.database', null);
        DB::purge('tenant');
    }

    /**
     * Get the active tenant in this request cycle.
     */
    public function getCurrentTenant(): ?Tenant
    {
        return $this->currentTenant;
    }

    /**
     * Find a tenant by user ID or tenant ID.
     */
    public function findTenantForUser(string $userId): ?Tenant
    {
        return Tenant::where('user_id', $userId)
            ->orWhere('id', $userId)
            ->first();
    }

    /**
     * Check if a user belongs to or owns a tenant.
     */
    public function isTenantUser(string $userId): bool
    {
        return Tenant::where('user_id', $userId)->orWhere('id', $userId)->exists();
    }

    /**
     * Provision tenant databases for all seeded organizations if not yet provisioned.
     */
    public function provisionAllSeededTenants(): array
    {
        $orgUsers = User::where('account_type', 'ORGANIZATION')
            ->orWhereIn('role', ['EMPLOYER', 'FARMER_COOP'])
            ->get();

        $provisioned = [];

        foreach ($orgUsers as $user) {
            $tenantId = $user->id;
            $orgName = $user->organization_name ?: $user->full_name;
            $tenant = $this->createTenantDatabase($tenantId, $orgName, $user->id);
            $provisioned[] = [
                'tenant_id' => $tenant->id,
                'org_name' => $tenant->organization_name,
                'database' => $tenant->database_name,
            ];
        }

        return $provisioned;
    }
}
