<?php

namespace App\Http\Controllers;

use App\Services\AniDBService;
use App\Models\WatchHistory;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    protected AniDBService $anidb;

    public function __construct(AniDBService $anidb)
    {
        $this->anidb = $anidb;
    }

    public function watch(Request $request, string $animeId, string $episodeId)
    {
        $preferredLang = $request->input('lang', 'sub');
        $episode = $this->anidb->getEpisodeById($animeId, $episodeId);
        
        if (!$episode) {
            return response("<h2 style='color:red; text-align:center; margin-top:50px;'>Error: Episode ID {$episodeId} data mismatch.</h2><p style='text-align:center;'>Please go back to the previous page and click the <b>Refresh Data</b> button.</p>", 404);
        }

        $animeInfo = $this->anidb->getAnimeInfo($animeId);
        $allEpisodes = $this->anidb->fetchAllEpisodes($animeId);
        $totalEpisodes = count($allEpisodes);

        // Hanya simpan history jika User sedang Login
        if (auth()->check()) {
            WatchHistory::updateOrCreate(
                ['user_id' => auth()->id(), 'anime_id' => $animeId],
                [
                    'episode_id' => $episode['id'],
                    'anime_title' => $animeInfo['judul'] ?? 'Unknown',
                    'episode_number' => $episode['number'] ?? '?',
                    'poster_url' => $animeInfo['poster_url'] ?? null,
                    'updated_at' => now(), 
                ]
            );
        }

        $prevEpisode = null;
        $nextEpisode = null;

        foreach ($allEpisodes as $index => $ep) {
            if ($ep['id'] == $episode['id']) {
                if ($index > 0) {
                    $prevEpisode = $allEpisodes[$index - 1]; 
                }
                if ($index < $totalEpisodes - 1) {
                    $nextEpisode = $allEpisodes[$index + 1]; 
                }
                break;
            }
        }

        $embedUrl = $this->anidb->getEmbedUrl($episode['id'], $preferredLang);
        $languages = $this->anidb->getLanguages($episode['id']);

        // --- TAMBAHKAN KODE INI UNTUK SOLUSI AUTO-PLAY ---
        // Jika link embed tidak ada tapi daftar bahasanya tersedia
        if (!$embedUrl && count($languages) > 0) {
            // Ambil kode bahasa pertama dari daftar
            $fallbackLang = $languages[0]['code'] ?? null;
            
            // Cegah redirect berulang (infinite loop) jika kodenya sudah sama
            if ($fallbackLang && $fallbackLang !== $preferredLang) {
                return redirect()->route('episode.watch', [
                    'animeId' => $animeId,
                    'episodeId' => $episode['id'],
                    'lang' => $fallbackLang
                ]);
            }
        }

        return view('watch', [
            'animeId' => $animeId,
            'episodeId' => $episode['id'], 
            'episode' => $episode,
            'animeInfo' => $animeInfo,
            'embedUrl' => $embedUrl,
            'languages' => $languages,
            'preferredLang' => $preferredLang,
            'prevEpisode' => $prevEpisode,
            'nextEpisode' => $nextEpisode,
            'totalEpisodes' => $totalEpisodes
        ]);
    }

    public function getEmbed(Request $request, string $episodeId)
    {
        $preferredLang = $request->input('lang', 'sub');
        $embedUrl = $this->anidb->getEmbedUrl($episodeId, $preferredLang);

        if (!$embedUrl) {
            return response()->json(['error' => 'Embed not found'], 404);
        }
        return response()->json(['embed_url' => $embedUrl]);
    }
}