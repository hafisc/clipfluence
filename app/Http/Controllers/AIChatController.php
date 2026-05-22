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
            ->takeLast(10)
            ->map(fn($m) => ['role' => $m['role'], 'content' => $m['content']])
            ->values()
            ->toArray();

        $messages = array_merge(
            [
                [
                    'role'    => 'system',
                    'content' => 'Kamu adalah AI Assistant dari Clipfluence, platform influencer marketing yang menghubungkan brand dengan kreator konten. '
                        . 'Bantu pengguna dengan pertanyaan seputar platform, cara kerja kampanye, cara mendaftar, cara submit konten, pembayaran, dan fitur-fitur Clipfluence. '
                        . 'Jawab dengan ramah, singkat, dan dalam Bahasa Indonesia. '
                        . 'Jika pertanyaan di luar topik Clipfluence, arahkan pengguna untuk menghubungi tim support via WhatsApp.',
                ],
            ],
            $history,
            [
                ['role' => 'user', 'content' => $request->input('message')],
            ]
        );

        try {
            $response = Http::withToken($apiKey)
                ->timeout(30)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model'       => 'llama-3.1-8b-instant',
                    'messages'    => $messages,
                    'temperature' => 0.7,
                    'max_tokens'  => 512,
                ]);

            if (!$response->successful()) {
                \Log::error('Groq AI chat error: ' . $response->body());
                return response()->json([
                    'reply' => 'Maaf, AI sedang sibuk. Silakan coba lagi atau hubungi kami via WhatsApp.',
                ], 200);
            }

            $reply = $response->json()['choices'][0]['message']['content'] ?? 'Maaf, saya tidak bisa merespons saat ini.';

            return response()->json(['reply' => trim($reply)]);

        } catch (\Exception $e) {
            \Log::error('AI chat exception: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Terjadi kesalahan. Silakan coba lagi atau hubungi kami via WhatsApp.',
            ], 200);
        }
    }
}
