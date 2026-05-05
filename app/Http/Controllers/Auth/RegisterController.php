<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Tampilkan halaman registrasi bawaan (jika diakses dari controller).
     * Saat ini menggunakan Route closure di web.php, tapi untuk kerapian bisa dipindah ke sini.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi akun (Tanpa Google)
     */
    public function register(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'role' => 'required|in:brand,creator',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau masuk.',
            'password.confirmed' => 'Konfirmasi sandi tidak sesuai.',
            'password.min' => 'Sandi minimal 8 karakter.'
        ]);

        // 2. Buat user baru ke database
        // Perlu diingat: form kita menggunakan 'creator' untuk role kreator.
        // Di database, mungkin role disave sebagai 'kreator' atau 'creator'. 
        // Saya akan menyesuaikan dengan string 'kreator' jika role adalah 'creator' karena web.php menggunakan route IsKreator.
        $roleToSave = $request->role === 'creator' ? 'kreator' : 'brand';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $roleToSave,
        ]);

        // 3. Login otomatis setelah daftar
        Auth::login($user);

        // 4. Redirect ke dashboard yang sesuai (seperti Onboarding/Google login)
        if ($user->role === 'brand') {
            return redirect()->route('brand.dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di Dashboard Brand.');
        }

        if ($user->role === 'kreator') {
            return redirect()->route('kreator.dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di Dashboard Kreator.');
        }

        return redirect('/');
    }
}
