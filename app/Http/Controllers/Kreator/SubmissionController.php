<?php

namespace App\Http\Controllers\Kreator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index()
    {
        $submissions = \App\Models\Submission::with('campaign')
                        ->where('user_id', auth()->id())
                        ->latest()
                        ->get();

        $history = [];
        foreach ($submissions as $sub) {
            
            // Map DB status to UI Status
            $uiStatus = 'Pending Review';
            $color = 'bg-amber-500/10 text-amber-400 shadow-[inset_0_0_0_1px_rgba(245,158,11,0.25)]';
            
            if ($sub->status === 'approved') {
                $uiStatus = 'Dibayar';
                $color = 'bg-emerald-500/10 text-emerald-400 shadow-[inset_0_0_0_1px_rgba(16,185,129,0.25)]';
            } elseif ($sub->status === 'rejected') {
                $uiStatus = 'Ditolak';
                $color = 'bg-red-500/10 text-red-300 shadow-[inset_0_0_0_1px_rgba(239,68,68,0.25)]';
            } elseif ($sub->video_url == '') {
                $uiStatus = 'Belum Submit';
                $color = 'bg-blue-500/10 text-blue-400 shadow-[inset_0_0_0_1px_rgba(59,130,246,0.25)]';
            }

            $dateDisplay = $sub->created_at->isToday() 
                           ? 'Hari ini, ' . $sub->created_at->format('H:i')
                           : ($sub->created_at->isYesterday() 
                                ? 'Kemarin, ' . $sub->created_at->format('H:i') 
                                : $sub->created_at->format('d M Y'));

            $h = [
                'id' => $sub->id,
                'campaign' => $sub->campaign ? $sub->campaign->title : 'Unknown Campaign',
                'platform' => $sub->platform ?: 'TikTok', // Default
                'status' => $uiStatus,
                'color' => $color,
                'views' => number_format($sub->views_claimed, 0, ',', '.'),
                'potensi' => 'Rp ' . number_format($sub->estimated_reward, 0, ',', '.'),
                'date' => $dateDisplay,
            ];

            if ($sub->status === 'rejected' && $sub->rejection_reason) {
                $h['alasan'] = $sub->rejection_reason;
            }

            $history[] = $h;
        }

        return view('kreator.submissions.index', compact('history'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'platform' => 'required|in:TikTok,Instagram,YouTube',
            'video_url' => 'required|url',
            'views_claimed' => 'required|integer|min:0',
            'analytics_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);
        
        // Upload analytics proof
        $proofPath = $request->file('analytics_proof')->store('analytics_proofs', 'public');
        
        // Get campaign to calculate reward
        $campaign = \App\Models\Campaign::findOrFail($validated['campaign_id']);
        $estimatedReward = ($validated['views_claimed'] / 1000) * $campaign->price_per_1k;
        
        // Create submission
        $submission = \App\Models\Submission::create([
            'user_id' => auth()->id(),
            'campaign_id' => $validated['campaign_id'],
            'platform' => $validated['platform'],
            'video_url' => $validated['video_url'],
            'views_claimed' => $validated['views_claimed'],
            'analytics_proof_path' => $proofPath,
            'estimated_reward' => $estimatedReward,
            'status' => 'pending',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Pekerjaan berhasil disubmit! Menunggu review dari brand.',
            'submission_id' => $submission->id
        ]);
    }
}

