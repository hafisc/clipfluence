<?php

namespace App\Http\Controllers\Kreator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Status Pekerjaan (Real data from submissions table)
        $submissions = \App\Models\Submission::with(['campaign', 'campaign.user'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        $jobs = [];
        foreach ($submissions as $submission) {
            // Determine status display
            $statusMap = [
                'pending' => ['text' => 'Pending Review', 'color' => 'amber', 'icon' => 'clock'],
                'approved' => ['text' => 'Approved', 'color' => 'emerald', 'icon' => 'check-circle'],
                'rejected' => ['text' => 'Rejected', 'color' => 'rose', 'icon' => 'x-circle'],
            ];
            
            $statusInfo = $statusMap[$submission->status] ?? ['text' => 'Unknown', 'color' => 'slate', 'icon' => 'help-circle'];
            
            $jobs[] = [
                'campaign' => $submission->campaign->title ?? 'Unknown Campaign',
                'views'    => number_format($submission->views_claimed, 0, ',', '.'),
                'revenue'  => $submission->estimated_reward,
                'status'   => $statusInfo['text'],
                'color'    => $statusInfo['color'],
                'icon'     => $statusInfo['icon'],
            ];
        }
        
        // If no submissions, show empty state
        if (empty($jobs)) {
            $jobs = [
                [
                    'campaign' => 'Belum Ada Pekerjaan',
                    'views'    => '0',
                    'revenue'  => 0,
                    'status'   => 'Empty',
                    'color'    => 'slate',
                    'icon'     => 'inbox',
                ]
            ];
        }

        // Campaign Rekomendasi (from DB)
        $campaignsData = Campaign::with('user')
            ->where('status', 'active')
            ->inRandomOrder()
            ->take(3)
            ->get();

        $recs = [];
        foreach ($campaignsData as $c) {
            $brandName = $c->user ? $c->user->name : 'Unknown';
            
            // Generate visual style based on campaign type
            if ($c->type === 'clip') {
                $dotColor = '#10b981';
                $bgAlpha = 'rgba(16,185,129,0.05)';
                $iconBg = 'rgba(16,185,129,0.12)';
                $tag = 'Nge-clip';
            } else {
                $dotColor = '#ec4899';
                $bgAlpha = 'rgba(236,72,153,0.05)';
                $iconBg = 'rgba(236,72,153,0.12)';
                $tag = 'UGC';
            }

            $recs[] = [
                'brand'     => $brandName,
                'title'     => $c->title,
                'rate'      => 'Rp ' . number_format($c->price_per_1k, 0, ',', '.') . ' / 1K views',
                'tag'       => $tag,
                'thumbnail' => $c->thumbnail, // Thumbnail path from database
                'dotColor'  => $dotColor,
                'bgAlpha'   => $bgAlpha,
                'iconBg'    => $iconBg,
            ];
        }

        // Statistics User (Real data from submissions)
        $allSubmissions = \App\Models\Submission::where('user_id', $user->id)->get();
        $approvedSubmissions = $allSubmissions->where('status', 'approved');
        $pendingSubmissions = $allSubmissions->where('status', 'pending');
        
        $stats = [
            'active_campaigns' => Campaign::where('status', 'active')->count(),
            'total_pendapatan' => $approvedSubmissions->sum('estimated_reward') + $user->balance,
            'saldo_tersedia'   => $user->balance,
            'dalam_review'     => $pendingSubmissions->sum('estimated_reward'),
            'total_views'      => $approvedSubmissions->sum('views_claimed'),
            'videos_approved'  => $approvedSubmissions->count(),
            'success_rate'     => $allSubmissions->count() > 0 ? ($approvedSubmissions->count() / $allSubmissions->count()) * 100 : 0,
            'revenue_growth'   => 0, // Default 0% selama belum ada tabel tracking pendapatan per hari/bulan
        ];

        return view('kreator.dashboard.index', compact('jobs', 'recs', 'stats'));
    }
}
