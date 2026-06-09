<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CampaignController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $campaigns = $user->campaigns()->latest()->get();
        return view('brand.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('brand.campaigns.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatedCampaign($request, true);

        // Determine status based on action button
        $status = $request->input('action') === 'active' ? 'active' : 'draft';

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->campaigns()->create(array_merge($validated, [
            'thumbnail' => $request->file('thumbnail')->store('campaigns', 'public'),
            'status' => $status,
        ]));

        return redirect()->route('brand.campaigns')->with('success', 'Campaign berhasil ' . ($status === 'active' ? 'diluncurkan!' : 'disimpan sebagai draft.'));
    }

    public function edit(Campaign $campaign)
    {
        $this->ensureOwner($campaign);

        return view('brand.campaigns.create', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $this->ensureOwner($campaign);

        $validated = $this->validatedCampaign($request, false);
        $validated['status'] = $request->input('action') === 'active' ? 'active' : 'draft';

        if ($request->hasFile('thumbnail')) {
            if ($campaign->thumbnail) {
                Storage::disk('public')->delete($campaign->thumbnail);
            }

            $validated['thumbnail'] = $request->file('thumbnail')->store('campaigns', 'public');
        }

        $campaign->update($validated);

        return redirect()->route('brand.campaigns')->with('success', 'Campaign berhasil diperbarui.');
    }

    public function destroy(Campaign $campaign)
    {
        $this->ensureOwner($campaign);

        if ($campaign->thumbnail) {
            Storage::disk('public')->delete($campaign->thumbnail);
        }

        $campaign->delete();

        return redirect()->route('brand.campaigns')->with('success', 'Campaign berhasil dihapus.');
    }
    
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        if (strlen($query) < 1) {
            return response()->json(['campaigns' => []]);
        }
        
        $user = auth()->user();
        
        $campaigns = Campaign::where('user_id', $user->id)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('desc', 'like', '%' . $query . '%');
            })
            ->limit(5)
            ->get()
            ->map(function($campaign) {
                return [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'type' => $campaign->type,
                    'status' => $campaign->status,
                    'price' => $campaign->price_per_1k,
                    'thumbnail' => $campaign->thumbnail,
                ];
            });
        
        return response()->json(['campaigns' => $campaigns]);
    }

    private function validatedCampaign(Request $request, bool $thumbnailRequired): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', Rule::in(['video', 'clip'])],
            'slots' => ['required', 'integer', 'min:1'],
            'thumbnail' => [$thumbnailRequired ? 'required' : 'nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'desc' => ['required', 'string'],
            'full_brief' => ['required', 'string'],
            'donts' => ['required', 'string'],
            'assets_url' => ['nullable', 'url'],
            'deadline' => ['required', 'date'],
            'video_length' => ['required', 'string', 'max:50'],
            'link' => ['required', 'url'],
            'platform' => ['required', 'string', Rule::in(['all', 'tiktok', 'ig_reels', 'yt_shorts'])],
            'budget' => ['required', 'numeric', 'min:0'],
            'price_per_1k' => ['required', 'numeric', 'min:0'],
        ]);
    }

    private function ensureOwner(Campaign $campaign): void
    {
        abort_unless($campaign->user_id === auth()->id(), 403);
    }
}
