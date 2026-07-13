<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alternative;
use App\Models\AhpResult;
use App\Models\Criteria;
use App\Models\Recommendation;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_jurusan' => Alternative::count(),
            'total_criteria' => Criteria::count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_recommendations' => Recommendation::count(),
        ];

        $latestAhp = AhpResult::latest()->first();

        // Top 5 jurusan berdasarkan ranking terbaru
        $latestRecommendation = Recommendation::with(['details' => function ($q) {
            $q->orderBy('ranking')->take(5)->with('alternative');
        }])->latest()->first();

        $topJurusan = $latestRecommendation?->details ?? collect();

        // Chart data: ranking dari rekomendasi terbaru
        $chartLabels = $topJurusan->pluck('alternative.nama')->toArray();
        $chartData = $topJurusan->pluck('nilai_preferensi')->map(fn($v) => round($v * 100, 2))->toArray();

        return view('admin.dashboard', compact(
            'stats', 'latestAhp', 'topJurusan', 'chartLabels', 'chartData'
        ));
    }
}
