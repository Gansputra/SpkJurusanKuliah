<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="Kalkulator SPK Pemilihan Jurusan Kuliah - Hitung rekomendasi jurusan menggunakan AHP & TOPSIS secara instan tanpa database"/>
    <title>Kalkulator SPK Jurusan Kuliah - AHP & TOPSIS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .hero-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 40%, #2563eb 70%, #3b82f6 100%);
        }
        .card-shadow {
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 5px 15px -3px rgba(0, 0, 0, 0.02);
        }
        @media print {
            .no-print { display: none !important; }
            .print-full { width: 100% !important; margin: 0 !important; padding: 0 !important; }
            body { background: white !important; color: black !important; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col" x-data="spkCalculator()">

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-gray-900 text-lg">SPK Jurusan Kuliah</span>
                </a>
                <div class="flex items-center space-x-3">
                    <a href="/" class="text-sm font-semibold text-gray-600 hover:text-blue-600 transition-colors">
                        ← Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content Area -->
    <main class="flex-1 pt-24 pb-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full print-full">
        <!-- Header -->
        <div class="mb-8 text-center no-print">
            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">🎯 Kalkulator Rekomendasi Jurusan</h1>
            <p class="text-gray-500 text-sm mt-2 max-w-xl mx-auto">
                Cari tahu jurusan kuliah terbaik berdasarkan kriteria pribadi Anda dengan metode AHP dan TOPSIS secara real-time.
            </p>
        </div>

        <!-- Wizard Navigation -->
        <div class="flex items-center justify-center mb-8 no-print">
            <div class="inline-flex p-1.5 bg-gray-200/80 backdrop-blur rounded-2xl space-x-1">
                <button @click="step = 1"
                        :class="step === 1 ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                        class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200">
                    1. Bobot Kriteria (AHP)
                </button>
                <button @click="step = 2"
                        :class="step === 2 ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                        class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200">
                    2. Input Nilai Diri
                </button>
                <button @click="calculate()"
                        :class="step === 3 ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                        class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200">
                    3. Hasil Rekomendasi
                </button>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- STEP 1: BOBOT KRITERIA (AHP)               -->
        <!-- ========================================== -->
        <div x-show="step === 1" class="no-print space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-gray-100 card-shadow">
                <h2 class="text-xl font-extrabold text-gray-900 mb-2">Pilih Metode Pembobotan Kriteria</h2>
                <p class="text-gray-450 text-xs mb-6">Tentukan bobot kepentingan untuk masing-masing kriteria (Nilai Akademik, Minat, Bakat, Peluang Kerja, Biaya Kuliah).</p>

                <div class="grid md:grid-cols-3 gap-4 mb-6">
                    <!-- Option A -->
                    <label class="relative flex flex-col p-4 bg-gray-50 border-2 rounded-2xl cursor-pointer hover:bg-gray-100/50 transition-all"
                           :class="weightMethod === 'default' ? 'border-blue-600 bg-blue-50/20' : 'border-gray-200'">
                        <input type="radio" name="weightMethod" value="default" x-model="weightMethod" class="sr-only"/>
                        <span class="font-bold text-gray-900 text-sm flex items-center">
                            <span class="w-4 h-4 border border-gray-300 rounded-full mr-2 flex items-center justify-center"
                                  :class="weightMethod === 'default' && 'border-blue-600 bg-blue-600'">
                                <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                            </span>
                            Bobot Default (Rekomendasi)
                        </span>
                        <span class="text-xs text-gray-400 mt-2">
                            Menggunakan standard bobot teruji: Akademik (25%), Minat (25%), Bakat (20%), Kerja (18%), Biaya (12%).
                        </span>
                    </label>

                    <!-- Option B -->
                    <label class="relative flex flex-col p-4 bg-gray-50 border-2 rounded-2xl cursor-pointer hover:bg-gray-100/50 transition-all"
                           :class="weightMethod === 'custom' ? 'border-blue-600 bg-blue-50/20' : 'border-gray-200'">
                        <input type="radio" name="weightMethod" value="custom" x-model="weightMethod" class="sr-only"/>
                        <span class="font-bold text-gray-900 text-sm flex items-center">
                            <span class="w-4 h-4 border border-gray-300 rounded-full mr-2 flex items-center justify-center"
                                  :class="weightMethod === 'custom' && 'border-blue-600 bg-blue-600'">
                                <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                            </span>
                            Atur dengan Slider Bebas
                        </span>
                        <span class="text-xs text-gray-400 mt-2">
                            Sesuaikan bobot kepentingan secara langsung menggunakan slider untuk masing-masing kriteria sesuai preferensi Anda.
                        </span>
                    </label>

                    <!-- Option C -->
                    <label class="relative flex flex-col p-4 bg-gray-50 border-2 rounded-2xl cursor-pointer hover:bg-gray-100/50 transition-all"
                           :class="weightMethod === 'ahp' ? 'border-blue-600 bg-blue-50/20' : 'border-gray-200'">
                        <input type="radio" name="weightMethod" value="ahp" x-model="weightMethod" class="sr-only"/>
                        <span class="font-bold text-gray-900 text-sm flex items-center">
                            <span class="w-4 h-4 border border-gray-300 rounded-full mr-2 flex items-center justify-center"
                                  :class="weightMethod === 'ahp' && 'border-blue-600 bg-blue-600'">
                                <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                            </span>
                            Hitung Ilmiah (Matriks AHP)
                        </span>
                        <span class="text-xs text-gray-400 mt-2">
                            Bandingkan kriteria secara berpasangan dengan skala Saaty 1-9 untuk menghasilkan bobot kriteria yang valid secara matematis.
                        </span>
                    </label>
                </div>

                <!-- Display/Form based on Method -->
                <!-- Default Display -->
                <div x-show="weightMethod === 'default'" class="p-4 bg-gray-50 rounded-2xl border border-gray-200/60 space-y-3">
                    <h3 class="font-bold text-gray-800 text-sm">Bobot yang Digunakan:</h3>
                    <div class="grid sm:grid-cols-5 gap-4">
                        <template x-for="(crit, index) in criteria" :key="crit.kode">
                            <div class="bg-white rounded-xl p-3 border border-gray-100 text-center">
                                <span class="text-xs text-gray-400 font-medium block" x-text="crit.kode"></span>
                                <span class="text-sm font-bold text-gray-800 block mt-1" x-text="crit.nama"></span>
                                <span class="text-lg font-black text-blue-600 mt-1 block" x-text="Math.round(defaultWeights[index]*100) + '%'"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Custom Slider Form -->
                <div x-show="weightMethod === 'custom'" class="p-5 bg-gray-50 rounded-2xl border border-gray-200/60 space-y-5">
                    <h3 class="font-bold text-gray-800 text-sm mb-3">Atur Nilai Kepentingan (Geser Slider):</h3>
                    <div class="space-y-4">
                        <template x-for="(crit, index) in criteria" :key="crit.kode">
                            <div class="bg-white p-4 rounded-xl border border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="w-full sm:w-1/3">
                                    <span class="text-xs font-bold text-gray-400 block" x-text="crit.kode"></span>
                                    <span class="text-sm font-bold text-gray-800" x-text="crit.nama"></span>
                                </div>
                                <div class="flex-1 flex items-center space-x-4">
                                    <input type="range" min="1" max="10" x-model.number="customSliders[index]"
                                           @input="normalizeSliders()"
                                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600"/>
                                    <span class="w-12 text-right font-black text-blue-600 text-sm" x-text="Math.round(customWeights[index] * 100) + '%'"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- AHP Pairwise Form -->
                <div x-show="weightMethod === 'ahp'" class="space-y-6">
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-200/60 space-y-4">
                        <h3 class="font-bold text-gray-800 text-sm">Bandingkan Pasangan Kriteria Di Bawah:</h3>
                        <p class="text-xs text-gray-400">Pilih kriteria mana yang lebih penting dan seberapa jauh kepentingannya dibandingkan kriteria lainnya.</p>

                        <div class="space-y-5 mt-4">
                            <template x-for="(pair, pairIndex) in ahpPairs" :key="pairIndex">
                                <div class="bg-white p-4 rounded-2xl border border-gray-100 space-y-3">
                                    <div class="flex justify-between items-center text-xs font-bold text-gray-500">
                                        <span x-text="criteria[pair.i].nama" :class="ahpSliders[pairIndex] < 0 ? 'text-blue-600' : ''"></span>
                                        <span class="bg-gray-100 px-2 py-0.5 rounded" x-text="'Perbandingan ' + (pairIndex+1)"></span>
                                        <span x-text="criteria[pair.j].nama" :class="ahpSliders[pairIndex] > 0 ? 'text-blue-600' : ''"></span>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <span class="text-xs font-semibold text-gray-400 w-16 text-left"
                                              :class="ahpSliders[pairIndex] < 0 && 'text-blue-600 font-bold'">
                                            <span x-show="ahpSliders[pairIndex] < 0" x-text="Math.abs(ahpSliders[pairIndex]) + 1"></span>
                                            <span x-show="ahpSliders[pairIndex] >= 0">1</span>
                                        </span>
                                        <input type="range" min="-8" max="8" step="1" x-model.number="ahpSliders[pairIndex]"
                                               @input="calculateAHP()"
                                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600"/>
                                        <span class="text-xs font-semibold text-gray-400 w-16 text-right"
                                              :class="ahpSliders[pairIndex] > 0 && 'text-blue-600 font-bold'">
                                            <span x-show="ahpSliders[pairIndex] > 0" x-text="ahpSliders[pairIndex] + 1"></span>
                                            <span x-show="ahpSliders[pairIndex] <= 0">1</span>
                                        </span>
                                    </div>
                                    <div class="text-center text-xs">
                                        <span class="text-gray-550" x-text="getPairDescription(pairIndex)"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- AHP Consistency Output -->
                    <div class="p-5 rounded-2xl border flex flex-col md:flex-row md:items-center justify-between gap-4"
                         :class="ahpResult.isConsistent ? 'bg-green-50/50 border-green-200 text-green-800' : 'bg-amber-50/50 border-amber-200 text-amber-800'">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xl" x-text="ahpResult.isConsistent ? '✅' : '⚠️'"></span>
                                <h4 class="font-bold text-sm" x-text="ahpResult.isConsistent ? 'Matriks Perbandingan Konsisten' : 'Matriks Tidak Konsisten!'"></h4>
                            </div>
                            <p class="text-xs mt-1" :class="ahpResult.isConsistent ? 'text-green-600' : 'text-amber-600'">
                                Nilai Consistency Ratio (CR) adalah <strong x-text="ahpResult.cr.toFixed(4)"></strong>.
                                <span x-show="ahpResult.isConsistent">Nilai ini memenuhi syarat batas (CR ≤ 0.1). Bobot valid untuk digunakan.</span>
                                <span x-show="!ahpResult.isConsistent">Nilai melebihi batas 0.1. Silakan sesuaikan kembali perbandingan Anda agar logis secara matematis.</span>
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="(w, idx) in ahpResult.weights" :key="idx">
                                <span class="px-2.5 py-1 bg-white border rounded-lg text-xs font-bold text-gray-700">
                                    <span x-text="criteria[idx].kode"></span>: <span x-text="Math.round(w*100) + '%'"></span>
                                </span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Next button -->
                <div class="mt-6 flex justify-end">
                    <button @click="step = 2"
                            :disabled="weightMethod === 'ahp' && !ahpResult.isConsistent"
                            :class="weightMethod === 'ahp' && !ahpResult.isConsistent ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-blue-600 hover:bg-blue-700'"
                            class="px-6 py-3 text-white font-bold rounded-xl text-sm transition-colors shadow-sm cursor-pointer">
                        Lanjut ke Langkah 2 →
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- STEP 2: INPUT NILAI DIRI                   -->
        <!-- ========================================== -->
        <div x-show="step === 2" class="no-print space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-gray-100 card-shadow">
                <h2 class="text-xl font-extrabold text-gray-900 mb-2">Masukkan Nilai Diri Anda</h2>
                <p class="text-gray-400 text-xs mb-6">Masukkan data diri Anda untuk disesuaikan dengan kriteria masing-masing jurusan.</p>

                <div class="space-y-6">
                    <!-- C1: Nilai Akademik -->
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 space-y-3">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-extrabold text-gray-800 text-sm">1. Nilai Akademik (C1)</h3>
                                <p class="text-gray-400 text-xs mt-0.5">Rata-rata nilai rapor SMA/SMK atau IPK kuliah Anda saat ini (Skala 1 - 10).</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-black text-sm" x-text="userScores[0].toFixed(1)"></span>
                        </div>
                        <input type="range" min="1" max="10" step="0.1" x-model.number="userScores[0]"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600"/>
                        <div class="flex justify-between text-[10px] text-gray-400 font-medium">
                            <span>Sangat Rendah (1.0)</span>
                            <span>Cukup (5.0)</span>
                            <span>Sangat Tinggi (10.0)</span>
                        </div>
                    </div>

                    <!-- C2: Minat -->
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 space-y-3">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-extrabold text-gray-800 text-sm">2. Minat terhadap Jurusan (C2)</h3>
                                <p class="text-gray-400 text-xs mt-0.5">Ketertarikan atau hasrat pribadi Anda untuk berkuliah dan belajar secara umum (Skala 1 - 10).</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-black text-sm" x-text="userScores[1].toFixed(1)"></span>
                        </div>
                        <input type="range" min="1" max="10" step="0.1" x-model.number="userScores[1]"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600"/>
                        <div class="flex justify-between text-[10px] text-gray-400 font-medium">
                            <span>Tidak Tertarik (1.0)</span>
                            <span>Tertarik (5.0)</span>
                            <span>Sangat Antusias (10.0)</span>
                        </div>
                    </div>

                    <!-- C3: Bakat -->
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 space-y-3">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-extrabold text-gray-800 text-sm">3. Bakat (C3)</h3>
                                <p class="text-gray-400 text-xs mt-0.5">Kemampuan alami atau bakat bawaan Anda (logika, seni, bahasa, dll) (Skala 1 - 10).</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-black text-sm" x-text="userScores[2].toFixed(1)"></span>
                        </div>
                        <input type="range" min="1" max="10" step="0.1" x-model.number="userScores[2]"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600"/>
                        <div class="flex justify-between text-[10px] text-gray-400 font-medium">
                            <span>Sangat Kurang (1.0)</span>
                            <span>Rata-rata (5.0)</span>
                            <span>Sangat Berbakat (10.0)</span>
                        </div>
                    </div>

                    <!-- C4: Peluang Kerja -->
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 space-y-3">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-extrabold text-gray-800 text-sm">4. Peluang Kerja (C4)</h3>
                                <p class="text-gray-400 text-xs mt-0.5">Seberapa penting prospek karier dan lapangan kerja yang luas setelah lulus menurut Anda (Skala 1 - 10).</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-black text-sm" x-text="userScores[3].toFixed(1)"></span>
                        </div>
                        <input type="range" min="1" max="10" step="0.1" x-model.number="userScores[3]"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600"/>
                        <div class="flex justify-between text-[10px] text-gray-400 font-medium">
                            <span>Biasa Saja (1.0)</span>
                            <span>Penting (5.0)</span>
                            <span>Sangat Prioritas (10.0)</span>
                        </div>
                    </div>

                    <!-- C5: Anggaran Biaya Kuliah -->
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200/60 space-y-3">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-extrabold text-gray-800 text-sm">5. Anggaran Biaya Kuliah (C5)</h3>
                                <p class="text-gray-400 text-xs mt-0.5">Kemampuan anggaran biaya kuliah per semester yang sanggup Anda bayar (Juta Rupiah / Semester).</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full font-black text-sm" x-text="userScores[4].toFixed(1) + ' Juta'"></span>
                        </div>
                        <input type="range" min="1" max="10" step="0.5" x-model.number="userScores[4]"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600"/>
                        <div class="flex justify-between text-[10px] text-gray-400 font-medium">
                            <span>Sangat Hemat (1.0 Juta)</span>
                            <span>Sedang (5.0 Juta)</span>
                            <span>Tinggi (10.0 Juta/lebih)</span>
                        </div>
                    </div>
                </div>

                <!-- Navigation buttons -->
                <div class="mt-8 flex justify-between">
                    <button @click="step = 1" class="px-5 py-3 text-gray-600 bg-gray-150 font-bold rounded-xl text-sm hover:bg-gray-200 transition-colors cursor-pointer">
                        ← Kembali
                    </button>
                    <button @click="calculate()" class="px-7 py-3 text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-xl text-sm transition-colors shadow-sm cursor-pointer">
                        Hitung Rekomendasi 🚀
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- STEP 3: HASIL REKOMENDASI                  -->
        <!-- ========================================== -->
        <div x-show="step === 3" class="space-y-6">
            <!-- Printing Header (Visible only when printed) -->
            <div class="hidden print:block text-center border-b pb-4 mb-6">
                <h1 class="text-2xl font-bold">Laporan Rekomendasi Jurusan Kuliah</h1>
                <p class="text-sm text-gray-500">Dihitung secara ilmiah dengan Metode AHP + TOPSIS</p>
                <p class="text-xs text-gray-400 mt-1">Tanggal Cetak: <span x-text="new Date().toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})"></span></p>
            </div>

            <!-- Top Result Highlight Card -->
            <template x-if="topsisResults.length > 0">
                <div class="bg-gradient-to-r from-yellow-400 to-amber-500 rounded-3xl p-6 text-amber-950 card-shadow relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 opacity-10 text-9xl">🥇</div>
                    <div class="flex items-center space-x-5 relative z-10">
                        <div class="text-5xl sm:text-6xl flex-shrink-0">🥇</div>
                        <div>
                            <p class="text-xs sm:text-sm font-bold uppercase tracking-wider opacity-85">Jurusan Terbaik Untukmu</p>
                            <h3 class="text-2xl sm:text-3xl font-black mt-0.5" x-text="topsisResults[0].alternative.nama"></h3>
                            <p class="text-sm mt-2 font-medium opacity-90">
                                Kecocokan: <strong class="text-lg font-black" x-text="(topsisResults[0].nilai_preferensi * 100).toFixed(2) + '%'"></strong> (Nilai Preferensi: <span x-text="topsisResults[0].nilai_preferensi.toFixed(4)"></span>)
                            </p>
                            <p class="text-xs sm:text-sm mt-2 opacity-85 max-w-2xl" x-text="topsisResults[0].alternative.deskripsi"></p>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Chart and Weights Grid -->
            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Chart -->
                <div class="bg-white rounded-3xl p-5 border border-gray-100 card-shadow no-print">
                    <h3 class="font-extrabold text-gray-900 mb-4 text-base flex items-center">
                        <span class="mr-2">📊</span> Grafik Ranking Jurusan (%)
                    </h3>
                    <div class="relative h-64">
                        <canvas id="rankingChart"></canvas>
                    </div>
                </div>

                <!-- Bobot kriteria yang digunakan -->
                <div class="bg-white rounded-3xl p-5 border border-gray-100 card-shadow">
                    <h3 class="font-extrabold text-gray-900 mb-4 text-base flex items-center">
                        <span class="mr-2">⚙️</span> Bobot Kriteria yang Digunakan
                    </h3>
                    <div class="space-y-3">
                        <template x-for="(w, idx) in activeWeights" :key="idx">
                            <div>
                                <div class="flex justify-between items-center mb-1 text-xs">
                                    <span class="font-bold text-gray-700" x-text="criteria[idx].kode + ' - ' + criteria[idx].nama"></span>
                                    <span class="text-blue-600 font-extrabold" x-text="(w * 100).toFixed(1) + '%'"></span>
                                </div>
                                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full" :style="'width: ' + (w * 100) + '%'"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="mt-4 p-3 bg-blue-50/50 rounded-xl text-xs text-blue-700 font-medium">
                        Metode pembobotan: <span class="font-bold capitalize" x-text="weightMethod === 'default' ? 'Bobot Default' : (weightMethod === 'custom' ? 'Kustomisasi Slider' : 'Analisis AHP')"></span>
                    </div>
                </div>
            </div>

            <!-- Print Data Summary (Only visible when printing) -->
            <div class="hidden print:block bg-gray-50 border rounded-2xl p-4 mb-4">
                <h3 class="font-bold text-sm mb-2">Nilai Masukan Anda:</h3>
                <div class="grid grid-cols-5 gap-2 text-center text-xs">
                    <template x-for="(crit, index) in criteria" :key="crit.kode">
                        <div class="bg-white p-2 border rounded-lg">
                            <span class="text-gray-400 font-bold block" x-text="crit.kode"></span>
                            <span class="font-bold block mt-1" x-text="userScores[index].toFixed(1)"></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Table of Rankings -->
            <div class="bg-white rounded-3xl border border-gray-100 card-shadow overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between no-print">
                    <h3 class="font-extrabold text-gray-900 text-base">Urutan Rekomendasi Jurusan</h3>
                    <div class="flex space-x-2">
                        <button @click="window.print()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl flex items-center transition-colors cursor-pointer">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak Laporan / PDF
                        </button>
                    </div>
                </div>
                <div class="px-5 py-4 border-b border-gray-100 hidden print:block">
                    <h3 class="font-bold text-gray-900 text-sm">Tabel Hasil Perangkingan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Rank</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Alternatif Jurusan</th>
                                <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 uppercase">Nilai Preferensi (Vi)</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tingkat Kesesuaian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(r, idx) in topsisResults" :key="r.alternative.kode">
                                <tr class="hover:bg-gray-50/50 transition-colors"
                                    :class="idx === 0 ? 'bg-yellow-50/30' : ''">
                                    <td class="px-5 py-4 font-bold">
                                        <template x-if="idx < 3">
                                            <span class="text-2xl" x-text="['🥇', '🥈', '🥉'][idx]"></span>
                                        </template>
                                        <template x-if="idx >= 3">
                                            <span class="w-7 h-7 inline-flex items-center justify-center bg-gray-100 text-gray-600 rounded-full text-xs font-bold" x-text="idx + 1"></span>
                                        </template>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-extrabold text-gray-800 text-sm" x-text="r.alternative.nama"></div>
                                        <div class="text-[10px] text-gray-400 font-bold tracking-wider mt-0.5" x-text="r.alternative.kode"></div>
                                        <div class="text-xs text-gray-400 mt-1 max-w-md line-clamp-2" x-text="r.alternative.deskripsi"></div>
                                    </td>
                                    <td class="px-5 py-4 text-center font-black text-blue-600" x-text="r.nilai_preferensi.toFixed(4)"></td>
                                    <td class="px-5 py-4 w-44">
                                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full"
                                                 :class="idx === 0 ? 'bg-gradient-to-r from-yellow-400 to-amber-500' : 'bg-gradient-to-r from-blue-500 to-indigo-500'"
                                                 :style="'width: ' + (r.nilai_preferensi * 100) + '%'"></div>
                                        </div>
                                        <span class="text-[10px] text-gray-400 block mt-1 font-bold" x-text="(r.nilai_preferensi * 100).toFixed(1) + '%'"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Calculation Detail Accordion -->
            <div class="bg-white rounded-3xl border border-gray-100 card-shadow overflow-hidden no-print" x-data="{ open: false }">
                <button @click="open = !open" class="w-full px-5 py-4 flex items-center justify-between hover:bg-gray-50/50 transition-all font-extrabold text-gray-900 border-none outline-none cursor-pointer">
                    <span class="flex items-center"><span class="mr-2">🔍</span> Detail Perhitungan Matematis (AHP & TOPSIS)</span>
                    <svg class="w-5 h-5 text-gray-500 transition-transform duration-205" :class="open ? 'transform rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="open" class="border-t border-gray-150 p-5 space-y-6">
                    <!-- Step 1: Decision Matrix -->
                    <div class="space-y-3">
                        <h4 class="font-extrabold text-gray-800 text-sm">1. Matriks Keputusan (D)</h4>
                        <p class="text-xs text-gray-400">Diperoleh dari tingkat kemiripan: <code>10 - |Nilai Diri - Nilai Acuan Jurusan|</code>.</p>
                        <div class="overflow-x-auto border rounded-xl">
                            <table class="min-w-full text-center text-xs">
                                <thead>
                                    <tr class="bg-gray-50 font-bold border-b text-gray-500">
                                        <th class="px-4 py-2.5 text-left">Jurusan</th>
                                        <template x-for="crit in criteria">
                                            <th class="px-4 py-2.5" x-text="crit.kode"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="r in topsisResults" :key="r.alternative.kode">
                                        <tr class="border-b hover:bg-gray-50/30">
                                            <td class="px-4 py-2.5 font-bold text-left text-gray-700" x-text="r.alternative.nama"></td>
                                            <template x-for="val in r.decision_matrix_row">
                                                <td class="px-4 py-2.5 text-gray-600" x-text="val.toFixed(2)"></td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Step 2: Normalized Decision Matrix -->
                    <div class="space-y-3">
                        <h4 class="font-extrabold text-gray-800 text-sm">2. Matriks Keputusan Ternormalisasi (R)</h4>
                        <p class="text-xs text-gray-400">Normalisasi Euclidean untuk masing-masing kriteria.</p>
                        <div class="overflow-x-auto border rounded-xl">
                            <table class="min-w-full text-center text-xs">
                                <thead>
                                    <tr class="bg-gray-50 font-bold border-b text-gray-500">
                                        <th class="px-4 py-2.5 text-left">Jurusan</th>
                                        <template x-for="crit in criteria">
                                            <th class="px-4 py-2.5" x-text="crit.kode"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="r in topsisResults" :key="r.alternative.kode">
                                        <tr class="border-b hover:bg-gray-50/30">
                                            <td class="px-4 py-2.5 font-bold text-left text-gray-700" x-text="r.alternative.nama"></td>
                                            <template x-for="val in r.normalized_row">
                                                <td class="px-4 py-2.5 text-gray-600" x-text="val.toFixed(4)"></td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Step 3: Weighted Normalized Matrix -->
                    <div class="space-y-3">
                        <h4 class="font-extrabold text-gray-800 text-sm">3. Matriks Terbobot Ternormalisasi (V)</h4>
                        <p class="text-xs text-gray-400">Hasil perkalian Matriks Ternormalisasi (R) dengan Bobot Kriteria (W).</p>
                        <div class="overflow-x-auto border rounded-xl">
                            <table class="min-w-full text-center text-xs">
                                <thead>
                                    <tr class="bg-gray-50 font-bold border-b text-gray-500">
                                        <th class="px-4 py-2.5 text-left">Jurusan</th>
                                        <template x-for="crit in criteria">
                                            <th class="px-4 py-2.5" x-text="crit.kode"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="r in topsisResults" :key="r.alternative.kode">
                                        <tr class="border-b hover:bg-gray-50/30">
                                            <td class="px-4 py-2.5 font-bold text-left text-gray-700" x-text="r.alternative.nama"></td>
                                            <template x-for="val in r.weighted_row">
                                                <td class="px-4 py-2.5 text-gray-600" x-text="val.toFixed(4)"></td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Ideals and distances -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Step 4: Ideal Solutions -->
                        <div class="space-y-3">
                            <h4 class="font-extrabold text-gray-800 text-sm">4. Solusi Ideal Positif (A+) & Solusi Ideal Negatif (A-)</h4>
                            <p class="text-xs text-gray-400">Ingat: C1-C4 (Benefit, Positif = Max, Negatif = Min) sedangkan C5 (Cost, Positif = Min, Negatif = Max).</p>
                            <div class="overflow-x-auto border rounded-xl">
                                <table class="min-w-full text-center text-xs">
                                    <thead>
                                        <tr class="bg-gray-50 font-bold border-b text-gray-500">
                                            <th class="px-4 py-2.5">Solusi</th>
                                            <template x-for="crit in criteria">
                                                <th class="px-4 py-2.5" x-text="crit.kode"></th>
                                            </template>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="border-b">
                                            <td class="px-4 py-2.5 font-bold text-green-700 bg-green-50/30">Ideal Positif (A+)</td>
                                            <template x-for="val in calculationDetails.idealPositive">
                                                <td class="px-4 py-2.5 font-semibold text-green-600 bg-green-50/10" x-text="val.toFixed(4)"></td>
                                            </template>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2.5 font-bold text-red-700 bg-red-50/30">Ideal Negatif (A-)</td>
                                            <template x-for="val in calculationDetails.idealNegative">
                                                <td class="px-4 py-2.5 font-semibold text-red-600 bg-red-50/10" x-text="val.toFixed(4)"></td>
                                            </template>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Step 5: Distances & Preferences -->
                        <div class="space-y-3">
                            <h4 class="font-extrabold text-gray-800 text-sm">5. Jarak Ideal (D+/D-) & Preferensi (V)</h4>
                            <div class="overflow-x-auto border rounded-xl">
                                <table class="min-w-full text-center text-xs">
                                    <thead>
                                        <tr class="bg-gray-50 font-bold border-b text-gray-500">
                                            <th class="px-4 py-2.5 text-left">Jurusan</th>
                                            <th class="px-4 py-2.5">Jarak Positif (D+)</th>
                                            <th class="px-4 py-2.5">Jarak Negatif (D-)</th>
                                            <th class="px-4 py-2.5">Preferensi (Vi)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="r in topsisResults" :key="r.alternative.kode">
                                            <tr class="border-b hover:bg-gray-50/30">
                                                <td class="px-4 py-2.5 font-bold text-left text-gray-700" x-text="r.alternative.nama"></td>
                                                <td class="px-4 py-2.5 text-red-600 font-medium" x-text="r.d_plus.toFixed(6)"></td>
                                                <td class="px-4 py-2.5 text-green-600 font-medium" x-text="r.d_minus.toFixed(6)"></td>
                                                <td class="px-4 py-2.5 text-blue-600 font-black" x-text="r.nilai_preferensi.toFixed(6)"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back and recalculate buttons -->
            <div class="mt-8 flex justify-between no-print">
                <button @click="step = 2" class="px-5 py-3 text-gray-600 bg-gray-150 font-bold rounded-xl text-sm hover:bg-gray-200 transition-colors cursor-pointer">
                    ← Ubah Nilai Diri
                </button>
                <button @click="resetCalculator()" class="px-5 py-3 text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-xl text-sm transition-colors shadow-sm cursor-pointer">
                    Mulai Ulang Kalkulator 🔄
                </button>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-8 no-print mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm">© 2026 SPK Pemilihan Jurusan Kuliah. Dibuat dengan ❤️ tanpa database menggunakan Metode AHP+TOPSIS.</p>
        </div>
    </footer>

    <!-- JS Logic -->
    <script>
        function spkCalculator() {
            // Preset data
            const criteria = [
                { kode: 'C1', nama: 'Nilai Akademik', tipe: 'benefit' },
                { kode: 'C2', nama: 'Minat terhadap Jurusan', tipe: 'benefit' },
                { kode: 'C3', nama: 'Bakat', tipe: 'benefit' },
                { kode: 'C4', nama: 'Peluang Kerja', tipe: 'benefit' },
                { kode: 'C5', nama: 'Biaya Kuliah', tipe: 'cost' }
            ];

            const alternatives = [
                { kode: 'A1', nama: 'Teknik Informatika', deskripsi: 'Mempelajari ilmu komputer, algoritma, pemrograman, dan sistem informasi.', scores: [8.5, 9.0, 8.0, 9.5, 5.0] },
                { kode: 'A2', nama: 'Sistem Informasi', deskripsi: 'Mempelajari pengelolaan informasi dan teknologi dalam organisasi.', scores: [7.5, 8.5, 7.5, 8.5, 4.5] },
                { kode: 'A3', nama: 'Teknik Industri', deskripsi: 'Mempelajari optimasi sistem manufaktur, produksi, dan manajemen operasi.', scores: [8.0, 7.0, 7.5, 8.0, 5.5] },
                { kode: 'A4', nama: 'Akuntansi', deskripsi: 'Mempelajari pencatatan, pelaporan, dan analisis keuangan bisnis.', scores: [7.0, 7.5, 7.0, 7.5, 4.0] },
                { kode: 'A5', nama: 'Manajemen', deskripsi: 'Mempelajari pengelolaan sumber daya, strategi bisnis, dan kepemimpinan.', scores: [7.5, 8.0, 7.5, 8.5, 4.5] },
                { kode: 'A6', nama: 'Ilmu Komunikasi', deskripsi: 'Mempelajari teori komunikasi, media massa, dan hubungan masyarakat.', scores: [7.0, 8.5, 8.0, 7.0, 4.0] },
                { kode: 'A7', nama: 'Teknik Mesin', deskripsi: 'Mempelajari perancangan, analisis, dan pembuatan mesin dan sistem mekanik.', scores: [8.5, 7.0, 8.5, 8.0, 5.5] },
                { kode: 'A8', nama: 'Teknik Sipil', deskripsi: 'Mempelajari perancangan, pembangunan, dan pemeliharaan infrastruktur.', scores: [8.0, 7.5, 8.0, 8.5, 6.0] },
                { kode: 'A9', nama: 'Psikologi', deskripsi: 'Mempelajari perilaku manusia, proses mental, dan perkembangan individu.', scores: [7.5, 9.0, 8.5, 7.0, 4.5] },
                { kode: 'A10', nama: 'Hukum', deskripsi: 'Mempelajari sistem hukum, perundang-undangan, dan praktik litigasi.', scores: [7.0, 7.5, 7.0, 7.5, 4.0] }
            ];

            const defaultWeights = [0.25, 0.25, 0.20, 0.18, 0.12];

            // 10 pairs from 5 criteria: (0,1), (0,2), (0,3), (0,4), (1,2), (1,3), (1,4), (2,3), (2,4), (3,4)
            const ahpPairs = [
                { i: 0, j: 1 }, { i: 0, j: 2 }, { i: 0, j: 3 }, { i: 0, j: 4 },
                { i: 1, j: 2 }, { i: 1, j: 3 }, { i: 1, j: 4 },
                { i: 2, j: 3 }, { i: 2, j: 4 },
                { i: 3, j: 4 }
            ];

            return {
                step: 1,
                weightMethod: 'default', // 'default', 'custom', 'ahp'
                criteria: criteria,
                alternatives: alternatives,
                defaultWeights: defaultWeights,

                // Slider Bebas state
                customSliders: [5, 5, 5, 5, 5],
                customWeights: [0.2, 0.2, 0.2, 0.2, 0.2],

                // AHP state
                ahpSliders: Array(10).fill(0), // Slider values from -8 to 8
                ahpPairs: ahpPairs,
                ahpResult: {
                    weights: [...defaultWeights],
                    lambdaMax: 5.0,
                    ci: 0.0,
                    cr: 0.0,
                    isConsistent: true
                },

                // User Score inputs (C1 - C5)
                userScores: [8.0, 8.0, 8.0, 8.0, 5.0],

                // Results State
                activeWeights: [...defaultWeights],
                topsisResults: [],
                calculationDetails: {
                    idealPositive: [],
                    idealNegative: []
                },
                chartInstance: null,

                init() {
                    this.normalizeSliders();
                    this.calculateAHP();
                },

                normalizeSliders() {
                    let sum = this.customSliders.reduce((a, b) => a + b, 0);
                    if (sum > 0) {
                        this.customWeights = this.customSliders.map(v => v / sum);
                    } else {
                        this.customWeights = Array(5).fill(0.2);
                    }
                },

                calculateAHP() {
                    let n = 5;
                    let matrix = Array(n).fill(0).map(() => Array(n).fill(1.0));

                    // Build matrix from sliders
                    this.ahpPairs.forEach((pair, idx) => {
                        let val = this.ahpSliders[idx];
                        if (val === 0) {
                            matrix[pair.i][pair.j] = 1.0;
                            matrix[pair.j][pair.i] = 1.0;
                        } else if (val > 0) {
                            // Right is more important (j)
                            matrix[pair.j][pair.i] = val + 1;
                            matrix[pair.i][pair.j] = 1 / (val + 1);
                        } else {
                            // Left is more important (i)
                            matrix[pair.i][pair.j] = Math.abs(val) + 1;
                            matrix[pair.j][pair.i] = 1 / (Math.abs(val) + 1);
                        }
                    });

                    // 1. Column Sums
                    let colSums = Array(n).fill(0);
                    for (let j = 0; j < n; j++) {
                        for (let i = 0; i < n; i++) {
                            colSums[j] += matrix[i][j];
                        }
                    }

                    // 2. Normalize and calculate Priority Vector (weights)
                    let weights = Array(n).fill(0);
                    for (let i = 0; i < n; i++) {
                        let sum = 0;
                        for (let j = 0; j < n; j++) {
                            sum += matrix[i][j] / colSums[j];
                        }
                        weights[i] = sum / n;
                    }

                    // 3. Lambda Max
                    let weightedSums = Array(n).fill(0);
                    for (let i = 0; i < n; i++) {
                        for (let j = 0; j < n; j++) {
                            weightedSums[i] += matrix[i][j] * weights[j];
                        }
                    }
                    let lambdaMax = 0;
                    for (let i = 0; i < n; i++) {
                        lambdaMax += weightedSums[i] / weights[i];
                    }
                    lambdaMax /= n;

                    // 4. Consistency Index and Ratio
                    let ci = (lambdaMax - n) / (n - 1);
                    let ri = 1.12; // n=5 Saaty Index
                    let cr = ri > 0 ? ci / ri : 0;

                    this.ahpResult = {
                        weights: weights,
                        lambdaMax: lambdaMax,
                        ci: ci,
                        cr: cr,
                        isConsistent: cr <= 0.1
                    };
                },

                getPairDescription(idx) {
                    let val = this.ahpSliders[idx];
                    let p = this.ahpPairs[idx];
                    let left = this.criteria[p.i].nama;
                    let right = this.criteria[p.j].nama;
                    
                    if (val === 0) {
                        return `${left} sama penting dengan ${right}`;
                    } else if (val > 0) {
                        let scale = val + 1;
                        let desc = scale === 3 ? 'sedikit lebih penting' : (scale === 5 ? 'lebih penting' : (scale === 7 ? 'sangat penting' : (scale === 9 ? 'mutlak lebih penting' : 'lebih penting')));
                        return `${right} ${desc} dari ${left} (Skala ${scale})`;
                    } else {
                        let scale = Math.abs(val) + 1;
                        let desc = scale === 3 ? 'sedikit lebih penting' : (scale === 5 ? 'lebih penting' : (scale === 7 ? 'sangat penting' : (scale === 9 ? 'mutlak lebih penting' : 'lebih penting')));
                        return `${left} ${desc} dari ${right} (Skala ${scale})`;
                    }
                },

                calculate() {
                    // 1. Determine active weights
                    if (this.weightMethod === 'default') {
                        this.activeWeights = [...this.defaultWeights];
                    } else if (this.weightMethod === 'custom') {
                        this.normalizeSliders();
                        this.activeWeights = [...this.customWeights];
                    } else if (this.weightMethod === 'ahp') {
                        this.calculateAHP();
                        if (!this.ahpResult.isConsistent) {
                            alert("Matriks AHP Anda masih tidak konsisten (CR > 0.10). Silakan sesuaikan kembali pada langkah 1.");
                            this.step = 1;
                            return;
                        }
                        this.activeWeights = [...this.ahpResult.weights];
                    }

                    // 2. Perform TOPSIS calculation
                    let n = this.criteria.length;
                    let m = this.alternatives.length;

                    // Step 2a: Decision Matrix (D)
                    // decisionMatrix[i][j] = 10 - abs(userScore - dbScore)
                    let decisionMatrix = [];
                    for (let i = 0; i < m; i++) {
                        decisionMatrix[i] = [];
                        for (let j = 0; j < n; j++) {
                            let dbScore = this.alternatives[i].scores[j];
                            let userScore = this.userScores[j];
                            decisionMatrix[i][j] = 10.0 - Math.abs(userScore - dbScore);
                        }
                    }

                    // Step 2b: Calculate column Euclidean Norms
                    let colNorms = Array(n).fill(0);
                    for (let j = 0; j < n; j++) {
                        let sumSq = 0;
                        for (let i = 0; i < m; i++) {
                            sumSq += Math.pow(decisionMatrix[i][j], 2);
                        }
                        colNorms[j] = Math.sqrt(sumSq);
                    }

                    // Step 2c: Normalized Matrix (R)
                    let normalizedMatrix = [];
                    for (let i = 0; i < m; i++) {
                        normalizedMatrix[i] = [];
                        for (let j = 0; j < n; j++) {
                            normalizedMatrix[i][j] = colNorms[j] > 0 ? (decisionMatrix[i][j] / colNorms[j]) : 0;
                        }
                    }

                    // Step 2d: Weighted Normalized Matrix (V)
                    let weightedMatrix = [];
                    for (let i = 0; i < m; i++) {
                        weightedMatrix[i] = [];
                        for (let j = 0; j < n; j++) {
                            weightedMatrix[i][j] = normalizedMatrix[i][j] * this.activeWeights[j];
                        }
                    }

                    // Step 2e: Ideal Positive (A+) and Negatives (A-)
                    let idealPositive = Array(n).fill(0);
                    let idealNegative = Array(n).fill(0);
                    for (let j = 0; j < n; j++) {
                        let column = weightedMatrix.map(row => row[j]);
                        let critType = this.criteria[j].tipe;

                        if (critType === 'benefit') {
                            idealPositive[j] = Math.max(...column);
                            idealNegative[j] = Math.min(...column);
                        } else {
                            // cost
                            idealPositive[j] = Math.min(...column);
                            idealNegative[j] = Math.max(...column);
                        }
                    }

                    // Step 2f: Distances (D+ and D-)
                    let dPlus = Array(m).fill(0);
                    let dMinus = Array(m).fill(0);
                    for (let i = 0; i < m; i++) {
                        let sumPlus = 0;
                        let sumMinus = 0;
                        for (let j = 0; j < n; j++) {
                            sumPlus += Math.pow(weightedMatrix[i][j] - idealPositive[j], 2);
                            sumMinus += Math.pow(weightedMatrix[i][j] - idealNegative[j], 2);
                        }
                        dPlus[i] = Math.sqrt(sumPlus);
                        dMinus[i] = Math.sqrt(sumMinus);
                    }

                    // Step 2g: Preference Value (Vi) = D- / (D+ + D-)
                    let preferenceValues = Array(m).fill(0);
                    for (let i = 0; i < m; i++) {
                        let total = dPlus[i] + dMinus[i];
                        preferenceValues[i] = total > 0 ? (dMinus[i] / total) : 0;
                    }

                    // Step 2h: Map results and sort by ranking
                    let results = [];
                    for (let i = 0; i < m; i++) {
                        results.push({
                            alternative: this.alternatives[i],
                            decision_matrix_row: decisionMatrix[i],
                            normalized_row: normalizedMatrix[i],
                            weighted_row: weightedMatrix[i],
                            d_plus: dPlus[i],
                            d_minus: dMinus[i],
                            nilai_preferensi: preferenceValues[i]
                        });
                    }

                    // Sort descending by preference value
                    results.sort((a, b) => b.nilai_preferensi - a.nilai_preferensi);

                    this.topsisResults = results;
                    this.calculationDetails = {
                        idealPositive: idealPositive,
                        idealNegative: idealNegative
                    };

                    this.step = 3;

                    // Render Chart.js
                    this.$nextTick(() => {
                        this.renderChart();
                    });
                },

                renderChart() {
                    let ctx = document.getElementById('rankingChart').getContext('2d');
                    let labels = this.topsisResults.map(r => r.alternative.nama);
                    let data = this.topsisResults.map(r => parseFloat((r.nilai_preferensi * 100).toFixed(2)));

                    if (this.chartInstance) {
                        this.chartInstance.destroy();
                    }

                    // Generasi warna harmonis (gold untuk peringkat 1, silver untuk 2, bronze 3, biru untuk sisanya)
                    let backgroundColors = this.topsisResults.map((r, idx) => {
                        if (idx === 0) return 'rgba(234, 179, 8, 0.85)'; // Gold
                        if (idx === 1) return 'rgba(156, 163, 175, 0.85)'; // Silver
                        if (idx === 2) return 'rgba(180, 83, 9, 0.85)'; // Bronze
                        return 'rgba(59, 130, 246, 0.7)'; // Blue
                    });

                    this.chartInstance = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Kesesuaian (%)',
                                data: data,
                                backgroundColor: backgroundColors,
                                borderRadius: 8,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: { callback: v => v + '%' }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 9 }, maxRotation: 45, minRotation: 0 }
                                }
                            }
                        }
                    });
                },

                resetCalculator() {
                    this.step = 1;
                    this.topsisResults = [];
                    if (this.chartInstance) {
                        this.chartInstance.destroy();
                        this.chartInstance = null;
                    }
                }
            };
        }
    </script>
</body>
</html>
