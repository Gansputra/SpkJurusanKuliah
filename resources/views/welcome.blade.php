<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="SPK Pemilihan Jurusan Kuliah menggunakan metode AHP dan TOPSIS - Bantu siswa SMA/SMK menemukan jurusan terbaik"/>
    <title>SPK Pemilihan Jurusan Kuliah - AHP & TOPSIS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 40%, #2563eb 70%, #3b82f6 100%);
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }
        .feature-icon {
            background: linear-gradient(135deg, #dbeafe, #eff6ff);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        .float-animation { animation: float 4s ease-in-out infinite; }
        .badge-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-gray-900 text-lg">SPK Jurusan Kuliah</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#fitur" class="text-sm text-gray-600 hover:text-blue-600 font-medium transition-colors">Fitur</a>
                    <a href="#metode" class="text-sm text-gray-600 hover:text-blue-600 font-medium transition-colors">Metode</a>
                    <a href="#kriteria" class="text-sm text-gray-600 hover:text-blue-600 font-medium transition-colors">Kriteria</a>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('kalkulator') }}" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg">
                        Mulai Rekomendasi →
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient pt-24 pb-20 min-h-screen flex items-center relative overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left content -->
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center px-4 py-2 bg-white/10 border border-white/20 rounded-full text-blue-100 text-sm font-medium mb-6 badge-pulse">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                        Menggunakan Metode AHP + TOPSIS
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
                        Temukan Jurusan
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">
                            Kuliahmu
                        </span>
                        yang Tepat
                    </h1>
                    <p class="text-blue-100 text-lg mb-8 leading-relaxed max-w-lg mx-auto lg:mx-0">
                        Sistem Pendukung Keputusan berbasis ilmiah untuk membantu siswa SMA/SMK
                        memilih jurusan kuliah yang paling sesuai dengan kemampuan, minat, dan tujuan karier.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('kalkulator') }}"
                           class="px-8 py-4 bg-white text-blue-700 font-bold rounded-2xl hover:bg-blue-50 transition-all duration-200 shadow-lg hover:shadow-xl text-center">
                            Mulai Cari Rekomendasi →
                        </a>
                    </div>
                    <div class="mt-10 flex items-center justify-center lg:justify-start space-x-8">
                        <div class="text-center">
                            <p class="text-3xl font-black text-white">{{ $totalJurusan }}+</p>
                            <p class="text-blue-300 text-sm">Jurusan</p>
                        </div>
                        <div class="w-px h-10 bg-blue-600"></div>
                        <div class="text-center">
                            <p class="text-3xl font-black text-white">5</p>
                            <p class="text-blue-300 text-sm">Kriteria</p>
                        </div>
                        <div class="w-px h-10 bg-blue-600"></div>
                        <div class="text-center">
                            <p class="text-3xl font-black text-white">2</p>
                            <p class="text-blue-300 text-sm">Metode Ilmiah</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Visual card -->
                <div class="hidden lg:flex justify-center float-animation">
                    <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-3xl p-8 w-96">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-white font-bold text-lg">Hasil Rekomendasi</h3>
                            <span class="text-xs bg-green-500 text-white px-3 py-1 rounded-full font-medium">✓ Terbaru</span>
                        </div>
                        <!-- Fake ranking bars -->
                        @php
                            $fakeRanking = [
                                ['name' => 'Teknik Informatika', 'score' => 88, 'rank' => 1, 'color' => 'from-yellow-400 to-orange-400'],
                                ['name' => 'Sistem Informasi', 'score' => 76, 'rank' => 2, 'color' => 'from-blue-400 to-blue-600'],
                                ['name' => 'Teknik Industri', 'score' => 71, 'rank' => 3, 'color' => 'from-purple-400 to-purple-600'],
                                ['name' => 'Manajemen', 'score' => 65, 'rank' => 4, 'color' => 'from-green-400 to-green-600'],
                            ];
                        @endphp
                        <div class="space-y-4">
                            @foreach($fakeRanking as $item)
                            <div class="flex items-center space-x-3">
                                <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ $item['rank'] }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-white text-sm font-medium">{{ $item['name'] }}</span>
                                        <span class="text-blue-200 text-xs">{{ $item['score'] }}%</span>
                                    </div>
                                    <div class="h-2 bg-white/20 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r {{ $item['color'] }} rounded-full"
                                             style="width: {{ $item['score'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-6 pt-4 border-t border-white/20">
                            <p class="text-blue-200 text-xs text-center">Powered by AHP + TOPSIS Algorithm</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-4">Fitur Unggulan</h2>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto">Sistem lengkap dengan perhitungan transparan dan visualisasi data yang memudahkan pengambilan keputusan</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $features = [
                        ['icon' => '🧮', 'title' => 'Metode AHP', 'desc' => 'Menentukan bobot kriteria secara ilmiah menggunakan Analytic Hierarchy Process dengan validasi Consistency Ratio'],
                        ['icon' => '📊', 'title' => 'Metode TOPSIS', 'desc' => 'Merangking alternatif berdasarkan kedekatan dengan solusi ideal positif dan negatif secara matematis'],
                        ['icon' => '📈', 'title' => 'Visualisasi Chart', 'desc' => 'Grafik interaktif untuk memvisualisasikan ranking, bobot kriteria, dan perbandingan jurusan secara mudah'],
                        ['icon' => '🔍', 'title' => 'Detail Perhitungan', 'desc' => 'Lihat seluruh proses perhitungan step-by-step, mulai dari matriks hingga nilai preferensi akhir'],
                        ['icon' => '📄', 'title' => 'Export PDF', 'desc' => 'Cetak laporan lengkap dalam format PDF berisi bobot AHP, perhitungan TOPSIS, dan ranking jurusan'],
                        ['icon' => '👤', 'title' => 'Rekomendasi Personal', 'desc' => 'User dapat mengisi nilai diri sendiri dan mendapatkan rekomendasi jurusan yang dipersonalisasi'],
                    ];
                @endphp
                @foreach($features as $feature)
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 card-hover">
                    <div class="text-4xl mb-4">{{ $feature['icon'] }}</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Metode Section -->
    <section id="metode" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-4">Alur Perhitungan</h2>
                <p class="text-gray-500 text-lg">Proses ilmiah yang transparan dan terstruktur</p>
            </div>
            <div class="grid md:grid-cols-2 gap-12 items-start">
                <!-- AHP -->
                <div class="bg-blue-50 rounded-3xl p-8 border border-blue-100">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold">A</div>
                        <h3 class="text-xl font-bold text-blue-900">Metode AHP</h3>
                    </div>
                    <div class="space-y-3">
                        @php
                            $ahpSteps = [
                                'Matriks Perbandingan Berpasangan (Skala Saaty 1-9)',
                                'Normalisasi Matriks Kolom',
                                'Priority Vector (Bobot Kriteria)',
                                'Hitung λmax (Lambda Max)',
                                'Hitung CI = (λmax - n) / (n-1)',
                                'Hitung CR = CI / RI',
                                'Validasi Konsistensi (CR ≤ 0.1)',
                            ];
                        @endphp
                        @foreach($ahpSteps as $idx => $step)
                        <div class="flex items-center space-x-3">
                            <span class="w-6 h-6 bg-blue-600 text-white rounded-full text-xs flex items-center justify-center font-bold flex-shrink-0">{{ $idx + 1 }}</span>
                            <span class="text-blue-800 text-sm">{{ $step }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- TOPSIS -->
                <div class="bg-indigo-50 rounded-3xl p-8 border border-indigo-100">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold">T</div>
                        <h3 class="text-xl font-bold text-indigo-900">Metode TOPSIS</h3>
                    </div>
                    <div class="space-y-3">
                        @php
                            $topsisSteps = [
                                'Matriks Keputusan dari Data Nilai',
                                'Normalisasi Matriks (Euclidean)',
                                'Normalisasi Terbobot × Bobot AHP',
                                'Solusi Ideal Positif (A+)',
                                'Solusi Ideal Negatif (A-)',
                                'Jarak ke Ideal Positif (D+)',
                                'Jarak ke Ideal Negatif (D-)',
                                'Nilai Preferensi Vi = D- / (D+ + D-)',
                                'Ranking Berdasarkan Vi Tertinggi',
                            ];
                        @endphp
                        @foreach($topsisSteps as $idx => $step)
                        <div class="flex items-center space-x-3">
                            <span class="w-6 h-6 bg-indigo-600 text-white rounded-full text-xs flex items-center justify-center font-bold flex-shrink-0">{{ $idx + 1 }}</span>
                            <span class="text-indigo-800 text-sm">{{ $step }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kriteria Section -->
    <section id="kriteria" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mb-4">Kriteria Penilaian</h2>
                <p class="text-gray-500 text-lg">5 kriteria ilmiah yang digunakan dalam penilaian</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-6">
                @php
                    $criteria = [
                        ['kode' => 'C1', 'nama' => 'Nilai Akademik', 'tipe' => 'benefit', 'emoji' => '📚', 'color' => 'blue'],
                        ['kode' => 'C2', 'nama' => 'Minat Jurusan', 'tipe' => 'benefit', 'emoji' => '❤️', 'color' => 'red'],
                        ['kode' => 'C3', 'nama' => 'Bakat', 'tipe' => 'benefit', 'emoji' => '⭐', 'color' => 'yellow'],
                        ['kode' => 'C4', 'nama' => 'Peluang Kerja', 'tipe' => 'benefit', 'emoji' => '💼', 'color' => 'green'],
                        ['kode' => 'C5', 'nama' => 'Biaya Kuliah', 'tipe' => 'cost', 'emoji' => '💰', 'color' => 'purple'],
                    ];
                @endphp
                @foreach($criteria as $c)
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center card-hover">
                    <div class="text-3xl mb-3">{{ $c['emoji'] }}</div>
                    <div class="text-xs font-bold text-gray-400 mb-1">{{ $c['kode'] }}</div>
                    <h3 class="font-bold text-gray-900 mb-2 text-sm">{{ $c['nama'] }}</h3>
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                        {{ $c['tipe'] === 'benefit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($c['tipe']) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 hero-gradient">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="text-3xl sm:text-4xl font-black text-white mb-4">
                Siap Menemukan Jurusan Terbaikmu?
            </h2>
            <p class="text-blue-100 text-lg mb-8">
                Gunakan kalkulator berbasis ilmiah AHP dan TOPSIS sekarang untuk mencari alternatif jurusan terbaik yang sesuai dengan minat, bakat, nilai akademik, dan anggaranmu secara instan.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('kalkulator') }}"
                   class="px-10 py-4 bg-white text-blue-700 font-bold rounded-2xl hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl text-center font-bold">
                    Buka Kalkulator Rekomendasi →
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm">© {{ date('Y') }} SPK Pemilihan Jurusan Kuliah. Dibuat dengan ❤️ menggunakan Laravel & Metode AHP+TOPSIS.</p>
        </div>
    </footer>

</body>
</html>
