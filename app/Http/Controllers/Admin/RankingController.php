<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AhpResult;
use App\Models\Criteria;
use App\Models\Recommendation;
use App\Services\TOPSISService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function __construct(protected TOPSISService $topsisService) {}

    public function index(Request $request)
    {
        $latestAhp = AhpResult::latest()->first();

        if (!$latestAhp || !$latestAhp->is_consistent) {
            return view('admin.ranking.index', [
                'result' => null,
                'latestAhp' => $latestAhp,
                'criteria' => Criteria::orderBy('urutan')->get(),
            ]);
        }

        $result = $this->topsisService->calculate(ahpResultId: $latestAhp->id);

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        // Chart data
        $chartLabels = collect($result['results'])->pluck('alternative.nama')->toArray();
        $chartData = collect($result['results'])->pluck('nilai_preferensi')
            ->map(fn($v) => round($v * 100, 2))->toArray();

        $criteria = Criteria::orderBy('urutan')->get();

        return view('admin.ranking.index', compact('result', 'latestAhp', 'chartLabels', 'chartData', 'criteria'));
    }

    public function exportPdf(Request $request)
    {
        $latestAhp = AhpResult::latest()->first();

        if (!$latestAhp || !$latestAhp->is_consistent) {
            return redirect()->route('admin.ranking.index')
                ->with('error', 'Tidak ada hasil yang dapat diekspor.');
        }

        $result = $this->topsisService->calculate(ahpResultId: $latestAhp->id);
        $criteria = Criteria::orderBy('urutan')->get();

        $pdf = Pdf::loadView('pdf.ranking', compact('result', 'latestAhp', 'criteria'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('ranking-jurusan-' . now()->format('Y-m-d') . '.pdf');
    }
}
