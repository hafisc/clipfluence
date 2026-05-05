<?php

namespace App\Jobs;

use App\Models\Clip;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessVideoClip implements ShouldQueue
{
    use Queueable;

    public $timeout = 600; // 10 menit
    public $tries   = 1;

    public function __construct(public Clip $clip) {}

    public function handle(): void
    {
        $clip = $this->clip;
        
        try {
            // Update status ke analyzing
            $clip->update(['status' => 'analyzing']);
            sleep(2); // Simulasi AI analysis
            
            // Update status ke downloading
            $clip->update(['status' => 'downloading']);
            
            $outputDir  = storage_path('app/public/clips');
            if (!is_dir($outputDir)) mkdir($outputDir, 0755, true);
            $outputFile = $outputDir . '/' . $clip->id . '.mp4';

            $ytdlpBin  = $this->findBinary('yt-dlp');
            $ffmpegBin = $this->findBinary('ffmpeg');

            Log::info("Processing clip #{$clip->id}: {$clip->title} ({$clip->start_time}s-{$clip->end_time}s, {$clip->ratio}, {$clip->quality}, captions: " . ($clip->has_captions ? 'yes' : 'no') . ")");

            $tempDir = storage_path('app/tmp_clips');
            if (!is_dir($tempDir)) @mkdir($tempDir, 0755, true);

            $tempInputVideo = $tempDir . '/temp_' . $clip->id . '_' . uniqid() . '.mp4';
            $duration = max(1, $clip->end_time - $clip->start_time);

            // Download specific section with yt-dlp dengan kualitas sesuai setting
            $qualityFormat = $this->getQualityFormat($clip->quality);
            
            // Download video TANPA section cutting dulu (download full, nanti FFmpeg yang potong)
            // Ini penting agar subtitle timing tetap sinkron
            $ytdlpCmd = sprintf(
                '%s --no-warnings -f "%s" -o %s %s 2>&1',
                escapeshellarg($ytdlpBin),
                $qualityFormat,
                escapeshellarg($tempInputVideo),
                escapeshellarg($clip->source_url)
            );

            exec($ytdlpCmd, $ytOutput, $ytCode);
            
            if (!file_exists($tempInputVideo)) {
                throw new \Exception("Failed to download video");
            }

            // Update status ke processing
            $clip->update(['status' => 'processing']);

            // Process with FFmpeg
            $videoFilters = [];

            // Handle aspect ratio dengan kualitas sesuai setting
            $outputResolution = $this->getOutputResolution($clip->quality, $clip->ratio);
            
            if ($clip->ratio === '9:16') {
                // Untuk vertical (TikTok/Reels) - scale dan pad jika perlu
                $videoFilters[] = "scale={$outputResolution['width']}:{$outputResolution['height']}:force_original_aspect_ratio=decrease,pad={$outputResolution['width']}:{$outputResolution['height']}:(ow-iw)/2:(oh-ih)/2:black";
            } elseif ($clip->ratio === '16:9') {
                // Untuk horizontal - scale dan pad jika perlu  
                $videoFilters[] = "scale={$outputResolution['width']}:{$outputResolution['height']}:force_original_aspect_ratio=decrease,pad={$outputResolution['width']}:{$outputResolution['height']}:(ow-iw)/2:(oh-ih)/2:black";
            }

            // Handle captions if requested
            Log::info("Clip #{$clip->id} has_captions value: " . ($clip->has_captions ? 'true' : 'false'));
            
            if ($clip->has_captions) {
                Log::info("Adding captions to clip #{$clip->id}");
                $this->addCaptions($clip, $videoFilters, $tempDir);
            } else {
                Log::info("Captions not requested for clip #{$clip->id}");
            }

            $vfArgument = '';
            if (!empty($videoFilters)) {
                $vfArgument = '-vf ' . escapeshellarg(implode(',', $videoFilters));
            }

            // FFmpeg command dengan setting kualitas yang lebih baik
            // PENTING: Gunakan -ss SEBELUM -i untuk fast seek, dan potong dengan -t
            $preset = $clip->quality === 'fhd' ? 'medium' : 'fast';
            $crf = $clip->quality === 'fhd' ? '23' : '28';
            
            $ffmpegCmd = sprintf(
                '%s -y -ss %s -i %s -t %s %s -c:v libx264 -preset %s -crf %s -c:a aac -b:a 128k -movflags +faststart %s 2>&1',
                escapeshellarg($ffmpegBin),
                $clip->start_time,
                escapeshellarg($tempInputVideo),
                $duration,
                $vfArgument,
                $preset,
                $crf,
                escapeshellarg($outputFile)
            );

            exec($ffmpegCmd, $ffOutput, $ffCode);

            // Cleanup temp file
            if (file_exists($tempInputVideo)) {
                @unlink($tempInputVideo);
            }

            if ($ffCode !== 0 || !file_exists($outputFile)) {
                Log::error("FFmpeg Error for clip #{$clip->id}: " . implode("\n", array_slice($ffOutput, -5)));
                throw new \Exception("FFmpeg processing failed");
            }

            // Update status ke uploading
            $clip->update(['status' => 'uploading']);
            sleep(1); // Simulasi upload

            $fileSize = filesize($outputFile);
            $filePath = 'clips/' . $clip->id . '.mp4';

            $clip->update([
                'status'    => 'completed',
                'file_path' => $filePath,
                'file_size' => $fileSize,
            ]);

            Log::info("Clip #{$clip->id} processed successfully");

        } catch (\Throwable $e) {
            Log::error("Error processing clip #{$clip->id}: " . $e->getMessage());
            $clip->update(['status' => 'failed']);
        }
    }

    private function addCaptions($clip, &$videoFilters, $tempDir)
    {
        try {
            $ytdlpBin = $this->findBinary('yt-dlp');
            $vttName = 'sub_' . $clip->id . '_' . uniqid();
            $vttPattern = $tempDir . '/' . $vttName . '.%(ext)s';
            
            Log::info("Downloading subtitles for clip #{$clip->id}");
            
            // Download subtitle untuk video penuh
            $ytdlpSubCmd = sprintf(
                '%s --write-auto-subs --write-subs --sub-lang id,en --skip-download --sub-format vtt -o %s %s 2>&1',
                escapeshellarg($ytdlpBin),
                escapeshellarg($vttPattern),
                escapeshellarg($clip->source_url)
            );
            
            exec($ytdlpSubCmd, $subOutput, $subCode);
            
            $files = glob($tempDir . '/' . $vttName . '*');
            
            if (empty($files)) {
                Log::warning("No subtitle files found for clip #{$clip->id}");
                return;
            }
            
            $vttPath = $files[0];
            Log::info("Subtitle downloaded: {$vttPath}");
            
            // Convert VTT to ASS dengan timing yang sudah di-adjust
            $assPath = $this->convertVTTtoASS($vttPath, $clip->start_time, $clip->end_time, $clip->quality, $tempDir);
            
            if ($assPath && file_exists($assPath)) {
                $escapedPath = str_replace(['\\', ':'], ['/', '\\\\:'], $assPath);
                
                // Gunakan ASS subtitle (sudah include styling dan timing adjustment)
                $videoFilters[] = "ass={$escapedPath}";
                
                Log::info("ASS subtitles added to video filters for clip #{$clip->id}");
                
                // Cleanup files after use
                register_shutdown_function(function() use ($vttPath, $assPath) {
                    if (file_exists($vttPath)) @unlink($vttPath);
                    if (file_exists($assPath)) @unlink($assPath);
                });
            } else {
                Log::warning("Failed to convert VTT to ASS for clip #{$clip->id}");
                if (file_exists($vttPath)) @unlink($vttPath);
            }
            
        } catch (\Exception $e) {
            Log::error("Error adding captions to clip #{$clip->id}: " . $e->getMessage());
        }
    }

    /**
     * Convert VTT to ASS format dengan timing adjustment dan styling
     */
    private function convertVTTtoASS($vttPath, $startTime, $endTime, $quality, $tempDir)
    {
        $content = file_get_contents($vttPath);
        if (!$content) {
            Log::warning("Failed to read VTT file: {$vttPath}");
            return null;
        }
        
        Log::info("Converting VTT to ASS for clip range: {$startTime}s - {$endTime}s");
        
        // Parse VTT
        $lines = explode("\n", $content);
        $cues = [];
        $i = 0;
        
        while ($i < count($lines)) {
            $line = trim($lines[$i]);
            
            // Skip headers
            if ($line === '' || str_starts_with($line, 'WEBVTT') || str_starts_with($line, 'Language:') || str_starts_with($line, 'NOTE') || str_starts_with($line, 'Kind:')) {
                $i++;
                continue;
            }
            
            // Check for timestamp line
            if (preg_match('/(\d{2}):(\d{2}):(\d{2})\.(\d{3})\s*-->\s*(\d{2}):(\d{2}):(\d{2})\.(\d{3})/', $line, $matches)) {
                $cueStartTime = $matches[1] * 3600 + $matches[2] * 60 + $matches[3] + $matches[4] / 1000;
                $cueEndTime = $matches[5] * 3600 + $matches[6] * 60 + $matches[7] + $matches[8] / 1000;
                
                // Check if within clip range (dengan toleransi)
                if ($cueEndTime >= ($startTime - 0.5) && $cueStartTime <= ($endTime + 0.5)) {
                    $cueText = [];
                    $i++;
                    
                    while ($i < count($lines)) {
                        $textLine = trim($lines[$i]);
                        if ($textLine === '') break;
                        
                        if (!preg_match('/^\d+$/', $textLine) && !preg_match('/\d{2}:\d{2}:\d{2}/', $textLine)) {
                            $cleanLine = strip_tags($textLine);
                            if ($cleanLine) $cueText[] = $cleanLine;
                        }
                        $i++;
                    }
                    
                    if (!empty($cueText)) {
                        // Adjust timing relative to clip start
                        $adjustedStart = max(0, $cueStartTime - $startTime);
                        $adjustedEnd = min($endTime - $startTime, $cueEndTime - $startTime);
                        
                        if ($adjustedEnd > $adjustedStart && $adjustedStart >= 0) {
                            $cues[] = [
                                'start' => $adjustedStart,
                                'end' => $adjustedEnd,
                                'text' => implode(' ', $cueText)
                            ];
                        }
                    }
                } else {
                    $i++;
                }
            } else {
                $i++;
            }
        }
        
        if (empty($cues)) {
            Log::warning("No cues found in clip range");
            return null;
        }
        
        Log::info("Found " . count($cues) . " cues for clip");
        
        // Create ASS file
        $assPath = $tempDir . '/sub_' . basename($vttPath, '.vtt') . '.ass';
        
        // ASS styling
        $fontSize = $quality === 'fhd' ? '18' : ($quality === 'hd' ? '16' : '14');
        $marginV = $quality === 'fhd' ? '10' : ($quality === 'hd' ? '8' : '6');
        
        $assContent = "[Script Info]\n";
        $assContent .= "ScriptType: v4.00+\n";
        $assContent .= "PlayResX: 1920\n";
        $assContent .= "PlayResY: 1080\n";
        $assContent .= "WrapStyle: 0\n\n";
        
        $assContent .= "[V4+ Styles]\n";
        $assContent .= "Format: Name, Fontname, Fontsize, PrimaryColour, SecondaryColour, OutlineColour, BackColour, Bold, Italic, Underline, StrikeOut, ScaleX, ScaleY, Spacing, Angle, BorderStyle, Outline, Shadow, Alignment, MarginL, MarginR, MarginV, Encoding\n";
        $assContent .= "Style: Default,Arial,{$fontSize},&H00FFFFFF,&H000000FF,&H00000000,&H80000000,-1,0,0,0,100,100,0,0,1,1,1,2,10,10,{$marginV},1\n\n";
        
        $assContent .= "[Events]\n";
        $assContent .= "Format: Layer, Start, End, Style, Name, MarginL, MarginR, MarginV, Effect, Text\n";
        
        foreach ($cues as $cue) {
            $startFormatted = $this->formatASSTime($cue['start']);
            $endFormatted = $this->formatASSTime($cue['end']);
            $text = str_replace("\n", "\\N", $cue['text']);
            $assContent .= "Dialogue: 0,{$startFormatted},{$endFormatted},Default,,0,0,0,,{$text}\n";
        }
        
        file_put_contents($assPath, $assContent);
        Log::info("ASS file created: {$assPath}");
        
        return $assPath;
    }
    
    /**
     * Format time untuk ASS (H:MM:SS.CS)
     */
    private function formatASSTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        $centiseconds = ($secs - floor($secs)) * 100;
        
        return sprintf('%d:%02d:%02d.%02d', $hours, $minutes, floor($secs), $centiseconds);
    }

    /**
     * Process VTT file untuk menyesuaikan dengan timestamp clip
     */
    private function processVTTForClip($vttPath, $startTime, $endTime, $tempDir)
    {
        $content = file_get_contents($vttPath);
        if (!$content) {
            Log::warning("Failed to read VTT file: {$vttPath}");
            return null;
        }
        
        Log::info("Processing VTT for clip range: {$startTime}s - {$endTime}s");
        
        $lines = explode("\n", $content);
        $processedLines = [];
        $processedLines[] = "WEBVTT";
        $processedLines[] = "";
        
        $cueCount = 0;
        $i = 0;
        
        while ($i < count($lines)) {
            $line = trim($lines[$i]);
            
            // Skip empty lines and headers
            if ($line === '' || str_starts_with($line, 'WEBVTT') || str_starts_with($line, 'Language:') || str_starts_with($line, 'NOTE') || str_starts_with($line, 'Kind:')) {
                $i++;
                continue;
            }
            
            // Check for timestamp line
            if (preg_match('/(\d{2}):(\d{2}):(\d{2})\.(\d{3})\s*-->\s*(\d{2}):(\d{2}):(\d{2})\.(\d{3})/', $line, $matches)) {
                // Parse timestamps (dalam detik)
                $cueStartTime = $matches[1] * 3600 + $matches[2] * 60 + $matches[3] + $matches[4] / 1000;
                $cueEndTime = $matches[5] * 3600 + $matches[6] * 60 + $matches[7] + $matches[8] / 1000;
                
                // Check if this cue is within our clip range (dengan toleransi 0.5 detik)
                if ($cueEndTime >= ($startTime - 0.5) && $cueStartTime <= ($endTime + 0.5)) {
                    // Collect cue text from next lines
                    $cueText = [];
                    $i++;
                    
                    while ($i < count($lines)) {
                        $textLine = trim($lines[$i]);
                        if ($textLine === '') {
                            break;
                        }
                        // Skip cue identifiers (numbers)
                        if (!preg_match('/^\d+$/', $textLine) && !preg_match('/\d{2}:\d{2}:\d{2}/', $textLine)) {
                            $cleanLine = strip_tags($textLine);
                            if ($cleanLine) {
                                $cueText[] = $cleanLine;
                            }
                        }
                        $i++;
                    }
                    
                    if (!empty($cueText)) {
                        // Adjust timestamps relative to clip start (PENTING: Jangan potong subtitle yang mulai sebelum clip)
                        $adjustedStart = max(0, $cueStartTime - $startTime);
                        $adjustedEnd = min($endTime - $startTime, $cueEndTime - $startTime);
                        
                        // Pastikan subtitle tidak terpotong di awal
                        if ($cueStartTime < $startTime) {
                            $adjustedStart = 0; // Mulai dari detik 0 jika subtitle sudah mulai sebelum clip
                        }
                        
                        if ($adjustedEnd > $adjustedStart && $adjustedStart >= 0) {
                            $startFormatted = $this->formatVTTTimestamp($adjustedStart);
                            $endFormatted = $this->formatVTTTimestamp($adjustedEnd);
                            
                            $processedLines[] = "{$startFormatted} --> {$endFormatted}";
                            $processedLines[] = implode(' ', $cueText);
                            $processedLines[] = "";
                            
                            $cueCount++;
                            
                            Log::info("Cue #{$cueCount}: Original {$cueStartTime}s-{$cueEndTime}s -> Adjusted {$adjustedStart}s-{$adjustedEnd}s | Text: " . substr(implode(' ', $cueText), 0, 50));
                        }
                    }
                } else {
                    $i++;
                }
            } else {
                $i++;
            }
        }
        
        Log::info("Processed {$cueCount} captions for clip");
        
        if ($cueCount === 0) {
            Log::warning("No captions found in clip range");
            return null;
        }
        
        // Save processed VTT
        $processedPath = $tempDir . '/processed_' . basename($vttPath);
        file_put_contents($processedPath, implode("\n", $processedLines));
        
        Log::info("Saved processed VTT to: {$processedPath}");
        
        return $processedPath;
    }

    /**
     * Format timestamp untuk VTT
     */
    private function formatVTTTimestamp($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        $milliseconds = ($secs - floor($secs)) * 1000;
        
        return sprintf('%02d:%02d:%02d.%03d', $hours, $minutes, floor($secs), $milliseconds);
    }

    /**
     * Get yt-dlp format string berdasarkan quality setting
     */
    private function getQualityFormat($quality)
    {
        $formatMap = [
            'sd' => 'best[height<=480][ext=mp4]/best[ext=mp4]',
            'hd' => 'best[height<=720][ext=mp4]/best[ext=mp4]', 
            'fhd' => 'best[height<=1080][ext=mp4]/best[ext=mp4]'
        ];
        
        return $formatMap[$quality] ?? $formatMap['fhd'];
    }

    /**
     * Get output resolution berdasarkan quality dan ratio
     */
    private function getOutputResolution($quality, $ratio)
    {
        if ($ratio === '9:16') {
            // Vertical resolutions
            $resolutionMap = [
                'sd' => ['width' => 480, 'height' => 854],   // 480p vertical
                'hd' => ['width' => 720, 'height' => 1280],  // 720p vertical  
                'fhd' => ['width' => 1080, 'height' => 1920] // 1080p vertical
            ];
        } else {
            // Horizontal resolutions
            $resolutionMap = [
                'sd' => ['width' => 854, 'height' => 480],   // 480p horizontal
                'hd' => ['width' => 1280, 'height' => 720],  // 720p horizontal
                'fhd' => ['width' => 1920, 'height' => 1080] // 1080p horizontal
            ];
        }
        
        return $resolutionMap[$quality] ?? $resolutionMap['fhd'];
    }

    private function findBinary(string $name): string
    {
        // 1. Cek konfigurasi di .env (Paling Tinggi Prioritasnya)
        $envKey = strtoupper(str_replace('-', '', $name)) . '_BIN_PATH';
        $envPath = env($envKey);

        if ($envPath && file_exists($envPath)) {
            return $envPath;
        }

        // 2. Fallback untuk versi Windows lokal developer (WinGet default)
        $wingetPath = 'C:\\Users\\USER\\AppData\\Local\\Microsoft\\WinGet\\Links\\' . $name . '.exe';
        if (file_exists($wingetPath)) {
            return $wingetPath;
        }

        // 3. Fallback sistem (Asumsi sudah ada di system PATH global di Linux / Hosting)
        return escapeshellarg($name);
    }
}
