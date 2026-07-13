<?php

namespace App\Services;

use App\Models\Alternative;
use App\Models\AhpResult;
use App\Models\Criteria;
use App\Models\Recommendation;
use App\Models\RecommendationDetail;
use Illuminate\Support\Facades\DB;

class TOPSISService
{
    /**
     * Jalankan seluruh proses TOPSIS.
     *
     * @param int|null $userId - jika null, gunakan nilai dari tabel alternative_scores (admin mode)
     * @param array|null $userScores - array [criteria_id => nilai] untuk mode user
     * @param int|null $ahpResultId - AHP Result ID yang digunakan
     * @return array hasil lengkap TOPSIS
     */
    public function calculate(
        ?int $userId = null,
        ?array $userScores = null,
        ?int $ahpResultId = null
    ): array {
        // Ambil kriteria & alternatif
        $criteria = Criteria::orderBy('urutan')->get();
        $alternatives = Alternative::where('active', true)->orderBy('kode')->get();

        $n = $criteria->count();  // jumlah kriteria
        $m = $alternatives->count(); // jumlah alternatif

        if ($n < 1 || $m < 1) {
            return ['error' => 'Data kriteria atau alternatif tidak tersedia.'];
        }

        // Ambil bobot AHP
        $ahpResult = $ahpResultId
            ? AhpResult::find($ahpResultId)
            : AhpResult::latest()->first();

        if (!$ahpResult || !$ahpResult->is_consistent) {
            return ['error' => 'Bobot AHP belum dihitung atau tidak konsisten. Jalankan AHP terlebih dahulu.'];
        }

        $weights = [];
        foreach ($criteria as $crit) {
            $weightData = collect($ahpResult->weights)->firstWhere('kode', $crit->kode);
            $weights[$crit->id] = $weightData ? (float) $weightData['bobot'] : 0;
        }

        // ==============================
        // STEP 1: Bangun Matriks Keputusan
        // ==============================
        $decisionMatrix = [];
        foreach ($alternatives as $altIdx => $alt) {
            foreach ($criteria as $critIdx => $crit) {
                $dbScore = $alt->scores()->where('criteria_id', $crit->id)->first()?->nilai ?? 0;
                if ($userScores && isset($userScores[$crit->id])) {
                    // Mode user: hitung kecocokan/kedekatan nilai user dengan nilai database kriteria alternatif
                    // 10 - selisih absolut untuk mencari tingkat kecocokan (makin kecil selisih, makin tinggi nilai kecocokan)
                    $decisionMatrix[$altIdx][$critIdx] = 10 - abs((float) $userScores[$crit->id] - (float) $dbScore);
                } else {
                    // Mode admin: gunakan nilai dari database langsung
                    $decisionMatrix[$altIdx][$critIdx] = (float) $dbScore;
                }
            }
        }

        // ==============================
        // STEP 2: Normalisasi Matriks
        // r_ij = x_ij / sqrt(Σ x_ij^2)
        // ==============================
        $colNorms = [];
        for ($j = 0; $j < $n; $j++) {
            $sumSq = 0;
            for ($i = 0; $i < $m; $i++) {
                $sumSq += pow($decisionMatrix[$i][$j], 2);
            }
            $colNorms[$j] = sqrt($sumSq);
        }

        $normalizedMatrix = [];
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normalizedMatrix[$i][$j] = ($colNorms[$j] > 0)
                    ? round($decisionMatrix[$i][$j] / $colNorms[$j], 6)
                    : 0;
            }
        }

        // ==============================
        // STEP 3: Normalisasi Terbobot
        // v_ij = w_j * r_ij
        // ==============================
        $weightedMatrix = [];
        $criteriaArray = $criteria->values()->toArray();
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $critId = $criteriaArray[$j]['id'];
                $weightedMatrix[$i][$j] = round($normalizedMatrix[$i][$j] * ($weights[$critId] ?? 0), 6);
            }
        }

        // ==============================
        // STEP 4 & 5: Solusi Ideal Positif (A+) dan Negatif (A-)
        // ==============================
        $idealPositive = [];
        $idealNegative = [];
        for ($j = 0; $j < $n; $j++) {
            $column = array_column($weightedMatrix, $j);
            $critId = $criteriaArray[$j]['id'];
            $critType = $criteriaArray[$j]['tipe'];

            if ($critType === 'benefit') {
                $idealPositive[$j] = max($column);
                $idealNegative[$j] = min($column);
            } else {
                // cost: positif = min, negatif = max
                $idealPositive[$j] = min($column);
                $idealNegative[$j] = max($column);
            }
        }

        // ==============================
        // STEP 6 & 7: Hitung D+ dan D-
        // D+ = sqrt(Σ (v_ij - A+_j)^2)
        // D- = sqrt(Σ (v_ij - A-_j)^2)
        // ==============================
        $dPlus = [];
        $dMinus = [];
        for ($i = 0; $i < $m; $i++) {
            $sumPlus = 0;
            $sumMinus = 0;
            for ($j = 0; $j < $n; $j++) {
                $sumPlus += pow($weightedMatrix[$i][$j] - $idealPositive[$j], 2);
                $sumMinus += pow($weightedMatrix[$i][$j] - $idealNegative[$j], 2);
            }
            $dPlus[$i] = round(sqrt($sumPlus), 8);
            $dMinus[$i] = round(sqrt($sumMinus), 8);
        }

        // ==============================
        // STEP 8: Hitung Nilai Preferensi
        // Vi = D- / (D+ + D-)
        // ==============================
        $preferenceValues = [];
        for ($i = 0; $i < $m; $i++) {
            $total = $dPlus[$i] + $dMinus[$i];
            $preferenceValues[$i] = ($total > 0)
                ? round($dMinus[$i] / $total, 8)
                : 0;
        }

        // ==============================
        // STEP 9: Ranking
        // ==============================
        $rankings = $preferenceValues;
        arsort($rankings);
        $rankMap = [];
        $rank = 1;
        foreach ($rankings as $idx => $val) {
            $rankMap[$idx] = $rank++;
        }

        // ==============================
        // Susun hasil lengkap
        // ==============================
        $results = [];
        $altArray = $alternatives->values()->toArray();
        for ($i = 0; $i < $m; $i++) {
            $results[] = [
                'alternative' => $altArray[$i],
                'decision_matrix_row' => $decisionMatrix[$i],
                'normalized_row' => $normalizedMatrix[$i],
                'weighted_row' => $weightedMatrix[$i],
                'd_plus' => $dPlus[$i],
                'd_minus' => $dMinus[$i],
                'nilai_preferensi' => $preferenceValues[$i],
                'ranking' => $rankMap[$i],
            ];
        }

        // Sort by ranking for display
        usort($results, fn($a, $b) => $a['ranking'] <=> $b['ranking']);

        $topsisData = [
            'criteria' => $criteriaArray,
            'alternatives' => $altArray,
            'weights' => $weights,
            'decision_matrix' => $decisionMatrix,
            'col_norms' => $colNorms,
            'normalized_matrix' => $normalizedMatrix,
            'weighted_matrix' => $weightedMatrix,
            'ideal_positive' => $idealPositive,
            'ideal_negative' => $idealNegative,
            'd_plus' => $dPlus,
            'd_minus' => $dMinus,
            'preference_values' => $preferenceValues,
            'results' => $results,
            'ahp_result_id' => $ahpResult->id,
        ];

        // Simpan ke database jika ada userId
        if ($userId) {
            $recommendation = $this->saveRecommendation($userId, $topsisData, $ahpResult->id);
            $topsisData['recommendation_id'] = $recommendation->id;
        }

        return $topsisData;
    }

    /**
     * Simpan hasil rekomendasi ke database
     */
    private function saveRecommendation(int $userId, array $topsisData, int $ahpResultId): Recommendation
    {
        return DB::transaction(function () use ($userId, $topsisData, $ahpResultId) {
            $recommendation = Recommendation::create([
                'user_id' => $userId,
                'session_name' => 'Rekomendasi ' . now()->format('d/m/Y H:i'),
                'ahp_result_id' => $ahpResultId,
                'topsis_steps' => [
                    'decision_matrix' => $topsisData['decision_matrix'],
                    'normalized_matrix' => $topsisData['normalized_matrix'],
                    'weighted_matrix' => $topsisData['weighted_matrix'],
                    'ideal_positive' => $topsisData['ideal_positive'],
                    'ideal_negative' => $topsisData['ideal_negative'],
                ],
                'calculated_at' => now(),
            ]);

            foreach ($topsisData['results'] as $result) {
                RecommendationDetail::create([
                    'recommendation_id' => $recommendation->id,
                    'alternative_id' => $result['alternative']['id'],
                    'nilai_preferensi' => $result['nilai_preferensi'],
                    'd_plus' => $result['d_plus'],
                    'd_minus' => $result['d_minus'],
                    'ranking' => $result['ranking'],
                ]);
            }

            return $recommendation;
        });
    }
}
