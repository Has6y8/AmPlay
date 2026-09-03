@extends('layouts.app')
@section('title', ($animeInfo['judul'] ?? 'Anime Details') . ' - AmPlay')

@section('head')
<style>
    .star-rating button:hover,
    .star-rating button:hover ~ button,
    .star-rating button.peer:hover ~ button {
        color: #facc15 !important;
    }
</style>
@endsection

@section('content')
    <div class="fade-in">
        <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
            @php
                $prevUrl = url()->previous();
                $currentUrl = url()->current();
                $fromWatch = strpos($prevUrl, '/watch/') !== false;
                $fromSelf = strpos($prevUrl, $currentUrl) !== false;
                
                if ($fromWatch || $fromSelf) {
                    $backUrl = session('last_browse_url', route('home'));
                } else {
                    session(['last_browse_url' => $prevUrl]);
                    $backUrl = $prevUrl;
                }
            @endphp

            <a href="{{ $backUrl }}" class="text-sm font-bold bg-[#2d3342] text-gray-300 hover:text-white hover:bg-orange px-4 py-2 rounded-xl transition-all duration-300 border border-[#3f475a] hover:border-orange flex items-center gap-2 shadow-sm hover:-translate-x-1">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            
            <form action="{{ route('anime.refresh', $animeId) }}" method="POST" onsubmit="return confirm('Refresh data for this anime?')">
                @csrf
                <button type="submit" class="text-xs px-4 py-2.5 rounded-xl bg-orange/10 hover:bg-orange text-orange hover:text-white border border-orange/50 transition-all duration-300 flex items-center gap-2 font-bold shadow-sm hover:shadow-orange/20">
                    <i class="fa-solid fa-rotate-right"></i> Refresh Data
                </button>
            </form>
        </div>

        <div class="bg-card/80 backdrop-blur-md rounded-3xl p-6 md:p-8 mb-8 flex flex-col md:flex-row gap-8 shadow-xl border border-[#2d3342] relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-orange/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex-shrink-0 w-48 md:w-64 aspect-[2/3] rounded-2xl bg-[#0f1115] overflow-hidden mx-auto md:mx-0 shadow-2xl border-2 border-[#2d3342] relative z-10" data-tilt data-tilt-max="10" data-tilt-speed="400" data-tilt-perspective="1000">
                @if (!empty($animeInfo['poster_url']))
                    <img src="{{ $animeInfo['poster_url'] }}" alt="{{ $animeInfo['judul'] }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-5xl text-gray-700"><i class="fa-solid fa-image"></i></div>
                @endif
            </div>
            
            <div class="flex-1 flex flex-col justify-center relative z-10">
                <div class="flex flex-wrap gap-2 mb-4">
                    <a href="{{ route('search', ['type' => $animeInfo['type'] ?? 'TV']) }}" class="bg-gradient-to-r from-orange to-[#ea580c] text-white px-3 py-1 rounded-md text-xs font-black uppercase tracking-wider hover:scale-105 transition-transform shadow-md">
                        {{ $animeInfo['type'] ?? 'TV' }}
                    </a>
                    <span class="bg-[#0f1115] border border-[#2d3342] text-gray-300 px-3 py-1 rounded-md text-xs font-bold flex items-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-signal text-green-400"></i> {{ $animeInfo['status'] ?? 'Unknown' }}
                    </span>
                    
                    <div class="bg-[#0f1115] border border-[#2d3342] text-gray-300 px-3 py-1 rounded-md text-xs font-bold flex items-center gap-1.5 shadow-sm relative group cursor-pointer">
                        <i class="fa-solid fa-star text-yellow-400"></i> 
                        {{ $averageRating ?? 'No Ratings' }} 
                        @if($averageRating) <span class="text-gray-500 font-normal">/ 5</span> @endif

                        @auth
                            <div class="absolute top-full left-0 mt-2 p-3 bg-card border border-[#2d3342] rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 flex gap-1">
                                <form action="{{ route('anime.rate', $animeId) }}" method="POST" class="flex flex-row-reverse justify-end star-rating">
                                    @csrf
                                    @for($i = 5; $i >= 1; $i--)
                                        <button type="submit" name="rating" value="{{ $i }}" class="text-2xl {{ $userRating >= $i ? 'text-yellow-400' : 'text-gray-600' }} hover:text-yellow-400 peer peer-hover:text-yellow-400 transition-colors">
                                            <i class="fa-solid fa-star"></i>
                                        </button>
                                    @endfor
                                </form>
                            </div>
                        @else
                            <div class="absolute top-full left-0 mt-2 p-3 bg-card border border-[#2d3342] rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 whitespace-nowrap text-[10px] text-gray-400">
                                Login to rate
                            </div>
                        @endauth
                    </div>
                </div>

                <h1 class="text-3xl md:text-5xl font-black text-white mb-5 leading-tight drop-shadow-lg tracking-tight">{{ $animeInfo['judul'] ?? 'Unknown' }}</h1>
                
                @if(!empty($animeInfo['genres']))
                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach($animeInfo['genres'] as $genre)
                            <a href="{{ route('search', ['genre' => strtolower($genre)]) }}" class="bg-white/5 hover:bg-orange/20 hover:text-orange hover:border-orange/50 transition-all text-gray-300 border border-white/10 px-3 py-1.5 rounded-lg text-xs font-semibold shadow-sm">
                                {{ $genre }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="flex flex-wrap gap-3 mb-6 text-sm">
                    <span class="bg-[#2d3342] px-4 py-2 rounded-xl text-white flex items-center gap-2 font-bold border border-[#3f475a] shadow-inner">
                        <i class="fa-solid fa-layer-group text-orange"></i> {{ $animeInfo['total_episodes'] ?? $episodes->total() }} Episodes
                    </span>
                    <span class="bg-[#2d3342] px-4 py-2 rounded-xl text-gray-400 flex items-center gap-2 font-semibold border border-[#3f475a]">
                        <i class="fa-solid fa-hashtag text-orange"></i> ID: {{ $animeId }}
                    </span>
                </div>
                
                @if (!empty($animeInfo['sinopsis']))
                    <div class="mt-auto bg-[#0f1115]/50 rounded-2xl p-5 border border-[#2d3342] hover:border-[#3f475a] transition-colors">
                        <h3 class="text-sm font-bold text-white mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-align-left text-orange"></i> Synopsis
                        </h3>
                        <div class="relative cursor-pointer group" onclick="this.classList.toggle('is-expanded')">
                            <p class="text-gray-400 text-sm leading-relaxed transition-[max-height] duration-700 ease-in-out overflow-hidden max-h-[4.5rem] group-[.is-expanded]:max-h-[100rem]">
                                {{ $animeInfo['sinopsis'] }}
                            </p>
                            <div class="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-[#0f1115] to-transparent pointer-events-none group-[.is-expanded]:opacity-0 transition-opacity duration-500 rounded-b-2xl"></div>
                            
                            <div class="mt-3 text-xs font-extrabold text-orange flex items-center gap-1.5 group-[.is-expanded]:hidden transition-transform group-hover:translate-x-1">
                                Read more <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="mt-4 text-xs font-extrabold text-orange items-center gap-1.5 hidden group-[.is-expanded]:flex transition-transform hover:-translate-y-1">
                                Show less <i class="fa-solid fa-chevron-up"></i>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-card/80 backdrop-blur-md rounded-2xl p-5 border border-[#2d3342] mb-10 flex items-center gap-4 flex-wrap shadow-md">
            <span class="text-sm font-bold text-gray-300 uppercase tracking-wide"><i class="fa-solid fa-earth-americas text-orange mr-1"></i> Audio/Sub:</span>
            <div class="flex gap-2 flex-wrap">
                @if (count($languages) > 0)
                    @foreach ($languages as $lang)
                        @php
                            $code = $lang['code'] ?? 'sub';
                            $name = $lang['name'] ?? $code;
                            if (strtolower($name) == 'subtitle (japanese)') $name = 'Sub (Japanese)';
                            if (strtolower($name) == 'dub (english)') $name = 'Dub (English)';
                            $isActive = ($code === $preferredLang) || (strpos(strtolower($name), strtolower($preferredLang)) !== false);
                        @endphp
                        <a href="{{ route('anime.show', ['animeId' => $animeId, 'lang' => $code]) }}" 
                           class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all border shadow-sm flex items-center gap-2
                                  {{ $isActive ? 'bg-gradient-to-r from-orange to-[#ea580c] text-white border-transparent shadow-orange/30 transform scale-105' : 'bg-[#0f1115] text-gray-400 border-[#2d3342] hover:text-white hover:border-orange hover:bg-orange/10' }}">
                            @if($isActive) <i class="fa-solid fa-check text-xs"></i> @endif
                            {{ $name }}
                        </a>
                    @endforeach
                @else
                    <span class="text-sm text-gray-500 font-medium bg-[#0f1115] px-3 py-1 rounded">No languages available</span>
                @endif
            </div>
        </div>

        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                <h2 class="text-2xl font-extrabold text-white flex items-center gap-3">
                    <i class="fa-solid fa-play text-orange"></i> Episode List
                </h2>
                
                @if ($episodes->lastPage() > 1)
                    <div class="relative min-w-[220px]">
                        <select onchange="window.location.href=this.value" 
                                class="appearance-none w-full bg-[#1a1c23] border-2 border-[#2d3342] text-white text-sm font-bold rounded-xl pl-5 pr-10 py-3 focus:outline-none focus:border-orange cursor-pointer hover:bg-[#2d3342] transition-colors shadow-sm">
                            @for ($i = 1; $i <= $episodes->lastPage(); $i++)
                                @php
                                    $start = ($i - 1) * $episodes->perPage() + 1;
                                    $end = min($i * $episodes->perPage(), $episodes->total());
                                @endphp
                                <option value="{{ $episodes->url($i) . '&lang=' . $preferredLang }}" 
                                    class="bg-[#0f1115] text-white font-medium"
                                    {{ $episodes->currentPage() == $i ? 'selected' : '' }}>
                                    Episodes {{ $start }} - {{ $end }}
                                </option>
                            @endfor
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-orange">
                            <i class="fa-solid fa-chevron-down text-sm"></i>
                        </div>
                    </div>
                @endif
            </div>

            @if ($episodes->isEmpty())
                <div class="text-center py-20 bg-card/60 rounded-3xl border border-[#2d3342] text-gray-500 shadow-sm">
                    <i class="fa-solid fa-folder-open text-5xl mb-4 opacity-50"></i>
                    <p class="font-bold text-lg text-gray-400">No episodes available.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach ($episodes as $ep)
                        <a href="{{ route('episode.watch', ['animeId' => $animeId, 'episodeId' => $ep['id'], 'lang' => $preferredLang]) }}" 
                           class="bg-card rounded-2xl p-4 border border-[#2d3342] hover:border-orange transition-all duration-300 group flex items-center gap-4 hover:-translate-y-1 hover:shadow-lg hover:shadow-orange/10 overflow-hidden relative">
                            <div class="absolute right-0 top-0 w-16 h-16 bg-orange/5 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="w-14 h-14 rounded-xl bg-[#0f1115] border border-[#2d3342] flex items-center justify-center text-gray-400 font-black text-lg group-hover:bg-orange group-hover:text-white group-hover:border-orange transition-all duration-300 shadow-inner group-hover:shadow-orange/40">
                                {{ $ep['number'] ?? '-' }}
                            </div>
                            <div class="flex-1 min-w-0 z-10">
                                <div class="text-sm font-bold text-white truncate group-hover:text-orange transition-colors">Episode {{ $ep['number'] ?? '?' }}</div>
                                <div class="text-xs text-gray-500 truncate mt-1 font-medium">{{ $ep['title'] ?? 'No title available' }}</div>
                            </div>
                            <div class="text-gray-600 group-hover:text-orange transition-colors opacity-0 group-hover:opacity-100 -translate-x-4 group-hover:translate-x-0 duration-300">
                                <i class="fa-solid fa-play text-sm"></i>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if ($episodes->hasPages())
                    <div class="mt-12 flex justify-center items-center gap-2 flex-wrap">
                        @if ($episodes->onFirstPage())
                            <span class="px-4 py-2.5 rounded-xl border border-[#2d3342] bg-[#0f1115] text-gray-600 cursor-not-allowed text-sm font-bold"><i class="fa-solid fa-angle-left"></i></span>
                        @else
                            <a href="{{ $episodes->previousPageUrl() . '&lang=' . $preferredLang }}" class="px-4 py-2.5 rounded-xl border border-[#2d3342] bg-card text-white hover:border-orange hover:text-orange transition-colors text-sm font-bold"><i class="fa-solid fa-angle-left"></i></a>
                        @endif

                        @php
                            $current = $episodes->currentPage();
                            $last = $episodes->lastPage();
                            $start = max(1, $current - 2);
                            $end = min($last, $current + 2);
                        @endphp

                        @if ($start > 1)
                            <a href="{{ $episodes->url(1) . '&lang=' . $preferredLang }}" class="px-4 py-2.5 rounded-xl border border-[#2d3342] bg-card text-white hover:border-orange transition-colors text-sm font-bold">1</a>
                            @if ($start > 2) <span class="px-2 py-2 text-gray-500 font-bold">...</span> @endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $current)
                                <span class="px-5 py-2.5 rounded-xl bg-gradient-to-br from-orange to-[#ea580c] text-white font-black text-sm border-transparent shadow-lg shadow-orange/30 transform scale-110">{{ $i }}</span>
                            @else
                                <a href="{{ $episodes->url($i) . '&lang=' . $preferredLang }}" class="px-4 py-2.5 rounded-xl border border-[#2d3342] bg-card text-white hover:border-orange transition-colors text-sm font-bold">{{ $i }}</a>
                            @endif
                        @endfor

                        @if ($end < $last)
                            @if ($end < $last - 1) <span class="px-2 py-2 text-gray-500 font-bold">...</span> @endif
                            <a href="{{ $episodes->url($last) . '&lang=' . $preferredLang }}" class="px-4 py-2.5 rounded-xl border border-[#2d3342] bg-card text-white hover:border-orange transition-colors text-sm font-bold">{{ $last }}</a>
                        @endif

                        @if ($episodes->hasMorePages())
                            <a href="{{ $episodes->nextPageUrl() . '&lang=' . $preferredLang }}" class="px-4 py-2.5 rounded-xl border border-[#2d3342] bg-card text-white hover:border-orange hover:text-orange transition-colors text-sm font-bold"><i class="fa-solid fa-angle-right"></i></a>
                        @else
                            <span class="px-4 py-2.5 rounded-xl border border-[#2d3342] bg-[#0f1115] text-gray-600 cursor-not-allowed text-sm font-bold"><i class="fa-solid fa-angle-right"></i></span>
                        @endif
                    </div>
                @endif
            @endif
        </div>

        <div class="mt-16 bg-card/80 backdrop-blur-md rounded-3xl p-6 md:p-10 border border-[#2d3342] shadow-xl relative overflow-hidden">
            <h2 class="text-2xl font-black text-white mb-8 flex items-center gap-3">
                <i class="fa-regular fa-comments text-orange"></i> Comments 
                <span class="bg-orange/20 text-orange px-3 py-1 rounded-lg text-sm">{{ count($comments) }}</span>
            </h2>

            @auth
                <form action="{{ route('anime.comment', $animeId) }}" method="POST" class="mb-12">
                    @csrf
                    <div class="flex flex-col gap-4">
                        <div class="relative">
                            <textarea name="body" rows="3" placeholder="What are your thoughts on this anime?" 
                                      class="w-full px-5 py-4 rounded-2xl bg-[#0f1115] border-2 border-[#2d3342] text-white focus:outline-none focus:border-orange transition-colors resize-none text-sm shadow-inner" required></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-orange to-[#ea580c] text-white text-sm font-bold rounded-xl hover:scale-105 transition-transform flex items-center gap-2 shadow-lg shadow-orange/20">
                                <i class="fa-solid fa-paper-plane"></i> Post Comment
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="mb-12 p-8 bg-[#0f1115] border border-[#2d3342] rounded-2xl text-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#2d3342]/10 to-transparent"></div>
                    <i class="fa-solid fa-lock text-3xl text-gray-600 mb-4"></i>
                    <p class="text-gray-400 font-medium mb-5 relative z-10">You must be logged in to post a comment.</p>
                    <a href="{{ route('login') }}" class="inline-block bg-white text-black px-8 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-200 transition-colors shadow-md relative z-10">Login Now</a>
                </div>
            @endauth

            <div class="flex flex-col gap-5">
                @forelse($comments as $comment)
                    <div class="p-6 bg-[#0f1115] rounded-2xl border border-[#2d3342] hover:border-[#3f475a] transition-colors">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-700 to-[#2d3342] flex items-center justify-center text-white font-black text-sm shadow-inner">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-bold text-white text-sm block">{{ $comment->user->name }}</span>
                                    <span class="text-[11px] text-gray-500 font-medium"><i class="fa-regular fa-clock mr-1"></i> {{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $comment->body }}</p>
                    </div>
                @empty
                    <div class="text-center py-16 border-2 border-dashed border-[#2d3342] rounded-3xl bg-[#0f1115]/50">
                        <i class="fa-regular fa-comment-dots text-4xl text-gray-600 mb-4"></i>
                        <p class="text-gray-400 font-bold">No comments yet.</p>
                        <p class="text-gray-500 text-sm mt-1">Be the first to share your thoughts!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection