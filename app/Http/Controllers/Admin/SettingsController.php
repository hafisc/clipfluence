<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    private const SETTINGS_FILE = 'settings/admin.json';

    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'general');

        $defaults = $this->defaultSettings();
        $stored = $this->readSettings();

        $settings = array_replace_recursive($defaults, $stored);

        return view('admin.settings.index', [
            'settings' => $settings,
            'activeTab' => $activeTab,
        ]);
    }

    public function update(Request $request)
    {
        $tab = $request->input('tab', 'general');

        $rules = [
            'general' => [
                'platform_name' => 'required|string|max:100',
                'support_email' => 'required|email|max:255',
                'platform_url' => 'required|url:http,https|max:255',
                'tagline' => 'nullable|string|max:255',
            ],
            'fees' => [
                'brand_fee_percent' => 'required|numeric|min:0|max:100',
                'creator_fee_percent' => 'required|numeric|min:0|max:100',
                'minimum_payout' => 'required|integer|min:0',
                'max_campaign_budget' => 'required|integer|min:0',
            ],
            'smtp' => [
                'mailer' => 'required|string|max:50',
                'host' => 'required|string|max:255',
                'port' => 'required|integer|min:1|max:65535',
                'username' => 'nullable|string|max:255',
                'password' => 'nullable|string|max:255',
                'encryption' => 'nullable|in:tls,ssl,none',
                'from_address' => 'required|email|max:255',
                'from_name' => 'required|string|max:100',
            ],
            'security' => [
                'session_timeout_minutes' => 'required|integer|min:5|max:1440',
                'max_login_attempts' => 'required|integer|min:3|max:20',
                'require_2fa_for_admin' => 'nullable|boolean',
                'force_strong_password' => 'nullable|boolean',
            ],
            'appearance' => [
                'primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
                'accent_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
                'dark_mode_default' => 'nullable|boolean',
                'compact_sidebar' => 'nullable|boolean',
            ],
            'api' => [
                'midtrans_enabled' => 'nullable|boolean',
                'openai_enabled' => 'nullable|boolean',
                'webhook_secret' => 'nullable|string|max:255',
                'rate_limit_per_minute' => 'required|integer|min:10|max:10000',
            ],
        ];

        abort_unless(array_key_exists($tab, $rules), 404);

        $validated = $request->validate($rules[$tab]);

        $booleanFields = [
            'require_2fa_for_admin',
            'force_strong_password',
            'dark_mode_default',
            'compact_sidebar',
            'midtrans_enabled',
            'openai_enabled',
        ];

        foreach ($booleanFields as $field) {
            if (array_key_exists($field, $rules[$tab])) {
                $validated[$field] = $request->boolean($field);
            }
        }

        $settings = array_replace_recursive($this->defaultSettings(), $this->readSettings());
        $settings[$tab] = array_merge($settings[$tab] ?? [], $validated);

        $this->writeSettings($settings);

        return back()
            ->with('success', 'Pengaturan berhasil disimpan.')
            ->with('activeTab', $tab);
    }

    private function readSettings(): array
    {
        if (!Storage::disk('local')->exists(self::SETTINGS_FILE)) {
            return [];
        }

        $content = Storage::disk('local')->get(self::SETTINGS_FILE);
        $decoded = json_decode($content, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function writeSettings(array $settings): void
    {
        Storage::disk('local')->put(
            self::SETTINGS_FILE,
            json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    private function defaultSettings(): array
    {
        return [
            'general' => [
                'platform_name' => 'Clipfluence',
                'support_email' => 'support@clipfluence.com',
                'platform_url' => 'https://clipfluence.com',
                'tagline' => 'Platform UGC & Clipper No. 1 di Indonesia',
            ],
            'fees' => [
                'brand_fee_percent' => 10,
                'creator_fee_percent' => 5,
                'minimum_payout' => 100000,
                'max_campaign_budget' => 500000000,
            ],
            'smtp' => [
                'mailer' => 'smtp',
                'host' => 'smtp.mailtrap.io',
                'port' => 2525,
                'username' => '',
                'password' => '',
                'encryption' => 'tls',
                'from_address' => 'noreply@clipfluence.com',
                'from_name' => 'Clipfluence',
            ],
            'security' => [
                'session_timeout_minutes' => 120,
                'max_login_attempts' => 5,
                'require_2fa_for_admin' => false,
                'force_strong_password' => true,
            ],
            'appearance' => [
                'primary_color' => '#6D28D9',
                'accent_color' => '#A855F7',
                'dark_mode_default' => true,
                'compact_sidebar' => false,
            ],
            'api' => [
                'midtrans_enabled' => true,
                'openai_enabled' => true,
                'webhook_secret' => '',
                'rate_limit_per_minute' => 120,
            ],
        ];
    }
}

