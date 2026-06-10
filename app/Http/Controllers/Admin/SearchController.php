<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Deposit;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $query) . '%';

        $pages = collect($this->adminPages())
            ->filter(fn ($page) => $query !== '' && str_contains(strtolower($page['keywords']), strtolower($query)))
            ->values();

        $users = collect();
        $campaigns = collect();
        $deposits = collect();
        $withdrawals = collect();

        if ($query !== '') {
            $users = User::query()
                ->where(function ($userQuery) use ($like) {
                    $userQuery
                        ->where('name', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('role', 'like', $like)
                        ->orWhere('company_name', 'like', $like)
                        ->orWhere('phone', 'like', $like);
                })
                ->latest()
                ->take(8)
                ->get();

            $campaigns = Campaign::with('user')
                ->where(function ($campaignQuery) use ($like) {
                    $campaignQuery
                        ->where('title', 'like', $like)
                        ->orWhere('status', 'like', $like)
                        ->orWhere('platform', 'like', $like)
                        ->orWhere('desc', 'like', $like);
                })
                ->latest()
                ->take(8)
                ->get();

            $deposits = Deposit::with('user')
                ->where(function ($depositQuery) use ($like) {
                    $depositQuery
                        ->where('order_id', 'like', $like)
                        ->orWhere('status', 'like', $like)
                        ->orWhere('payment_type', 'like', $like);
                })
                ->latest()
                ->take(8)
                ->get();

            $withdrawals = Withdrawal::with('user')
                ->where(function ($withdrawalQuery) use ($like) {
                    $withdrawalQuery
                        ->where('status', 'like', $like)
                        ->orWhere('bank_name', 'like', $like)
                        ->orWhere('bank_account', 'like', $like)
                        ->orWhere('account_name', 'like', $like);
                })
                ->latest()
                ->take(8)
                ->get();
        }

        return view('admin.search.index', compact(
            'query',
            'pages',
            'users',
            'campaigns',
            'deposits',
            'withdrawals'
        ));
    }

    private function adminPages(): array
    {
        return [
            ['title' => 'Dasbor', 'subtitle' => 'Ringkasan operasional admin', 'route' => 'admin.dashboard', 'icon' => 'layout-dashboard', 'keywords' => 'dasbor dashboard ringkasan statistik admin'],
            ['title' => 'Staf Internal', 'subtitle' => 'Kelola akun admin internal', 'route' => 'admin.users', 'icon' => 'users', 'keywords' => 'staf internal admin user pengguna'],
            ['title' => 'Daftar Kreator', 'subtitle' => 'Kelola pengguna kreator', 'route' => 'admin.kreators', 'icon' => 'user-check', 'keywords' => 'kreator creator pengguna user'],
            ['title' => 'Daftar Merek', 'subtitle' => 'Kelola akun brand dan perusahaan', 'route' => 'admin.brands', 'icon' => 'building-2', 'keywords' => 'merek brand perusahaan pengguna user'],
            ['title' => 'Semua Kampanye', 'subtitle' => 'Kelola campaign platform', 'route' => 'admin.campaigns', 'icon' => 'megaphone', 'keywords' => 'campaign kampanye iklan konten brand'],
            ['title' => 'Moderasi UGC', 'subtitle' => 'Tinjau konten kreator', 'route' => 'admin.ugc', 'icon' => 'video', 'keywords' => 'ugc video konten moderasi submission'],
            ['title' => 'Pembayaran & Escrow', 'subtitle' => 'Validasi top up brand', 'route' => 'admin.payouts', 'icon' => 'vault', 'keywords' => 'pembayaran escrow top up deposit transaksi'],
            ['title' => 'Penarikan Dana', 'subtitle' => 'Proses withdrawal kreator', 'route' => 'admin.withdrawals', 'icon' => 'wallet', 'keywords' => 'penarikan dana withdrawal payout transfer'],
            ['title' => 'Tiket Bantuan', 'subtitle' => 'Pantau dispute dan bantuan', 'route' => 'admin.disputes', 'icon' => 'message-circle', 'keywords' => 'dispute tiket bantuan masalah support'],
            ['title' => 'Analitik Platform', 'subtitle' => 'Laporan performa platform', 'route' => 'admin.analytics', 'icon' => 'bar-chart-3', 'keywords' => 'analytics analitik laporan statistik'],
            ['title' => 'Notifikasi Broadcast', 'subtitle' => 'Kirim notifikasi ke pengguna', 'route' => 'admin.notifications', 'icon' => 'bell', 'keywords' => 'notifikasi broadcast pengumuman pesan'],
            ['title' => 'Log Aktivitas', 'subtitle' => 'Audit trail aktivitas sistem', 'route' => 'admin.logs', 'icon' => 'scroll-text', 'keywords' => 'log aktivitas audit trail sistem'],
            ['title' => 'Pengaturan', 'subtitle' => 'Konfigurasi platform', 'route' => 'admin.settings', 'icon' => 'settings', 'keywords' => 'pengaturan setting konfigurasi platform'],
        ];
    }
}
