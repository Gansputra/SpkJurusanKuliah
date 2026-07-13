<?php

namespace Database\Seeders;

use App\Models\Alternative;
use App\Models\AlternativeScore;
use App\Models\Criteria;
use Illuminate\Database\Seeder;

class AlternativeScoreSeeder extends Seeder
{
    public function run(): void
    {
        $criteria = Criteria::orderBy('urutan')->get();
        $alternatives = Alternative::orderBy('kode')->get();

        // Scores matrix [alternative][criteria]
        // C1=Nilai Akademik (1-10), C2=Minat (1-10), C3=Bakat (1-10),
        // C4=Peluang Kerja (1-10), C5=Biaya Kuliah (dalam juta/semester)
        $scores = [
            'A1'  => [8.5, 9.0, 8.0, 9.5, 5.0],   // Teknik Informatika
            'A2'  => [7.5, 8.5, 7.5, 8.5, 4.5],   // Sistem Informasi
            'A3'  => [8.0, 7.0, 7.5, 8.0, 5.5],   // Teknik Industri
            'A4'  => [7.0, 7.5, 7.0, 7.5, 4.0],   // Akuntansi
            'A5'  => [7.5, 8.0, 7.5, 8.5, 4.5],   // Manajemen
            'A6'  => [7.0, 8.5, 8.0, 7.0, 4.0],   // Ilmu Komunikasi
            'A7'  => [8.5, 7.0, 8.5, 8.0, 5.5],   // Teknik Mesin
            'A8'  => [8.0, 7.5, 8.0, 8.5, 6.0],   // Teknik Sipil
            'A9'  => [7.5, 9.0, 8.5, 7.0, 4.5],   // Psikologi
            'A10' => [7.0, 7.5, 7.0, 7.5, 4.0],   // Hukum
        ];

        AlternativeScore::truncate();

        foreach ($alternatives as $alt) {
            if (!isset($scores[$alt->kode])) continue;
            $altScores = $scores[$alt->kode];
            foreach ($criteria as $idx => $crit) {
                AlternativeScore::create([
                    'alternative_id' => $alt->id,
                    'criteria_id' => $crit->id,
                    'nilai' => $altScores[$idx] ?? 0,
                ]);
            }
        }
    }
}
