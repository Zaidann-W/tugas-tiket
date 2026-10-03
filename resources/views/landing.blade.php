@extends('layouts.app')

@section('content')
<!-- ========================
     HERO SECTION
======================== -->
<section class="relative min-h-screen flex items-center overflow-hidden">
    <!-- Hero background image -->
    <div class="absolute inset-0">
        <img src="{{ asset('images/over-distortion/hero_band_banner.jpg') }}"
             alt="Over Distortion Live"
             class="w-full h-full object-cover object-center">
        <!-- Dark overlays for depth -->
        <div class="absolute inset-0 bg-gradient-to-r from-punk-black via-punk-black/70 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-punk-black via-punk-black/30 to-transparent"></div>
        <!-- Halftone pattern overlay -->
        <div class="absolute inset-0 halftone-bg opacity-30"></div>
    </div>

    <!-- Red accent line top -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-punk-red via-punk-red-light to-punk-red"></div>

    <div class="relative z-10 max-w-6xl mx-auto px-4 pt-24 pb-16">
        <div class="max-w-2xl">
            <!-- Band label -->
            <div class="inline-flex items-center gap-2 bg-punk-red/20 border border-punk-red/40 rounded px-3 py-1.5 mb-6 animate-float-up">
                <div class="w-2 h-2 bg-punk-red rounded-full animate-pulse"></div>
                <span class="font-sans text-punk-red text-xs tracking-[0.3em] uppercase font-medium">Dirty Punk Rock • Sleman, Yogyakarta • Sejak 2010</span>
            </div>

            <!-- Main headline -->
            <h1 class="font-sans font-bold text-5xl md:text-7xl leading-none tracking-tight text-punk-cream mb-4 animate-float-up-delay">
                OVER<br>
                <span class="text-punk-red text-glow-red flicker">DISTORTION</span><br>
                <span class="text-3xl md:text-4xl text-punk-cream/80">LIVE IN SLEMAN</span>
            </h1>

            @if($event)
            <!-- Event date/venue info -->
            <div class="flex flex-col sm:flex-row gap-4 mb-8 animate-float-up-delay-2">
                <div class="flex items-center gap-2 text-punk-cream/70">
                    <svg class="w-4 h-4 text-punk-red shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-body text-sm">{{ $event->event_date->format('d F Y') }}</span>
                </div>
                <div class="flex items-center gap-2 text-punk-cream/70">
                    <svg class="w-4 h-4 text-punk-red shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="font-body text-sm">{{ $event->venue }}</span>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-wrap gap-4">
                <a href="#tiers"
                   class="inline-flex items-center gap-2 bg-punk-red hover:bg-punk-red-dark text-white font-sans font-bold px-8 py-4 text-sm tracking-widest uppercase transition-all duration-200 hover:scale-105 glow-red">
                    AMANKAN TIKETMU
                </a>
                <a href="#band"
                   class="inline-flex items-center gap-2 border border-punk-cream/30 hover:border-punk-cream text-punk-cream/70 hover:text-punk-cream font-body px-6 py-4 text-sm transition-all duration-200">
                    Kenali Bandnya →
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Tagline bottom bar -->
    @if($event)
    <div class="absolute bottom-0 left-0 right-0 bg-punk-red/10 border-t border-punk-red/30 py-3">
        <div class="max-w-6xl mx-auto px-4">
            <p class="font-sans text-punk-red/80 text-sm tracking-widest text-center uppercase">{{ $event->tagline }}</p>
        </div>
    </div>
    @endif
</section>

<!-- ========================
     BAND PROFILE / LINEUP
