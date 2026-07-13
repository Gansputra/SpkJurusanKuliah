<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePairwiseMatrixRequest;
use App\Models\Criteria;
use App\Models\PairwiseMatrix;
use App\Services\AHPService;

class PairwiseMatrixController extends Controller
{
    public function __construct(protected AHPService $ahpService) {}

    public function index()
    {
        $criteria = Criteria::orderBy('urutan')->get();
        $n = $criteria->count();

        // Build matrix for display
        $matrix = [];
        foreach ($criteria as $c1) {
            foreach ($criteria as $c2) {
                $pw = PairwiseMatrix::where('criteria1_id', $c1->id)
                    ->where('criteria2_id', $c2->id)
                    ->first();
                $matrix[$c1->id][$c2->id] = $pw ? $pw->nilai : 1;
            }
        }

        return view('admin.ahp.matrix', compact('criteria', 'matrix', 'n'));
    }

    public function store(StorePairwiseMatrixRequest $request)
    {
        $matrixInput = $request->validated()['matrix'];
        $criteria = Criteria::orderBy('urutan')->get();

        foreach ($criteria as $c1) {
            foreach ($criteria as $c2) {
                if ($c1->id === $c2->id) {
                    // Diagonal selalu 1
                    PairwiseMatrix::updateOrCreate(
                        ['criteria1_id' => $c1->id, 'criteria2_id' => $c2->id],
                        ['nilai' => 1]
                    );
                } elseif ($c1->id < $c2->id) {
                    // Input dari user (upper triangle)
                    $nilai = isset($matrixInput[$c1->id][$c2->id])
                        ? (float) $matrixInput[$c1->id][$c2->id]
                        : 1;

                    PairwiseMatrix::updateOrCreate(
                        ['criteria1_id' => $c1->id, 'criteria2_id' => $c2->id],
                        ['nilai' => $nilai]
                    );

                    // Reciprocal (lower triangle)
                    PairwiseMatrix::updateOrCreate(
                        ['criteria1_id' => $c2->id, 'criteria2_id' => $c1->id],
                        ['nilai' => round(1 / $nilai, 6)]
                    );
                }
            }
        }

        return redirect()->route('admin.ahp.calculate')
            ->with('success', 'Matriks berhasil disimpan. Silakan hitung AHP.');
    }
}
