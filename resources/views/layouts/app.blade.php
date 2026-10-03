<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $metaDescription ?? 'Over Distortion - Dirty Punk Rock asal Sleman, Yogyakarta. Beli tiket konser Over Distortion secara online.' }}">
    <title>{{ $title ?? 'Over Distortion' }} | Gig Ticketing</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-punk-black text-punk-cream min-h-screen font-body">

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 border-b border-punk-gray/50 bg-punk-black/90 backdrop-blur-md">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-8 h-8 bg-punk-red rounded flex items-center justify-center font-sans font-bold text-white text-sm glow-red">
                    OD
                </div>
                <span class="font-sans font-bold text-lg tracking-widest text-punk-cream group-hover:text-punk-red transition-colors">
                    OVER DISTORTION
                </span>
            </a>
            <div class="hidden md:flex items-center gap-6 text-sm font-body">
                <a href="{{ route('home') }}#lineup" class="text-punk-cream/60 hover:text-punk-red transition-colors uppercase tracking-wider">Lineup</a>
                <a href="{{ route('home') }}#tiers" class="text-punk-cream/60 hover:text-punk-red transition-colors uppercase tracking-wider">Tiket</a>
                <a href="{{ route('home') }}#venue" class="text-punk-cream/60 hover:text-punk-red transition-colors uppercase tracking-wider">Venue</a>
            </div>
            <!-- Mobile menu button -->
            <button id="mobile-menu-btn" class="md:hidden text-punk-cream/60 hover:text-punk-red transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-punk-dark border-t border-punk-gray/30">
            <div class="px-4 py-3 flex flex-col gap-3 text-sm font-body">
                <a href="{{ route('home') }}#lineup" class="text-punk-cream/60 hover:text-punk-red transition-colors uppercase tracking-wider py-2">Lineup</a>
                <a href="{{ route('home') }}#tiers" class="text-punk-cream/60 hover:text-punk-red transition-colors uppercase tracking-wider py-2">Tiket</a>
                <a href="{{ route('home') }}#venue" class="text-punk-cream/60 hover:text-punk-red transition-colors uppercase tracking-wider py-2">Venue</a>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-punk-gray/40 bg-punk-black py-10 mt-20">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="font-sans font-bold text-2xl tracking-widest text-punk-red mb-2">OVER DISTORTION</div>
            <p class="text-punk-cream/40 text-sm font-body">Dirty Punk Rock / Street Punk • Sleman, D.I. Yogyakarta • Aktif Sejak 2010</p>
            <div class="mt-4 h-px bg-gradient-to-r from-transparent via-punk-red/40 to-transparent"></div>
            <p class="text-punk-cream/20 text-xs mt-4 font-body">&copy; {{ date('Y') }} Over Distortion. Built for the barisan.</p>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
            const menu = document.getElementById('mobile-menu');
            menu?.classList.toggle('hidden');
        });
    </script>
</body>
</html>
