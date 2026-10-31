<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Models\User;
use App\Services\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantDatabaseMiddleware
{
    protected TenantManager $tenantManager;

    public function __construct(TenantManager $tenantManager)
    {
        $this->tenantManager = $tenantManager;
    }

    /**
     * Handle an incoming request and configure tenant database if applicable.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Resolve tenant identity from headers or parameters
        $tenantId = $request->header('X-Tenant-Id')
            ?? $request->query('tenant_id')
            ?? $request->input('tenant_id');

        $userId = $request->header('X-User-Id')
            ?? $request->query('for_user_id')
            ?? $request->query('user_id')
            ?? $request->input('posterId')
            ?? ($request->user() ? $request->user()->id : null);

        $tenant = null;

        if ($tenantId) {
            $tenant = Tenant::where('id', $tenantId)->orWhere('database_name', $tenantId)->first();
        }

        if (!$tenant && $userId) {
            $tenant = $this->tenantManager->findTenantForUser($userId);
            // If user is an organization but tenant DB doesn't exist yet, provision it dynamically
            if (!$tenant) {
                $user = User::find($userId);
                if ($user && ($user->account_type === 'ORGANIZATION' || in_array($user->role, ['EMPLOYER', 'FARMER_COOP']))) {
                    $tenant = $this->tenantManager->createTenantDatabase(
                        $user->id,
                        $user->organization_name ?: $user->full_name,
                        $user->id
                    );
                }
            }
        }

        // 2. If a tenant is identified, activate their dedicated database connection
        if ($tenant) {
            $this->tenantManager->switchToTenant($tenant);
            $request->attributes->set('tenant', $tenant);
        }

        // 3. Process the request
        $response = $next($request);

        // 4. Inject multi-tenant diagnostics headers if active
        if ($tenant) {
            $response->headers->set('X-Tenant-Id', $tenant->id);
            $response->headers->set('X-Tenant-Database', $tenant->database_name);
            $response->headers->set('X-Tenant-Isolation', 'DATABASE_PER_TENANT');
        }

        return $response;
    }
}
