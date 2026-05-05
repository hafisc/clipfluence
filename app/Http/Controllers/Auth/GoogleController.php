<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user sudah terdaftar berdasarkan email
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Update google_id & avatar (kalau sebelumnya lewat manual)
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar
                ]);

                Auth::login($user, true); // Login with remember
                
                return $this->redirectBasedOnRole($user);
            }

            // Mendaftarkan User Baru yang Masuk Lewat Google
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'password' => null, // Password belum ada (lewat sosmed)
                'role' => null // Set null agar diarahkan ke onboarding
            ]);

            Auth::login($newUser, true);
            
            // Redirect ke halaman Onboarding karena role belum diset
            return redirect()->route('onboarding');

        } catch (Exception $e) {
            return redirect('/')->with('error', 'Gagal login menggunakan Google. Pesan: '.$e->getMessage());
        }
    }

    /**
     * Helper untuk routing pintar
     */
    private function redirectBasedOnRole($user)
    {
        if (empty($user->role)) {
            return redirect()->route('onboarding');
        }

        if ($user->role === 'brand') {
            return redirect()->route('brand.dashboard');
        }

        if ($user->role === 'kreator') {
            return redirect()->route('kreator.dashboard');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect('/');
    }
}
