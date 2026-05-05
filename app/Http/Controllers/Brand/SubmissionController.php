<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index()
    {
        $brandId = auth()->id();
        
        // Get all submissions for brand's campaigns
        $submissions = Submission::with(['user', 'campaign'])
            ->whereHas('campaign', function($query) use ($brandId) {
                $query->where('user_id', $brandId);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Count by status
        $pendingCount = $submissions->where('status', 'pending')->count();
        $approvedCount = $submissions->where('status', 'approved')->count();
        $rejectedCount = $submissions->where('status', 'rejected')->count();
        
        return view('brand.submissions.index', compact(
            'submissions',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }
    
    public function approve(Request $request, Submission $submission)
    {
        // Verify brand owns this campaign
        if ($submission->campaign->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $submission->update(['status' => 'approved']);
        
        // TODO: Process payment to creator
        // Add balance to creator's account
        $creator = $submission->user;
        $creator->increment('balance', $submission->estimated_reward);
        
        return response()->json([
            'success' => true,
            'message' => 'Submission disetujui dan pembayaran diproses!'
        ]);
    }
    
    public function reject(Request $request, Submission $submission)
    {
        // Verify brand owns this campaign
        if ($submission->campaign->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);
        
        $submission->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason ?? 'Tidak memenuhi kriteria campaign'
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Submission ditolak'
        ]);
    }
}
