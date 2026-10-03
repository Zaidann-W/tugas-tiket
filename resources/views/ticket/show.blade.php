@extends('layouts.app')

@section('content')
<div class="min-h-screen pt-20 pb-20">
    <div class="max-w-4xl mx-auto px-4">

        <!-- Success banner -->
        @if(session('success'))
        <div class="bg-punk-green/10 border border-punk-green/40 text-punk-green font-body text-sm p-4 mt-4 mb-6 flex items-center gap-3 stamp-animation">
            <div class="w-5 h-5 rounded-full border-2 border-punk-green flex items-center justify-center shrink-0">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            </div>
            <div>
                <p class="font-bold">{{ session('success') }}</p>
                <p class="text-punk-green/70 text-xs mt-0.5">Download tiket kamu di bawah dan simpan baik-baik!</p>
            </div>
        </div>
        @endif

        <!-- Order Header -->
        <div class="mt-4 mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="font-body text-punk-cream/40 text-xs uppercase tracking-wider mb-1">Order Berhasil</p>
                <h1 class="font-sans font-bold text-2xl text-punk-cream">{{ $order->order_code }}</h1>
                <p class="font-body text-punk-cream/50 text-sm mt-1">
                    Dibeli oleh: {{ $order->customer_name }} •
                    {{ $order->paid_at?->format('d M Y, H:i') }} WIB
                </p>
            </div>
            <div class="flex items-center gap-2 bg-punk-green/10 border border-punk-green/30 px-4 py-2">
                <div class="w-2 h-2 bg-punk-green rounded-full animate-pulse"></div>
                <span class="font-sans font-bold text-punk-green text-sm tracking-widest uppercase">PAID</span>
            </div>
        </div>

        <!-- Tickets grid -->
        <div class="space-y-6">
            @foreach($order->orderItems as $item)
                @foreach($item->tickets as $ticket)
                <div class="relative bg-punk-charcoal border border-punk-gray/40 overflow-hidden group hover:border-punk-red/30 transition-colors">
                    <!-- Top red bar -->
                    <div class="h-1 bg-gradient-to-r from-punk-red via-punk-red-light to-punk-red"></div>

                    <div class="flex flex-col md:flex-row">
                        <!-- Left: Ticket info -->
                        <div class="flex-1 p-6 space-y-4">
                            <!-- Band + Event -->
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-sans font-bold text-punk-red text-xs tracking-[0.3em] uppercase">OVER DISTORTION</p>
                                    <h2 class="font-sans font-bold text-xl text-punk-cream mt-0.5">
                                        {{ $item->ticketTier->event->title }}
                                    </h2>
                                </div>
                                <div class="bg-punk-red text-white font-sans font-bold text-xs px-2 py-1 tracking-widest uppercase">
                                    E-TIK
                                </div>
                            </div>

                            <!-- Ticket details grid -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="font-body text-punk-cream/40 text-xs uppercase tracking-wider mb-1">Holder</p>
                                    <p class="font-sans font-bold text-punk-cream">{{ $ticket->holder_name }}</p>
                                </div>
                                <div>
                                    <p class="font-body text-punk-cream/40 text-xs uppercase tracking-wider mb-1">Kategori</p>
                                    <p class="font-sans font-bold text-punk-cream text-sm">{{ $item->ticketTier->name }}</p>
                                </div>
                                <div>
                                    <p class="font-body text-punk-cream/40 text-xs uppercase tracking-wider mb-1">Tanggal</p>
                                    <p class="font-body text-punk-cream/80 text-sm">
                                        {{ $item->ticketTier->event->event_date->format('d M Y') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="font-body text-punk-cream/40 text-xs uppercase tracking-wider mb-1">Venue</p>
                                    <p class="font-body text-punk-cream/80 text-xs">
                                        {{ Str::limit($item->ticketTier->event->venue, 40) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Ticket code -->
                            <div class="bg-punk-black/50 border border-punk-gray/30 px-4 py-3 ticket-tear">
                                <p class="font-body text-punk-cream/30 text-xs uppercase tracking-wider mb-1">Kode Tiket</p>
                                <p class="font-mono text-punk-red font-bold tracking-widest text-sm">{{ $ticket->ticket_code }}</p>
                            </div>

                            <!-- Status badge -->
                            @if($ticket->is_used)
                            <div class="flex items-center gap-2 text-punk-gold/80">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span class="font-body text-xs">Sudah digunakan pada {{ $ticket->used_at?->format('d M Y H:i') }}</span>
                            </div>
                            @else
                            <div class="flex items-center gap-2 text-punk-green">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="font-body text-xs font-medium">Tiket valid &amp; belum digunakan</span>
                            </div>
                            @endif
                        </div>

                        <!-- Right: QR Code area with dashed separator -->
                        <div class="md:w-52 border-t md:border-t-0 md:border-l border-dashed border-punk-gray/40 flex flex-col items-center justify-center p-6 gap-4 bg-punk-black/30">
                            <!-- QR Code -->
                            <div class="bg-white p-3">
                                {!! QrCode::size(150)->generate($ticket->ticket_code) !!}
                            </div>
                            <a href="{{ route('ticket.single', $ticket->ticket_code) }}"
                               target="_blank"
                               class="w-full text-center border border-punk-cream/20 hover:border-punk-red text-punk-cream/50 hover:text-punk-red font-body text-xs py-2 px-3 transition-colors uppercase tracking-wider">
                                Print / Download
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            @endforeach
        </div>

        <!-- Back CTA -->
        <div class="mt-10 text-center">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 text-punk-cream/40 hover:text-punk-cream font-body text-sm transition-colors">
                ← Kembali ke halaman utama
            </a>
        </div>
    </div>
</div>
@endsection
