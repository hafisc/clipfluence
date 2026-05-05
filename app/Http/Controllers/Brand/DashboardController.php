<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Statistik (Card Atas)
        $balance = 500000;
        $totalViews = 12500;
        $totalUgc = 12;
        $pendingReview = 3;

        // 2. Data Campaign Aktif (Gunakan array/collection agar @forelse tidak error)
        // Nanti ini bisa kamu ganti dengan query database: Campaign::where('status', 'active')->get();
        $campaigns = collect([
            (object)[
                'title' => 'Promo Ramadhan Ceria',
                'platform' => 'TikTok',
                'status' => 'active',
                'budget' => 2500000,
            ],
            (object)[
                'title' => 'UGC Video Review Produk',
                'platform' => 'Instagram',
                'status' => 'active',
                'budget' => 1200000,
            ],
        ]);

        // 3. Kirim semua variabel ke view
        return view('brand.dashboard.index', compact(
            'balance', 
            'totalViews', 
            'totalUgc', 
            'pendingReview', 
            'campaigns'
        ));
    }
}