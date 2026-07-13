<?php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $criteria = [
            ['kode' => 'C1', 'nama' => 'Nilai Akademik', 'tipe' => 'benefit', 'bobot' => 0, 'urutan' => 1],
            ['kode' => 'C2', 'nama' => 'Minat terhadap Jurusan', 'tipe' => 'benefit', 'bobot' => 0, 'urutan' => 2],
            ['kode' => 'C3', 'nama' => 'Bakat', 'tipe' => 'benefit', 'bobot' => 0, 'urutan' => 3],
            ['kode' => 'C4', 'nama' => 'Peluang Kerja', 'tipe' => 'benefit', 'bobot' => 0, 'urutan' => 4],
            ['kode' => 'C5', 'nama' => 'Biaya Kuliah', 'tipe' => 'cost', 'bobot' => 0, 'urutan' => 5],
        ];

        foreach ($criteria as $c) {
            Criteria::updateOrCreate(['kode' => $c['kode']], $c);
        }
    }
}
