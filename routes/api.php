<?php

use App\Http\Controllers\EpisodeController;
use Illuminate\Support\Facades\Route;

Route::get('/episode/{episodeId}/embed', [EpisodeController::class, 'getEmbed'])->name('api.embed');
Route::get('/episode/{episodeId}/stream', [EpisodeController::class, 'getStream'])->name('api.stream');