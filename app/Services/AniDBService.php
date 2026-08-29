<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class AniDBService
{
    protected string $flaskBaseUrl;
    protected int $cacheTtl = 86400; // 24 jam
    protected int $perPage = 30;

    public function __construct()
    {
        $this->flaskBaseUrl = env('FLASK_API_URL', 'http://127.0.0.1:5000');
        $this->perPage = config('anime.per_page', 30);
    }

    // 1. Ubah default timeout menjadi 45 detik
    protected function callFlask(string $endpoint, array $params = [], int $timeout = 45): ?array
    {
        $url = 'http://127.0.0.1:5000' . $endpoint;

        try {
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->withOptions(['proxy' => '']) 
                ->timeout($timeout) // Memberikan waktu ekstra untuk Python bekerja
                ->get($url, $params);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Koneksi Python Terputus: " . $e->getMessage());
        }
        
        return null;
    }

    // 2. Terapkan timeout 45 detik khusus untuk pengambilan daftar episode
    public function fetchAllEpisodes(string $animeId): array
    {
        $key = 'episodes_' . $animeId;
        $cached = Cache::get($key);
        if ($cached !== null) {
            return $cached;
        }

        // Tembak API Flask dengan batas waktu tunggu yang panjang
        $data = $this->callFlask('/api/anime/' . $animeId . '/episodes', [], 45);
        
        if ($data && is_array($data) && !empty($data)) {
            Cache::put($key, $data, $this->cacheTtl);
            return $data;
        }

        $fallback = $this->getFallbackEpisodes($animeId);
        Cache::put($key, $fallback, 3600);
        return $fallback;
    }

    public function search(array $params): array
    {
        if (isset($params['q'])) {
            $params['q'] = strtolower(trim($params['q']));
        }

        ksort($params);
        $key = 'search_' . md5(json_encode($params));
        
        $cached = Cache::get($key);
        if ($cached !== null) {
            return $cached;
        }

        // Pakai timeout 5 detik untuk search
        $data = $this->callFlask('/api/search', $params, 5);
        if ($data && is_array($data) && !empty($data)) {
            Cache::put($key, $data, $this->cacheTtl);
            return $data;
        }

        $fallback = $this->getFallbackSearch($params);
        Cache::put($key, $fallback, 3600); 
        return $fallback;
    }

    // 2. FALLBACK PENCARIAN & FILTER UNIVERSAL
    protected function getFallbackSearch(array $params): array
    {
        $defaultFallback = [
            ['id' => '3880', 'judul' => 'One Piece (Fallback Mode)', 'poster_url' => 'https://cdn.xlsbox.com/poster/small/1782735600/3880.jpg', 'type' => 'TV'],
            ['id' => '6378', 'judul' => 'Bleach: TYBW (Fallback Mode)', 'poster_url' => 'https://cdn.xlsbox.com/poster/small/1786126813/6378.jpg', 'type' => 'TV'],
            ['id' => '1735', 'judul' => 'Naruto: Shippuden (Fallback Mode)', 'poster_url' => 'https://cdn.myanimelist.net/images/anime/5/17407.jpg', 'type' => 'TV'],
            ['id' => '5114', 'judul' => 'FMA: Brotherhood (Fallback Mode)', 'poster_url' => 'https://cdn.myanimelist.net/images/anime/1223/96541.jpg', 'type' => 'TV'],
        ];

        if (isset($params['q'])) {
            $queryLower = strtolower(trim($params['q']));
            $map = [
                'one piece' => [$defaultFallback[0]],
                'bleach' => [$defaultFallback[1]],
                'naruto' => [$defaultFallback[2]],
            ];
            return $map[$queryLower] ?? $defaultFallback;
        }

        // Kalau klik genre atau tipe dan API mati, tampilkan semua default ini
        return $defaultFallback;
    }

    // 3. GENERATOR EPISODE DINAMIS (Anti Hilang)
    protected function getFallbackEpisodes(string $animeId): array
    {
        // Berapapun ID animenya, kalau API mati, otomatis buatkan 12 episode dummy
        // Ini mencegah UI grid episode hancur/kosong
        $dummyEpisodes = [];
        for ($i = 1; $i <= 12; $i++) {
            $dummyEpisodes[] = [
                'id' => $animeId . 'ep' . $i, 
                'number' => $i, 
                'title' => 'Server Timeout - Episode ' . $i, 
                'poster_url' => null
            ];
        }
        return $dummyEpisodes;
    }

    public function getEpisodes(string $animeId, int $page = 1): LengthAwarePaginator
    {
        $all = $this->fetchAllEpisodes($animeId);
        $collection = collect($all);
        $perPage = $this->perPage;
        $items = $collection->forPage($page, $perPage)->values();
        $total = $collection->count();

        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => route('anime.show', $animeId), 'pageName' => 'page']
        );
    }

    public function getEmbedUrl(string $episodeId, string $preferredLang = 'sub'): ?string
    {
        $key = 'embed_' . $episodeId . '_' . $preferredLang;
        $cached = Cache::get($key);
        if ($cached !== null) {
            return $cached === 'null' ? null : $cached;
        }

        $data = $this->callFlask('/api/episode/' . $episodeId . '/embed', ['lang' => $preferredLang], 5);
        if ($data && isset($data['embed_url']) && $data['embed_url']) {
            Cache::put($key, $data['embed_url'], $this->cacheTtl);
            return $data['embed_url'];
        }

        Cache::put($key, 'null', 3600);
        return null;
    }

    public function getLanguages(string $episodeId): array
    {
        $key = 'languages_' . $episodeId;
        $cached = Cache::get($key);
        if ($cached !== null) {
            return $cached;
        }

        $data = $this->callFlask('/api/episode/' . $episodeId . '/languages', [], 5);
        if ($data && isset($data['languages']) && is_array($data['languages'])) {
            Cache::put($key, $data['languages'], $this->cacheTtl);
            return $data['languages'];
        }

        $fallback = [
            ['code' => 'sub', 'name' => 'Subtitle (Japanese)'],
            ['code' => 'dub', 'name' => 'Dub (English)'],
        ];
        Cache::put($key, $fallback, 3600);
        return $fallback;
    }

    public function getLanguagesForAnime(string $animeId): array
    {
        $key = 'anime_languages_' . $animeId;
        $cached = Cache::get($key);
        if ($cached !== null) {
            return $cached;
        }

        $episodes = $this->fetchAllEpisodes($animeId);
        $allLanguages = [];

        if (!empty($episodes) && isset($episodes[0]['id'])) {
            $langs = $this->getLanguages($episodes[0]['id']);
            foreach ($langs as $lang) {
                $code = $lang['code'] ?? 'sub';
                $name = $lang['name'] ?? $code;
                $keyLang = $code . '_' . $name;
                if (!isset($allLanguages[$keyLang])) {
                    $allLanguages[$keyLang] = [
                        'code' => $code,
                        'name' => $name,
                    ];
                }
            }
        }

        $result = array_values($allLanguages);
        if (empty($result)) {
            $result = [
                ['code' => 'sub', 'name' => 'Subtitle (Japanese)'],
                ['code' => 'dub', 'name' => 'Dub (English)'],
            ];
        }
        Cache::put($key, $result, $this->cacheTtl);
        return $result;
    }

    public function clearAnimeCache(string $animeId): void
    {
        Cache::forget('episodes_' . $animeId);
        Cache::forget('anime_languages_' . $animeId);
        Cache::forget('anime_info_' . $animeId);
    }

    public function getEpisodeById(string $animeId, string $episodeId): ?array
    {
        $episodes = $this->fetchAllEpisodes($animeId);
        
        foreach ($episodes as $ep) {
            if (($ep['id'] ?? '') == $episodeId) {
                return $ep;
            }
        }
        
        foreach ($episodes as $ep) {
            if (($ep['number'] ?? '') == $episodeId) {
                return $ep;
            }
        }
        return null;
    }

    public function getAnimeInfo(string $animeId): ?array
    {
        $key = 'anime_info_' . $animeId;
        $cached = Cache::get($key);

        if ($cached === null) {
            $data = $this->callFlask('/api/anime/' . $animeId . '/info', [], 5);
            
            if ($data && isset($data['judul'])) {
                $cached = $data;
                Cache::put($key, $cached, $this->cacheTtl);
            } else {
                $episodes = $this->fetchAllEpisodes($animeId);
                if (empty($episodes)) {
                    return null;
                }
                $first = $episodes[0] ?? [];
                $cached = [
                    'id' => $animeId,
                    'judul' => $first['anime_title'] ?? $first['title'] ?? 'Server Disconnected - Anime ' . $animeId,
                    'poster_url' => $first['poster_url'] ?? null,
                    'sinopsis' => 'Gagal mengambil data dari server. Menampilkan mode fallback cadangan.',
                    'status' => 'Unknown',
                    'score' => '?',
                    'type' => 'TV',
                    'genres' => []
                ];
            }
        }

        $episodes = $this->fetchAllEpisodes($animeId);
        $cached['total_episodes'] = count($episodes);

        return $cached;
    }
}