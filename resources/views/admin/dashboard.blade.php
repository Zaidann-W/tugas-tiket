@extends('layouts.app')

@section('content')
<div class="min-h-screen pt-20 pb-20">
    <div class="max-w-6xl mx-auto px-4">

        <!-- Header -->
        <div class="mt-8 mb-10">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-body text-punk-red text-xs tracking-[0.4em] uppercase mb-1">Monitoring Real-time</p>
                    <h1 class="font-sans font-bold text-4xl text-punk-cream">ADMIN DASHBOARD</h1>
                </div>
                <div class="flex items-center gap-2 text-punk-cream/30 text-xs font-body">
                    <div class="w-2 h-2 bg-punk-green rounded-full animate-pulse"></div>
                    Live {{ now()->format('H:i:s') }} WIB
                </div>
            </div>
            @if($event)
            <p class="font-body text-punk-cream/50 text-sm mt-2">{{ $event->title }} • {{ $event->event_date->format('d F Y') }}</p>
            @endif
        </div>

        <!-- Key metrics -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
            @foreach([
                ['REV', 'Total Revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.'), 'bg-punk-green/10 border-punk-green/30 text-punk-green'],
                ['TIK', 'Tiket Terjual', $totalTicketsSold . ' tiket', 'bg-punk-red/10 border-punk-red/30 text-punk-red'],
                ['ORD', 'Total Order', $totalOrders . ' order', 'bg-blue-900/30 border-blue-700/30 text-blue-400'],
                ['IN', 'Check-in', $totalCheckedIn . ' / ' . $totalTicketsSold, 'bg-punk-gold/10 border-punk-gold/30 text-punk-gold'],
            ] as $metric)
            <div class="bg-punk-charcoal border {{ $metric[3] }} overflow-hidden">
                <div class="p-5">
                    <div class="font-sans font-bold text-base mb-2 tracking-widest">{{ $metric[0] }}</div>
                    <p class="font-body text-punk-cream/40 text-xs uppercase tracking-wider mb-1">{{ $metric[1] }}</p>
                    <p class="font-sans font-bold text-xl {{ explode(' ', $metric[3])[2] }}">{{ $metric[2] }}</p>
                </div>
                <div class="h-0.5 bg-gradient-to-r from-transparent via-current to-transparent opacity-20"></div>
            </div>
            @endforeach
        </div>

        <!-- Check-in progress bar -->
        @if($totalTicketsSold > 0)
        <div class="bg-punk-charcoal border border-punk-gray/40 p-5 mb-8">
            <div class="flex justify-between items-center mb-3">
                <p class="font-sans text-punk-cream/60 text-xs tracking-widest uppercase">Progress Check-in</p>
                <p class="font-sans font-bold text-punk-cream text-sm">
                    {{ round(($totalCheckedIn / $totalTicketsSold) * 100) }}%
                </p>
            </div>
            <div class="h-2 bg-punk-gray/50 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-punk-green to-punk-green-light rounded-full progress-fill"
                     style="width: {{ round(($totalCheckedIn / $totalTicketsSold) * 100) }}%">
                </div>
            </div>
            <div class="flex justify-between mt-2 font-body text-punk-cream/30 text-xs">
                <span>{{ $totalCheckedIn }} sudah masuk</span>
                <span>{{ $totalTicketsSold - $totalCheckedIn }} belum masuk</span>
            </div>
        </div>
        @endif

        <!-- Tier breakdown -->
        @if($event && $tierStats->count() > 0)
        <div class="mb-8">
            <h2 class="font-sans font-bold text-xl text-punk-cream mb-4">BREAKDOWN TIKET PER TIER</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($event->ticketTiers as $tier)
                @php
                    $soldCount = $tier->total_quota - $tier->available_stock;
                    $soldPct = $tier->total_quota > 0 ? round(($soldCount / $tier->total_quota) * 100) : 0;
                @endphp
                <div class="bg-punk-charcoal border border-punk-gray/40 p-4 space-y-3">
                    <div>
                        <p class="font-sans font-bold text-punk-cream text-sm leading-tight">{{ $tier->name }}</p>
                        <p class="font-body text-punk-cream/40 text-xs mt-0.5">Rp {{ number_format($tier->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="h-1.5 bg-punk-gray/50 rounded-full overflow-hidden">
                        <div class="h-full rounded-full progress-fill {{ $soldPct >= 90 ? 'bg-punk-red' : ($soldPct >= 60 ? 'bg-punk-gold' : 'bg-punk-green') }}"
                             style="width: {{ $soldPct }}%">
                        </div>
                    </div>
                    <div class="flex justify-between font-body text-punk-cream/50 text-xs">
                        <span>{{ $soldCount }} terjual</span>
                        <span>{{ $tier->available_stock }} sisa / {{ $tier->total_quota }}</span>
                    </div>
                    <div class="font-sans font-bold text-lg {{ $soldPct >= 90 ? 'text-punk-red' : ($soldPct >= 60 ? 'text-punk-gold' : 'text-punk-green') }}">
                        {{ $soldPct }}%
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent orders -->
        <div>
            <h2 class="font-sans font-bold text-xl text-punk-cream mb-4">ORDER TERBARU</h2>
            <div class="bg-punk-charcoal border border-punk-gray/40 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-punk-black/50 border-b border-punk-gray/30">
                                <th class="text-left font-sans text-punk-cream/40 text-xs tracking-widest uppercase px-5 py-3">Order Code</th>
                                <th class="text-left font-sans text-punk-cream/40 text-xs tracking-widest uppercase px-5 py-3">Pembeli</th>
                                <th class="text-left font-sans text-punk-cream/40 text-xs tracking-widest uppercase px-5 py-3 hidden sm:table-cell">Tier</th>
                                <th class="text-right font-sans text-punk-cream/40 text-xs tracking-widest uppercase px-5 py-3">Total</th>
                                <th class="text-left font-sans text-punk-cream/40 text-xs tracking-widest uppercase px-5 py-3 hidden md:table-cell">Waktu</th>
                                <th class="text-center font-sans text-punk-cream/40 text-xs tracking-widest uppercase px-5 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr class="border-b border-punk-gray/20 hover:bg-punk-black/30 transition-colors">
                                <td class="px-5 py-3">
                                    <span class="font-mono text-punk-red text-xs">{{ $order->order_code }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    <p class="font-body text-punk-cream text-sm">{{ $order->customer_name }}</p>
                                    <p class="font-body text-punk-cream/40 text-xs">{{ $order->customer_email }}</p>
                                </td>
                                <td class="px-5 py-3 hidden sm:table-cell">
                                    <span class="font-body text-punk-cream/60 text-xs">
                                        {{ $order->orderItems->first()?->ticketTier?->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <span class="font-sans font-bold text-punk-green text-sm">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 hidden md:table-cell">
                                    <span class="font-body text-punk-cream/40 text-xs">
                                        {{ $order->paid_at?->format('d M Y H:i') ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <a href="{{ route('ticket.show', $order->order_code) }}"
                                       class="font-body text-punk-cream/40 hover:text-punk-red text-xs transition-colors">
                                        Lihat →
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center font-body text-punk-cream/20 text-sm">
                                    Belum ada order masuk
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick links -->
        <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 gap-3">
            <a href="{{ route('home') }}"
               class="flex items-center gap-2 border border-punk-gray/30 hover:border-punk-red/40 bg-punk-charcoal text-punk-cream/60 hover:text-punk-cream font-body text-sm px-4 py-3 transition-all">
                Halaman Utama
            </a>
            <a href="{{ route('gate.scan') }}"
               class="flex items-center gap-2 border border-punk-green/20 hover:border-punk-green bg-punk-charcoal text-punk-green/60 hover:text-punk-green font-body text-sm px-4 py-3 transition-all">
                Gate Scanner
            </a>
            <button onclick="location.reload()"
                    class="flex items-center gap-2 border border-punk-gray/30 hover:border-punk-cream/40 bg-punk-charcoal text-punk-cream/60 hover:text-punk-cream font-body text-sm px-4 py-3 transition-all">
                Refresh Data
            </button>
        </div>
    </div>
</div>
@endsection
