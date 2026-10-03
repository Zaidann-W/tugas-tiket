<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket {{ $ticket->ticket_code }} | Over Distortion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body { background: white !important; }
            .no-print { display: none !important; }
            .print-ticket { box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-punk-black min-h-screen flex items-center justify-center p-6">

    <!-- Print button -->
    <div class="no-print fixed top-4 right-4 z-10 flex gap-2">
        <button onclick="window.print()"
                class="bg-punk-red text-white font-sans font-bold text-xs px-4 py-2 tracking-widest uppercase hover:bg-punk-red-dark transition-colors">
            PRINT
        </button>
        <a href="{{ route('ticket.show', $ticket->orderItem->order->order_code) }}"
           class="border border-punk-cream/30 text-punk-cream/60 hover:text-punk-cream font-body text-xs px-4 py-2 transition-colors">
            ← Semua Tiket
        </a>
    </div>

    <!-- E-Ticket Card -->
    <div class="print-ticket relative w-full max-w-md bg-punk-charcoal border border-punk-gray/40 overflow-hidden shadow-2xl stamp-animation">

        <!-- Background watermark texture -->
        <div class="absolute inset-0 opacity-5">
            <img src="{{ asset('images/over-distortion/ticket_card_bg.jpg') }}"
                 alt="" class="w-full h-full object-cover">
        </div>

        <!-- Red top bar -->
        <div class="h-2 bg-gradient-to-r from-punk-red via-punk-red-light to-punk-red"></div>

        <!-- Header -->
        <div class="relative bg-punk-black/60 px-6 py-5 text-center border-b border-punk-gray/30">
            <div class="inline-flex items-center justify-center w-10 h-10 bg-punk-red rounded mb-2 font-sans font-bold text-white text-lg">OD</div>
            <p class="font-sans font-bold text-punk-red text-xs tracking-[0.4em] uppercase">OVER DISTORTION</p>
            <h1 class="font-sans font-bold text-punk-cream text-xl mt-0.5 leading-tight">
                {{ $ticket->orderItem->ticketTier->event->title }}
            </h1>
        </div>

        <!-- Event info -->
        <div class="relative px-6 py-4 grid grid-cols-2 gap-4 border-b border-punk-gray/30">
            <div>
                <p class="font-body text-punk-cream/40 text-xs uppercase tracking-wider mb-0.5">Tanggal</p>
                <p class="font-sans font-bold text-punk-cream text-sm">
                    {{ $ticket->orderItem->ticketTier->event->event_date->format('d M Y') }}
                </p>
                <p class="font-body text-punk-cream/60 text-xs">
                    {{ $ticket->orderItem->ticketTier->event->event_date->format('H:i') }} WIB
                </p>
            </div>
            <div>
                <p class="font-body text-punk-cream/40 text-xs uppercase tracking-wider mb-0.5">Venue</p>
                <p class="font-body text-punk-cream/80 text-xs leading-snug">
                    {{ Str::limit($ticket->orderItem->ticketTier->event->venue, 50) }}
                </p>
            </div>
        </div>

        <!-- Holder & Tier info -->
        <div class="relative px-6 py-5 space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-body text-punk-cream/40 text-xs uppercase tracking-wider mb-1">Nama Holder</p>
                    <p class="font-sans font-bold text-punk-cream text-xl">{{ $ticket->holder_name }}</p>
                </div>
                <div class="text-right">
                    <p class="font-body text-punk-cream/40 text-xs uppercase tracking-wider mb-1">Kategori</p>
                    <div class="bg-punk-red/20 border border-punk-red/40 px-3 py-1">
                        <p class="font-sans font-bold text-punk-red text-xs">{{ $ticket->orderItem->ticketTier->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Status -->
            @if($ticket->is_used)
            <div class="flex items-center gap-2 bg-punk-gold/10 border border-punk-gold/30 px-3 py-2">
                <svg class="w-4 h-4 shrink-0 text-punk-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <p class="font-sans font-bold text-punk-gold text-xs">SUDAH DIGUNAKAN</p>
                    <p class="font-body text-punk-gold/60 text-xs">{{ $ticket->used_at?->format('d M Y H:i:s') }}</p>
                </div>
            </div>
            @else
            <div class="flex items-center gap-2 bg-punk-green/10 border border-punk-green/30 px-3 py-2">
                <svg class="w-4 h-4 shrink-0 text-punk-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="font-sans font-bold text-punk-green text-xs">TIKET VALID</p>
            </div>
            @endif
        </div>

        <!-- Dashed separator (ticket perforation style) -->
        <div class="relative ticket-tear h-px bg-punk-gray/30 mx-4"></div>
        <div class="absolute left-0 right-0 flex justify-between" style="margin-top: -10px;">
            <div class="w-5 h-5 rounded-full bg-punk-black -ml-2.5"></div>
            <div class="w-5 h-5 rounded-full bg-punk-black -mr-2.5"></div>
        </div>

        <!-- QR Code section -->
        <div class="relative px-6 pt-6 pb-5 flex flex-col items-center gap-4 bg-punk-black/20">
            <div class="bg-white p-4">
                {!! QrCode::size(180)->generate($ticket->ticket_code) !!}
            </div>

            <div class="text-center">
                <p class="font-mono text-punk-red font-bold text-sm tracking-[0.2em]">{{ $ticket->ticket_code }}</p>
                <p class="font-body text-punk-cream/30 text-xs mt-1">Tunjukkan kode ini di gate</p>
            </div>
        </div>

        <!-- Green bottom bar -->
        <div class="h-1.5 bg-gradient-to-r from-punk-green via-punk-green-light to-punk-green"></div>
    </div>

    <p class="no-print text-punk-cream/20 font-body text-xs text-center mt-6">
        Screenshot atau print halaman ini sebagai e-tiket kamu
    </p>
</body>
</html>
