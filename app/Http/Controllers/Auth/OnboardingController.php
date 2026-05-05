<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    /**
     * Tampilkan halaman onboarding
     */
    public function index()
    {
        $user = Auth::user();

        // Jika user sudah punya role, ngapain di sini? Kembalikan ke dashboard-nya.
        if (!empty($user->role)) {
            if ($user->role === 'brand') return redirect()->route('brand.dashboard');
            if ($user->role === 'kreator') return redirect()->route('kreator.dashboard');
            if ($user->role === 'admin') return redirect()->route('admin.dashboard');
        }

        return view('auth.onboarding');
    }

    /**
     * Simpan role pilihan user
     */
    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|in:brand,kreator'
        ]);

        $user = Auth::user();
        
        // Simpan role
        $user->role = $request->role;
        $user->save();

        // Redirect sesuai role yang dipilih
        if ($user->role === 'brand') {
            return redirect()->route('brand.dashboard')->with('success', 'Selamat datang di Clipfluence sebagai Brand!');
        }

        return redirect()->route('kreator.dashboard')->with('success', 'Selamat datang di Clipfluence sebagai Kreator!');
    }
}
