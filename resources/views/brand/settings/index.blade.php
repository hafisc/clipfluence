@extends('layouts.brand')

@section('title', 'Pengaturan Merek')

@section('content')
<div class="max-w-6xl mx-auto pb-12 pt-2" x-data="{ 
    activeTab: 'profile',
    uploading: false,
    saving: false,
    async submitForm(event, url) {
        this.saving = true;
        const formData = new FormData(event.target);
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: formData
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                throw new Galat(data.message || 'Terjadi kesalahan');
            }
            
            alert(data.message);
            if (data.success) location.reload();
        } catch (error) {
            console.error('Galat:', error);
            alert(error.message || 'Terjadi kesalahan');
        } finally {
            this.saving = false;
        }
    }
}">

    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="text-2xl lg:text-3xl font-black text-white tracking-tight mb-2">Pengaturan Merek</h1>
        <p class="text-xs text-slate-400">Atur profil perusahaan, integrasi media sosial, dan preferensi akun Anda.</p>
    </div>

    <div class="flex flex-col lg:flex-row w-full gap-6">
        
        {{-- LEFT NAVBAR (Tabs) --}}
        <div class="flex-shrink-0 w-full max-w-full lg:max-w-xs xl:max-w-[250px]">
            <div class="flex flex-row lg:flex-col gap-2 overflow-x-auto pb-2 lg:pb-0 w-full">
                <button @click="activeTab = 'profile'" 
                        :class="activeTab === 'profile' ? 'bg-violet-500/10 text-violet-400 border-violet-500/20' : 'text-zinc-400 hover:bg-white/[0.03] hover:text-white border-transparent'"
                        class="flex items-center gap-3 px-5 py-3.5 font-bold text-sm rounded-2xl transition-all duration-200 cursor-pointer shrink-0 border">
                    <i data-lucide="user" class="w-4 h-4"></i> Profil Merek
                </button>
                <button @click="activeTab = 'business'" 
                        :class="activeTab === 'business' ? 'bg-violet-500/10 text-violet-400 border-violet-500/20' : 'text-zinc-400 hover:bg-white/[0.03] hover:text-white border-transparent'"
                        class="flex items-center gap-3 px-5 py-3.5 font-bold text-sm rounded-2xl transition-all duration-200 cursor-pointer shrink-0 border">
                    <i data-lucide="building-2" class="w-4 h-4"></i> Informasi Bisnis
                </button>
                <button @click="activeTab = 'social'" 
                        :class="activeTab === 'social' ? 'bg-violet-500/10 text-violet-400 border-violet-500/20' : 'text-zinc-400 hover:bg-white/[0.03] hover:text-white border-transparent'"
                        class="flex items-center gap-3 px-5 py-3.5 font-bold text-sm rounded-2xl transition-all duration-200 cursor-pointer shrink-0 border">
                    <i data-lucide="share-2" class="w-4 h-4"></i> Media Sosial
                </button>
                <button @click="activeTab = 'security'" 
                        :class="activeTab === 'security' ? 'bg-violet-500/10 text-violet-400 border-violet-500/20' : 'text-zinc-400 hover:bg-white/[0.03] hover:text-white border-transparent'"
                        class="flex items-center gap-3 px-5 py-3.5 font-bold text-sm rounded-2xl transition-all duration-200 cursor-pointer shrink-0 border">
                    <i data-lucide="key" class="w-4 h-4"></i> Keamanan
                </button>
            </div>
        </div>

        {{-- MAIN CONTENT AREA --}}
        <div class="flex-1 w-full min-w-0 space-y-6">
            
            {{-- TAB: PROFILE --}}
            <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <form @submit.prevent="submitForm($event, '{{ route('brand.settings.profile') }}')">
            <div class="bg-[#111111] border border-[#1f1f1f] rounded-2xl relative overflow-hidden p-6 lg:p-8">
                <div class="border-b border-white/10 pb-5 mb-6">
                    <h2 class="text-lg font-bold text-white mb-1">Profil Merek</h2>
                    <p class="text-xs text-slate-400">Pembaruan foto profil dan detail identitas yang dilihat kreator.</p>
                </div>

                <div class="flex flex-col lg:flex-row gap-8 items-start mb-8 w-full">
                    {{-- Avatar Section --}}
                    <div class="flex flex-col items-center gap-4 flex-shrink-0">
                        @php
                            $avatarUrl = $user->avatar 
                                ? (str_contains($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar))
                                : 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($user->name);
                        @endphp
                        <div class="w-24 h-24 lg:w-28 lg:h-28 rounded-2xl border-2 border-dashed border-violet-500/30 flex items-center justify-center p-1 relative group cursor-pointer overflow-hidden transition-all hover:border-violet-500/60">
                            <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="w-full h-full rounded-xl object-cover">
                            <!-- Overlay Unggah -->
                            <label for="avatar-upload" class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                <i data-lucide="camera" class="w-6 h-6 text-white"></i>
                            </label>
                            <input type="file" id="avatar-upload" accept="image/*" class="hidden" @change="
                                uploading = true;
                                const formData = new FormData();
                                formData.append('avatar', $event.target.files[0]);
                                fetch('{{ route('brand.settings.avatar') }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                                    body: formData
                                })
                                .then(res => res.json())
                                .then(data => {
                                    alert(data.message);
                                    if(data.success) location.reload();
                                    uploading = false;
                                })
                                .catch(err => { alert('Terjadi kesalahan'); uploading = false; });
                            ">
                        </div>
                        <div class="text-center">
                            <label for="avatar-upload" class="text-[11px] font-bold text-violet-400 hover:text-violet-300 cursor-pointer">Ubah Logo</label>
                            <p class="text-[9px] text-slate-500 mt-1">Format JPG, PNG (Maks. 2MB)</p>
                        </div>
                    </div>

                    {{-- Identity Form --}}
                    <div class="flex-1 w-full flex flex-col gap-5">
                        <div class="w-full">
                            <label class="block text-xs font-bold text-zinc-400 mb-2">Nama Merek</label>
                            <input type="text" name="name" value="{{ $user->name }}" required class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(139,92,246,0.15)]">
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-5 w-full">
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-zinc-400 mb-2">Email</label>
                                <input type="email" name="email" value="{{ $user->email }}" required class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(139,92,246,0.15)]">
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-zinc-400 mb-2">Nomor Telepon</label>
                                <input type="tel" name="phone" value="{{ $user->phone }}" placeholder="+62 812-3456-7890" class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(139,92,246,0.15)]">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/10 pt-6 flex justify-end gap-3">
                    <button type="button" class="bg-transparent border border-zinc-700 text-zinc-200 px-6 py-3 rounded-xl text-sm font-bold transition-all duration-200 hover:bg-white/5 hover:border-zinc-500 active:scale-95">Batal</button>
                    <button type="submit" :disabled="saving" class="bg-gradient-to-br from-violet-600 to-fuchsia-600 text-white px-6 py-3 rounded-xl text-sm font-extrabold flex items-center justify-center gap-2 transition-all duration-200 shadow-[0_8px_20px_rgba(124,58,237,0.25)] hover:shadow-[0_8px_30px_rgba(124,58,237,0.4)] hover:-translate-y-0.5 active:scale-95 border-none outline-none disabled:opacity-50">
                        <span x-show="!saving">Simpan Perubahan</span>
                        <span x-show="saving">Menyimpan...</span>
                    </button>
                </div>
            </div>
            </form>
            </div>

            {{-- TAB: BUSINESS INFO --}}
            <div x-show="activeTab === 'business'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" style="display: none;">
            <form @submit.prevent="submitForm($event, '{{ route('brand.settings.profile') }}')">
            <input type="hidden" name="name" value="{{ $user->name }}">
            <input type="hidden" name="email" value="{{ $user->email }}">
            <div class="bg-[#111111] border border-[#1f1f1f] rounded-2xl relative overflow-hidden p-6 lg:p-8">
                <div class="border-b border-white/10 pb-5 mb-6">
                    <h2 class="text-lg font-bold text-white mb-1">Informasi Bisnis</h2>
                    <p class="text-xs text-slate-400">Rincian perusahaan dan industri untuk profil brand Anda.</p>
                </div>

                <div class="space-y-5">
                    <div class="w-full">
                        <label class="block text-xs font-bold text-zinc-400 mb-2">Nama Perusahaan</label>
                        <input type="text" name="company_name" value="{{ $user->company_name }}" placeholder="PT. Merek Indonesia" class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(139,92,246,0.15)]">
                    </div>

                    <div class="flex flex-col sm:flex-row gap-5 w-full">
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-zinc-400 mb-2">Industri Kategori</label>
                            <select name="industry" class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(139,92,246,0.15)] appearance-none cursor-pointer">
                                <option value="">Pilih Industri</option>
                                <option value="Kecantikan & Kosmetik" {{ $user->industry == 'Kecantikan & Kosmetik' ? 'selected' : '' }}>Kecantikan & Kosmetik</option>
                                <option value="Teknologi" {{ $user->industry == 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                                <option value="Fashion & Apparel" {{ $user->industry == 'Fashion & Apparel' ? 'selected' : '' }}>Fashion & Apparel</option>
                                <option value="Makanan & Minuman" {{ $user->industry == 'Makanan & Minuman' ? 'selected' : '' }}>Makanan & Minuman</option>
                                <option value="E-commerce" {{ $user->industry == 'E-commerce' ? 'selected' : '' }}>E-commerce</option>
                                <option value="Gaming" {{ $user->industry == 'Gaming' ? 'selected' : '' }}>Gaming</option>
                                <option value="Lainnya" {{ $user->industry == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-zinc-400 mb-2">Website Resmi</label>
                            <input type="url" name="website" value="{{ $user->website }}" placeholder="https://www.brandanda.com" class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(139,92,246,0.15)]">
                        </div>
                    </div>

                    <div class="w-full">
                        <label class="block text-xs font-bold text-zinc-400 mb-2">Bio Singkat</label>
                        <textarea name="bio" class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(139,92,246,0.15)] min-h-[120px] resize-y" placeholder="Ceritakan singkat tentang brand Anda dan produk unggulan yang ditawarkan...">{{ $user->bio }}</textarea>
                        <p class="text-[10px] text-slate-500 mt-2">Maksimal 500 karakter</p>
                    </div>
                </div>

                <div class="border-t border-white/10 pt-6 mt-6 flex justify-end gap-3">
                    <button type="button" class="bg-transparent border border-zinc-700 text-zinc-200 px-6 py-3 rounded-xl text-sm font-bold transition-all duration-200 hover:bg-white/5 hover:border-zinc-500 active:scale-95">Batal</button>
                    <button type="submit" :disabled="saving" class="bg-gradient-to-br from-violet-600 to-fuchsia-600 text-white px-6 py-3 rounded-xl text-sm font-extrabold flex items-center justify-center gap-2 transition-all duration-200 shadow-[0_8px_20px_rgba(124,58,237,0.25)] hover:shadow-[0_8px_30px_rgba(124,58,237,0.4)] hover:-translate-y-0.5 active:scale-95 border-none outline-none disabled:opacity-50">
                        <span x-show="!saving">Simpan Perubahan</span>
                        <span x-show="saving">Menyimpan...</span>
                    </button>
                </div>
            </div>
            </form>
            </div>

            {{-- TAB: SOCIAL MEDIA --}}
            <div x-show="activeTab === 'social'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" style="display: none;">
            <form @submit.prevent="submitForm($event, '{{ route('brand.settings.profile') }}')">
            <input type="hidden" name="name" value="{{ $user->name }}">
            <input type="hidden" name="email" value="{{ $user->email }}">
            <div class="bg-[#111111] border border-[#1f1f1f] rounded-2xl relative overflow-hidden p-6 lg:p-8">
                <div class="border-b border-white/10 pb-5 mb-6">
                    <h2 class="text-lg font-bold text-white mb-1">Tautan Media Sosial</h2>
                    <p class="text-xs text-slate-400">Hubungkan profil sosial media brand agar kreator mudah menandai kontennya.</p>
                </div>

                <div class="space-y-5">
                    {{-- Instagram --}}
                    <div class="flex flex-col gap-3 p-5 rounded-xl border border-white/5 bg-white/[0.02]">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-yellow-500 via-pink-500 to-purple-500 flex items-center justify-center text-white flex-shrink-0">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-white mb-1">Instagram</p>
                                <p class="text-[10px] text-slate-400">{{ $user->instagram_url ? 'Terhubung' : 'Belum terhubung' }}</p>
                            </div>
                        </div>
                        <input type="url" name="instagram_url" value="{{ $user->instagram_url }}" placeholder="https://instagram.com/brandanda" class="bg-[#09090b] border border-zinc-800 rounded-xl px-4 py-3 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(139,92,246,0.15)]">
                    </div>

                    {{-- TikTok --}}
                    <div class="flex flex-col gap-3 p-5 rounded-xl border border-white/5 bg-white/[0.02]">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-black border border-white/10 flex items-center justify-center text-white flex-shrink-0">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-white mb-1">TikTok</p>
                                <p class="text-[10px] text-slate-400">{{ $user->tiktok_url ? 'Terhubung' : 'Belum terhubung' }}</p>
                            </div>
                        </div>
                        <input type="url" name="tiktok_url" value="{{ $user->tiktok_url }}" placeholder="https://tiktok.com/@brandanda" class="bg-[#09090b] border border-zinc-800 rounded-xl px-4 py-3 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(139,92,246,0.15)]">
                    </div>
                </div>

                <div class="border-t border-white/10 pt-6 mt-6 flex justify-end gap-3">
                    <button type="button" class="bg-transparent border border-zinc-700 text-zinc-200 px-6 py-3 rounded-xl text-sm font-bold transition-all duration-200 hover:bg-white/5 hover:border-zinc-500 active:scale-95">Batal</button>
                    <button type="submit" :disabled="saving" class="bg-gradient-to-br from-violet-600 to-fuchsia-600 text-white px-6 py-3 rounded-xl text-sm font-extrabold flex items-center justify-center gap-2 transition-all duration-200 shadow-[0_8px_20px_rgba(124,58,237,0.25)] hover:shadow-[0_8px_30px_rgba(124,58,237,0.4)] hover:-translate-y-0.5 active:scale-95 border-none outline-none disabled:opacity-50">
                        <span x-show="!saving">Simpan Perubahan</span>
                        <span x-show="saving">Menyimpan...</span>
                    </button>
                </div>
            </div>
            </form>
            </div>

            {{-- TAB: SECURITY --}}
            <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" style="display: none;">
            <form @submit.prevent="submitForm($event, '{{ route('brand.settings.password') }}')">
            <div class="bg-[#111111] border border-[#1f1f1f] rounded-2xl relative overflow-hidden p-6 lg:p-8">
                <div class="border-b border-white/10 pb-5 mb-6">
                    <h2 class="text-lg font-bold text-white mb-1">Keamanan Akun</h2>
                    <p class="text-xs text-slate-400">Ubah password dan kelola keamanan akun Anda.</p>
                </div>

                <div class="space-y-5">
                    <div class="w-full">
                        <label class="block text-xs font-bold text-zinc-400 mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" required class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(139,92,246,0.15)]" placeholder="Masukkan password saat ini">
                    </div>

                    <div class="w-full">
                        <label class="block text-xs font-bold text-zinc-400 mb-2">Password Baru</label>
                        <input type="password" name="new_password" required class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(139,92,246,0.15)]" placeholder="Minimal 8 karakter">
                        <p class="text-[10px] text-slate-500 mt-2">Gunakan kombinasi huruf, angka, dan simbol untuk keamanan lebih baik</p>
                    </div>

                    <div class="w-full">
                        <label class="block text-xs font-bold text-zinc-400 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" required class="bg-[#09090b] border border-zinc-800 rounded-2xl px-4 py-3.5 text-white w-full transition-all duration-200 outline-none text-sm font-medium focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(139,92,246,0.15)]" placeholder="Ketik ulang password baru">
                    </div>

                    {{-- Account Info --}}
                    <div class="bg-white/[0.02] border border-white/5 rounded-xl p-5 mt-6">
                        <h3 class="text-sm font-bold text-white mb-3">Informasi Akun</h3>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Email Terdaftar:</span>
                                <span class="text-white font-medium">{{ $user->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Tipe Akun:</span>
                                <span class="text-violet-400 font-bold uppercase">{{ $user->role }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Bergabung Sejak:</span>
                                <span class="text-white font-medium">{{ $user->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/10 pt-6 mt-6 flex justify-end gap-3">
                    <button type="button" class="bg-transparent border border-zinc-700 text-zinc-200 px-6 py-3 rounded-xl text-sm font-bold transition-all duration-200 hover:bg-white/5 hover:border-zinc-500 active:scale-95">Batal</button>
                    <button type="submit" :disabled="saving" class="bg-gradient-to-br from-violet-600 to-fuchsia-600 text-white px-6 py-3 rounded-xl text-sm font-extrabold flex items-center justify-center gap-2 transition-all duration-200 shadow-[0_8px_20px_rgba(124,58,237,0.25)] hover:shadow-[0_8px_30px_rgba(124,58,237,0.4)] hover:-translate-y-0.5 active:scale-95 border-none outline-none disabled:opacity-50">
                        <span x-show="!saving">Ubah Password</span>
                        <span x-show="saving">Mengubah...</span>
                    </button>
                </div>
            </div>
            </form>
            </div>

        </div>
    </div>

</div>
@endsection
