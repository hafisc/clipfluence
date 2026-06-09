<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KycController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['kreator', 'brand'])
            ->latest()
            ->paginate(10);

        return view('admin.kyc.index', [
            'users' => $users,
            'stats' => [
                'pending' => User::whereIn('role', ['kreator', 'brand'])->where('kyc_status', 'pending')->count(),
                'verified' => User::whereIn('role', ['kreator', 'brand'])->where('kyc_status', 'verified')->count(),
                'rejected' => User::whereIn('role', ['kreator', 'brand'])->where('kyc_status', 'rejected')->count(),
            ],
        ]);
    }

    public function update(Request $request, User $user)
    {
        abort_unless(in_array($user->role, ['kreator', 'brand'], true), 403);

        $validated = $request->validate([
            'kyc_status' => ['required', Rule::in(['verified', 'rejected'])],
        ]);

        $user->update([
            'kyc_status' => $validated['kyc_status'],
            'kyc_document_type' => $user->kyc_document_type ?: ($user->role === 'brand' ? 'NPWP' : 'KTP'),
            'kyc_reviewed_at' => now(),
        ]);

        return back()->with('success', $validated['kyc_status'] === 'verified'
            ? 'KYC pengguna berhasil diverifikasi.'
            : 'KYC pengguna berhasil ditolak.');
    }
}
