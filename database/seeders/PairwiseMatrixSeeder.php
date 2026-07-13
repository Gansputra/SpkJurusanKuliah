<?php

namespace Database\Seeders;

use App\Models\Criteria;
use App\Models\PairwiseMatrix;
use Illuminate\Database\Seeder;

class PairwiseMatrixSeeder extends Seeder
{
    public function run(): void
    {
        $criteriaIds = Criteria::orderBy('urutan')->pluck('id')->toArray();
        $n = count($criteriaIds);

        // Pairwise comparison values (Saaty scale 1-9)
        // This matrix is designed to be consistent (CR <= 0.1)
        // C1=Nilai Akademik, C2=Minat, C3=Bakat, C4=Peluang Kerja, C5=Biaya Kuliah
        $matrix = [
            //  C1    C2    C3    C4    C5
            [1,    3,    5,    3,    7],    // C1
            [1/3,  1,    3,    1,    5],    // C2
            [1/5,  1/3,  1,    1/3,  3],   // C3
            [1/3,  1,    3,    1,    5],    // C4
            [1/7,  1/5,  1/3,  1/5,  1],   // C5
        ];

        PairwiseMatrix::truncate();

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                PairwiseMatrix::create([
                    'criteria1_id' => $criteriaIds[$i],
                    'criteria2_id' => $criteriaIds[$j],
                    'nilai' => $matrix[$i][$j],
                ]);
            }
        }
    }
}
