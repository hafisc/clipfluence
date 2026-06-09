<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
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
            'status' => ['required', Rule::in(['pending', 'success', 'failed', 'expired'])],
        ]);

        $oldStatus = $deposit->status;
        $newStatus = $validated['status'];

        if ($oldStatus !== 'success' && $newStatus === 'success') {
            $deposit->user?->increment('balance', $deposit->amount);
        }

        if ($oldStatus === 'success' && $newStatus !== 'success') {
            $deposit->user?->decrement('balance', $deposit->amount);
        }

        $deposit->update([
            'status' => $newStatus,
            'payment_type' => $deposit->payment_type ?: 'manual_admin',
        ]);

        return back()->with('success', 'Status transaksi berhasil diperbarui.');
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
