@extends('layouts.app')
@section('title', 'Home - AmPlay')

@section('content')
    <div class="fade-in">
        {{-- Hero Section (Animasi 2D Canvas & Slideshow Dinamis) --}}
        <div class="w-screen h-[calc(100vh-64px)] relative left-1/2 -translate-x-1/2 mt-[-2rem] mb-16 flex items-center justify-center overflow-hidden bg-[#0f1115]">
            
            {{-- Background Slideshow Asli --}}
            <div id="hero-bg" class="absolute inset-0 bg-cover bg-top transition-opacity duration-1000 ease-in-out opacity-100" 
                 style="background-image: url('https://cdn.myanimelist.net/images/anime/10/47347.jpg');">
            </div>
            
            {{-- Overlay Gradasi --}}
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f1115] via-black/70 to-black/40"></div>

            {{-- Elemen Grafik 2D Interaktif (HTML5 Canvas) --}}
            <canvas id="interactive-canvas" class="absolute inset-0 z-0 pointer-events-none opacity-60"></canvas>

            {{-- Search Content & Teks Dinamis --}}
            <div class="relative z-10 w-full max-w-3xl px-4 text-center pb-20">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-10 md:mb-12 tracking-tight drop-shadow-2xl whitespace-nowrap min-h-[40px] md:min-h-[80px]">
                    <span id="typewriter-text" class="text-transparent bg-clip-text bg-gradient-to-r from-white via-gray-200 to-orange"></span>
                    <span class="animate-pulse text-orange">|</span>
                </h1>
                
                {{-- Form Pencarian Dipertahankan 100% --}}
                <form action="{{ route('search') }}" method="GET" class="relative group max-w-2xl mx-auto">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search for anime, movies, or OVA..." 
                        class="w-full px-8 py-5 rounded-full bg-black/40 backdrop-blur-md border border-white/20 text-white placeholder-gray-300 focus:outline-none focus:border-orange focus:bg-black/60 transition-all shadow-2xl text-lg" autocomplete="off">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 w-12 h-12 bg-orange rounded-full text-white hover:bg-[#ea580c] hover:scale-110 transition-transform flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-magnifying-glass text-lg"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Trending Now Section --}}
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
            <i class="fa-solid fa-fire text-orange animate-bounce"></i> Trending Now
        </h2>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5 mb-16">
            @foreach ($popularAnime as $anime)
                <a href="{{ route('anime.show', $anime['id']) }}" class="anime-card bg-card rounded-xl overflow-hidden group block border border-[#2d3342]" data-tilt data-tilt-glare data-tilt-max-glare="0.4" data-tilt-scale="1.05">
                    <div class="relative aspect-[3/4] bg-[#0f1115] overflow-hidden">
                        @if (!empty($anime['poster_url']))
                            <img src="{{ $anime['poster_url'] }}" alt="{{ $anime['judul'] }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl text-gray-700 bg-[#0f1115]"><i class="fa-solid fa-image"></i></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0f1115] via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <div class="p-4 relative z-10">
                        <h3 class="text-sm font-semibold text-white line-clamp-2 group-hover:text-orange transition-colors">{{ $anime['judul'] ?? 'Unknown' }}</h3>
                    </div>
                </a>
            @endforeach
        </div>

        @php
            // Data Tipe Seri dan Genre persis seperti aslinya
            $types = [
                ['name' => 'TV', 'img' => 'https://cdn.myanimelist.net/images/anime/13/17405.jpg'], 
                ['name' => 'Movie', 'img' => 'https://cdn.myanimelist.net/images/anime/5/87048.jpg'], 
                ['name' => 'OVA', 'img' => 'https://cdn.myanimelist.net/images/anime/11/34211.jpg'], 
                ['name' => 'ONA', 'img' => 'https://cdn.myanimelist.net/images/anime/1122/96435.jpg'], 
                ['name' => 'Special', 'img' => 'https://cdn.myanimelist.net/images/anime/1079/138100.jpg'], 
            ];

            $genres = [
                ['name' => 'Action', 'img' => 'https://cdn.myanimelist.net/images/anime/12/76049.jpg'], 
                ['name' => 'Adventure', 'img' => 'https://cdn.myanimelist.net/images/anime/6/73245.jpg'], 
                ['name' => 'Comedy', 'img' => 'https://cdn.myanimelist.net/images/anime/10/73274.jpg'], 
                ['name' => 'Drama', 'img' => 'https://cdn.myanimelist.net/images/anime/3/67177.jpg'], 
                ['name' => 'Fantasy', 'img' => 'https://cdn.myanimelist.net/images/anime/1223/96541.jpg'], 
                ['name' => 'Romance', 'img' => 'https://cdn.myanimelist.net/images/anime/13/22128.jpg'], 
                ['name' => 'Sci-Fi', 'img' => 'https://cdn.myanimelist.net/images/anime/5/73199.jpg'], 
                ['name' => 'Supernatural', 'img' => 'https://cdn.myanimelist.net/images/anime/1286/99889.jpg'], 
                ['name' => 'Mystery', 'img' => 'https://cdn.myanimelist.net/images/anime/9/9453.jpg'], 
                ['name' => 'Slice of Life', 'img' => 'https://cdn.myanimelist.net/images/anime/10/76120.jpg'], 
                ['name' => 'Sports', 'img' => 'https://cdn.myanimelist.net/images/anime/7/76014.jpg'], 
                ['name' => 'Horror', 'img' => 'https://cdn.myanimelist.net/images/anime/5/64449.jpg'], 
                ['name' => 'Ecchi', 'img' => 'https://cdn.myanimelist.net/images/anime/4/75509.jpg'], 
                ['name' => 'Gourmet', 'img' => 'https://cdn.myanimelist.net/images/anime/6/79597.jpg'], 
            ];
        @endphp

        {{-- Browse by Type --}}
        <div class="mb-12">
            <h2 class="text-xl font-bold text-white mb-5 flex items-center gap-2">
                <i class="fa-solid fa-layer-group text-orange"></i> Browse by Type
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($types as $type)
                    <a href="{{ route('search', ['type' => $type['name']]) }}" class="relative overflow-hidden rounded-xl h-24 group block border border-[#2d3342] hover:border-orange hover:shadow-[0_0_20px_rgba(249,115,22,0.4)] transition-all duration-300">
                        <img src="{{ $type['img'] }}" referrerpolicy="no-referrer" class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-110 group-hover:rotate-2 transition-transform duration-700" alt="{{ $type['name'] }}">
                        <div class="absolute inset-0 bg-black/60 group-hover:bg-black/30 transition-colors duration-300"></div>
                        <div class="absolute inset-0 flex items-center justify-center p-2">
                            <span class="text-white font-extrabold text-base tracking-wider drop-shadow-[0_2px_4px_rgba(0,0,0,1)]">{{ $type['name'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Browse by Genre --}}
        <div class="mb-10">
            <h2 class="text-xl font-bold text-white mb-5 flex items-center gap-2">
                <i class="fa-solid fa-tags text-orange"></i> Browse by Genre
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($genres as $genre)
                    <a href="{{ route('search', ['genre' => strtolower($genre['name'])]) }}" class="relative overflow-hidden rounded-xl h-20 group block border border-[#2d3342] hover:border-orange hover:shadow-[0_0_15px_rgba(249,115,22,0.3)] transition-all duration-300">
                        <img src="{{ $genre['img'] }}" referrerpolicy="no-referrer" class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-110 group-hover:-rotate-2 transition-transform duration-700" alt="{{ $genre['name'] }}">
                        <div class="absolute inset-0 bg-black/70 group-hover:bg-black/40 transition-colors duration-300"></div>
                        <div class="absolute inset-0 flex items-center justify-center p-2">
                            <span class="text-white font-bold text-sm tracking-wide drop-shadow-[0_2px_4px_rgba(0,0,0,1)]">{{ $genre['name'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Kumpulan Script: Typewriter Dinamis, Slideshow & Grafik 2D Canvas Interaktif --}}
    <script>
        // 1. Elemen Teks Dinamis (Typewriter)
        document.addEventListener('DOMContentLoaded', function() {
            const words = ["What will you watch today?", "Find your favorite Series...", "Stream without limits!"];
            let i = 0; let j = 0; let isDeleting = false;
            
            function type() {
                const currentWord = words[i];
                const el = document.getElementById('typewriter-text');
                if (!el) return;

                if (isDeleting) {
                    el.textContent = currentWord.substring(0, j - 1);
                    j--;
                } else {
                    el.textContent = currentWord.substring(0, j + 1);
                    j++;
                }

                if (!isDeleting && j === currentWord.length) {
                    isDeleting = true;
                    setTimeout(type, 2000); 
                } else if (isDeleting && j === 0) {
                    isDeleting = false;
                    i = (i + 1) % words.length;
                    setTimeout(type, 500); 
                } else {
                    setTimeout(type, isDeleting ? 40 : 80);
                }
            }
            type();
        });

        // 2. Background Slideshow
        document.addEventListener('DOMContentLoaded', function() {
            const slides = [
                'https://cdn.myanimelist.net/images/anime/10/47347.jpg',
                'https://cdn.myanimelist.net/images/anime/1171/109222.jpg',
                'https://cdn.myanimelist.net/images/anime/1286/99889.jpg',
                'https://cdn.myanimelist.net/images/anime/5/87048.jpg' 
            ];
            
            const bgElement = document.getElementById('hero-bg');
            if(!bgElement) return;
            let currentSlide = 0;
            
            setInterval(() => {
                currentSlide = (currentSlide + 1) % slides.length;
                bgElement.style.opacity = '0';
                setTimeout(() => {
                    bgElement.style.backgroundImage = `url('${slides[currentSlide]}')`;
                    bgElement.style.opacity = '1';
                }, 700); 
            }, 6000); 
        });

        // 3. Elemen Grafik Animasi Interaktif 2D (HTML5 Canvas)
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('interactive-canvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            
            let particles = [];
            let mouse = { x: null, y: null };

            function resizeCanvas() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }
            window.addEventListener('resize', resizeCanvas);
            resizeCanvas();

            // Interaksi Kursor Pembaca
            window.addEventListener('mousemove', function(e) {
                mouse.x = e.x;
                mouse.y = e.y;
            });

            class Particle {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.size = Math.random() * 2.5 + 0.5;
                    this.baseX = this.x;
                    this.baseY = this.y;
                    this.density = (Math.random() * 20) + 1;
                    this.color = `rgba(249, 115, 22, ${Math.random() * 0.5 + 0.2})`; // Warna tema orange
                }
                draw() {
                    ctx.fillStyle = this.color;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.closePath();
                    ctx.fill();
                }
                update() {
                    let dx = mouse.x - this.x;
                    let dy = mouse.y - this.y;
                    let distance = Math.sqrt(dx * dx + dy * dy);
                    let forceDirectionX = dx / distance;
                    let forceDirectionY = dy / distance;
                    let maxDistance = 100; // Radius interaksi kursor
                    let force = (maxDistance - distance) / maxDistance;
                    let directionX = forceDirectionX * force * this.density;
                    let directionY = forceDirectionY * force * this.density;

                    // Jika kursor mendekat, partikel menghindar
                    if (distance < maxDistance && mouse.x !== null) {
                        this.x -= directionX;
                        this.y -= directionY;
                    } else {
                        // Kembali ke posisi melayang perlahan
                        if (this.x !== this.baseX) {
                            let dx = this.x - this.baseX;
                            this.x -= dx / 10;
                        }
                        if (this.y !== this.baseY) {
                            let dy = this.y - this.baseY;
                            this.y -= dy / 10;
                        }
                    }
                }
            }

            function initParticles() {
                particles = [];
                // Jumlah partikel diatur agar sangat ringan (performa Skor 4)
                let numberOfParticles = (canvas.width * canvas.height) / 12000; 
                for (let i = 0; i < numberOfParticles; i++) {
                    particles.push(new Particle());
                }
            }

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                for (let i = 0; i < particles.length; i++) {
                    particles[i].update();
                    particles[i].draw();
                }
                requestAnimationFrame(animate);
            }

            initParticles();
            animate();
            
            // Hapus interaksi saat mouse keluar dari layar
            window.addEventListener('mouseout', function() {
                mouse.x = undefined;
                mouse.y = undefined;
            });
        });
    </script>
@endsection