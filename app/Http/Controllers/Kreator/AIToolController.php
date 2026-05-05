<?php

namespace App\Http\Controllers\Kreator;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessVideoClip;
use App\Models\Clip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AIToolController extends Controller
{
    /**
     * Tampilkan halaman AI Auto-Clipper
     */
    public function index()
    {
        return view('kreator.ai_tools.index');
    }

    /**
     * Get video info dari URL (YouTube, TikTok, dll)
     */
    public function getVideoInfo(Request $request)
    {
        try {
            $request->validate([
                'url' => 'required|url'
            ]);

            $url = $request->url;
            $videoInfo = null;

            \Log::info('Getting video info for: ' . $url);

            // Deteksi platform
            if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
                $videoInfo = $this->getYouTubeInfo($url);
            } elseif (str_contains($url, 'tiktok.com')) {
                $videoInfo = $this->getTikTokInfo($url);
            } else {
                // Generic video info
                $videoInfo = $this->getGenericVideoInfo($url);
            }

            if (!$videoInfo) {
                \Log::error('Failed to get video info for: ' . $url);
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat memuat informasi video. Pastikan link valid.'
                ], 400);
            }

            \Log::info('Video info retrieved successfully', $videoInfo);

            return response()->json([
                'success' => true,
                'video' => $videoInfo
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'URL tidak valid'
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error getting video info: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate clips dengan AI
     */
    public function generate(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'settings' => 'required|array',
            'settings.orientation' => 'required|in:vertical,horizontal',
            'settings.clipDuration' => 'required|integer|min:15|max:180',
            'settings.clipCount' => 'required|integer|min:1|max:5',
            'settings.autoCaptions' => 'required|boolean',
            'settings.quality' => 'required|in:sd,hd,fhd',
        ]);

        try {
            $url = $request->url;
            $settings = $request->settings;

            // Get video transcript untuk AI analysis (hanya jika video pendek)
            $transcript = null;
            if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
                $transcript = $this->getVideoTranscript($url);
            }
            
            // Call AI untuk analyze dan tentukan timestamp terbaik
            $clipConcepts = $this->analyzeWithAI($url, $transcript, $settings);

            if (empty($clipConcepts)) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI tidak dapat menghasilkan clip. Coba video lain.'
                ], 400);
            }

            // Create clip records dan dispatch jobs
            $clips = [];
            foreach ($clipConcepts as $concept) {
                $clip = Clip::create([
                    'user_id' => auth()->id(),
                    'title' => $concept['title'],
                    'hook' => $concept['hook'] ?? null,
                    'source_url' => $url,
                    'video_id' => $this->extractVideoId($url),
                    'ratio' => $settings['orientation'] === 'vertical' ? '9:16' : '16:9',
                    'quality' => $settings['quality'],
                    'has_captions' => $settings['autoCaptions'],
                    'start_time' => $concept['start_time'],
                    'end_time' => $concept['end_time'],
                    'duration' => $this->formatClipDuration($concept['start_time'], $concept['end_time']),
                    'score' => $concept['score'] ?? 85,
                    'status' => 'queued',
                ]);

                // Dispatch job untuk process video
                ProcessVideoClip::dispatch($clip);

                $clips[] = [
                    'id' => $clip->id,
                    'title' => $clip->title,
                    'duration' => $clip->duration,
                    'status' => 'queued',
                    'start_time' => $clip->start_time,
                    'end_time' => $clip->end_time,
                    'elapsed_time' => 0,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => count($clips) . ' clip sedang diproses oleh AI',
                'clips' => $clips
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate clips: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check status clips (polling)
     */
    public function checkClipsStatus(Request $request)
    {
        $request->validate([
            'clipIds' => 'required|array'
        ]);

        $clips = Clip::whereIn('id', $request->clipIds)
            ->where('user_id', auth()->id())
            ->get()
            ->map(function($clip) {
                $elapsedTime = $clip->created_at ? now()->diffInSeconds($clip->created_at) * 1000 : 0;
                
                return [
                    'id' => $clip->id,
                    'title' => $clip->title,
                    'duration' => $clip->duration,
                    'score' => $clip->score,
                    'status' => $this->mapStatus($clip->status),
                    'url' => $clip->file_url,
                    'quality' => $clip->quality,
                    'elapsed_time' => $elapsedTime,
                    'start_time' => $clip->start_time,
                    'end_time' => $clip->end_time,
                ];
            });

        return response()->json([
            'success' => true,
            'clips' => $clips
        ]);
    }

    /**
     * Map internal status to frontend status
     */
    private function mapStatus($internalStatus)
    {
        $statusMap = [
            'queued' => 'queued',
            'analyzing' => 'analyzing',
            'downloading' => 'downloading', 
            'processing' => 'processing',
            'uploading' => 'uploading',
            'completed' => 'completed',
            'done' => 'completed', // Legacy support
            'failed' => 'failed',
        ];

        return $statusMap[$internalStatus] ?? 'processing';
    }

    /**
     * Get YouTube video info
     */
    private function getYouTubeInfo($url)
    {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches);
        $videoId = $matches[1] ?? null;

        if (!$videoId) {
            \Log::error('Could not extract video ID from URL: ' . $url);
            return null;
        }

        \Log::info('Extracted video ID: ' . $videoId);

        try {
            // Gunakan yt-dlp untuk get info
            $ytdlpBin = $this->getYtDlpPath();
            $cmd = sprintf(
                '%s --dump-json --no-playlist --no-warnings %s 2>&1',
                escapeshellarg($ytdlpBin),
                escapeshellarg($url)
            );

            \Log::info('Executing command: ' . $cmd);
            exec($cmd, $output, $code);
            
            $json = implode("\n", $output);
            \Log::info('yt-dlp output: ' . substr($json, 0, 500) . '...');
            
            $data = json_decode($json, true);

            if (!$data || $code !== 0) {
                \Log::warning('yt-dlp failed or returned invalid JSON, using fallback');
                // Fallback: basic info
                return [
                    'id' => $videoId,
                    'title' => 'YouTube Video',
                    'thumbnail' => "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg",
                    'duration' => 'Unknown',
                    'channel' => 'Unknown',
                ];
            }

            $videoInfo = [
                'id' => $videoId,
                'title' => $data['title'] ?? 'YouTube Video',
                'thumbnail' => $data['thumbnail'] ?? "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg",
                'duration' => $this->formatDuration($data['duration'] ?? 0),
                'channel' => $data['uploader'] ?? $data['channel'] ?? 'Unknown',
                'views' => isset($data['view_count']) ? number_format($data['view_count']) . ' views' : null,
            ];

            \Log::info('Successfully parsed video info', $videoInfo);
            return $videoInfo;

        } catch (\Exception $e) {
            \Log::error('Exception in getYouTubeInfo: ' . $e->getMessage());
            
            // Fallback: basic info
            return [
                'id' => $videoId,
                'title' => 'YouTube Video',
                'thumbnail' => "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg",
                'duration' => 'Unknown',
                'channel' => 'Unknown',
            ];
        }
    }

    /**
     * Get TikTok video info
     */
    private function getTikTokInfo($url)
    {
        // TikTok memerlukan scraping atau API khusus
        // Untuk sementara return basic info
        return [
            'id' => Str::random(10),
            'title' => 'TikTok Video',
            'thumbnail' => 'https://via.placeholder.com/640x360?text=TikTok+Video',
            'duration' => 'Unknown',
            'channel' => 'TikTok User',
        ];
    }

    /**
     * Get generic video info
     */
    private function getGenericVideoInfo($url)
    {
        return [
            'id' => Str::random(10),
            'title' => 'Video',
            'thumbnail' => 'https://via.placeholder.com/640x360?text=Video',
            'duration' => 'Unknown',
            'channel' => 'Unknown',
        ];
    }

    /**
     * Get video transcript menggunakan yt-dlp
     */
    private function getVideoTranscript($url)
    {
        if (!str_contains($url, 'youtube.com') && !str_contains($url, 'youtu.be')) {
            return null;
        }

        $ytdlpBin = $this->getYtDlpPath();
        $tmpDir = storage_path('app/tmp_subs');
        
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        $fileName = 'sub_' . uniqid();
        $outputPath = $tmpDir . '/' . $fileName . '.%(ext)s';

        $cmd = sprintf(
            '%s --write-auto-subs --write-subs --sub-lang id,en --skip-download --sub-format vtt -o %s %s 2>&1',
            escapeshellarg($ytdlpBin),
            escapeshellarg($outputPath),
            escapeshellarg($url)
        );

        exec($cmd, $output, $code);

        $files = glob($tmpDir . '/' . $fileName . '*');
        if (empty($files)) {
            return null;
        }

        $vttPath = $files[0];
        $vttContent = file_get_contents($vttPath);
        
        // Parse VTT to readable transcript
        $transcript = $this->parseVTT($vttContent);

        // Cleanup
        foreach ($files as $f) {
            @unlink($f);
        }

        return $transcript;
    }

    /**
     * Parse VTT subtitle file
     */
    private function parseVTT($vttContent)
    {
        $lines = explode("\n", $vttContent);
        $transcript = "";
        $currentText = [];
        $currentTime = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            
            if (preg_match('/(\d{2}):(\d{2}):(\d{2})\.\d{3}\s*-->/', $line, $m)) {
                $sec = $m[1] * 3600 + $m[2] * 60 + $m[3];
                
                if ($sec - $currentTime >= 10 || empty($currentText)) {
                    if (!empty($currentText)) {
                        $transcript .= "[" . gmdate("H:i:s", $currentTime) . "] " . implode(" ", array_unique($currentText)) . "\n";
                    }
                    $currentText = [];
                    $currentTime = $sec;
                }
            } elseif ($line !== '' && !preg_match('/^[\d\s:]+$/', $line) && !str_starts_with($line, 'WEBVTT') && !str_starts_with($line, 'Language:')) {
                $cleanLine = trim(strip_tags(preg_replace('/<[^>]+>/', '', $line)));
                
                if ($cleanLine && !in_array($cleanLine, $currentText) && strlen($cleanLine) > 2) {
                    $currentText[] = $cleanLine;
                }
            }
        }
        
        if (!empty($currentText)) {
            $transcript .= "[" . gmdate("H:i:s", $currentTime) . "] " . implode(" ", array_unique($currentText)) . "\n";
        }

        return $transcript;
    }

    /**
     * Analyze video dengan AI (Groq)
     */
    private function analyzeWithAI($url, $transcript, $settings)
    {
        $apiKey = env('GROQ_API_KEY');

        if (!$apiKey) {
            throw new \Exception('GROQ_API_KEY tidak ditemukan di .env');
        }

        $clipCount = $settings['clipCount'];
        $clipDuration = $settings['clipDuration'];

        $systemPrompt = 'Kamu adalah AI analyzer video viral yang ahli dalam memilih momen terbaik untuk TikTok, Reels, dan Shorts. Tugasmu adalah menemukan momen yang paling engaging, shocking, funny, atau emotional yang bisa membuat video FYP (For You Page). Berikan TEPAT sejumlah clip yang diminta dari bagian BERBEDA di video. Setiap clip harus memiliki konten unik dan berjarak minimal 60 detik. Kembalikan hanya JSON. PENTING: Semua title dan hook HARUS dalam BAHASA INDONESIA.';

        if ($transcript) {
            // Batasi transcript agar tidak melebihi token limit (max 3000 chars untuk safety)
            $safeTranscript = substr($transcript, 0, 3000);
            
            $userPrompt = "Video: {$url}\n\nTranscript:\n{$safeTranscript}\n\nANALISIS MENDALAM: Baca transcript dengan teliti dan temukan TEPAT {$clipCount} momen PALING VIRAL dari bagian BERBEDA di video.\n\nKRITERIA WAJIB untuk setiap clip:\n\n1. **HOOK KUAT** (3 detik pertama):\n   - Mulai dengan pertanyaan provokatif\n   - Statement mengejutkan/kontroversial\n   - Fakta yang bikin penasaran\n   - Visual atau aksi menarik\n\n2. **KONTEN BERKUALITAS**:\n   - EMOTIONAL: Bikin tersentuh, tertawa, marah, atau relate\n   - SURPRISING: Ada plot twist atau fakta mengejutkan\n   - ACTIONABLE: Tips praktis yang bisa langsung dipraktekkan\n   - STORYTELLING: Ada konflik, klimaks, dan resolusi\n   - TRENDING: Topik yang sedang viral atau hot\n\n3. **TITLE CLICKBAIT** (WAJIB):\n   - Gunakan kata viral: \"Ternyata\", \"Rahasia\", \"Cara\", \"Tips\", \"Jangan\", \"Wajib Tahu\", \"Viral\", \"Gila\", \"Ngakak\"\n   - Bikin penasaran tapi TIDAK BOHONG (sesuai konten)\n   - Maksimal 60 karakter\n   - Contoh BAGUS: \"Ternyata Ini Rahasia Trader Sukses! (Jarang yang Tahu)\"\n   - Contoh BURUK: \"Video Menarik\" atau \"Clip #1\"\n\n4. **HOOK TEXT**:\n   - Ambil kalimat PERTAMA dari momen itu di transcript\n   - Harus bikin penasaran\n   - Maksimal 100 karakter\n\nPERATURAN TEKNIS:\n- Setiap clip TEPAT {$clipDuration} detik (end_time = start_time + {$clipDuration})\n- Jarak minimal 60 detik antar clip\n- Pilih momen dari BAGIAN BERBEDA (awal, tengah, akhir)\n- Score 85-98 (semakin viral semakin tinggi)\n\nContoh OUTPUT yang BENAR:\n{\"clips\":[\n  {\n    \"title\":\"Ternyata Ini Rahasia Sukses Trading! (Jarang yang Tahu)\",\n    \"hook\":\"Kebanyakan trader gagal karena tidak tahu ini...\",\n    \"start_time\":60,\n    \"end_time\":" . (60 + $clipDuration) . ",\n    \"duration\":\"{$clipDuration}s\",\n    \"score\":95,\n    \"viral_reason\":\"Hook kuat + tips actionable + clickbait title\"\n  }\n]}\n\nJANGAN ASAL-ASALAN! Baca transcript dan pilih momen yang BENAR-BENAR menarik.";
        } else {
            // Fallback tanpa transcript - tetap fokus pada viral moments
            $userPrompt = "Video: {$url}\n\nGenerate TEPAT {$clipCount} clip PALING VIRAL dari bagian BERBEDA di video.\n\nKRITERIA WAJIB:\n\n1. **HOOK KUAT** - 3 detik pertama harus menarik\n2. **KONTEN BERKUALITAS** - Emotional, funny, surprising, atau actionable\n3. **TITLE CLICKBAIT** - Gunakan kata viral: \"Ternyata\", \"Rahasia\", \"Cara\", \"Tips\", \"Jangan\", \"Wajib Tahu\", \"Viral\", \"Gila\", \"Ngakak\"\n4. **TIDAK ASAL-ASALAN** - Title harus masuk akal untuk jenis video ini\n\nPERATURAN TEKNIS:\n- Setiap clip TEPAT {$clipDuration} detik (end_time = start_time + {$clipDuration})\n- Jarak minimal 60 detik antar clip\n- Pilih momen dari BAGIAN BERBEDA (awal, tengah, akhir)\n- Score 85-95\n\nContoh OUTPUT:\n{\"clips\":[\n  {\n    \"title\":\"Cara Cepat Dapat Uang dari TikTok (Terbukti!)\",\n    \"hook\":\"Gak nyangka bisa segampang ini...\",\n    \"start_time\":60,\n    \"end_time\":" . (60 + $clipDuration) . ",\n    \"duration\":\"{$clipDuration}s\",\n    \"score\":90,\n    \"viral_reason\":\"Clickbait title + actionable content\"\n  }\n]}";
        }

        $response = Http::withToken($apiKey)
            ->timeout(60)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.3,
                'max_tokens' => 512,
            ]);

        if (!$response->successful()) {
            throw new \Exception('AI gagal merespons: ' . $response->body());
        }

        $content = $response->json()['choices'][0]['message']['content'] ?? '';

        // Extract JSON jika terbungkus markdown
        if (!str_starts_with(trim($content), '{')) {
            preg_match('/\{.*\}/s', $content, $m);
            $content = $m[0] ?? '{}';
        }

        $data = json_decode($content, true);
        $clips = $data['clips'] ?? [];
        
        // Pastikan hanya mengambil sesuai jumlah yang diminta
        $clips = array_slice($clips, 0, $clipCount);
        
        // Validasi dan perbaiki durasi + timestamp
        $usedTimestamps = [];
        $minGap = 60; // Minimal 60 detik jarak antar clip
        
        foreach ($clips as $index => &$clip) {
            $startTime = (int) ($clip['start_time'] ?? ($index * 120)); // Default: 0, 120, 240, dst
            $endTime = (int) ($clip['end_time'] ?? ($startTime + $clipDuration));
            
            // Pastikan title tidak kosong atau generic
            if (empty($clip['title']) || str_contains($clip['title'], 'Unique Title') || str_contains($clip['title'], 'Title')) {
                $clip['title'] = $this->generateFallbackTitle($index + 1, $clipDuration);
            }
            
            // Pastikan durasi sesuai yang diminta
            $actualDuration = $endTime - $startTime;
            if ($actualDuration !== $clipDuration) {
                $clip['end_time'] = $startTime + $clipDuration;
                $clip['duration'] = $clipDuration . 's';
            }
            
            // Pastikan tidak terlalu dekat dengan clip lain
            foreach ($usedTimestamps as $usedStart) {
                if (abs($startTime - $usedStart) < $minGap) {
                    // Geser timestamp jika terlalu dekat
                    $startTime = $usedStart + $minGap;
                    $clip['start_time'] = $startTime;
                    $clip['end_time'] = $startTime + $clipDuration;
                    break;
                }
            }
            
            $usedTimestamps[] = $startTime;
            
            // Pastikan tidak melebihi durasi video (asumsi max 30 menit = 1800 detik)
            if ($clip['end_time'] > 1800) {
                $clip['start_time'] = max(0, 1800 - $clipDuration);
                $clip['end_time'] = $clip['start_time'] + $clipDuration;
            }
        }
        
        return $clips;
    }

    /**
     * Extract video ID dari URL
     */
    private function extractVideoId($url)
    {
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches);
            return $matches[1] ?? null;
        }

        return Str::random(10);
    }

    /**
     * Format duration dari detik ke format readable
     */
    private function formatDuration($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $secs);
        }

        return sprintf('%d:%02d', $minutes, $secs);
    }

    /**
     * Get history clips untuk user
     */
    public function getHistory(Request $request)
    {
        $request->validate([
            'filter' => 'required|in:all,today,week,month'
        ]);

        $query = Clip::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->orderByDesc('created_at');

        // Apply filter
        switch ($request->filter) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->where('created_at', '>=', now()->subWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', now()->subMonth());
                break;
        }

        $clips = $query->limit(50)->get()->map(function($clip) {
            return [
                'id' => $clip->id,
                'title' => $clip->title,
                'duration' => $clip->duration,
                'score' => $clip->score,
                'quality' => $clip->quality,
                'file_url' => $clip->file_url,
                'file_size_human' => $clip->file_size_human,
                'created_at' => $clip->created_at->toISOString(),
            ];
        });

        return response()->json([
            'success' => true,
            'clips' => $clips
        ]);
    }

    /**
     * Delete clip
     */
    public function deleteClip(Clip $clip)
    {
        // Pastikan user hanya bisa hapus clip miliknya
        if ($clip->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            // Hapus file fisik jika ada
            if ($clip->file_path && Storage::disk('public')->exists($clip->file_path)) {
                Storage::disk('public')->delete($clip->file_path);
            }

            // Hapus record dari database
            $clip->delete();

            return response()->json([
                'success' => true,
                'message' => 'Clip berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus clip'
            ], 500);
        }
    }

    /**
     * Generate fallback title jika AI gagal (dalam bahasa Indonesia dengan style viral)
     */
    private function generateFallbackTitle($index, $duration)
    {
        $titles = [
            "Ternyata Ini Rahasianya! (Wajib Tahu)",
            "Cara Cepat yang Jarang Diketahui",
            "Jangan Sampai Salah! Tips Penting Ini",
            "Viral! Momen Tak Terduga Ini",
            "Gila! Lihat Apa yang Terjadi",
            "Tips Ampuh yang Harus Dicoba",
            "Rahasia Sukses yang Jarang Dibagikan",
            "Momen Langka yang Bikin Ngakak",
            "Fakta Mengejutkan yang Perlu Kamu Tahu",
            "Tutorial Singkat yang Sangat Berguna",
        ];
        
        return $titles[($index - 1) % count($titles)];
    }

    /**
     * Format clip duration dari start/end time
     */
    private function formatClipDuration($startTime, $endTime)
    {
        $duration = $endTime - $startTime;
        return $duration . 's';
    }

    /**
     * Get yt-dlp binary path
     */
    private function getYtDlpPath()
    {
        $ytdlpBin = env('YTDLP_BIN_PATH', 'C:\\Users\\USER\\AppData\\Local\\Microsoft\\WinGet\\Links\\yt-dlp.exe');
        
        if (!file_exists($ytdlpBin)) {
            $ytdlpBin = 'yt-dlp';
        }

        return $ytdlpBin;
    }
}
