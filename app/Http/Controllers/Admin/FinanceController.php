<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FinanceController extends Controller
{
    public function payouts()
    {
        $deposits = Deposit::with('user')->latest()->paginate(10);

        return view('admin.payouts.index', [
            'deposits' => $deposits,
            'stats' => [
                'escrow' => Deposit::where('status', 'success')->sum('amount'),
                'pending_deposit' => Deposit::where('status', 'pending')->sum('amount'),
                'paid_withdrawal' => Withdrawal::where('status', 'completed')->sum('amount'),
                'transactions' => Deposit::count() + Withdrawal::count(),
            ],
        ]);
    }

    public function updateDeposit(Request $request, Deposit $deposit)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['success', 'failed'])],
        ]);

        $newStatus = $validated['status'];

        $processed = DB::transaction(function () use ($deposit, $newStatus) {
            $lockedDeposit = Deposit::whereKey($deposit->getKey())->lockForUpdate()->firstOrFail();

            if ($lockedDeposit->status !== 'pending') {
                return false;
            }

            if ($newStatus === 'success') {
                $lockedDeposit->user?->increment('balance', $lockedDeposit->amount);
            }

            $lockedDeposit->update([
                'status' => $newStatus,
                'payment_type' => $lockedDeposit->payment_type ?: 'manual_admin',
            ]);

            return true;
        });

        if (! $processed) {
            return back()->with('error', 'Transaksi ini sudah diproses dan tidak bisa diubah lagi.');
        }

        return back()->with('success', $newStatus === 'success'
            ? 'Transaksi berhasil disahkan dan saldo brand ditambahkan.'
            : 'Transaksi berhasil digagalkan.');
    }

    public function withdrawals()
    {
        $withdrawals = Withdrawal::with('user')->latest()->paginate(10);

        return view('admin.withdrawals.index', [
            'withdrawals' => $withdrawals,
            'stats' => [
                'pending' => Withdrawal::where('status', 'pending')->count(),
                'completed_amount' => Withdrawal::where('status', 'completed')->sum('amount'),
                'average' => Withdrawal::avg('amount') ?: 0,
            ],
        ]);
    }

    public function updateWithdrawal(Request $request, Withdrawal $withdrawal)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['completed', 'rejected'])],
        ]);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Penarikan ini sudah diproses.');
        }

        if ($validated['status'] === 'rejected') {
            $withdrawal->user?->increment('balance', $withdrawal->amount);
        }

        $withdrawal->update(['status' => $validated['status']]);

        return back()->with('success', $validated['status'] === 'completed'
            ? 'Penarikan berhasil dicairkan.'
            : 'Penarikan berhasil ditolak dan saldo dikembalikan.');
    }
}
