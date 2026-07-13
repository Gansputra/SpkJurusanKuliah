<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AhpResult;
use App\Services\TOPSISService;

class TOPSISController extends Controller
{
    public function __construct(protected TOPSISService $topsisService) {}

    public function calculate()
    {
        $latestAhp = AhpResult::latest()->first();

        if (!$latestAhp) {
            return redirect()->route('admin.ahp.calculate')
                ->with('error', 'Hitung AHP terlebih dahulu sebelum menjalankan TOPSIS.');
        }

        if (!$latestAhp->is_consistent) {
            return redirect()->route('admin.ahp.calculate')
                ->with('error', 'Bobot AHP tidak konsisten (CR > 0.1). Perbaiki matriks perbandingan.');
        }

        $result = $this->topsisService->calculate(ahpResultId: $latestAhp->id);

        if (isset($result['error'])) {
            return redirect()->route('admin.ahp.calculate')
                ->with('error', $result['error']);
        }

        return view('admin.topsis.hasil', compact('result'));
    }
}
