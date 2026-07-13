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

// ============================================================
// AUTHENTICATED ROUTES (shared)
// ============================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kriteria CRUD
    Route::resource('criteria', CriteriaController::class)->except(['show']);

    // Jurusan (Alternatif) CRUD
    Route::resource('alternatives', AlternativeController::class)->except(['show']);

    // Nilai Alternatif
    Route::get('/scores', [AlternativeScoreController::class, 'index'])->name('scores.index');
    Route::get('/scores/{alternative}/edit', [AlternativeScoreController::class, 'edit'])->name('scores.edit');
    Route::put('/scores/{alternative}', [AlternativeScoreController::class, 'update'])->name('scores.update');
    Route::post('/scores/bulk', [AlternativeScoreController::class, 'bulkUpdate'])->name('scores.bulk');

    // AHP
    Route::get('/ahp/matrix', [PairwiseMatrixController::class, 'index'])->name('ahp.matrix');
    Route::post('/ahp/matrix', [PairwiseMatrixController::class, 'store'])->name('ahp.matrix.store');
    Route::get('/ahp/calculate', [AHPController::class, 'calculate'])->name('ahp.calculate');
    Route::get('/ahp/history', [AHPController::class, 'history'])->name('ahp.history');
    Route::get('/ahp/{ahpResult}', [AHPController::class, 'show'])->name('ahp.show');

    // TOPSIS
    Route::get('/topsis', [TOPSISController::class, 'calculate'])->name('topsis.calculate');

    // Ranking
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');
    Route::get('/ranking/export-pdf', [RankingController::class, 'exportPdf'])->name('ranking.export-pdf');
});

// ============================================================
// USER ROUTES
// ============================================================
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Input Nilai
    Route::get('/scores', [ScoreController::class, 'index'])->name('scores.index');
    Route::post('/scores', [ScoreController::class, 'store'])->name('scores.store');

    // Rekomendasi
    Route::get('/recommendation', [RecommendationController::class, 'index'])->name('recommendation.index');

    // Riwayat
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/history/{recommendation}', [HistoryController::class, 'show'])->name('history.show');
});

// ============================================================
// AUTH REDIRECT
// ============================================================
Route::get('/dashboard', function () {
    return auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('user.dashboard');
})->middleware('auth')->name('dashboard');

require __DIR__ . '/auth.php';
