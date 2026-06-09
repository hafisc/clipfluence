<div class="grid gap-3 sm:grid-cols-2">
    <select name="user_id" required class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none">
        <option value="">Pilih Brand</option>
        @foreach($brands as $brand)
            <option value="{{ $brand->id }}" @selected(old('user_id', $campaign?->user_id) == $brand->id)>{{ $brand->company_name ?: $brand->name }}</option>
        @endforeach
    </select>
    <input name="title" value="{{ old('title', $campaign?->title) }}" required class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Nama campaign">
    <select name="type" required class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none">
        <option value="video" @selected(old('type', $campaign?->type ?? 'video') === 'video')>UGC Video</option>
        <option value="clip" @selected(old('type', $campaign?->type) === 'clip')>Clip Video</option>
    </select>
    <input name="slots" type="number" min="1" value="{{ old('slots', $campaign?->slots) }}" required class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Slot kreator">
    <input name="thumbnail" type="file" accept="image/*" @if($requireThumbnail) required @endif class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-slate-300 outline-none">
    <select name="status" required class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none">
        @foreach(['draft' => 'Draft', 'active' => 'Aktif', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $campaign?->status ?? 'draft') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    <input name="deadline" type="date" value="{{ old('deadline', $campaign?->deadline ? \Illuminate\Support\Carbon::parse($campaign->deadline)->format('Y-m-d') : '') }}" required class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none">
    <input name="video_length" value="{{ old('video_length', $campaign?->video_length) }}" required class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Durasi video">
    <input name="link" type="url" value="{{ old('link', $campaign?->link) }}" required class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="URL target">
    <input name="assets_url" type="url" value="{{ old('assets_url', $campaign?->assets_url) }}" class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="URL aset">
    <select name="platform" required class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none">
        <option value="all" @selected(old('platform', $campaign?->platform ?? 'all') === 'all')>Semua Platform</option>
        <option value="tiktok" @selected(old('platform', $campaign?->platform) === 'tiktok')>TikTok</option>
        <option value="ig_reels" @selected(old('platform', $campaign?->platform) === 'ig_reels')>Instagram Reels</option>
        <option value="yt_shorts" @selected(old('platform', $campaign?->platform) === 'yt_shorts')>YouTube Shorts</option>
    </select>
    <input name="budget" type="number" min="0" value="{{ old('budget', $campaign?->budget) }}" required class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Budget">
    <input name="price_per_1k" type="number" min="0" value="{{ old('price_per_1k', $campaign?->price_per_1k) }}" required class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Rate per 1K">
    <textarea name="desc" required rows="3" class="sm:col-span-2 rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Deskripsi singkat">{{ old('desc', $campaign?->desc) }}</textarea>
    <textarea name="full_brief" required rows="4" class="sm:col-span-2 rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Full brief">{{ old('full_brief', $campaign?->full_brief) }}</textarea>
    <textarea name="donts" required rows="3" class="sm:col-span-2 rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Larangan konten">{{ old('donts', $campaign?->donts) }}</textarea>
</div>
