<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

public function showLoginForm()
    {
        return view('auth.login');
    }

public function login(Request $request)
{
    // validasi dulu
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // cek login
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return redirect('/dashboard'); // pindah ke dashboard
    }

    // kalau gagal
    return back()->withErrors([
        'email' => 'Email atau password salah',
    ])->withInput();
}
}
