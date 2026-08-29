@extends('layouts.app')
@section('title', 'Browse Anime - AmPlay')

@section('content')
    <div class="fade-in">
        {{-- Header Area: Tombol Back & Teks Dinamis --}}
        <div class="flex items-center justify-between flex-wrap gap-4 mb-8 bg-card/50 backdrop-blur-md p-6 rounded-2xl border border-[#2d3342] shadow-sm">
            <div>
                {{-- Elemen Teks Dinamis: Mengikuti Parameter Pencarian --}}
                <h1 class="text-2xl md:text-3xl font-extrabold text-white flex items-center gap-3 drop-shadow-md">
                    <i class="fa-solid fa-filter text-orange"></i> 
                    @if (!empty($params['q']))
                        Search Results: <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange to-yellow-400">"{{ $params['q'] }}"</span>
                    @elseif (!empty($params['genre']))
                        Genre: <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange to-yellow-400 capitalize">{{ $params['genre'] }}</span>
                    @elseif (!empty($params['type']))
                        Type: <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange to-yellow-400 capitalize">{{ $params['type'] }}</span>
                    @else
                        Browse Anime
                    @endif
                </h1>
                
                {{-- Elemen Teks Dinamis: Penghitung Hasil --}}
                @if (!empty($params) && count($results) > 0)
                    <p class="text-gray-400 text-sm mt-3 font-medium flex items-center gap-2">
                        <span class="bg-green-500/10 text-green-500 px-2.5 py-1 rounded-md border border-green-500/20 font-bold">
                            Found {{ count($results) }} anime
                        </span>
                        <span class="bg-[#2d3342] px-2.5 py-1 rounded-md border border-[#3f475a] text-gray-300">
                            Page {{ $page }}
                        </span>
                    </p>
                @endif
            </div>
            
            {{-- Tombol Back Dipertahankan --}}
            <a href="{{ route('home') }}" class="text-sm font-bold bg-[#2d3342] text-gray-300 hover:text-white hover:bg-orange px-5 py-2.5 rounded-xl transition-all duration-300 border border-[#3f475a] hover:border-orange flex items-center gap-2 shadow-sm hover:-translate-y-1">
                <i class="fa-solid fa-arrow-left"></i> Back to Home
            </a>
        </div>

        {{-- Area Hasil Pencarian --}}
        @if (empty($params))
            {{-- Empty State 1: Awal Membuka Halaman (Dengan Animasi Interaktif 2D) --}}
            <div class="text-center py-24 bg-card/60 backdrop-blur-md rounded-2xl border border-[#2d3342] text-gray-500 shadow-lg relative overflow-hidden group" id="empty-state-container">
                <div id="interactive-icon" class="text-6xl mb-6 text-[#3f475a] transition-transform duration-100 ease-out inline-block drop-shadow-xl">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-300 mb-2">Ready to explore?</h2>
                <p class="text-sm font-medium text-gray-500 max-w-md mx-auto">Select a category from the home page or type a keyword in the search bar above to start browsing.</p>
            </div>
            
        @elseif (count($results) === 0)
            {{-- Empty State 2: Hasil Tidak Ditemukan (Dengan Animasi Interaktif 2D) --}}
            <div class="text-center py-24 bg-card/60 backdrop-blur-md rounded-2xl border border-[#2d3342] text-gray-500 shadow-lg relative overflow-hidden" id="empty-state-container">
                <div id="interactive-icon" class="text-6xl mb-6 text-red-500/50 transition-transform duration-100 ease-out inline-block drop-shadow-xl">
                    <i class="fa-regular fa-face-frown"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-300 mb-2">No anime found</h2>
                <p class="text-sm font-medium text-gray-500 max-w-md mx-auto">We couldn't find any anime matching your criteria. Try adjusting your filters or keywords.</p>
            </div>
            
        @else
            {{-- Grid Hasil Pencarian dengan Efek 2D Transform CSS --}}
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
                            {{-- Overlay 2D Gradient --}}
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

            {{-- Paginasi Dinamis Dipertahankan 100% Logikanya --}}
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

    {{-- Script untuk Animasi Interaktif 2D pada Empty State --}}
    @if (empty($params) || count($results) === 0)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const container = document.getElementById('empty-state-container');
                const icon = document.getElementById('interactive-icon');
                
                if(container && icon) {
                    container.addEventListener('mousemove', (e) => {
                        // Menghitung posisi kursor relatif terhadap tengah container
                        const rect = container.getBoundingClientRect();
                        const x = e.clientX - rect.left - rect.width / 2;
                        const y = e.clientY - rect.top - rect.height / 2;
                        
                        // Menerapkan 2D Transform CSS agar ikon mengikuti kursor secara halus (Parallax 2D)
                        icon.style.transform = `translate(${x * 0.1}px, ${y * 0.1}px) rotate(${x * 0.05}deg) scale(1.1)`;
                        icon.style.color = '#f97316'; // Berubah menjadi oranye saat diinteraksi
                    });

                    container.addEventListener('mouseleave', () => {
                        // Mengembalikan ke posisi semula
                        icon.style.transform = `translate(0px, 0px) rotate(0deg) scale(1)`;
                        icon.style.color = ''; 
                    });
                }
            });
        </script>
    @endif
@endsection