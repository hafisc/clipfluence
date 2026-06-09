<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::with('user')->latest()->paginate(10);
        $brands = User::where('role', 'brand')->orderBy('name')->get();

        return view('admin.campaigns.index', [
            'campaigns' => $campaigns,
            'brands' => $brands,
            'stats' => [
                'total' => Campaign::count(),
                'active' => Campaign::where('status', 'active')->count(),
                'draft' => Campaign::where('status', 'draft')->count(),
                'completed' => Campaign::where('status', 'completed')->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatedCampaign($request, true);

        Campaign::create(array_merge($validated, [
            'thumbnail' => $request->file('thumbnail')->store('campaigns', 'public'),
        ]));

        return back()->with('success', 'Campaign berhasil ditambahkan.');
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $this->validatedCampaign($request, false);

        if ($request->hasFile('thumbnail')) {
            if ($campaign->thumbnail) {
                Storage::disk('public')->delete($campaign->thumbnail);
            }

            $validated['thumbnail'] = $request->file('thumbnail')->store('campaigns', 'public');
        }

        $campaign->update($validated);

        return back()->with('success', 'Campaign berhasil diperbarui.');
    }

    public function destroy(Campaign $campaign)
    {
        if ($campaign->thumbnail) {
            Storage::disk('public')->delete($campaign->thumbnail);
        }

        $campaign->delete();

        return back()->with('success', 'Campaign berhasil dihapus.');
    }

    private function validatedCampaign(Request $request, bool $thumbnailRequired): array
    {
        return $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')->where('role', 'brand')],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['video', 'clip'])],
            'slots' => ['required', 'integer', 'min:1'],
            'thumbnail' => [$thumbnailRequired ? 'required' : 'nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'desc' => ['required', 'string'],
            'full_brief' => ['required', 'string'],
            'donts' => ['required', 'string'],
            'assets_url' => ['nullable', 'url'],
            'deadline' => ['required', 'date'],
            'video_length' => ['required', 'string', 'max:50'],
            'link' => ['required', 'url'],
            'platform' => ['required', Rule::in(['all', 'tiktok', 'ig_reels', 'yt_shorts'])],
            'budget' => ['required', 'numeric', 'min:0'],
            'price_per_1k' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['draft', 'active', 'completed', 'cancelled'])],
        ]);
    }
}
