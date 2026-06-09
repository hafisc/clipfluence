<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function internal()
    {
        $users = User::where('role', 'admin')->latest()->paginate(10);

        return view('admin.users.index', [
            'users' => $users,
            'stats' => $this->stats(),
        ]);
    }

    public function kreators()
    {
        $kreators = User::where('role', 'kreator')->latest()->paginate(10);

        return view('admin.kreators.index', [
            'kreators' => $kreators,
            'stats' => $this->stats(),
        ]);
    }

    public function brands()
    {
        $brands = User::where('role', 'brand')->latest()->paginate(10);

        return view('admin.brands.index', [
            'brands' => $brands,
            'stats' => $this->stats(),
        ]);
    }

    public function storeInternal(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $validated['role'] = 'admin';

        User::create($validated);

        return back()->with('success', 'Staf internal berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:30'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url' => ['nullable', 'url', 'max:255'],
        ]);

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $user->update($validated);

        return back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Akun yang sedang digunakan tidak bisa dihapus.');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    private function stats(): array
    {
        return [
            'admins' => User::where('role', 'admin')->count(),
            'kreators' => User::where('role', 'kreator')->count(),
            'brands' => User::where('role', 'brand')->count(),
            'total' => User::count(),
        ];
    }
}
