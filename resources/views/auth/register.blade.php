@extends('layouts.app')
@section('title', 'Register - AmPlay')

@section('content')
<div class="flex justify-center items-center min-h-[75vh] px-4 py-8">
    <div class="bg-card/80 backdrop-blur-xl border border-[#2d3342] p-8 md:p-10 rounded-3xl w-full max-w-md shadow-2xl fade-in relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange/20 rounded-full blur-2xl pointer-events-none"></div>

        <h2 class="text-3xl font-black text-white mb-8 text-center tracking-tight relative z-10">Create Account</h2>
        
        {{-- Teks Dinamis: List Error --}}
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-4 rounded-xl mb-8 text-sm font-medium">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Register Dipertahankan 100% --}}
        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-5 relative z-10">
            @csrf
            <div class="group">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-orange transition-colors">Username</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                       class="w-full px-5 py-3.5 rounded-xl bg-[#0f1115] border-2 border-[#2d3342] text-white focus:border-orange focus:ring-4 focus:ring-orange/10 outline-none transition-all duration-300 shadow-inner">
            </div>
            <div class="group">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-orange transition-colors">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       class="w-full px-5 py-3.5 rounded-xl bg-[#0f1115] border-2 border-[#2d3342] text-white focus:border-orange focus:ring-4 focus:ring-orange/10 outline-none transition-all duration-300 shadow-inner">
            </div>
            <div class="group">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-orange transition-colors">Password</label>
                <input type="password" name="password" required 
                       class="w-full px-5 py-3.5 rounded-xl bg-[#0f1115] border-2 border-[#2d3342] text-white focus:border-orange focus:ring-4 focus:ring-orange/10 outline-none transition-all duration-300 shadow-inner">
            </div>
            <div class="group">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-orange transition-colors">Confirm Password</label>
                <input type="password" name="password_confirmation" required 
                       class="w-full px-5 py-3.5 rounded-xl bg-[#0f1115] border-2 border-[#2d3342] text-white focus:border-orange focus:ring-4 focus:ring-orange/10 outline-none transition-all duration-300 shadow-inner">
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-orange to-[#ea580c] hover:from-[#ea580c] hover:to-orange text-white font-black py-4 rounded-xl transition-all duration-300 mt-4 shadow-lg shadow-orange/20 hover:-translate-y-1 transform">
                Register
            </button>
        </form>
        
        <p class="mt-8 text-center text-sm text-gray-400 font-medium relative z-10">
            Already have an account? <a href="{{ route('login') }}" class="text-orange hover:text-[#ea580c] transition-colors border-b-2 border-orange/30 hover:border-orange pb-0.5">Login here</a>
        </p>
    </div>
</div>
@endsection