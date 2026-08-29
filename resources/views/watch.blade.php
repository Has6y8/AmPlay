@extends('layouts.app')
@section('title', 'Watch - ' . ($animeInfo['judul'] ?? 'Anime') . ' EP ' . ($episode['number'] ?? '?') . ' - AmPlay')

@section('head')
    <style>
        /* Desain UI/UX Player yang lebih Modern & Interaktif */
        .player-wrapper { 
            background: #000; 
            overflow: hidden; 
            position: relative; 
            border-radius: 20px 20px 0 0; 
            box-shadow: inset 0 -10px 30px rgba(0,0,0,0.5);
        }
        .player-wrapper iframe { 
            width: 100%; 
            aspect-ratio: 16/9; 
            border: none; 
            display: block; 
        }
        
        /* Animasi 2D Interaktif pada Tombol Navigasi */
        .nav-btn { 
            padding: 12px 24px; 
            border-radius: 12px; 
            background: #2d3342; 
            color: #e2e8f0; 
            font-size: 14px; 
            font-weight: 700; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            display: inline-flex; 
            align-items: center; 
            justify-content: center;
            gap: 10px; 
            border: 1px solid #3f475a; 
        }
        .nav-btn:hover:not(:disabled) { 
            background: linear-gradient(135deg, #f97316, #ea580c); 
            color: white; 
            border-color: transparent; 
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3); 
            transform: translateY(-2px); 
        }
        .nav-btn:disabled { 
            opacity: 0.4; 
            cursor: not-allowed; 
            background: #0f1115; 
        }
    </style>
@endsection

@section('content')
    <div class="fade-in max-w-5xl mx-auto">
        {{-- Tombol Back Dipertahankan 100% --}}
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('anime.show', ['animeId' => $animeId, 'lang' => $preferredLang]) }}" class="text-sm font-bold bg-[#2d3342] text-gray-300 hover:text-white hover:bg-orange px-5 py-2.5 rounded-xl transition-all duration-300 border border-[#3f475a] hover:border-orange flex items-center gap-2 shadow-sm hover:-translate-x-1">
                <i class="fa-solid fa-arrow-left"></i> Back to Episodes
            </a>
        </div>

        <div class="bg-card/90 backdrop-blur-xl rounded-[20px] border border-[#2d3342] shadow-2xl relative overflow-hidden">
            {{-- Area Video Player --}}
            <div class="player-wrapper border-b-2 border-[#2d3342]">
                @if ($embedUrl)
                    <iframe src="{{ $embedUrl }}" allowfullscreen loading="lazy" id="playerFrame"></iframe>
                @else
                    <div class="aspect-video flex items-center justify-center bg-[#0f1115] text-gray-500 flex-col gap-5 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-[#0f1115] to-[#1a1c23]"></div>
                        <i class="fa-solid fa-video-slash text-6xl opacity-30 relative z-10 animate-pulse"></i>
                        <p class="font-bold text-lg tracking-wide relative z-10 text-gray-400">Video is currently unavailable.</p>
                    </div>
                @endif
            </div>

            <div class="p-6 md:p-8">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 border-b border-[#2d3342] pb-8 mb-8">
                    <div>
                        {{-- Elemen Teks Dinamis: Judul & Episode --}}
                        <h2 class="text-2xl md:text-3xl font-black text-white mb-3 drop-shadow-md">{{ $animeInfo['judul'] ?? 'Anime' }}</h2>
                        <p class="inline-flex items-center gap-3">
                            <span class="bg-gradient-to-r from-orange to-[#ea580c] text-white font-black text-sm px-3 py-1.5 rounded-lg shadow-md">
                                Episode {{ $episode['number'] ?? '?' }}
                            </span>
                            <span class="text-gray-400 text-sm font-bold">{{ $episode['title'] ?? '' }}</span>
                        </p>
                    </div>
                    
                    {{-- Navigasi Episode (Prev/Next) Dipertahankan 100% --}}
                    <div class="flex gap-3 w-full lg:w-auto">
                        @if ($prevEpisode)
                            <a href="{{ route('episode.watch', ['animeId' => $animeId, 'episodeId' => $prevEpisode['id'], 'lang' => $preferredLang]) }}" class="nav-btn flex-1 lg:flex-none">
                                <i class="fa-solid fa-backward-step"></i> Prev
                            </a>
                        @else
                            <button class="nav-btn flex-1 lg:flex-none" disabled>
                                <i class="fa-solid fa-backward-step"></i> Prev
                            </button>
                        @endif
                        
                        @if ($nextEpisode)
                            <a href="{{ route('episode.watch', ['animeId' => $animeId, 'episodeId' => $nextEpisode['id'], 'lang' => $preferredLang]) }}" class="nav-btn flex-1 lg:flex-none">
                                Next <i class="fa-solid fa-forward-step"></i>
                            </a>
                        @else
                            <button class="nav-btn flex-1 lg:flex-none" disabled>
                                Next <i class="fa-solid fa-forward-step"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    {{-- Pilihan Bahasa Audio/Sub (Dipertahankan 100%) --}}
                    @if (count($languages) > 0)
                        <div>
                            <span class="text-xs text-gray-400 block mb-3 font-black uppercase tracking-widest"><i class="fa-solid fa-language text-orange mr-1"></i> Audio / Subtitles</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($languages as $lang)
                                    @php
                                        $code = $lang['code'] ?? '';
                                        $name = $lang['name'] ?? $code;
                                        if (strtolower($name) == 'subtitle (japanese)') $name = 'Sub (Japanese)';
                                        if (strtolower($name) == 'dub (english)') $name = 'Dub (English)';
                                        
                                        $isActive = ($code === $preferredLang) || (strpos(strtolower($name), strtolower($preferredLang)) !== false);
                                    @endphp
                                    <a href="{{ route('episode.watch', ['animeId' => $animeId, 'episodeId' => $episodeId, 'lang' => $code]) }}"
                                       class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all border shadow-sm flex items-center gap-2 hover:-translate-y-0.5
                                              {{ $isActive ? 'bg-gradient-to-r from-orange to-[#ea580c] text-white border-transparent shadow-orange/30' : 'bg-[#0f1115] text-gray-400 border-[#2d3342] hover:bg-[#2d3342] hover:text-white hover:border-[#3f475a]' }}">
                                        @if($isActive) <i class="fa-solid fa-circle-check text-white text-xs"></i> @endif
                                        {{ $name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Tombol Aksi Ekstra (Dipertahankan 100%) --}}
                    <div class="flex flex-wrap gap-3 w-full md:w-auto mt-4 md:mt-0">
                        @if ($embedUrl)
                            <button onclick="navigator.clipboard.writeText('{{ $embedUrl }}').then(() => showToast('Link copied successfully!', 'success'))" 
                                    class="flex-1 md:flex-none bg-[#0f1115] hover:bg-[#2d3342] text-gray-300 hover:text-white px-5 py-3 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 border border-[#2d3342] hover:border-[#3f475a] shadow-sm hover:-translate-y-1">
                                <i class="fa-solid fa-link"></i> Copy Link
                            </button>
                            <button onclick="window.open('{{ $embedUrl }}', '_blank')" 
                                    class="flex-1 md:flex-none bg-orange/10 hover:bg-orange text-orange hover:text-white px-5 py-3 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 border border-orange/30 hover:border-transparent shadow-sm hover:shadow-orange/20 hover:-translate-y-1">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Open Externally
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection