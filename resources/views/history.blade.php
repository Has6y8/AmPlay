@extends('layouts.app')
@section('title', 'Watch History - AmPlay')

@section('content')
    <div class="fade-in">
        <div class="flex items-center justify-between flex-wrap gap-4 mb-8">
            <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                <i class="fa-solid fa-clock-rotate-left text-orange"></i> Watch History
            </h1>
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-orange transition text-sm flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Home
            </a>
        </div>

        @if($histories->isEmpty())
            <div class="text-center py-20 bg-card rounded-xl border border-[#2d3342] text-gray-500 shadow-sm">
                <i class="fa-solid fa-ghost text-5xl mb-4 text-[#2d3342]"></i>
                <p class="text-lg font-medium text-gray-400">No watch history yet.</p>
                <p class="text-sm mt-2">Watch some anime and your history will automatically appear here!</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                @foreach ($histories as $history)
                    <a href="{{ route('episode.watch', ['animeId' => $history->anime_id, 'episodeId' => $history->episode_id]) }}" class="anime-card bg-card rounded-xl overflow-hidden group block border border-[#2d3342] relative">
                        <div class="relative aspect-[3/4] bg-[#0f1115] overflow-hidden">
                            @if (!empty($history->poster_url))
                                <img src="{{ $history->poster_url }}" alt="{{ $history->anime_title }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-4xl text-gray-700 bg-[#0f1115]"><i class="fa-solid fa-image"></i></div>
                            @endif
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <i class="fa-solid fa-play text-4xl text-orange drop-shadow-lg scale-75 group-hover:scale-100 transition-transform"></i>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-white truncate group-hover:text-orange transition-colors">{{ $history->anime_title }}</h3>
                            <p class="text-xs text-orange mt-1 font-medium bg-orange/10 px-2 py-0.5 rounded inline-block">Resume Ep {{ $history->episode_number }}</p>
                            <p class="text-[10px] text-gray-500 mt-2"><i class="fa-regular fa-clock"></i> {{ $history->updated_at->diffForHumans() }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection