<?php

namespace App\Http\Controllers\Kreator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    private function mapCampaignToViewArray($campaign)
    {
        $statusText = 'TERSEDIA';
        $statusCls = 's-green';
        $crColor = 'text-emerald-400';
        $creator = 'Tersedia';
        $isFull = false;

        if ($campaign->slots === 0 || $campaign->deadline < \Carbon\Carbon::now()) {
            $statusText = 'DANA HABIS';
            $statusCls = 's-red';
            $crColor = 'text-red-400';
            $creator = 'Habis';
            $isFull = true;
        } elseif ($campaign->slots > 0 && $campaign->slots <= 3) {
            $statusText = 'SISA ' . $campaign->slots . ' SLOT';
            $statusCls = 's-amber';
            $crColor = 'text-amber-400';
            $creator = 'Hampir Penuh';
        }

        $brandName = $campaign->user ? $campaign->user->name : 'Unknown';
        
        return [
            'id'         => $campaign->id,
            'brand'      => $brandName,
            'initial'    => strtoupper(substr($brandName, 0, 1)),
            'dotColor'   => $campaign->type === 'clip' ? '#10b981' : '#f97316', // mock colors based on type
            'category'   => $campaign->type === 'clip' ? 'Content Clip' : 'User Generated Content',
            'type'       => $campaign->type,
            'title'      => $campaign->title,
            'desc'       => $campaign->desc,
            'full_brief' => $campaign->full_brief . "\n\nDo's & Don'ts:\n" . $campaign->donts,
            'rate'       => 'Rp ' . number_format($campaign->price_per_1k, 0, ',', '.'),
            'deadline'   => \Carbon\Carbon::parse($campaign->deadline)->format('d M Y'),
            'creator'    => $creator,
            'crColor'    => $crColor,
            'statusText' => $statusText,
            'statusCls'  => $statusCls,
            'image'      => asset('storage/' . $campaign->thumbnail), // Try to use storage first, but default mock uses direct asset
            'cover'      => asset('storage/' . $campaign->thumbnail),
            'full'       => $isFull,
        ];
    }

    public function index()
    {
        $campaignsData = \App\Models\Campaign::with('user')
            ->where('status', 'active')
            ->latest()
            ->get();

        $campaigns = [];
        foreach ($campaignsData as $c) {
            // For seeder that uses dummy paths like 'images/campaigns/tokopedia.png'
            $mapped = $this->mapCampaignToViewArray($c);
            if (str_starts_with($c->thumbnail, 'images/')) {
                $mapped['image'] = asset($c->thumbnail);
                $mapped['cover'] = asset($c->thumbnail);
            }
            $campaigns[] = $mapped;
        }

        return view('kreator.campaigns.index', compact('campaigns'));
    }

    public function show($id)
    {
        $c = \App\Models\Campaign::with('user')->findOrFail($id);
        
        $campaign = $this->mapCampaignToViewArray($c);
        if (str_starts_with($c->thumbnail, 'images/')) {
            $campaign['image'] = asset($c->thumbnail);
            $campaign['cover'] = asset($c->thumbnail);
        }
        
        $isJoined = \App\Models\Submission::where('user_id', auth()->id())
                        ->where('campaign_id', $id)
                        ->exists();

        return view('kreator.campaigns.show', compact('campaign', 'isJoined'));
    }

    public function join($id)
    {
        $campaign = \App\Models\Campaign::findOrFail($id);

        if ($campaign->slots <= 0) {
            return back()->with('error', 'Maaf, slot untuk campaign ini sudah habis.');
        }

        $exists = \App\Models\Submission::where('user_id', auth()->id())
                        ->where('campaign_id', $id)
                        ->exists();

        if ($exists) {
            return back()->with('error', 'Kamu sudah bergabung dalam campaign ini!');
        }

        // Tambah ke tabel submission dengan status awal: pending
        \App\Models\Submission::create([
            'user_id' => auth()->id(),
            'campaign_id' => $id,
            'status' => 'pending',
            'views_claimed' => 0,
            'estimated_reward' => 0,
            'platform' => $campaign->platform ?? 'Mixed',
            'video_url' => '',
            'analytics_proof_path' => ''
        ]);

        // Kurangi sisa slot
        $campaign->decrement('slots');

        return redirect()->back()->with('success', 'Berhasil bergabung dengan Campaign! Buka menu Tugas & Submit untuk mulai bekerja.');
    }

    /**
     * Search campaigns (AJAX endpoint)
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 1) {
            return response()->json(['campaigns' => []]);
        }

        $campaignsData = \App\Models\Campaign::with('user')
            ->where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('desc', 'like', '%' . $query . '%')
                  ->orWhereHas('user', function($userQuery) use ($query) {
                      $userQuery->where('name', 'like', '%' . $query . '%');
                  });
            })
            ->latest()
            ->take(5)
            ->get();

        $campaigns = [];
        foreach ($campaignsData as $c) {
            $brandName = $c->user ? $c->user->name : 'Unknown';
            $campaigns[] = [
                'id' => $c->id,
                'title' => $c->title,
                'brand' => $brandName,
                'type' => $c->type,
                'price' => $c->price_per_1k,
                'thumbnail' => $c->thumbnail,
            ];
        }

        return response()->json(['campaigns' => $campaigns]);
    }
}
