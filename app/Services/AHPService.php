<?php

namespace App\Services;

use App\Models\AhpResult;
use App\Models\Criteria;
use App\Models\PairwiseMatrix;

class AHPService
{
    // Random Index (RI) dari Saaty untuk n = 1..10
    private array $randomIndex = [
        1 => 0.00,
        2 => 0.00,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49,
    ];

    /**
     * Jalankan seluruh proses AHP dan simpan hasilnya.
     *
     * @return array hasil lengkap AHP
     */
    public function calculate(): array
    {
        $criteria = Criteria::orderBy('urutan')->get();
        $n = $criteria->count();

        if ($n < 2) {
            return ['error' => 'Minimal 2 kriteria diperlukan untuk AHP.'];
        }

        // 1. Bangun Matriks Perbandingan Berpasangan
        $matrix = $this->buildMatrix($criteria);

        // 2. Hitung jumlah kolom
        $colSums = $this->calculateColumnSums($matrix, $n);

        // 3. Normalisasi Matriks
        $normalizedMatrix = $this->normalizeMatrix($matrix, $colSums, $n);

        // 4. Hitung Priority Vector (rata-rata baris)
        $priorityVector = $this->calculatePriorityVector($normalizedMatrix, $n);

        // 5. Hitung λmax
        $lambdaMax = $this->calculateLambdaMax($matrix, $priorityVector, $n);

        // 6. Hitung CI
        $ci = ($lambdaMax - $n) / ($n - 1);

        // 7. Dapatkan RI
        $ri = $this->randomIndex[$n] ?? 1.49;

        // 8. Hitung CR
        $cr = ($ri > 0) ? $ci / $ri : 0;

        // 9. Tentukan konsistensi
        $isConsistent = $cr <= 0.1;

        // 10. Siapkan data weights dengan nama kriteria
        $weights = [];
        foreach ($criteria as $idx => $crit) {
            $weights[$crit->id] = [
                'kode' => $crit->kode,
                'nama' => $crit->nama,
                'bobot' => round($priorityVector[$idx], 6),
            ];
        }

        // 11. Simpan bobot ke tabel criteria
        if ($isConsistent) {
            foreach ($criteria as $idx => $crit) {
                $crit->update(['bobot' => round($priorityVector[$idx], 6)]);
            }
        }

        // 12. Simpan hasil AHP ke database
        $ahpResult = AhpResult::create([
            'lambda_max' => round($lambdaMax, 8),
            'ci' => round($ci, 8),
            'cr' => round($cr, 8),
            'ri' => $ri,
            'is_consistent' => $isConsistent,
            'weights' => $weights,
            'normalized_matrix' => $normalizedMatrix,
            'priority_vector' => $priorityVector,
            'pairwise_matrix_snapshot' => $this->matrixWithLabels($matrix, $criteria),
        ]);

        return [
            'ahp_result_id' => $ahpResult->id,
            'criteria' => $criteria,
            'n' => $n,
            'matrix' => $matrix,
            'col_sums' => $colSums,
            'normalized_matrix' => $normalizedMatrix,
            'priority_vector' => $priorityVector,
            'weights' => $weights,
            'lambda_max' => round($lambdaMax, 6),
            'ci' => round($ci, 6),
            'ri' => $ri,
            'cr' => round($cr, 6),
            'is_consistent' => $isConsistent,
        ];
    }

    /**
     * Bangun matriks n×n dari database
     */
    private function buildMatrix($criteria): array
    {
        $n = $criteria->count();
        $criteriaIds = $criteria->pluck('id')->toArray();
        $matrix = array_fill(0, $n, array_fill(0, $n, 1.0));

        $pairwises = PairwiseMatrix::whereIn('criteria1_id', $criteriaIds)
            ->whereIn('criteria2_id', $criteriaIds)
            ->get();

        foreach ($pairwises as $pw) {
            $i = array_search($pw->criteria1_id, $criteriaIds);
            $j = array_search($pw->criteria2_id, $criteriaIds);
            if ($i !== false && $j !== false) {
                $matrix[$i][$j] = (float) $pw->nilai;
            }
        }

        return $matrix;
    }

    /**
     * Hitung jumlah setiap kolom
     */
    private function calculateColumnSums(array $matrix, int $n): array
    {
        $colSums = array_fill(0, $n, 0.0);
        for ($j = 0; $j < $n; $j++) {
            for ($i = 0; $i < $n; $i++) {
                $colSums[$j] += $matrix[$i][$j];
            }
        }
        return $colSums;
    }

    /**
     * Normalisasi matriks: bagi tiap elemen dengan jumlah kolomnya
     */
    private function normalizeMatrix(array $matrix, array $colSums, int $n): array
    {
        $normalized = [];
        for ($i = 0; $i < $n; $i++) {
            $normalized[$i] = [];
            for ($j = 0; $j < $n; $j++) {
                $normalized[$i][$j] = ($colSums[$j] > 0)
                    ? round($matrix[$i][$j] / $colSums[$j], 6)
                    : 0;
            }
        }
        return $normalized;
    }

    /**
     * Hitung Priority Vector: rata-rata baris dari matriks ternormalisasi
     */
    private function calculatePriorityVector(array $normalizedMatrix, int $n): array
    {
        $priorityVector = [];
        for ($i = 0; $i < $n; $i++) {
            $priorityVector[$i] = round(array_sum($normalizedMatrix[$i]) / $n, 6);
        }
        return $priorityVector;
    }

    /**
     * Hitung λmax = rata-rata dari (Σ kolom × bobot)
     */
    private function calculateLambdaMax(array $matrix, array $priorityVector, int $n): float
    {
        $weightedSums = array_fill(0, $n, 0.0);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $weightedSums[$i] += $matrix[$i][$j] * $priorityVector[$j];
            }
        }

        $lambdaValues = [];
        for ($i = 0; $i < $n; $i++) {
            if ($priorityVector[$i] > 0) {
                $lambdaValues[] = $weightedSums[$i] / $priorityVector[$i];
            }
        }

        return count($lambdaValues) > 0
            ? array_sum($lambdaValues) / count($lambdaValues)
            : $n;
    }

    /**
     * Format matriks dengan label kriteria untuk snapshot
     */
    private function matrixWithLabels(array $matrix, $criteria): array
    {
        $result = [];
        foreach ($criteria as $i => $c1) {
            $row = [];
            foreach ($criteria as $j => $c2) {
                $row[$c2->kode] = $matrix[$i][$j];
            }
            $result[$c1->kode] = $row;
        }
        return $result;
    }

    /**
     * Ambil hasil AHP terbaru
     */
    public function getLatestResult(): ?AhpResult
    {
        return AhpResult::latest()->first();
    }

    /**
     * Konversi nilai ke pecahan Saaty untuk display
     */
    public static function toSaatiFraction(float $value): string
    {
        if (abs($value - round($value)) < 0.01) {
            return (string) (int) round($value);
        }

        $fractions = [
            1/9 => '1/9', 1/8 => '1/8', 1/7 => '1/7', 1/6 => '1/6',
            1/5 => '1/5', 1/4 => '1/4', 1/3 => '1/3', 1/2 => '1/2',
        ];

        foreach ($fractions as $decimal => $fraction) {
            if (abs($value - $decimal) < 0.02) {
                return $fraction;
            }
        }

        return number_format($value, 4);
    }
}
