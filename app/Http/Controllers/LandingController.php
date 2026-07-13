<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Recommendation;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $totalJurusan = Alternative::where('active', true)->count();
        $latestRecommendation = Recommendation::with(['details.alternative'])
            ->latest()
            ->first();

        return view('welcome', compact('totalJurusan', 'latestRecommendation'));
    }
}
