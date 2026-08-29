@extends('layouts.app')
@section('title', 'My Account - AmPlay')

@section('content')
<div class="max-w-3xl mx-auto fade-in mt-6">
    <h1 class="text-3xl font-black text-white mb-8 flex items-center gap-3 drop-shadow-md">
        <i class="fa-solid fa-user-gear text-orange"></i> Account Settings
    </h1>

    <div class="bg-card/90 backdrop-blur-xl border border-[#2d3342] rounded-3xl overflow-hidden shadow-2xl relative">
        {{-- Hiasan Glow Background 2D --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-orange/10 rounded-full blur-3xl pointer-events-none"></div>

        {{-- Profile Header dengan Teks Dinamis --}}
        <div class="bg-[#0f1115]/60 p-8 md:p-10 flex flex-col md:flex-row items-center md:items-start gap-8 border-b border-[#2d3342] relative z-10">
            <div class="w-28 h-28 rounded-full bg-gradient-to-br from-orange to-[#ea580c] flex items-center justify-center text-5xl font-black text-white shadow-lg border-4 border-[#1a1c23] transform hover:scale-105 transition-transform duration-300">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="text-center md:text-left flex-1 mt-2">
                <h2 class="text-3xl font-black text-white tracking-tight">{{ auth()->user()->name }}</h2>
                <p class="text-gray-400 mt-2 flex items-center justify-center md:justify-start gap-2 font-medium">
                    <i class="fa-solid fa-envelope text-orange"></i> {{ auth()->user()->email }}
                </p>
                <p class="text-xs text-gray-400 mt-4 inline-block bg-[#2d3342] px-4 py-1.5 rounded-lg border border-[#3f475a] shadow-inner font-bold">
                    Member since {{ auth()->user()->created_at->format('M d, Y') }}
                </p>
            </div>
        </div>
        
        {{-- Actions (Seluruh rute dipertahankan 100%) --}}
        <div class="p-8 md:p-10 relative z-10">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Account Actions</h3>
            <div class="flex flex-col sm:flex-row gap-5">
                <a href="{{ route('history') }}" class="flex-1 bg-[#2d3342] hover:bg-[#3f475a] text-white text-center py-4 rounded-xl font-bold transition-all duration-300 flex items-center justify-center gap-3 shadow-sm border border-[#3f475a] hover:-translate-y-1 hover:shadow-lg">
                    <i class="fa-solid fa-clock-rotate-left text-orange"></i> View Watch History
                </a>
                
                <form action="{{ route('logout') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white border border-red-500/30 hover:border-transparent text-center py-4 rounded-xl font-bold transition-all duration-300 flex items-center justify-center gap-3 shadow-sm hover:-translate-y-1 hover:shadow-red-500/20">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection