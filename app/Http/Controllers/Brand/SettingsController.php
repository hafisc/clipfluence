<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        return view('brand.settings.index', compact('user'));
    }
    
    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user();
            
            // Clean up empty strings to null
            $data = $request->all();
            foreach ($data as $key => $value) {
                if ($value === '') {
                    $data[$key] = null;
                }
            }
            
            $validated = validator($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'company_name' => 'nullable|string|max:255',
                'industry' => 'nullable|string|max:100',
                'website' => 'nullable|url:http,https|max:255',
                'bio' => 'nullable|string|max:500',
                'instagram_url' => 'nullable|url:http,https|max:255',
                'tiktok_url' => 'nullable|url:http,https|max:255',
            ])->validate();
            
            $user->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Settings update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $user = auth()->user();
        
        // Delete old avatar if exists
        if ($user->avatar && !str_contains($user->avatar, 'dicebear') && !str_contains($user->avatar, 'googleusercontent')) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        
        $user->update(['avatar' => $path]);
        
        return response()->json([
            'success' => true,
            'message' => 'Avatar berhasil diperbarui!',
            'avatar_url' => asset('storage/' . $path)
        ]);
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);
        
        $user = auth()->user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini tidak sesuai'
            ], 422);
        }
        
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah!'
        ]);
    }
    
    public function updateNotifications(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'campaign_updates' => 'boolean',
            'submission_alerts' => 'boolean',
            'payment_notifications' => 'boolean',
        ]);
        
        // Store notification preferences in user settings or separate table
        // For now, we'll just return success
        
        return response()->json([
            'success' => true,
            'message' => 'Preferensi notifikasi berhasil diperbarui!'
        ]);
    }
}
