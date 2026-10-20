<?php

namespace App\Services;

use App\Models\EscrowTransaction;
use App\Models\AuditLog;
use App\Models\NotificationItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EscrowService
{
    /**
     * Deposit funds into Escrow
     */
    public function deposit(
        ?string $listingId,
        string $listingTitle,
        ?string $payerId,
        string $payerName,
        string $payeeName,
        int $amountTzs,
        string $paymentMethod
    ): EscrowTransaction {
        return DB::transaction(function () use ($listingId, $listingTitle, $payerId, $payerName, $payeeName, $amountTzs, $paymentMethod) {
            $feeTzs = (int)round($amountTzs * 0.05); // 5% platform fee
            $txId = 'tx_' . strtolower(Str::random(8));
            $auditCode = 'MLP-' . strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $paymentMethod), 0, 4)) . '-' . rand(10000, 99999) . '-ESC';

            $transaction = EscrowTransaction::create([
                'id' => $txId,
                'listing_id' => $listingId,
                'listing_title' => $listingTitle,
                'payer_id' => $payerId,
                'payer_name' => $payerName,
                'payee_id' => null,
                'payee_name' => $payeeName,
                'amount_tzs' => $amountTzs,
                'fee_tzs' => $feeTzs,
                'escrow_status' => 'HELD_IN_ESCROW',
                'payment_method' => $paymentMethod . ' Escrow',
                'timestamp_str' => date('Y-m-d H:i'),
                'audit_code' => $auditCode,
            ]);

            // Audit Trail
            AuditLog::create([
                'event' => 'ESCROW_DEPOSIT',
                'user_id' => $payerId,
                'payload' => [
                    'transaction_id' => $txId,
                    'amount_tzs' => $amountTzs,
                    'fee_tzs' => $feeTzs,
                    'payment_method' => $paymentMethod,
                    'audit_code' => $auditCode,
                ],
            ]);

            // Notification
            NotificationItem::create([
                'id' => 'n_' . round(microtime(true) * 1000),
                'user_id' => $payerId,
                'title' => "{$paymentMethod} Escrow Deposit Confirmed",
                'body' => "Locked TZS " . number_format($amountTzs) . " in escrow for '{$listingTitle}'. Audit Code: {$auditCode}.",
                'channel' => "SMS (Africa's Talking)",
                'timestamp_str' => 'Just now',
                'is_read' => false,
            ]);

            return $transaction;
        });
    }

    /**
     * Release Escrow funds to worker/provider
     */
    public function release(string $transactionId): EscrowTransaction
    {
        return DB::transaction(function () use ($transactionId) {
            $transaction = EscrowTransaction::findOrFail($transactionId);
            $transaction->escrow_status = 'RELEASED';
            $transaction->save();

            // Update user stats & reputation if payee exists
            if ($transaction->payee_id) {
                $payee = User::find($transaction->payee_id);
                if ($payee) {
                    $payee->total_transactions += 1;
                    $payee->completed_gigs += 1;
                    $payee->reputation_score = min(1000, $payee->reputation_score + 20);
                    $payee->save();
                }
            }

            AuditLog::create([
                'event' => 'ESCROW_RELEASE',
                'user_id' => $transaction->payer_id,
                'payload' => [
                    'transaction_id' => $transactionId,
                    'amount_tzs' => $transaction->amount_tzs,
                    'audit_code' => $transaction->audit_code,
                ],
            ]);

            NotificationItem::create([
                'id' => 'n_' . round(microtime(true) * 1000),
                'user_id' => $transaction->payer_id,
                'title' => "Escrow Funds Released",
                'body' => "TZS " . number_format($transaction->amount_tzs) . " has been disbursed to {$transaction->payee_name}.",
                'channel' => "SMS (Africa's Talking)",
                'timestamp_str' => 'Just now',
                'is_read' => false,
            ]);

            return $transaction;
        });
    }

    /**
     * File an Escrow Dispute
     */
    public function dispute(string $transactionId, string $reason): EscrowTransaction
    {
        return DB::transaction(function () use ($transactionId, $reason) {
            $transaction = EscrowTransaction::findOrFail($transactionId);
            $transaction->escrow_status = 'DISPUTED';
            $transaction->dispute_reason = $reason;
            $transaction->save();

            AuditLog::create([
                'event' => 'ESCROW_DISPUTE_RAISED',
                'user_id' => $transaction->payer_id,
                'payload' => [
                    'transaction_id' => $transactionId,
                    'reason' => $reason,
                ],
            ]);

            return $transaction;
        });
    }
}
