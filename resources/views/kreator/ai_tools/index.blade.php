@extends('layouts.kreator')

@section('title', 'AI Auto-Clipper')

@push('styles')
<style>
@keyframes orbFloat {
    0%, 100% { transform: translate(0,0) scale(1); }
    50% { transform: translate(20px,15px) scale(1.05); }
}
.animate-orb { animation: orbFloat 8s ease-in-out infinite; }
.animate-orb-delay { animation: orbFloat 8s ease-in-out infinite 3s; }

@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}
.shimmer {
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
    background-size: 1000px 100%;
    animation: shimmer 2s infinite;
}
</style>
@endpush

@section('content')
<div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
    <div class="absolute rounded-full blur-[80px] opacity-10 animate-orb w-[400px] h-[400px] bg-violet-600 -top-[100px] -left-[100px]"></div>
    <div class="absolute rounded-full blur-[80px] opacity-10 animate-orb-delay w-[300px] h-[300px] bg-fuchsia-500 -bottom-[100px] -right-[50px]"></div>
</div>

<div class="relative z-10 max-w-6xl mx-auto space-y-6 pb-12 px-4" x-data="aiClipper()" x-init="init()">

    {{-- HEADER --}}
    <div class="text-center space-y-3 pt-6">
        {{-- <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-violet-500/10 shadow-[0_0_0_1px_rgba(139,92,246,0.25)]">
            <i data-lucide="sparkles" class="w-4 h-4 text-violet-400"></i>
            <span class="text-xs font-bold text-violet-300 uppercase tracking-wider">AI-Powered Video Clipper</span>
        </div>
        <h1 class="text-3xl font-black text-white">Potong Video Otomatis dengan AI</h1> --}}
        <p class="text-sm text-slate-400 max-w-2xl mx-auto">Paste link video dari YouTube, TikTok, atau platform lainnya. AI akan menganalisis dan memotong bagian terbaik secara otomatis.</p>
    </div>

    {{-- TAB NAVIGATION --}}
    <div class="flex items-center justify-center gap-2 max-w-md mx-auto">
        <button @click="activeTab = 'generate'" 
                class="flex-1 py-3 px-4 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2"
                :class="activeTab === 'generate' ? 'bg-violet-500/20 text-violet-300 shadow-[0_0_0_1px_rgba(139,92,246,0.5)]' : 'text-slate-500 hover:text-slate-300 hover:bg-white/5'">
            <i data-lucide="wand-2" class="w-4 h-4"></i>
            Generate Clips
        </button>
        <button @click="activeTab = 'history'" 
                class="flex-1 py-3 px-4 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2"
                :class="activeTab === 'history' ? 'bg-violet-500/20 text-violet-300 shadow-[0_0_0_1px_rgba(139,92,246,0.5)]' : 'text-slate-500 hover:text-slate-300 hover:bg-white/5'">
            <i data-lucide="history" class="w-4 h-4"></i>
            Riwayat
        </button>
    </div>

    {{-- STEPS INDICATOR (hanya tampil di tab Generate) --}}
    <div class="flex items-center justify-center gap-2 max-w-4xl mx-auto" x-show="activeTab === 'generate'">
        <template x-for="(stepInfo, idx) in steps" :key="idx">
            <div class="flex items-center gap-2">
                <div class="flex flex-col items-center gap-2 transition-all duration-300" :class="currentStep >= idx + 1 ? 'opacity-100' : 'opacity-40'">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-black transition-all duration-300"
                         :class="currentStep >= idx + 1 ? 'bg-violet-500 text-white shadow-lg shadow-violet-500/50' : 'bg-violet-500/10 text-violet-400'">
                        <span x-text="idx + 1"></span>
                    </div>
                    <div class="text-center">
                        <i :data-lucide="stepInfo.icon" class="w-4 h-4 mx-auto mb-1"></i>
                        <p class="text-[10px] font-semibold text-slate-500" x-text="stepInfo.label"></p>
                    </div>
                </div>
                <div x-show="idx < steps.length - 1" class="w-16 h-0.5 bg-gradient-to-r from-violet-500/20 to-transparent mb-8"></div>
            </div>
        </template>
    </div>

    {{-- MAIN CARD --}}
    <div class="bg-[#0f0f0f] shadow-[0_0_0_1px_rgba(255,255,255,0.05)] rounded-2xl overflow-hidden" x-show="activeTab === 'generate'">
        
        {{-- STEP 1: PASTE URL --}}
        <div class="p-6 border-b border-white/5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-bold text-white flex items-center gap-2">
                    <i data-lucide="link" class="w-4 h-4 text-violet-400"></i>
                    Paste Link Video
                </h2>
                <span x-show="videoInfo" class="text-xs px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 font-semibold" style="display:none">
                    <i data-lucide="check-circle-2" class="w-3 h-3 inline"></i> Video Terdeteksi
                </span>
            </div>

            <div class="relative">
                <input x-model="videoUrl" 
                       @input="debounceLoadVideo()" 
                       type="text" 
                       placeholder="https://www.youtube.com/watch?v=... atau https://www.tiktok.com/@..."
                       class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-violet-500/50 focus:ring-2 focus:ring-violet-500/20 transition-all"
                       :disabled="isProcessing">
                <div x-show="isLoadingVideo" class="absolute right-3 top-1/2 -translate-y-1/2" style="display:none">
                    <svg class="animate-spin h-5 w-5 text-violet-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            {{-- VIDEO PREVIEW --}}
            <div x-show="videoInfo" x-transition class="mt-4 bg-black/40 rounded-xl overflow-hidden border border-white/10" style="display:none">
                <div class="grid md:grid-cols-[280px_1fr] gap-4 p-4">
                    <div class="relative aspect-video bg-black rounded-lg overflow-hidden">
                        <img :src="videoInfo?.thumbnail" :alt="videoInfo?.title" class="w-full h-full object-cover">
                        <div class="absolute bottom-2 right-2 px-2 py-1 bg-black/90 rounded text-xs font-bold text-white">
                            <span x-text="videoInfo?.duration"></span>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center space-y-2">
                        <h3 class="text-sm font-bold text-white line-clamp-2" x-text="videoInfo?.title"></h3>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
                            <span class="flex items-center gap-1">
                                <i data-lucide="user" class="w-3 h-3"></i>
                                <span x-text="videoInfo?.channel || 'Unknown'"></span>
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                <span x-text="videoInfo?.duration"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STEP 2: SETTINGS --}}
        <div x-show="videoInfo" class="p-6 space-y-6" style="display:none">
            <h2 class="text-sm font-bold text-white flex items-center gap-2 mb-4">
                <i data-lucide="settings" class="w-4 h-4 text-fuchsia-400"></i>
                Pengaturan Clip
            </h2>

            <div class="grid md:grid-cols-2 gap-6">
                
                {{-- ORIENTASI --}}
                <div class="space-y-3">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="smartphone" class="w-3.5 h-3.5"></i>
                        Orientasi Video
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" 
                                @click="settings.orientation = 'vertical'"
                                class="p-4 rounded-xl border transition-all duration-200 flex flex-col items-center gap-2"
                                :class="settings.orientation === 'vertical' ? 'bg-violet-500/10 border-violet-500/50 text-violet-400' : 'bg-black/40 border-white/10 text-slate-500 hover:border-white/20'">
                            <i data-lucide="smartphone" class="w-6 h-6"></i>
                            <span class="text-xs font-semibold">Vertikal (9:16)</span>
                            <span class="text-[10px] text-slate-600">TikTok, Reels, Shorts</span>
                        </button>
                        <button type="button" 
                                @click="settings.orientation = 'horizontal'"
                                class="p-4 rounded-xl border transition-all duration-200 flex flex-col items-center gap-2"
                                :class="settings.orientation === 'horizontal' ? 'bg-violet-500/10 border-violet-500/50 text-violet-400' : 'bg-black/40 border-white/10 text-slate-500 hover:border-white/20'">
                            <i data-lucide="monitor" class="w-6 h-6"></i>
                            <span class="text-xs font-semibold">Horizontal (16:9)</span>
                            <span class="text-[10px] text-slate-600">YouTube, Facebook</span>
                        </button>
                    </div>
                </div>

                {{-- KUALITAS VIDEO --}}
                <div class="space-y-3">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                        Kualitas Video
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" 
                                @click="settings.quality = 'sd'"
                                class="py-3 px-2 rounded-lg border text-xs font-semibold transition-all flex flex-col items-center gap-1"
                                :class="settings.quality === 'sd' ? 'bg-violet-500/10 border-violet-500/50 text-violet-400' : 'bg-black/40 border-white/10 text-slate-500 hover:border-white/20'">
                            <span>SD</span>
                            <span class="text-[9px] text-slate-600">480p</span>
                        </button>
                        <button type="button" 
                                @click="settings.quality = 'hd'"
                                class="py-3 px-2 rounded-lg border text-xs font-semibold transition-all flex flex-col items-center gap-1"
                                :class="settings.quality === 'hd' ? 'bg-violet-500/10 border-violet-500/50 text-violet-400' : 'bg-black/40 border-white/10 text-slate-500 hover:border-white/20'">
                            <span>HD</span>
                            <span class="text-[9px] text-slate-600">720p</span>
                        </button>
                        <button type="button" 
                                @click="settings.quality = 'fhd'"
                                class="py-3 px-2 rounded-lg border text-xs font-semibold transition-all flex flex-col items-center gap-1"
                                :class="settings.quality === 'fhd' ? 'bg-violet-500/10 border-violet-500/50 text-violet-400' : 'bg-black/40 border-white/10 text-slate-500 hover:border-white/20'">
                            <span>Full HD</span>
                            <span class="text-[9px] text-slate-600">1080p</span>
                        </button>
                    </div>
                </div>

                {{-- DURASI CLIP --}}
                <div class="space-y-3">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                        Durasi Per Clip
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" 
                                @click="settings.clipDuration = 30"
                                class="py-3 px-2 rounded-lg border text-xs font-semibold transition-all"
                                :class="settings.clipDuration === 30 ? 'bg-violet-500/10 border-violet-500/50 text-violet-400' : 'bg-black/40 border-white/10 text-slate-500 hover:border-white/20'">
                            30 detik
                        </button>
                        <button type="button" 
                                @click="settings.clipDuration = 60"
                                class="py-3 px-2 rounded-lg border text-xs font-semibold transition-all"
                                :class="settings.clipDuration === 60 ? 'bg-violet-500/10 border-violet-500/50 text-violet-400' : 'bg-black/40 border-white/10 text-slate-500 hover:border-white/20'">
                            60 detik
                        </button>
                        <button type="button" 
                                @click="settings.clipDuration = 90"
                                class="py-3 px-2 rounded-lg border text-xs font-semibold transition-all"
                                :class="settings.clipDuration === 90 ? 'bg-violet-500/10 border-violet-500/50 text-violet-400' : 'bg-black/40 border-white/10 text-slate-500 hover:border-white/20'">
                            90 detik
                        </button>
                    </div>
                </div>

                {{-- JUMLAH CLIP --}}
                <div class="space-y-3">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="scissors" class="w-3.5 h-3.5"></i>
                        Jumlah Clip yang Dihasilkan
                    </label>
                    <div class="grid grid-cols-5 gap-2">
                        <template x-for="num in [1,2,3,4,5]" :key="num">
                            <button type="button" 
                                    @click="settings.clipCount = num"
                                    class="py-3 rounded-lg border text-sm font-bold transition-all"
                                    :class="settings.clipCount === num ? 'bg-violet-500/10 border-violet-500/50 text-violet-400' : 'bg-black/40 border-white/10 text-slate-500 hover:border-white/20'"
                                    x-text="num"></button>
                        </template>
                    </div>
                </div>

                {{-- AUTO CAPTIONS --}}
                <div class="space-y-3">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="type" class="w-3.5 h-3.5"></i>
                        Subtitle Otomatis
                    </label>
                    <div class="bg-black/40 border border-white/10 rounded-xl p-4 flex items-center justify-between cursor-pointer hover:border-white/20 transition-all"
                         @click="settings.autoCaptions = !settings.autoCaptions">
                        <div>
                            <p class="text-sm font-semibold text-white">AI Auto-Captions</p>
                            <p class="text-xs text-slate-500">Generate subtitle dari audio</p>
                        </div>
                        <div class="relative w-12 h-6 rounded-full transition-colors duration-300"
                             :class="settings.autoCaptions ? 'bg-violet-500' : 'bg-slate-700'">
                            <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform duration-300"
                                 :class="settings.autoCaptions ? 'translate-x-6' : ''"></div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- GENERATE BUTTON --}}
            <div class="pt-4 border-t border-white/5">
                <button @click="generateClips()" 
                        :disabled="isProcessing || !videoInfo"
                        class="w-full py-4 rounded-xl font-bold text-sm uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-2"
                        :class="isProcessing ? 'bg-slate-800 text-slate-600 cursor-not-allowed' : 'bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white hover:shadow-lg hover:shadow-violet-500/50 hover:-translate-y-0.5'">
                    <template x-if="!isProcessing">
                        <span class="flex items-center gap-2">
                            <i data-lucide="wand-2" class="w-5 h-5"></i>
                            Generate Clips dengan AI
                        </span>
                    </template>
                    <template x-if="isProcessing">
                        <span class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="processingMessage"></span>
                        </span>
                    </template>
                </button>
                
                {{-- Info saat processing --}}
                <div x-show="isProcessing && processingClips.length === 0" class="mt-3 text-center text-xs text-slate-500" style="display:none">
                    <i data-lucide="loader" class="w-4 h-4 inline animate-spin"></i>
                    Mohon tunggu, AI sedang menganalisis video...
                </div>
            </div>
        </div>

    </div>

    {{-- PROCESSING QUEUE --}}
    <div x-show="processingClips.length > 0" class="space-y-4" style="display:none">
        <div class="flex items-center gap-3">
            <h2 class="text-sm font-bold text-white">Sedang Diproses</h2>
            <span class="text-xs px-3 py-1 rounded-full bg-amber-500/10 text-amber-400 font-semibold">
                <span x-text="processingClips.length"></span> clip
            </span>
        </div>
        <template x-for="clip in processingClips" :key="clip.id">
            <div class="bg-[#0f0f0f] border border-white/5 rounded-xl p-5 space-y-4">
                {{-- Header --}}
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-white truncate" x-text="clip.title"></h3>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-slate-500" x-text="clip.duration"></span>
                            <span class="text-xs text-slate-600">•</span>
                            <span class="text-xs text-slate-500" x-text="formatTime(clip.start_time) + ' - ' + formatTime(clip.end_time)"></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 ml-4">
                        <div class="w-8 h-8 rounded-full border-2 border-violet-500/20 border-t-violet-500 animate-spin"></div>
                        <span class="text-xs px-2.5 py-1 rounded-lg bg-amber-500/10 text-amber-400 font-semibold uppercase whitespace-nowrap"
                              x-text="getStatusText(clip.status)"></span>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-400" x-text="getProgressText(clip.status)"></span>
                        <span class="text-slate-500" x-text="getEstimatedTime(clip.status, clip.elapsed_time)"></span>
                    </div>
                    <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-violet-500 to-fuchsia-500 rounded-full transition-all duration-500 ease-out"
                             :style="'width: ' + getProgressPercentage(clip.status) + '%'"></div>
                    </div>
                </div>

                {{-- Status Details --}}
                <div class="flex items-center gap-4 text-xs text-slate-600">
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="brain-circuit" class="w-3.5 h-3.5" :class="clip.status === 'analyzing' ? 'text-violet-400' : 'text-slate-600'"></i>
                        <span :class="clip.status === 'analyzing' ? 'text-violet-400 font-semibold' : ''">AI Analisis</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="download" class="w-3.5 h-3.5" :class="clip.status === 'downloading' ? 'text-blue-400' : 'text-slate-600'"></i>
                        <span :class="clip.status === 'downloading' ? 'text-blue-400 font-semibold' : ''">Download</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="scissors" class="w-3.5 h-3.5" :class="clip.status === 'processing' ? 'text-orange-400' : 'text-slate-600'"></i>
                        <span :class="clip.status === 'processing' ? 'text-orange-400 font-semibold' : ''">Potong Video</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="upload" class="w-3.5 h-3.5" :class="clip.status === 'uploading' ? 'text-green-400' : 'text-slate-600'"></i>
                        <span :class="clip.status === 'uploading' ? 'text-green-400 font-semibold' : ''">Upload</span>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- COMPLETED CLIPS --}}
    <div x-show="completedClips.length > 0" class="space-y-4" style="display:none">
        <div class="flex items-center gap-3">
            <h2 class="text-sm font-bold text-white">Clip Siap Diunduh</h2>
            <span class="text-xs px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 font-semibold">
                <span x-text="completedClips.length"></span> clip
            </span>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            <template x-for="clip in completedClips" :key="clip.id">
                <div class="bg-[#0f0f0f] border border-white/5 rounded-xl overflow-hidden hover:border-violet-500/30 transition-all hover:-translate-y-1">
                    <div class="relative aspect-video bg-black">
                        <video :src="clip.url" class="w-full h-full object-cover" controls></video>
                    </div>
                    <div class="p-4 space-y-3">
                        <h3 class="text-sm font-semibold text-white line-clamp-2" x-text="clip.title"></h3>
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <div class="flex items-center gap-3">
                                <span class="flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    <span x-text="clip.duration"></span>
                                </span>
                                <span class="flex items-center gap-1">
                                    <i data-lucide="monitor" class="w-3 h-3"></i>
                                    <span x-text="getQualityLabel(clip.quality)"></span>
                                </span>
                            </div>
                            <span class="flex items-center gap-1">
                                <i data-lucide="flame" class="w-3 h-3 text-orange-400"></i>
                                <span x-text="clip.score + '/100'"></span>
                            </span>
                        </div>
                        <a :href="clip.url" 
                           :download="clip.title + '.mp4'"
                           class="block w-full py-2 bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold rounded-lg text-center transition-all">
                            <i data-lucide="download" class="w-3 h-3 inline"></i> Unduh HD
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- EMPTY STATE (hanya di tab Generate) --}}
    <div x-show="activeTab === 'generate' && !videoInfo && !isProcessing && completedClips.length === 0" class="text-center py-16">
        <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-[#0f0f0f] border border-white/5 flex items-center justify-center">
            <i data-lucide="video" class="w-10 h-10 text-slate-700"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-500 mb-2">Belum Ada Video</h3>
        <p class="text-sm text-slate-600 mb-6">Paste link video di atas untuk memulai</p>
        <div class="flex flex-wrap justify-center gap-2">
            <span class="px-3 py-1.5 rounded-lg bg-[#0f0f0f] border border-white/5 text-xs text-slate-500">Podcast</span>
            <span class="px-3 py-1.5 rounded-lg bg-[#0f0f0f] border border-white/5 text-xs text-slate-500">Tutorial</span>
            <span class="px-3 py-1.5 rounded-lg bg-[#0f0f0f] border border-white/5 text-xs text-slate-500">Review Produk</span>
            <span class="px-3 py-1.5 rounded-lg bg-[#0f0f0f] border border-white/5 text-xs text-slate-500">Vlog</span>
        </div>
    </div>

    {{-- ERROR MESSAGE --}}
    <div x-show="errorMessage" x-transition class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 flex items-start gap-3" style="display:none">
        <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5"></i>
        <div class="flex-1">
            <p class="text-sm font-semibold text-red-400">Error</p>
            <p class="text-xs text-red-300/80" x-text="errorMessage"></p>
        </div>
        <button @click="errorMessage = ''" class="text-red-400 hover:text-red-300">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>

    {{-- HISTORY TAB --}}
    <div x-show="activeTab === 'history'" class="space-y-6" style="display:none">
        
        

        {{-- HISTORY FILTERS --}}
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <select x-model="historyFilter" @change="loadHistory()" 
                        class="bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-violet-500/50">
                    <option value="all">Semua Clip</option>
                    <option value="today">Hari Ini</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month">Bulan Ini</option>
                </select>
                <button @click="loadHistory()" 
                        class="px-3 py-2 bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold rounded-lg transition-all flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    Refresh
                </button>
            </div>
            <div class="text-xs text-slate-500">
                Total: <span x-text="historyClips.length"></span> clip
            </div>
        </div>

        {{-- HISTORY LOADING --}}
        <div x-show="isLoadingHistory" class="text-center py-8" style="display:none">
            <div class="inline-flex items-center gap-2 text-slate-500">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memuat riwayat...
            </div>
        </div>

        {{-- HISTORY CLIPS GRID --}}
        <div x-show="!isLoadingHistory && historyClips.length > 0" class="space-y-6" style="display:none">
            <template x-for="group in groupedHistory" :key="group.date">
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <span x-text="group.date"></span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-violet-500/10 text-violet-400" x-text="group.clips.length + ' clip'"></span>
                    </h3>
                    
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <template x-for="clip in group.clips" :key="clip.id">
                            <div class="bg-[#0f0f0f] border border-white/5 rounded-xl overflow-hidden hover:border-violet-500/30 transition-all hover:-translate-y-1">
                                <div class="relative aspect-video bg-black">
                                    <video :src="clip.file_url" class="w-full h-full object-cover" controls preload="metadata"></video>
                                    <div class="absolute top-2 right-2 px-2 py-1 bg-black/80 rounded text-xs font-bold text-white">
                                        <span x-text="clip.duration"></span>
                                    </div>
                                </div>
                                <div class="p-4 space-y-3">
                                    <h4 class="text-sm font-semibold text-white line-clamp-2" x-text="clip.title"></h4>
                                    
                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <div class="flex items-center gap-3">
                                            <span class="flex items-center gap-1">
                                                <i data-lucide="clock" class="w-3 h-3"></i>
                                                <span x-text="clip.duration"></span>
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <i data-lucide="monitor" class="w-3 h-3"></i>
                                                <span x-text="getQualityLabel(clip.quality)"></span>
                                            </span>
                                        </div>
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="flame" class="w-3 h-3 text-orange-400"></i>
                                            <span x-text="clip.score + '/100'"></span>
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2 text-xs text-slate-600">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        <span x-text="formatDate(clip.created_at)"></span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <a :href="clip.file_url" 
                                           :download="clip.title + '.mp4'"
                                           class="flex-1 py-2 bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold rounded-lg text-center transition-all flex items-center justify-center gap-1">
                                            <i data-lucide="download" class="w-3 h-3"></i> Unduh
                                        </a>
                                        <button @click="deleteClip(clip.id)" 
                                                class="px-3 py-2 bg-red-600/20 hover:bg-red-600/30 text-red-400 text-xs font-bold rounded-lg transition-all">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        {{-- HISTORY EMPTY STATE --}}
        <div x-show="!isLoadingHistory && historyClips.length === 0" class="text-center py-16" style="display:none">
            <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-[#0f0f0f] border border-white/5 flex items-center justify-center">
                <i data-lucide="history" class="w-10 h-10 text-slate-700"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-500 mb-2">Belum Ada Riwayat</h3>
            <p class="text-sm text-slate-600 mb-6">Generate clip pertama Anda untuk melihat riwayat</p>
            <button @click="activeTab = 'generate'" 
                    class="px-6 py-3 bg-violet-600 hover:bg-violet-500 text-white text-sm font-bold rounded-lg transition-all">
                Generate Clip Sekarang
            </button>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
function aiClipper() {
    return {
        // State
        videoUrl: '',
        videoInfo: null,
        isLoadingVideo: false,
        isProcessing: false,
        processingMessage: 'Memproses...',
        errorMessage: '',
        currentStep: 1,
        activeTab: 'generate', // 'generate' atau 'history'
        
        // Settings
        settings: {
            orientation: 'vertical',
            clipDuration: 60,
            clipCount: 3,
            autoCaptions: true,
            quality: 'fhd' // Default Full HD
        },
        
        // Clips
        processingClips: [],
        completedClips: [],
        
        // History
        historyClips: [],
        historyFilter: 'all',
        isLoadingHistory: false,
        
        // Steps
        steps: [
            { icon: 'link', label: 'Paste Link' },
            { icon: 'settings', label: 'Atur Setting' },
            { icon: 'brain-circuit', label: 'AI Proses' },
            { icon: 'download', label: 'Unduh' }
        ],
        
        debounceTimer: null,
        
        init() {
            console.log('AI Clipper initialized');
            // Initialize Lucide icons
            setTimeout(() => {
                lucide.createIcons();
                console.log('Lucide icons created');
            }, 100);
            
            // Load history when page loads
            this.loadHistory();
        },
        
        debounceLoadVideo() {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                if (this.videoUrl.trim()) {
                    this.loadVideoInfo();
                } else {
                    this.videoInfo = null;
                    this.currentStep = 1;
                }
            }, 800);
        },
        
        async loadVideoInfo() {
            if (!this.videoUrl.trim()) return;
            
            this.isLoadingVideo = true;
            this.errorMessage = '';
            
            console.log('Loading video info for:', this.videoUrl);
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
                
                const response = await fetch('/kreator/ai-tools/video-info', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ url: this.videoUrl })
                });
                
                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);
                
                if (data.success) {
                    this.videoInfo = data.video;
                    this.currentStep = 2;
                    setTimeout(() => lucide.createIcons(), 100);
                } else {
                    this.errorMessage = data.message || 'Gagal memuat info video';
                    this.videoInfo = null;
                }
            } catch (error) {
                console.error('Error loading video:', error);
                this.errorMessage = 'Terjadi kesalahan saat memuat video: ' + error.message;
                this.videoInfo = null;
            } finally {
                this.isLoadingVideo = false;
            }
        },
        
        async generateClips() {
            if (!this.videoInfo) return;
            
            this.isProcessing = true;
            this.currentStep = 3;
            this.errorMessage = '';
            this.processingMessage = 'AI sedang menganalisis video...';
            
            console.log('Starting clip generation...');
            
            try {
                const response = await fetch('/kreator/ai-tools/generate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        url: this.videoUrl,
                        settings: this.settings
                    })
                });
                
                const data = await response.json();
                console.log('Generate response:', data);
                
                if (data.success) {
                    this.processingClips = data.clips;
                    console.log('Processing clips:', this.processingClips);
                    
                    // Force UI update
                    this.$nextTick(() => {
                        lucide.createIcons();
                    });
                    
                    // Start polling immediately
                    this.pollClipsStatus();
                } else {
                    this.errorMessage = data.message || 'Gagal generate clips';
                    this.isProcessing = false;
                }
            } catch (error) {
                console.error('Error generating clips:', error);
                this.errorMessage = 'Terjadi kesalahan saat generate clips: ' + error.message;
                this.isProcessing = false;
            }
        },
        
        pollClipsStatus() {
            console.log('Starting polling for', this.processingClips.length, 'clips');
            
            const interval = setInterval(async () => {
                if (this.processingClips.length === 0) {
                    console.log('All clips processed, stopping poll');
                    clearInterval(interval);
                    this.isProcessing = false;
                    this.currentStep = 4;
                    return;
                }
                
                console.log('Polling status for clips:', this.processingClips.map(c => c.id));
                
                try {
                    const response = await fetch('/kreator/ai-tools/clips-status', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            clipIds: this.processingClips.map(c => c.id)
                        })
                    });
                    
                    const data = await response.json();
                    console.log('Status response:', data);
                    
                    if (data.success) {
                        data.clips.forEach(clipData => {
                            console.log('Clip', clipData.id, 'status:', clipData.status);
                            
                            if (clipData.status === 'completed') {
                                this.processingClips = this.processingClips.filter(c => c.id !== clipData.id);
                                this.completedClips.push(clipData);
                                setTimeout(() => lucide.createIcons(), 100);
                            } else if (clipData.status === 'failed') {
                                this.processingClips = this.processingClips.filter(c => c.id !== clipData.id);
                                this.errorMessage = `Clip "${clipData.title}" gagal diproses`;
                            } else {
                                // Update progress
                                const found = this.processingClips.find(c => c.id === clipData.id);
                                if (found) {
                                    found.status = clipData.status;
                                    found.elapsed_time = clipData.elapsed_time || 0;
                                    found.progress = clipData.progress || 0;
                                }
                            }
                        });
                    }
                } catch (error) {
                    console.error('Error polling clips:', error);
                }
            }, 2000); // Poll setiap 2 detik
        },

        // Helper functions for progress display
        formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        },

        getStatusText(status) {
            const statusMap = {
                'queued': 'Antrian',
                'analyzing': 'Analisis AI',
                'downloading': 'Download',
                'processing': 'Memotong',
                'uploading': 'Upload',
                'completed': 'Selesai',
                'failed': 'Gagal'
            };
            return statusMap[status] || 'Proses';
        },

        getProgressText(status) {
            const textMap = {
                'queued': 'Menunggu dalam antrian...',
                'analyzing': 'AI sedang menganalisis momen terbaik...',
                'downloading': 'Mengunduh video dari YouTube...',
                'processing': 'FFmpeg sedang memotong video...',
                'uploading': 'Menyimpan hasil clip...',
                'completed': 'Clip berhasil dibuat!',
                'failed': 'Terjadi kesalahan dalam proses'
            };
            return textMap[status] || 'Sedang diproses...';
        },

        getProgressPercentage(status) {
            const progressMap = {
                'queued': 5,
                'analyzing': 20,
                'downloading': 50,
                'processing': 80,
                'uploading': 95,
                'completed': 100,
                'failed': 0
            };
            return progressMap[status] || 10;
        },

        getEstimatedTime(status, elapsedTime = 0) {
            const estimateMap = {
                'queued': '~2-3 menit',
                'analyzing': '~30 detik',
                'downloading': '~1-2 menit',
                'processing': '~30-60 detik',
                'uploading': '~10 detik',
                'completed': 'Selesai',
                'failed': 'Gagal'
            };
            
            if (elapsedTime > 0) {
                const elapsed = Math.floor(elapsedTime / 1000);
                return `${Math.floor(elapsed / 60)}:${(elapsed % 60).toString().padStart(2, '0')} berlalu`;
            }
            
            return estimateMap[status] || '~1-2 menit';
        },

        // Helper function untuk quality label
        getQualityLabel(quality) {
            const qualityMap = {
                'sd': '480p',
                'hd': '720p HD',
                'fhd': '1080p Full HD'
            };
            return qualityMap[quality] || '1080p Full HD';
        },

        // History functions
        async loadHistory() {
            this.isLoadingHistory = true;
            
            try {
                const response = await fetch('/kreator/ai-tools/history', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        filter: this.historyFilter
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.historyClips = data.clips;
                    setTimeout(() => lucide.createIcons(), 100);
                } else {
                    this.errorMessage = data.message || 'Gagal memuat riwayat';
                }
            } catch (error) {
                console.error('Error loading history:', error);
                this.errorMessage = 'Terjadi kesalahan saat memuat riwayat';
            } finally {
                this.isLoadingHistory = false;
            }
        },

        async deleteClip(clipId) {
            if (!confirm('Yakin ingin menghapus clip ini?')) return;
            
            try {
                const response = await fetch(`/kreator/ai-tools/clip/${clipId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.historyClips = this.historyClips.filter(c => c.id !== clipId);
                } else {
                    this.errorMessage = data.message || 'Gagal menghapus clip';
                }
            } catch (error) {
                console.error('Error deleting clip:', error);
                this.errorMessage = 'Terjadi kesalahan saat menghapus clip';
            }
        },

        // Computed properties
        get groupedHistory() {
            const groups = {};
            
            this.historyClips.forEach(clip => {
                const date = this.formatDateGroup(clip.created_at);
                if (!groups[date]) {
                    groups[date] = [];
                }
                groups[date].push(clip);
            });
            
            return Object.keys(groups).map(date => ({
                date,
                clips: groups[date]
            }));
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        formatDateGroup(dateString) {
            const date = new Date(dateString);
            const today = new Date();
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            
            if (date.toDateString() === today.toDateString()) {
                return 'Hari Ini';
            } else if (date.toDateString() === yesterday.toDateString()) {
                return 'Kemarin';
            } else {
                return date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            }
        }
    }
}
</script>
@endpush
