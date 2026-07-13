<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Recommendation;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $latestRecommendation = Recommendation::with(['details' => function ($q) {
            $q->orderBy('ranking')->take(3)->with('alternative');
        }])->where('user_id', $user->id)->latest()->first();

        $totalRecommendations = Recommendation::where('user_id', $user->id)->count();
        $criteria = Criteria::orderBy('urutan')->get();

        // Chart data dari rekomendasi terbaru
        $chartLabels = $latestRecommendation?->details->pluck('alternative.nama')->toArray() ?? [];
        $chartData = $latestRecommendation?->details->pluck('nilai_preferensi')
            ->map(fn($v) => round($v * 100, 2))->toArray() ?? [];

        return view('user.dashboard', compact(
            'user', 'latestRecommendation', 'totalRecommendations',
            'criteria', 'chartLabels', 'chartData'
        ));
    }
}
