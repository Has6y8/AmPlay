<?php

namespace App\Http\Controllers;

use App\Services\AniDBService;
use App\Models\Comment;
use App\Models\WatchHistory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AnimeController extends Controller
{
    protected AniDBService $anidb;

    public function __construct(AniDBService $anidb)
    {
        $this->anidb = $anidb;
    }

    public function home()
    {
        $popularIds = ['3880', '6378', '670', '457', '3686'];
        $popularAnime = [];

        foreach ($popularIds as $id) {
            $info = $this->anidb->getAnimeInfo($id);
            if ($info && !empty($info['judul'])) {
                $popularAnime[] = $info;
            }
        }

        if (empty($popularAnime)) {
            $popularAnime = [
                ['id' => '3880', 'judul' => 'One Piece', 'total_episodes' => 1100, 'poster_url' => 'https://cdn.xlsbox.com/poster/small/1782735600/3880.jpg'],
                ['id' => '6378', 'judul' => 'Bleach: Thousand-Year Blood War - The Calamity', 'total_episodes' => 14, 'poster_url' => 'https://cdn.xlsbox.com/poster/small/1786126813/6378.jpg'],
            ];
        }

        return view('home', compact('popularAnime'));
    }

    public function search(Request $request)
    {
        $page = (int) $request->input('page', 1);
        if ($page < 1) $page = 1;

        $params = array_filter([
            'q' => $request->input('q', ''),
            'type' => $request->input('type', ''),
            'genre' => $request->input('genre', ''),
            'page' => $page > 1 ? $page : null,
        ]);

        $results = [];
        if (!empty($params)) {
            $results = $this->anidb->search($params);
        }

        return view('search', compact('results', 'params', 'page'));
    }

    public function liveSearch(Request $request)
    {
        $q = $request->input('q');
        
        // Jika input kosong, kembalikan array kosong
        if (empty($q)) {
            return response()->json([]);
        }

        // Cari anime berdasarkan keyword
        $results = $this->anidb->search(['q' => $q]);
        
        // Ambil 5 hasil teratas saja untuk preview
        $limitedResults = array_slice($results, 0, 5);

        return response()->json($limitedResults);
    }

    public function show(Request $request, string $animeId)
    {
        $page = (int) $request->input('page', 1);
        if ($page < 1) $page = 1;

        $preferredLang = $request->input('lang', 'sub');

        $episodesPaginator = $this->anidb->getEpisodes($animeId, $page);
        $animeInfo = $this->anidb->getAnimeInfo($animeId);
        $languages = $this->anidb->getLanguagesForAnime($animeId);

        if (!$animeInfo && $episodesPaginator->isEmpty()) {
            abort(404, 'Anime not found');
        }

        if (!$animeInfo && $episodesPaginator->isNotEmpty()) {
            $first = $episodesPaginator->first();
            $animeInfo = [
                'id' => $animeId,
                'judul' => $first['anime_title'] ?? $first['title'] ?? 'Unknown',
                'total_episodes' => $episodesPaginator->total(),
                'poster_url' => $first['poster_url'] ?? null,
                'sinopsis' => $first['sinopsis'] ?? $first['description'] ?? null,
            ];
        }

        // Ambil komentar beserta data usernya
        $comments = Comment::with('user')->where('anime_id', $animeId)->latest()->get();

        return view('anime', [
            'animeId' => $animeId,
            'animeInfo' => $animeInfo,
            'episodes' => $episodesPaginator,
            'currentPage' => $page,
            'languages' => $languages,
            'preferredLang' => $preferredLang,
            'comments' => $comments,
        ]);
    }

    public function refresh(string $animeId)
    {
        $this->anidb->clearAnimeCache($animeId);
        return redirect()->route('anime.show', $animeId)->with('success', 'Anime cache refreshed successfully!');
    }

    public function storeComment(Request $request, string $animeId)
    {
        $request->validate(['body' => 'required|max:1000']);

        Comment::create([
            'user_id' => auth()->id(),
            'anime_id' => $animeId,
            'body' => $request->input('body')
        ]);

        return redirect()->back()->with('success', 'Comment posted successfully!');
    }

    public function history(Request $request)
    {
        $histories = WatchHistory::where('user_id', auth()->id())->latest('updated_at')->get();
        return view('history', compact('histories'));
    }
}