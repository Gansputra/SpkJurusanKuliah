<?php

namespace Database\Seeders;

use App\Models\Alternative;
use Illuminate\Database\Seeder;

class AlternativeSeeder extends Seeder
{
    public function run(): void
    {
        $alternatives = [
            [
                'kode' => 'A1',
                'nama' => 'Teknik Informatika',
                'deskripsi' => 'Mempelajari ilmu komputer, algoritma, pemrograman, dan sistem informasi.',
                'active' => true,
            ],
            [
                'kode' => 'A2',
                'nama' => 'Sistem Informasi',
                'deskripsi' => 'Mempelajari pengelolaan informasi dan teknologi dalam organisasi.',
                'active' => true,
            ],
            [
                'kode' => 'A3',
                'nama' => 'Teknik Industri',
                'deskripsi' => 'Mempelajari optimasi sistem manufaktur, produksi, dan manajemen operasi.',
                'active' => true,
            ],
            [
                'kode' => 'A4',
                'nama' => 'Akuntansi',
                'deskripsi' => 'Mempelajari pencatatan, pelaporan, dan analisis keuangan bisnis.',
                'active' => true,
            ],
            [
                'kode' => 'A5',
                'nama' => 'Manajemen',
                'deskripsi' => 'Mempelajari pengelolaan sumber daya, strategi bisnis, dan kepemimpinan.',
                'active' => true,
            ],
            [
                'kode' => 'A6',
                'nama' => 'Ilmu Komunikasi',
                'deskripsi' => 'Mempelajari teori komunikasi, media massa, dan hubungan masyarakat.',
                'active' => true,
            ],
            [
                'kode' => 'A7',
                'nama' => 'Teknik Mesin',
                'deskripsi' => 'Mempelajari perancangan, analisis, dan pembuatan mesin dan sistem mekanik.',
                'active' => true,
            ],
            [
                'kode' => 'A8',
                'nama' => 'Teknik Sipil',
                'deskripsi' => 'Mempelajari perancangan, pembangunan, dan pemeliharaan infrastruktur.',
                'active' => true,
            ],
            [
                'kode' => 'A9',
                'nama' => 'Psikologi',
                'deskripsi' => 'Mempelajari perilaku manusia, proses mental, dan perkembangan individu.',
                'active' => true,
            ],
            [
                'kode' => 'A10',
                'nama' => 'Hukum',
                'deskripsi' => 'Mempelajari sistem hukum, perundang-undangan, dan praktik litigasi.',
                'active' => true,
            ],
        ];

        foreach ($alternatives as $alt) {
            Alternative::updateOrCreate(['kode' => $alt['kode']], $alt);
        }
    }
}
