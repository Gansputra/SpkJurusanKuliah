<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $totalJurusan = 10;
        $latestRecommendation = null;

        return view('welcome', compact('totalJurusan', 'latestRecommendation'));
    }
}
