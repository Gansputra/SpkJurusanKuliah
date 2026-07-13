<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <title>Laporan Ranking Jurusan - SPK</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1f2937; background: white; }
        .header { background: linear-gradient(135deg, #1e3a8a, #2563eb); color: white; padding: 20px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: bold; margin-bottom: 4px; }
        .header p { font-size: 10px; opacity: 0.85; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .content { padding: 0 24px; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 13px; font-weight: bold; color: #1e40af; border-bottom: 2px solid #3b82f6; padding-bottom: 4px; margin-bottom: 10px; }
        .info-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 16px; }
        .info-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; text-align: center; }
        .info-card .label { font-size: 9px; color: #6b7280; margin-bottom: 4px; }
        .info-card .value { font-size: 14px; font-weight: bold; color: #1f2937; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #1e40af; color: white; padding: 6px 8px; text-align: left; font-size: 9px; text-transform: uppercase; }
        td { padding: 5px 8px; border-bottom: 1px solid #f1f5f9; font-size: 10px; }
        tr:nth-child(even) td { background: #f8fafc; }
        .rank-1 td { background: #fffbeb !important; font-weight: bold; }
        .rank-bar { background: #e5e7eb; border-radius: 4px; height: 6px; overflow: hidden; }
        .rank-fill { background: #3b82f6; height: 100%; border-radius: 4px; }
        .footer { position: fixed; bottom: 10px; left: 0; right: 0; text-align: center; font-size: 9px; color: #9ca3af; }
        .consistent { color: #065f46; background: #d1fae5; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 9px; }
        .inconsistent { color: #991b1b; background: #fee2e2; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 9px; }
        .top-jurusan { background: linear-gradient(135deg, #fefce8, #fef9c3); border: 2px solid #facc15; border-radius: 8px; padding: 12px; margin-bottom: 20px; text-align: center; }
        .top-jurusan h2 { font-size: 16px; font-weight: bold; color: #92400e; }
        .top-jurusan p { font-size: 11px; color: #b45309; margin-top: 4px; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>📊 Laporan Sistem Pendukung Keputusan</h1>
        <p>Pemilihan Jurusan Kuliah Menggunakan Metode AHP dan TOPSIS</p>
        <p style="margin-top: 8px;">Dicetak: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <div class="content">
        <!-- Top Result -->
        @php $topResult = collect($result['results'])->where('ranking', 1)->first(); @endphp
        @if($topResult)
        <div class="top-jurusan">
            <div style="font-size: 24px; margin-bottom: 4px;">🥇</div>
            <h2>{{ $topResult['alternative']['nama'] }}</h2>
            <p>Jurusan Terbaik Berdasarkan AHP + TOPSIS</p>
            <p style="font-size: 13px; font-weight: bold; color: #1e40af; margin-top: 4px;">
                Nilai Preferensi: {{ number_format($topResult['nilai_preferensi'], 4) }} ({{ number_format($topResult['nilai_preferensi'] * 100, 2) }}%)
            </p>
        </div>
        @endif

        <!-- AHP Info -->
        <div class="section">
            <div class="section-title">Informasi Bobot AHP</div>
            <div class="info-grid">
                <div class="info-card">
                    <div class="label">Konsistensi</div>
                    <div class="value">
                        <span class="{{ $latestAhp->is_consistent ? 'consistent' : 'inconsistent' }}">
                            {{ $latestAhp->is_consistent ? 'Konsisten' : 'Tidak Konsisten' }}
                        </span>
                    </div>
                </div>
                <div class="info-card">
                    <div class="label">λmax</div>
                    <div class="value">{{ number_format($latestAhp->lambda_max, 4) }}</div>
                </div>
                <div class="info-card">
                    <div class="label">CI</div>
                    <div class="value">{{ number_format($latestAhp->ci, 4) }}</div>
                </div>
                <div class="info-card">
                    <div class="label">CR</div>
                    <div class="value">{{ number_format($latestAhp->cr, 4) }}</div>
                </div>
            </div>

            <!-- Weights Table -->
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Kriteria</th>
                        <th>Tipe</th>
                        <th>Bobot (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($criteria as $c)
                    <tr>
                        <td><strong>{{ $c->kode }}</strong></td>
                        <td>{{ $c->nama }}</td>
                        <td>
                            <span class="badge {{ $c->tipe === 'benefit' ? 'badge-green' : 'badge-red' }}">
                                {{ ucfirst($c->tipe) }}
                            </span>
                        </td>
                        <td><strong>{{ number_format($c->bobot * 100, 2) }}%</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- TOPSIS Ranking -->
        <div class="section">
            <div class="section-title">Hasil Ranking TOPSIS</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;">Rank</th>
                        <th>Jurusan</th>
                        <th style="width: 70px; text-align: center;">D⁺</th>
                        <th style="width: 70px; text-align: center;">D⁻</th>
                        <th style="width: 80px; text-align: center;">Vi (Preferensi)</th>
                        <th style="width: 100px;">Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result['results'] as $r)
                    <tr class="{{ $r['ranking'] === 1 ? 'rank-1' : '' }}">
                        <td style="text-align: center; font-weight: bold; font-size: 13px;">
                            @if($r['ranking'] === 1) 🥇
                            @elseif($r['ranking'] === 2) 🥈
                            @elseif($r['ranking'] === 3) 🥉
                            @else {{ $r['ranking'] }}
                            @endif
                        </td>
                        <td>
                            <strong>{{ $r['alternative']['nama'] }}</strong>
                            <br><span style="font-size: 9px; color: #6b7280;">{{ $r['alternative']['kode'] }}</span>
                        </td>
                        <td style="text-align: center; font-family: monospace; color: #dc2626;">{{ number_format($r['d_plus'], 4) }}</td>
                        <td style="text-align: center; font-family: monospace; color: #059669;">{{ number_format($r['d_minus'], 4) }}</td>
                        <td style="text-align: center; font-weight: bold; color: #1e40af; font-size: 12px;">{{ number_format($r['nilai_preferensi'], 4) }}</td>
                        <td>
                            <div class="rank-bar">
                                <div class="rank-fill" style="width: {{ round($r['nilai_preferensi'] * 100, 1) }}%"></div>
                            </div>
                            <span style="font-size: 9px; color: #6b7280;">{{ round($r['nilai_preferensi'] * 100, 1) }}%</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        SPK Pemilihan Jurusan Kuliah | Metode AHP & TOPSIS | {{ config('app.name') }}
    </div>

</body>
</html>
