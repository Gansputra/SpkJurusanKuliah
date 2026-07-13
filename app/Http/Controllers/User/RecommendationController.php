<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AhpResult;
use App\Models\Criteria;
use App\Services\TOPSISService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RecommendationController extends Controller
{
    public function __construct(protected TOPSISService $topsisService) {}

    public function index()
    {
        $user = auth()->user();
        $criteria = Criteria::orderBy('urutan')->get();

        // Cek apakah user sudah input nilai
        $userScores = Session::get('user_scores', []);

        if (empty($userScores)) {
            return redirect()->route('user.scores.index')
                ->with('info', 'Silakan isi nilai Anda terlebih dahulu untuk mendapatkan rekomendasi.');
        }

        // Cek AHP result
        $latestAhp = AhpResult::where('is_consistent', true)->latest()->first();

        if (!$latestAhp) {
            // Coba hitung otomatis jika data matriks berpasangan lengkap
            $expectedMatrixCount = $criteria->count() * $criteria->count();
            if ($criteria->count() >= 2 && \App\Models\PairwiseMatrix::count() >= $expectedMatrixCount) {
                $ahpService = app(\App\Services\AHPService::class);
                $ahpCalc = $ahpService->calculate();
                if (isset($ahpCalc['is_consistent']) && $ahpCalc['is_consistent']) {
                    $latestAhp = AhpResult::find($ahpCalc['ahp_result_id']);
                }
            }
        }

        if (!$latestAhp) {
            return view('user.recommendation.index', [
                'result' => null,
                'error' => 'Sistem belum memiliki bobot AHP yang valid. Hubungi admin.',
                'criteria' => $criteria,
                'userScores' => $userScores,
            ]);
        }

        // Jalankan TOPSIS dengan nilai user
        // User scores: [criteria_id => nilai]
        $criteriaMap = $criteria->keyBy('id');
        $formattedScores = [];

        foreach ($criteria as $crit) {
            if (isset($userScores[$crit->id])) {
                $formattedScores[$crit->id] = (float) $userScores[$crit->id];
            }
        }

        // Hitung TOPSIS menggunakan nilai kecocokan userScores dengan database
        // userId di-set null agar tidak menyimpan riwayat baru ke database saat halaman hanya di-refresh (GET)
        $result = $this->topsisService->calculate(
            userId: null,
            userScores: $formattedScores,
            ahpResultId: $latestAhp->id
        );

        if (isset($result['error'])) {
            return view('user.recommendation.index', [
                'result' => null,
                'error' => $result['error'],
                'criteria' => $criteria,
                'userScores' => $userScores,
            ]);
        }

        // Chart data
        $chartLabels = collect($result['results'])->pluck('alternative.nama')->toArray();
        $chartData = collect($result['results'])->pluck('nilai_preferensi')
            ->map(fn($v) => round($v * 100, 2))->toArray();

        return view('user.recommendation.index', compact(
            'result', 'criteria', 'userScores', 'chartLabels', 'chartData', 'latestAhp'
        ));
    }
}
