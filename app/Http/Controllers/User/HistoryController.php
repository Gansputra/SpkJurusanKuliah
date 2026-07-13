<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Recommendation;

class HistoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $recommendations = Recommendation::with(['details' => function ($q) {
            $q->orderBy('ranking')->take(1)->with('alternative');
        }])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('user.history.index', compact('recommendations'));
    }

    public function show(Recommendation $recommendation)
    {
        if ($recommendation->user_id !== auth()->id()) {
            abort(403);
        }

        $recommendation->load(['details' => function ($q) {
            $q->orderBy('ranking')->with('alternative');
        }, 'ahpResult']);

        return view('user.history.show', compact('recommendation'));
    }
}