======================== -->
<section id="band" class="py-20 bg-punk-dark">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Section header -->
        <div class="text-center mb-12">
            <p class="font-sans text-punk-red text-xs tracking-[0.4em] uppercase mb-2">Siapa Mereka</p>
            <h2 class="font-sans font-bold text-4xl md:text-5xl text-punk-cream tracking-tight">THE BAND</h2>
            <div class="mt-4 w-20 h-0.5 bg-punk-red mx-auto"></div>
        </div>

        <div class="grid md:grid-cols-2 gap-12 items-center">
            <!-- Band photo -->
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-punk-red to-punk-green opacity-20 group-hover:opacity-40 transition-opacity rounded blur-sm"></div>
                <img src="{{ asset('images/over-distortion/band_members_trio.jpg') }}"
                     alt="Over Distortion - Amek, Kukun, Angga"
                     class="relative w-full aspect-4/3 object-cover object-center border border-punk-gray/40">
                <!-- Caption overlay -->
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-punk-black p-4">
                    <div class="flex gap-4 justify-center">
                        @foreach([['Amek', 'Vocal & Guitar'], ['Kukun', 'Bass & Backing Vocal'], ['Angga', 'Drums']] as $member)
                        <div class="text-center">
                            <div class="font-sans font-bold text-punk-red text-sm">{{ $member[0] }}</div>
                            <div class="font-body text-punk-cream/50 text-xs">{{ $member[1] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Band story -->
            <div class="space-y-6">
                <div class="border-l-4 border-punk-red pl-6">
                    <h3 class="font-sans font-bold text-2xl text-punk-cream mb-1">OVER DISTORTION</h3>
                    <p class="font-body text-punk-red text-sm tracking-wider">Dirty Punk Rock / Street Punk / Curva Rockers</p>
                </div>

                @if($event)
                <p class="font-body text-punk-cream/70 leading-relaxed text-sm">
                    {{ Str::limit($event->description, 300) }}
                </p>
                @else
                <p class="font-body text-punk-cream/70 leading-relaxed text-sm">
                    Aktif sejak 2010, Over Distortion adalah trio punk rock asal Sleman, Yogyakarta yang memadukan energi Dirty Punk Rock dengan semangat Curva Rockers. Musik mereka berteriak untuk barisan—di stadion, di jalanan, dan di moshpit.
                </p>
                @endif

                <!-- Member cards -->
                <div class="grid grid-cols-3 gap-3">
                    @foreach([
                        ['Amek', 'Fahmi Muzaki', 'Vocal & Guitar', ''],
                        ['Kukun', 'Hasan Ashari', 'Bass & BV', ''],
                        ['Angga', 'Angga Dipura', 'Drums', '']
                    ] as $member)
                    <div class="bg-punk-charcoal border border-punk-gray/40 hover:border-punk-red/40 transition-colors p-3 text-center group">
                        <div class="font-sans font-bold text-punk-cream text-sm group-hover:text-punk-red transition-colors">{{ $member[0] }}</div>
                        <div class="font-body text-punk-cream/40 text-xs mt-0.5">{{ $member[2] }}</div>
                    </div>
                    @endforeach
                </div>

                <!-- Discography highlights -->
                <div>
                    <p class="font-sans text-punk-cream/50 text-xs tracking-widest uppercase mb-3">Setlist Anthem</p>
                    <div class="space-y-2">
                        @foreach([
                            '10 Tahun di Barisan',
                            'Sambut Hari Esok',
                            'Selebrasi',
                            'Sepak Bola Selalu',
                            'Where Love Is Home'
                        ] as $index => $song)
                        <div class="flex items-center gap-3 group">
                            <span class="font-mono text-punk-red/50 text-xs w-5">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="flex-1 h-px bg-punk-gray/50 group-hover:bg-punk-red/40 transition-colors"></div>
                            <span class="font-body text-punk-cream/70 group-hover:text-punk-cream text-sm transition-colors">{{ $song }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================
     TICKET TIERS SECTION
======================== -->
@if($event && $event->ticketTiers->count() > 0)
<section id="tiers" class="py-20 bg-punk-dark">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <p class="font-sans text-punk-red text-xs tracking-[0.4em] uppercase mb-2">Pilih Posisimu</p>
            <h2 class="font-sans font-bold text-4xl md:text-5xl text-punk-cream tracking-tight">TIKET GIG</h2>
            <div class="mt-4 w-20 h-0.5 bg-punk-red mx-auto"></div>
            <p class="mt-4 font-body text-punk-cream/50 text-sm">Max 4 tiket per checkout. Gunakan sebelum tiket habis.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($event->ticketTiers as $tier)
            @php
                $isSoldOut = $tier->isSoldOut();
                $isLowStock = $tier->isLowStock();
                $soldCount = $tier->total_quota - $tier->available_stock;
            @endphp
            <div class="relative flex flex-col bg-punk-charcoal border {{ $isSoldOut ? 'border-punk-muted/30 opacity-60' : 'border-punk-gray/40 hover:border-punk-red/60' }} transition-all duration-300 group {{ !$isSoldOut ? 'hover:-translate-y-1' : '' }}">

                <!-- Low stock badge -->
                @if($isLowStock)
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-punk-gold text-punk-black font-sans font-bold text-xs px-3 py-0.5 tracking-widest uppercase whitespace-nowrap">
                    HAMPIR HABIS
                </div>
                @elseif($isSoldOut)
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-punk-muted text-punk-cream/60 font-sans font-bold text-xs px-3 py-0.5 tracking-widest uppercase whitespace-nowrap">
                    SOLD OUT
                </div>
                @endif

                <!-- Tier name -->
                <div class="bg-punk-black/50 border-b border-punk-gray/30 px-5 py-4">
                    <p class="font-sans font-bold text-punk-red text-xs tracking-widest uppercase mb-1">
                        {{ strtoupper(explode(' - ', $tier->name)[0] ?? $tier->name) }}
                    </p>
                    <h3 class="font-sans font-bold text-punk-cream text-lg leading-tight">
                        {{ explode(' - ', $tier->name)[1] ?? $tier->name }}
                    </h3>
                </div>

                <div class="flex-1 p-5 space-y-4">
                    <!-- Price -->
                    <div>
                        <span class="font-sans font-bold text-3xl text-punk-cream group-hover:text-punk-red transition-colors">
                            Rp {{ number_format($tier->price, 0, ',', '.') }}
                        </span>
                        <span class="font-body text-punk-cream/40 text-sm">/tiket</span>
                    </div>

                    <!-- Stock progress -->
                    <div>
                        <div class="flex justify-between text-xs font-body text-punk-cream/40 mb-1.5">
                            <span>{{ $soldCount }} terjual</span>
                            <span>{{ $tier->available_stock }} tersisa</span>
                        </div>
                        <div class="h-1.5 bg-punk-gray rounded-full overflow-hidden">
                            <div class="h-full rounded-full progress-fill {{ $isLowStock ? 'bg-punk-gold' : ($isSoldOut ? 'bg-punk-muted' : 'bg-punk-red') }}"
                                 style="width: {{ min(100, round(($soldCount / max(1, $tier->total_quota)) * 100)) }}%">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="px-5 pb-5">
                    @if($isSoldOut)
                    <button disabled class="w-full bg-punk-muted/30 text-punk-cream/30 font-sans font-bold text-sm py-3 tracking-widest uppercase cursor-not-allowed">
                        SOLD OUT
                    </button>
                    @else
                    <a href="{{ route('booking.checkout', $tier->id) }}"
                       class="block w-full text-center bg-punk-red hover:bg-punk-red-dark text-white font-sans font-bold text-sm py-3 tracking-widest uppercase transition-all duration-200 hover:scale-[1.02] glow-red">
                        BELI SEKARANG
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ========================
     VENUE & CROWD SECTION
======================== -->
<section id="venue" class="py-20 bg-punk-black">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="order-2 md:order-1 space-y-6">
                <div>
                    <p class="font-sans text-punk-red text-xs tracking-[0.4em] uppercase mb-2">Lokasi & Info</p>
                    <h2 class="font-sans font-bold text-4xl text-punk-cream">VENUE &<br>ATURAN GIG</h2>
                    <div class="mt-4 w-20 h-0.5 bg-punk-red"></div>
                </div>

                @if($event)
                <div class="bg-punk-charcoal border border-punk-gray/40 p-5 space-y-3">
                    <div class="flex items-start gap-3">
                        <span class="text-punk-red mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <div>
                            <p class="font-sans font-bold text-punk-cream text-sm">Venue</p>
                            <p class="font-body text-punk-cream/60 text-sm">{{ $event->venue }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-punk-red mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                        <div>
                            <p class="font-sans font-bold text-punk-cream text-sm">Tanggal</p>
                            <p class="font-body text-punk-cream/60 text-sm">{{ $event->event_date->format('l, d F Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-punk-green mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <div>
                            <p class="font-sans font-bold text-punk-cream text-sm">Buka Pintu</p>
                            <p class="font-body text-punk-cream/60 text-sm">{{ $event->event_date->format('H:i') }} WIB</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Venue rules -->
                <div>
                    <p class="font-sans text-punk-cream/50 text-xs tracking-widest uppercase mb-3">Barang Dilarang Masuk</p>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([
                            ['×', 'Flare di dalam hall'],
                            ['×', 'Senjata tajam'],
                            ['×', 'Botol kaca'],
                            ['×', 'Kembang api'],
                        ] as $rule)
                        <div class="flex items-center gap-2 bg-punk-charcoal/50 border border-punk-gray/20 px-3 py-2">
                            <span class="text-sm">{{ $rule[0] }}</span>
                            <span class="font-body text-punk-cream/60 text-xs">{{ $rule[1] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Safety tips -->
                <div>
                    <p class="font-sans text-punk-cream/50 text-xs tracking-widest uppercase mb-3">Tips Moshpit Safety</p>
                    <ul class="space-y-2">
                        @foreach([
                            'Jaga teman-teman di sekitarmu',
                            'Angkat yang jatuh, bukan diinjak',
                            'Hormati batas personal space',
                            'Circle pit — bukan fight club',
                        ] as $tip)
                        <li class="flex items-center gap-2 font-body text-punk-cream/60 text-sm">
                            <span class="text-punk-green font-bold">+</span>
                            {{ $tip }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Crowd photo -->
            <div class="order-1 md:order-2 relative">
                <div class="absolute -inset-1 bg-gradient-to-l from-punk-red to-punk-green opacity-15 blur-sm"></div>
                <img src="{{ asset('images/over-distortion/live_crowd_moshpit.jpg') }}"
                     alt="Over Distortion Moshpit"
                     class="relative w-full aspect-4/3 object-cover object-top border border-punk-gray/40">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-punk-black/80 to-transparent p-4">
                    <p class="font-sans font-bold text-punk-cream text-center text-sm tracking-widest">SATU BARISAN, SATU DISTORSI</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================
     FINAL CTA SECTION
======================== -->
<section class="py-20 relative overflow-hidden">
    <div class="absolute inset-0 halftone-bg opacity-20"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-punk-red/10 to-punk-green/10"></div>
    <div class="relative max-w-6xl mx-auto px-4 text-center">
        <h2 class="font-sans font-bold text-5xl md:text-7xl text-punk-cream mb-4 tracking-tight">
            JANGAN SAMPAI<br>
            <span class="text-punk-red text-glow-red">KETINGGALAN</span>
        </h2>
        <p class="font-body text-punk-cream/60 text-lg mb-8">Tiket terbatas. Moshpit nggak nunggu siapa-siapa.</p>
        @if($event)
        <a href="#tiers"
           class="inline-flex items-center gap-3 bg-punk-red hover:bg-punk-red-dark text-white font-sans font-bold px-12 py-5 text-base tracking-widest uppercase transition-all duration-200 hover:scale-105 glow-red">
            AMANKAN TIKETMU SEKARANG
        </a>
        @endif
    </div>
</section>
@endsection
