<?php

use App\Http\Controllers\Admin\AHPController;
use App\Http\Controllers\Admin\AlternativeController;
use App\Http\Controllers\Admin\AlternativeScoreController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PairwiseMatrixController;
use App\Http\Controllers\Admin\RankingController;
use App\Http\Controllers\Admin\TOPSISController;
use App\Http\Controllers\Admin\CriteriaController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\HistoryController;
use App\Http\Controllers\User\RecommendationController;
use App\Http\Controllers\User\ScoreController;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/kalkulator', function () {
    return view('kalkulator');
})->name('kalkulator');
