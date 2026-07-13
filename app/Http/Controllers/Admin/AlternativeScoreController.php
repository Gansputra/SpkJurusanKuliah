<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlternativeScoreRequest;
use App\Models\Alternative;
use App\Models\AlternativeScore;
use App\Models\Criteria;

class AlternativeScoreController extends Controller
{
    public function index()
    {
        $alternatives = Alternative::with(['scores.criteria'])->orderBy('kode')->get();
        $criteria = Criteria::orderBy('urutan')->get();

        return view('admin.scores.index', compact('alternatives', 'criteria'));
    }

    public function edit(Alternative $alternative)
    {
        $criteria = Criteria::orderBy('urutan')->get();
        $scores = $alternative->scores->keyBy('criteria_id');

        return view('admin.scores.edit', compact('alternative', 'criteria', 'scores'));
    }

    public function update(StoreAlternativeScoreRequest $request, Alternative $alternative)
    {
        $scores = $request->validated()['scores'][$alternative->id] ?? $request->input('scores');

        foreach ($scores as $criteriaId => $nilai) {
            AlternativeScore::updateOrCreate(
                ['alternative_id' => $alternative->id, 'criteria_id' => $criteriaId],
                ['nilai' => $nilai]
            );
        }

        return redirect()->route('admin.scores.index')
            ->with('success', "Nilai untuk {$alternative->nama} berhasil diperbarui.");
    }

    public function bulkUpdate(StoreAlternativeScoreRequest $request)
    {
        $scores = $request->validated()['scores'];

        foreach ($scores as $altId => $criteriaScores) {
            foreach ($criteriaScores as $critId => $nilai) {
                AlternativeScore::updateOrCreate(
                    ['alternative_id' => $altId, 'criteria_id' => $critId],
                    ['nilai' => $nilai]
                );
            }
        }

        return redirect()->route('admin.scores.index')
            ->with('success', 'Semua nilai alternatif berhasil diperbarui.');
    }
}
