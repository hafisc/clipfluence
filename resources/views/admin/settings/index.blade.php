@extends('layouts.admin')
@section('title', 'Pengaturan')
@section('page_title', 'Pengaturan Platform')
@section('page_subtitle', 'Konfigurasi umum sistem Clipfluence')

@section('content')
@php
    $tabs = [
        'general' => ['icon' => 'sliders-horizontal', 'label' => 'Umum'],
        'fees' => ['icon' => 'percent', 'label' => 'Komisi & Fee'],
        'smtp' => ['icon' => 'mail', 'label' => 'Email & SMTP'],
        'security' => ['icon' => 'shield', 'label' => 'Keamanan'],
        'appearance' => ['icon' => 'palette', 'label' => 'Tampilan'],
        'api' => ['icon' => 'plug', 'label' => 'Integrasi API'],
    ];

    $currentTab = session('activeTab', $activeTab ?? 'general');
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ tab: '{{ $currentTab }}' }">
    <div class="space-y-1 bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-3 h-fit">
        @foreach($tabs as $key => $tab)
            <button
                type="button"
                @click="tab = '{{ $key }}'"
                :class="tab === '{{ $key }}' ? 'bg-brand/10 text-brand border border-brand/20' : 'text-slate-400 hover:text-white hover:bg-white/5'"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-colors"
            >
                <i data-lucide="{{ $tab['icon'] }}" class="w-4 h-4"></i> {{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    <div class="lg:col-span-2 space-y-5">
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm rounded-xl px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-200 text-sm rounded-xl px-4 py-3">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6" x-show="tab === 'general'" x-cloak>
            @csrf
            <input type="hidden" name="tab" value="general">
            <h3 class="text-sm font-semibold text-white mb-5">Pengaturan Umum</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Nama Platform</label>
                    <input name="platform_name" type="text" value="{{ old('platform_name', $settings['general']['platform_name']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Email Support</label>
                    <input name="support_email" type="email" value="{{ old('support_email', $settings['general']['support_email']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">URL Platform</label>
                    <input name="platform_url" type="url" value="{{ old('platform_url', $settings['general']['platform_url']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Tagline</label>
                    <input name="tagline" type="text" value="{{ old('tagline', $settings['general']['tagline']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button class="flex items-center gap-2 px-6 py-2.5 bg-brand text-white text-sm font-semibold rounded-xl hover:bg-brand-hover transition-colors">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6" x-show="tab === 'fees'" x-cloak>
            @csrf
            <input type="hidden" name="tab" value="fees">
            <h3 class="text-sm font-semibold text-white mb-5">Komisi & Fee Platform</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Fee Platform dari Merek (%)</label>
                    <input name="brand_fee_percent" type="number" min="0" max="100" value="{{ old('brand_fee_percent', $settings['fees']['brand_fee_percent']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Fee Platform dari Kreator (%)</label>
                    <input name="creator_fee_percent" type="number" min="0" max="100" value="{{ old('creator_fee_percent', $settings['fees']['creator_fee_percent']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Minimum Payout Kreator (Rp)</label>
                    <input name="minimum_payout" type="number" min="0" value="{{ old('minimum_payout', $settings['fees']['minimum_payout']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Maksimum Budget Kampanye (Rp)</label>
                    <input name="max_campaign_budget" type="number" min="0" value="{{ old('max_campaign_budget', $settings['fees']['max_campaign_budget']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button class="flex items-center gap-2 px-6 py-2.5 bg-brand text-white text-sm font-semibold rounded-xl hover:bg-brand-hover transition-colors">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6" x-show="tab === 'smtp'" x-cloak>
            @csrf
            <input type="hidden" name="tab" value="smtp">
            <h3 class="text-sm font-semibold text-white mb-5">Email & SMTP</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Mailer</label>
                    <input name="mailer" type="text" value="{{ old('mailer', $settings['smtp']['mailer']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Host SMTP</label>
                    <input name="host" type="text" value="{{ old('host', $settings['smtp']['host']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Port</label>
                    <input name="port" type="number" min="1" max="65535" value="{{ old('port', $settings['smtp']['port']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Enkripsi</label>
                    <select name="encryption" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                        @foreach(['tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'None'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('encryption', $settings['smtp']['encryption']) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Username SMTP</label>
                    <input name="username" type="text" value="{{ old('username', $settings['smtp']['username']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Password SMTP</label>
                    <input name="password" type="password" value="{{ old('password', $settings['smtp']['password']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">From Address</label>
                    <input name="from_address" type="email" value="{{ old('from_address', $settings['smtp']['from_address']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">From Name</label>
                    <input name="from_name" type="text" value="{{ old('from_name', $settings['smtp']['from_name']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button class="flex items-center gap-2 px-6 py-2.5 bg-brand text-white text-sm font-semibold rounded-xl hover:bg-brand-hover transition-colors">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6" x-show="tab === 'security'" x-cloak>
            @csrf
            <input type="hidden" name="tab" value="security">
            <h3 class="text-sm font-semibold text-white mb-5">Keamanan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Session Timeout (menit)</label>
                    <input name="session_timeout_minutes" type="number" min="5" max="1440" value="{{ old('session_timeout_minutes', $settings['security']['session_timeout_minutes']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Maksimum Login Gagal</label>
                    <input name="max_login_attempts" type="number" min="3" max="20" value="{{ old('max_login_attempts', $settings['security']['max_login_attempts']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
            </div>
            <div class="space-y-3 mt-5">
                <label class="flex items-center gap-3 text-sm text-slate-300">
                    <input type="checkbox" name="require_2fa_for_admin" value="1" @checked(old('require_2fa_for_admin', $settings['security']['require_2fa_for_admin'])) class="rounded border-neutral-600 bg-neutral-800 text-brand focus:ring-brand">
                    Wajibkan 2FA untuk akun admin
                </label>
                <label class="flex items-center gap-3 text-sm text-slate-300">
                    <input type="checkbox" name="force_strong_password" value="1" @checked(old('force_strong_password', $settings['security']['force_strong_password'])) class="rounded border-neutral-600 bg-neutral-800 text-brand focus:ring-brand">
                    Wajibkan password kuat untuk user baru
                </label>
            </div>
            <div class="flex justify-end mt-6">
                <button class="flex items-center gap-2 px-6 py-2.5 bg-brand text-white text-sm font-semibold rounded-xl hover:bg-brand-hover transition-colors">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6" x-show="tab === 'appearance'" x-cloak>
            @csrf
            <input type="hidden" name="tab" value="appearance">
            <h3 class="text-sm font-semibold text-white mb-5">Tampilan</h3>
            <div class="mb-4 p-3 rounded-xl border border-neutral-700 bg-neutral-800/50 text-xs text-slate-300 space-y-1">
                <p><span class="text-slate-400">Warna default primer:</span> <span class="font-semibold">#6D28D9</span></p>
                <p><span class="text-slate-400">Warna default aksen:</span> <span class="font-semibold">#A855F7</span></p>
                <p class="text-slate-500">Kamu bisa pilih manual atau klik preset warna di bawah.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Warna Primer</label>
                    <input name="primary_color" type="color" value="{{ old('primary_color', $settings['appearance']['primary_color']) }}" class="w-full h-11 bg-neutral-800 border border-neutral-700 rounded-xl px-2 py-2">
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach(['#6D28D9', '#2563EB', '#059669', '#DC2626', '#0EA5E9'] as $preset)
                            <button type="button" onclick="this.closest('div').previousElementSibling.value='{{ $preset }}'" class="w-7 h-7 rounded-lg border border-white/20" style="background-color: {{ $preset }}" title="{{ $preset }}"></button>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Warna Aksen</label>
                    <input name="accent_color" type="color" value="{{ old('accent_color', $settings['appearance']['accent_color']) }}" class="w-full h-11 bg-neutral-800 border border-neutral-700 rounded-xl px-2 py-2">
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach(['#A855F7', '#38BDF8', '#22C55E', '#F59E0B', '#F43F5E'] as $preset)
                            <button type="button" onclick="this.closest('div').previousElementSibling.value='{{ $preset }}'" class="w-7 h-7 rounded-lg border border-white/20" style="background-color: {{ $preset }}" title="{{ $preset }}"></button>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="button" onclick="this.closest('form').querySelector('[name=primary_color]').value='#6D28D9'; this.closest('form').querySelector('[name=accent_color]').value='#A855F7'" class="text-xs px-3 py-1.5 rounded-lg border border-neutral-600 text-slate-300 hover:bg-white/5 transition-colors">
                    Kembalikan ke Warna Default
                </button>
            </div>
            <div class="space-y-3 mt-5">
                <label class="flex items-center gap-3 text-sm text-slate-300">
                    <input type="checkbox" name="dark_mode_default" value="1" @checked(old('dark_mode_default', $settings['appearance']['dark_mode_default'])) class="rounded border-neutral-600 bg-neutral-800 text-brand focus:ring-brand">
                    Aktifkan mode gelap sebagai default
                </label>
                <label class="flex items-center gap-3 text-sm text-slate-300">
                    <input type="checkbox" name="compact_sidebar" value="1" @checked(old('compact_sidebar', $settings['appearance']['compact_sidebar'])) class="rounded border-neutral-600 bg-neutral-800 text-brand focus:ring-brand">
                    Gunakan sidebar kompak
                </label>
            </div>
            <div class="flex justify-end mt-6">
                <button class="flex items-center gap-2 px-6 py-2.5 bg-brand text-white text-sm font-semibold rounded-xl hover:bg-brand-hover transition-colors">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6" x-show="tab === 'api'" x-cloak>
            @csrf
            <input type="hidden" name="tab" value="api">
            <h3 class="text-sm font-semibold text-white mb-5">Integrasi API</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Webhook Secret</label>
                    <input name="webhook_secret" type="text" value="{{ old('webhook_secret', $settings['api']['webhook_secret']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1.5">Rate Limit API (per menit)</label>
                    <input name="rate_limit_per_minute" type="number" min="10" max="10000" value="{{ old('rate_limit_per_minute', $settings['api']['rate_limit_per_minute']) }}" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                </div>
                <label class="flex items-center gap-3 text-sm text-slate-300">
                    <input type="checkbox" name="midtrans_enabled" value="1" @checked(old('midtrans_enabled', $settings['api']['midtrans_enabled'])) class="rounded border-neutral-600 bg-neutral-800 text-brand focus:ring-brand">
                    Aktifkan integrasi Midtrans
                </label>
                <label class="flex items-center gap-3 text-sm text-slate-300">
                    <input type="checkbox" name="openai_enabled" value="1" @checked(old('openai_enabled', $settings['api']['openai_enabled'])) class="rounded border-neutral-600 bg-neutral-800 text-brand focus:ring-brand">
                    Aktifkan integrasi OpenAI
                </label>
            </div>
            <div class="flex justify-end mt-6">
                <button class="flex items-center gap-2 px-6 py-2.5 bg-brand text-white text-sm font-semibold rounded-xl hover:bg-brand-hover transition-colors">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
