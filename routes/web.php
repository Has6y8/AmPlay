<?php

use App\Http\Controllers\AnimeController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', [AnimeController::class, 'home'])->name('home');
Route::get('/search', [AnimeController::class, 'search'])->name('search');
Route::get('/search/live', [AnimeController::class, 'liveSearch'])->name('search.live');

Route::prefix('anime')->group(function () {
    Route::get('/{animeId}', [AnimeController::class, 'show'])->name('anime.show');
    Route::post('/{animeId}/refresh', [AnimeController::class, 'refresh'])->name('anime.refresh');
});

Route::prefix('watch')->group(function () {
    Route::get('/{animeId}/{episodeId}', [EpisodeController::class, 'watch'])->name('episode.watch');
});

Route::middleware('auth')->group(function () {
    Route::get('/history', [AnimeController::class, 'history'])->name('history');
    Route::post('/anime/{animeId}/comment', [AnimeController::class, 'storeComment'])->name('anime.comment');
    Route::post('/anime/{animeId}/rate', [AnimeController::class, 'rate'])->name('anime.rate');
    Route::get('/account', [AuthController::class, 'account'])->name('account'); 
});

Route::prefix('api')->group(function () {
    Route::get('/episode/{episodeId}/embed', [EpisodeController::class, 'getEmbed'])->name('api.embed');
    Route::get('/episode/{episodeId}/stream', [EpisodeController::class, 'getStream'])->name('api.stream');
});