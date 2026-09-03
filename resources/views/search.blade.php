@extends('layouts.app')
@section('title', 'Browse Anime - AmPlay')

@section('content')
<div class="fade-in">
    
    {{-- Bilah Filter Dinamis (Berada di dalam konten utama) --}}
    <form action="{{ route('search') }}" method="GET" class="bg-card/60 backdrop-blur-md border border-[#2d3342] p-3 rounded-2xl flex flex-wrap gap-3 mb-8 shadow-sm">
        
        {{-- Search Bar --}}
        <div class="flex-1 min-w-[200px] relative group">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-orange transition-colors"></i>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search title..." 
                   class="w-full bg-[#0f1115] border border-[#2d3342] text-white rounded-xl pl-11 pr-4 py-2.5 focus:border-orange focus:ring-2 focus:ring-orange/20 outline-none transition-all text-sm shadow-inner">
        </div>

        {{-- Dropdown: Types --}}
        <select name="type" class="bg-[#0f1115] border border-[#2d3342] text-gray-300 rounded-xl px-4 py-2.5 text-sm focus:border-orange focus:ring-2 focus:ring-orange/20 outline-none appearance-none cursor-pointer hover:bg-[#1a1c23] transition-colors min-w-[120px]">
            <option value="">All Types</option>
            <option value="TV" @selected(request('type') == 'TV')>TV</option>
            <option value="Movie" @selected(request('type') == 'Movie')>Movie</option>
            <option value="OVA" @selected(request('type') == 'OVA')>OVA</option>
            <option value="ONA" @selected(request('type') == 'ONA')>ONA</option>
            <option value="Special" @selected(request('type') == 'Special')>Special</option>
        </select>

        {{-- Dropdown: Genres --}}
        <select name="genre" class="bg-[#0f1115] border border-[#2d3342] text-gray-300 rounded-xl px-4 py-2.5 text-sm focus:border-orange focus:ring-2 focus:ring-orange/20 outline-none appearance-none cursor-pointer hover:bg-[#1a1c23] transition-colors min-w-[120px]">
            <option value="">All Genres</option>
            <option value="action" @selected(request('genre') == 'action')>Action</option>
            <option value="adventure" @selected(request('genre') == 'adventure')>Adventure</option>
            <option value="comedy" @selected(request('genre') == 'comedy')>Comedy</option>
            <option value="drama" @selected(request('genre') == 'drama')>Drama</option>
            <option value="fantasy" @selected(request('genre') == 'fantasy')>Fantasy</option>
            <option value="romance" @selected(request('genre') == 'romance')>Romance</option>
            <option value="sci-fi" @selected(request('genre') == 'sci-fi')>Sci-Fi</option>
            <option value="supernatural" @selected(request('genre') == 'supernatural')>Supernatural</option>
            <option value="mystery" @selected(request('genre') == 'mystery')>Mystery</option>
            <option value="horror" @selected(request('genre') == 'horror')>Horror</option>
            <option value="sports" @selected(request('genre') == 'sports')>Sports</option>
        </select>
    </form>

    {{-- Area Hasil Pencarian & Teks Dinamis --}}
    @if (empty($params))
        {{-- Empty State 1: Awal Membuka Halaman (Animasi Interaktif 2D) --}}
        <div class="text-center py-24 bg-card/60 backdrop-blur-md rounded-2xl border border-[#2d3342] text-gray-500 shadow-lg relative overflow-hidden group" id="empty-state-container">
            <div id="interactive-icon" class="text-6xl mb-6 text-[#3f475a] transition-transform duration-100 ease-out inline-block drop-shadow-xl">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-300 mb-2">Ready to explore?</h2>
            <p class="text-sm font-medium text-gray-500 max-w-md mx-auto">Select a category or type a keyword in the search bar above to start browsing.</p>
        </div>
        
    @elseif (count($results) === 0)
        {{-- Empty State 2: Hasil Tidak Ditemukan (Animasi Interaktif 2D) --}}
        <div class="text-center py-24 bg-card/60 backdrop-blur-md rounded-2xl border border-[#2d3342] text-gray-500 shadow-lg relative overflow-hidden" id="empty-state-container">
            <div id="interactive-icon" class="text-6xl mb-6 text-red-500/50 transition-transform duration-100 ease-out inline-block drop-shadow-xl">
                <i class="fa-regular fa-face-frown"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-300 mb-2">No anime found</h2>
            <p class="text-sm font-medium text-gray-500 max-w-md mx-auto">We couldn't find any anime matching your criteria. Try adjusting your filters.</p>
        </div>
        
    @else
        {{-- Header Hasil --}}
        <div class="mb-5 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white flex items-center gap-2"><i class="fa-solid fa-list text-orange"></i> Search Results</h2>
            <p class="text-sm text-gray-400 bg-[#2d3342] px-3 py-1 rounded-md border border-[#3f475a]">Found <span class="text-white font-bold">{{ count($results) }}</span> anime</p>
        </div>

        {{-- Grid Hasil Pencarian --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
            @foreach ($results as $anime)
                <a href="{{ route('anime.show', $anime['id']) }}" class="anime-card bg-card rounded-xl overflow-hidden group block border border-[#2d3342] shadow-sm relative" data-tilt data-tilt-glare data-tilt-max-glare="0.3" data-tilt-scale="1.05">
                    <div class="relative aspect-[3/4] bg-[#0f1115] overflow-hidden">
                        @if (!empty($anime['poster_url']))
                            <img src="{{ $anime['poster_url'] }}" 
                                 alt="{{ $anime['judul'] }}" 
                                 loading="lazy"
                                 class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110 group-hover:rotate-1"
                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-4xl text-gray-700 bg-[#0f1115]\'><i class=\'fa-solid fa-image\'></i></div>'">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl text-gray-700 bg-[#0f1115]">
                                <i class="fa-solid fa-image"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0f1115] via-[#0f1115]/40 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <h3 class="text-sm font-bold text-white line-clamp-2 drop-shadow-md group-hover:text-orange transition-colors">{{ $anime['judul'] ?? 'Unknown' }}</h3>
                        <p class="text-[10px] text-gray-400 mt-2 font-semibold bg-black/50 backdrop-blur-sm inline-block px-2 py-1 rounded border border-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">
                            <i class="fa-solid fa-hashtag text-orange"></i> {{ $anime['id'] }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Paginasi Dinamis --}}
        @if ($page > 1 || count($results) >= 20)
            <div class="mt-14 flex justify-center items-center gap-3">
                @if ($page > 1)
                    <a href="{{ route('search', array_merge($params, ['page' => $page - 1])) }}" class="px-5 py-2.5 rounded-xl border border-[#2d3342] bg-card text-white hover:border-orange hover:bg-orange/10 hover:text-orange transition-all duration-300 text-sm font-bold flex items-center gap-2 shadow-sm hover:-translate-x-1">
                        <i class="fa-solid fa-angle-left"></i> Prev
                    </a>
                @else
                    <span class="px-5 py-2.5 rounded-xl border border-[#2d3342] bg-[#0f1115] text-gray-600 cursor-not-allowed text-sm font-bold transition flex items-center gap-2 opacity-70">
                        <i class="fa-solid fa-angle-left"></i> Prev
                    </span>
                @endif

                <span class="px-5 py-2.5 rounded-xl bg-gradient-to-br from-orange to-[#ea580c] text-white font-black text-sm shadow-lg shadow-orange/30 border border-orange/50 transform scale-105">
                    {{ $page }}
                </span>

                @if (count($results) >= 20)
                    <a href="{{ route('search', array_merge($params, ['page' => $page + 1])) }}" class="px-5 py-2.5 rounded-xl border border-[#2d3342] bg-card text-white hover:border-orange hover:bg-orange/10 hover:text-orange transition-all duration-300 text-sm font-bold flex items-center gap-2 shadow-sm hover:translate-x-1">
                        Next <i class="fa-solid fa-angle-right"></i>
                    </a>
                @else
                    <span class="px-5 py-2.5 rounded-xl border border-[#2d3342] bg-[#0f1115] text-gray-600 cursor-not-allowed text-sm font-bold transition flex items-center gap-2 opacity-70">
                        Next <i class="fa-solid fa-angle-right"></i>
                    </span>
                @endif
            </div>
        @endif
    @endif
</div>

{{-- Skrip Animasi Interaktif 2D --}}
@if (empty($params) || count($results) === 0)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('empty-state-container');
            const icon = document.getElementById('interactive-icon');
            
            if(container && icon) {
                container.addEventListener('mousemove', (e) => {
                    const rect = container.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;
                    
                    icon.style.transform = `translate(${x * 0.1}px, ${y * 0.1}px) rotate(${x * 0.05}deg) scale(1.1)`;
                    icon.style.color = '#f97316';
                });

                container.addEventListener('mouseleave', () => {
                    icon.style.transform = `translate(0px, 0px) rotate(0deg) scale(1)`;
                    icon.style.color = ''; 
                });
            }
        });
    </script>
@endif
@endsection