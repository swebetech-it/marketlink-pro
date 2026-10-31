<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EscrowTransaction;
use App\Services\EscrowService;
use Illuminate\Http\Request;

class EscrowController extends Controller
{
    protected EscrowService $escrowService;

    public function __construct(EscrowService $escrowService)
    {
        $this->escrowService = $escrowService;
    }

    public function transactions(Request $request)
    {
        $targetUserId = $request->header('X-User-Id') ?? $request->query('user_id');
        $currentUser = $targetUserId ? \App\Models\User::find($targetUserId) : null;

        $query = EscrowTransaction::query();

        if ($currentUser && $currentUser->role !== 'ADMIN') {
            $name = $currentUser->organization_name ?: $currentUser->full_name;
            $query->where(function($q) use ($name, $currentUser) {
                $q->where('payer_name', 'LIKE', "%{$name}%")
                  ->orWhere('payee_name', 'LIKE', "%{$currentUser->full_name}%");
            });
        }

        $txs = $query->orderBy('created_at', 'desc')->get()->map(fn($t) => [
            'id' => (string)$t->id,
            'listingId' => (string)($t->listing_id ?? ''),
            'listingTitle' => $t->listing_title,
            'payerName' => $t->payer_name,
            'payeeName' => $t->payee_name,
            'amountTzs' => (int)$t->amount_tzs,
            'feeTzs' => (int)$t->fee_tzs,
            'escrowStatus' => $t->escrow_status,
            'paymentMethod' => $t->payment_method,
            'timestamp' => $t->timestamp_str ?? (string)$t->created_at,
            'auditCode' => $t->audit_code,
        ]);

        return response()->json([
            'success' => true,
            'data' => $txs,
        ]);
    }

    public function deposit(Request $request)
    {
        $validated = $request->validate([
            'listingId' => 'nullable|string',
            'listingTitle' => 'required|string',
            'payeeName' => 'required|string',
            'amountTzs' => 'required|integer|min:1000',
            'paymentMethod' => 'required|string',
        ]);

        $payerId = $request->input('payerId') ?? 'user_' . Str::random(6);
        $payerName = $request->input('payerName') ?? 'Client / Buyer';

        $tx = $this->escrowService->deposit(
            $validated['listingId'] ?? null,
            $validated['listingTitle'],
            $payerId,
            $payerName,
            $validated['payeeName'],
            (int)$validated['amountTzs'],
            $validated['paymentMethod']
        );

        return response()->json([
            'success' => true,
            'message' => 'Escrow deposit initiated successfully',
            'data' => [
                'id' => (string)$tx->id,
                'listingId' => (string)($tx->listing_id ?? ''),
                'listingTitle' => $tx->listing_title,
                'payerName' => $tx->payer_name,
                'payeeName' => $tx->payee_name,
                'amountTzs' => (int)$tx->amount_tzs,
                'feeTzs' => (int)$tx->fee_tzs,
                'escrowStatus' => $tx->escrow_status,
                'paymentMethod' => $tx->payment_method,
                'timestamp' => $tx->timestamp_str,
                'auditCode' => $tx->audit_code,
            ],
        ], 201);
    }

    public function release(string $id)
    {
        $tx = $this->escrowService->release($id);

        return response()->json([
            'success' => true,
            'message' => 'Escrow funds released successfully',
            'data' => [
                'id' => (string)$tx->id,
                'listingId' => (string)($tx->listing_id ?? ''),
                'listingTitle' => $tx->listing_title,
                'payerName' => $tx->payer_name,
                'payeeName' => $tx->payee_name,
                'amountTzs' => (int)$tx->amount_tzs,
                'feeTzs' => (int)$tx->fee_tzs,
                'escrowStatus' => $tx->escrow_status,
                'paymentMethod' => $tx->payment_method,
                'timestamp' => $tx->timestamp_str,
                'auditCode' => $tx->audit_code,
            ],
        ]);
    }

    public function dispute(Request $request, string $id)
    {
        $reason = $request->input('reason', 'Client or worker dispute raised');
        $tx = $this->escrowService->dispute($id, $reason);

        return response()->json([
            'success' => true,
            'message' => 'Transaction marked as disputed for SuperAdmin arbitration',
            'data' => $tx,
        ]);
    }

    public function mpesaCallback(Request $request)
    {
        // IPN Listener for Vodacom M-Pesa / Tigo Pesa / Selcom
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Accepted',
        ]);
    }
}
