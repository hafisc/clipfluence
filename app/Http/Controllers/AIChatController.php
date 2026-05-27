<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIChatController extends Controller
{
    /**
     * Handle AI chat messages from the floating chatbox.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history'  => 'nullable|array',
            'history.*.role'    => 'required|in:user,assistant',
            'history.*.content' => 'required|string|max:2000',
        ]);

        $apiKey = env('GROQ_API_KEY');

        if (!$apiKey) {
            return response()->json([
                'reply' => 'Maaf, layanan AI sedang tidak tersedia saat ini.',
            ], 503);
        }

        // Build message history (max 10 turns to keep tokens low)
        $history = collect($request->input('history', []))
            ->slice(-10)  // Get last 10 items (compatible with older Laravel versions)
            ->map(fn($m) => ['role' => $m['role'], 'content' => $m['content']])
            ->values()
            ->toArray();

        $messages = array_merge(
            [
                [
                    'role'    => 'system',
                    'content' => 'Kamu adalah AI Customer Service Assistant dari Clipfluence, platform influencer marketing terkemuka yang menghubungkan brand dengan kreator konten berkualitas. ' .
                        'Peranmu adalah memberikan bantuan profesional, ramah, dan responsif kepada pengguna. ' . "\n\n" .
                        'TANGGUNG JAWAB UTAMA:' . "\n" .
                        '• Menjawab pertanyaan tentang cara mendaftar (untuk brand dan kreator)' . "\n" .
                        '• Menjelaskan cara kerja platform dan fitur-fitur utama' . "\n" .
                        '• Memberikan panduan tentang kampanye (membuat, join, submit konten)' . "\n" .
                        '• Membantu proses pembayaran dan withdrawal dana' . "\n" .
                        '• Menjelaskan sistem rating dan verifikasi konten' . "\n" .
                        '• Memberikan tips dan best practices untuk sukses di platform' . "\n" .
                        '• Menangani komplain dan masalah teknis dengan solusi awal' . "\n\n" .
                        'GAYA KOMUNIKASI:' . "\n" .
                        '• Ramah, profesional, dan mudah dipahami' . "\n" .
                        '• Jawab SINGKAT dan TO THE POINT (maksimal 2-3 kalimat)' . "\n" .
                        '• Gunakan Bahasa Indonesia yang baik dan benar' . "\n" .
                        '• Jika pengguna bertanya hal teknis kompleks atau mengeluh, tawarkan untuk hubungi tim support' . "\n\n" .
                        'BATASAN:' . "\n" .
                        '• Jika pertanyaan di luar topik Clipfluence, dengan sopan arahkan ke topik platform' . "\n" .
                        '• Jika tidak yakin dengan jawaban, lebih baik rekomendasikan hubungi support via WhatsApp' . "\n" .
                        '• Jangan membuat janji atau komitmen yang tidak bisa dipenuhi' . "\n\n" .
                        'Tim support kami siap 24/7 via WhatsApp untuk bantuan lebih lanjut.',
                ],
            ],
            $history,
            [
                ['role' => 'user', 'content' => $request->input('message')],
            ]
        );

        try {
            \Log::info('AI Chat Request', ['message_length' => strlen($request->input('message')), 'history_count' => count($history)]);
            
            $response = Http::withToken($apiKey)
                ->timeout(30)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model'       => 'llama-3.1-8b-instant',
                    'messages'    => $messages,
                    'temperature' => 0.7,
                    'max_tokens'  => 512,
                ]);

            if (!$response->successful()) {
                $errorBody = $response->body();
                \Log::error('Groq API Error', ['status' => $response->status(), 'body' => $errorBody]);
                
                return response()->json([
                    'reply' => 'Maaf, AI sedang sibuk. Silakan coba lagi dalam beberapa saat atau hubungi kami via WhatsApp untuk bantuan lebih cepat.',
                ], 200);
            }

            $reply = $response->json()['choices'][0]['message']['content'] ?? 'Maaf, saya tidak bisa merespons saat ini.';
            
            \Log::info('AI Chat Response', ['reply_length' => strlen($reply)]);

            return response()->json(['reply' => trim($reply)]);

        } catch (\Exception $e) {
            \Log::error('AI chat exception', ['error' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
            
            return response()->json([
                'reply' => 'Terjadi kesalahan teknis. Silakan coba lagi atau hubungi tim support kami via WhatsApp untuk bantuan.',
            ], 200);
        }
    }
}
