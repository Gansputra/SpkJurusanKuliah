<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserScoreRequest;
use App\Models\AlternativeScore;
use App\Models\Alternative;
use App\Models\Criteria;
use App\Services\TOPSISService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ScoreController extends Controller
{
    public function index()
    {
        $criteria = Criteria::orderBy('urutan')->get();

        // Ambil nilai yang sudah ada di session
        $sessionScores = Session::get('user_scores', []);

        return view('user.scores.index', compact('criteria', 'sessionScores'));
    }

    public function store(StoreUserScoreRequest $request)
    {
        $scores = $request->validated()['scores'];

        // Simpan ke session
        Session::put('user_scores', $scores);

        // Cari bobot AHP yang valid
        $latestAhp = \App\Models\AhpResult::where('is_consistent', true)->latest()->first();

        // Jika belum ada AHP di DB, coba hitung otomatis jika matriks perbandingan lengkap
        if (!$latestAhp) {
            $criteriaCount = Criteria::count();
            $expectedMatrixCount = $criteriaCount * $criteriaCount;
            if ($criteriaCount >= 2 && \App\Models\PairwiseMatrix::count() >= $expectedMatrixCount) {
                $ahpService = app(\App\Services\AHPService::class);
                $ahpCalc = $ahpService->calculate();
                if (isset($ahpCalc['is_consistent']) && $ahpCalc['is_consistent']) {
                    $latestAhp = \App\Models\AhpResult::find($ahpCalc['ahp_result_id']);
                }
            }
        }

        // Simpan hasil rekomendasi ke database riwayat saat submit nilai baru
        if ($latestAhp) {
            $formattedScores = [];
            foreach ($scores as $critId => $val) {
                $formattedScores[$critId] = (float) $val;
            }

            $topsisService = app(TOPSISService::class);
            $topsisService->calculate(
                userId: auth()->id(),
                userScores: $formattedScores,
                ahpResultId: $latestAhp->id
            );
        }

        return redirect()->route('user.recommendation.index')
            ->with('success', 'Nilai berhasil disimpan. Silakan lihat rekomendasi.');
    }
}
