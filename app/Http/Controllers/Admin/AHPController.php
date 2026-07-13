<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AhpResult;
use App\Models\Criteria;
use App\Models\PairwiseMatrix;
use App\Services\AHPService;

class AHPController extends Controller
{
    public function __construct(protected AHPService $ahpService) {}

    public function calculate()
    {
        $criteria = Criteria::orderBy('urutan')->get();

        if ($criteria->count() < 2) {
            return redirect()->route('admin.criteria.index')
                ->with('error', 'Minimal 2 kriteria diperlukan untuk menghitung AHP.');
        }

        // Cek apakah matriks sudah diisi
        $matrixCount = PairwiseMatrix::count();
        $expectedCount = $criteria->count() * $criteria->count();

        if ($matrixCount < $expectedCount) {
            return redirect()->route('admin.ahp.matrix')
                ->with('error', 'Matriks perbandingan belum lengkap. Harap isi terlebih dahulu.');
        }

        $result = $this->ahpService->calculate();

        if (isset($result['error'])) {
            return redirect()->route('admin.ahp.matrix')
                ->with('error', $result['error']);
        }

        return view('admin.ahp.hasil', compact('result'));
    }

    public function history()
    {
        $ahpResults = AhpResult::latest()->paginate(10);
        return view('admin.ahp.history', compact('ahpResults'));
    }

    public function show(AhpResult $ahpResult)
    {
        $criteria = Criteria::orderBy('urutan')->get();
        return view('admin.ahp.show', compact('ahpResult', 'criteria'));
    }
}
