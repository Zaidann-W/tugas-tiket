@extends('layouts.app')

@section('content')
<div class="min-h-screen pt-20 pb-20">
    <div class="max-w-3xl mx-auto px-4">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-punk-cream/30 text-xs font-body mb-8 mt-4">
            <a href="{{ route('home') }}" class="hover:text-punk-red transition-colors">Beranda</a>
            <span>/</span>
            <span class="text-punk-cream/60">Checkout Tiket</span>
        </div>

        <div class="grid md:grid-cols-5 gap-8">
            <!-- Left: Tier summary -->
            <div class="md:col-span-2">
                <div class="sticky top-24 space-y-4">
                    <div class="bg-punk-charcoal border border-punk-gray/40 overflow-hidden">
                        <!-- Ticket card bg -->
                        <div class="relative h-28 overflow-hidden">
                            <img src="{{ asset('images/over-distortion/hero_band_banner.jpg') }}"
                                 alt="Event" class="w-full h-full object-cover object-center">
                            <div class="absolute inset-0 bg-gradient-to-t from-punk-black via-punk-black/60 to-transparent"></div>
                            <div class="absolute bottom-3 left-4">
                                <p class="font-sans font-bold text-punk-red text-xs tracking-widest uppercase">OVER DISTORTION</p>
                                <p class="font-sans font-bold text-punk-cream text-sm">{{ $event->title }}</p>
                            </div>
                        </div>

                        <div class="p-4 space-y-3">
                            <div>
                                <p class="font-body text-punk-cream/40 text-xs uppercase tracking-wider mb-1">Kategori</p>
                                <p class="font-sans font-bold text-punk-cream">{{ $tier->name }}</p>
                            </div>
                            <div class="h-px bg-punk-gray/40 ticket-tear"></div>
                            <div class="flex justify-between">
                                <span class="font-body text-punk-cream/40 text-xs uppercase tracking-wider">Harga per tiket</span>
                                <span class="font-sans font-bold text-punk-red">Rp {{ number_format($tier->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-body text-punk-cream/40 text-xs uppercase tracking-wider">Tersisa</span>
                                <span class="font-sans font-bold {{ $tier->isLowStock() ? 'text-punk-gold' : 'text-punk-green' }}">
                                    {{ $tier->available_stock }} tiket
                                </span>
                            </div>
                            <div class="h-px bg-punk-gray/40"></div>
                            <div>
                                <p class="font-body text-punk-cream/40 text-xs uppercase tracking-wider mb-1">Venue & Tanggal</p>
                                <p class="font-body text-punk-cream/70 text-xs">{{ $event->event_date->format('d F Y, H:i') }} WIB</p>
                                <p class="font-body text-punk-cream/50 text-xs mt-0.5">{{ Str::limit($event->venue, 60) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div id="total-box" class="bg-punk-red/10 border border-punk-red/30 p-4">
                        <div class="flex justify-between items-center">
                            <span class="font-body text-punk-cream/60 text-sm">Total</span>
                            <span id="total-amount" class="font-sans font-bold text-punk-red text-xl">
                                Rp {{ number_format($tier->price, 0, ',', '.') }}
                            </span>
                        </div>
                        <p class="font-body text-punk-cream/30 text-xs mt-1">Instant paid simulation (sandbox)</p>
                    </div>
                </div>
            </div>

            <!-- Right: Checkout form -->
            <div class="md:col-span-3">
                <div class="mb-6">
                    <h1 class="font-sans font-bold text-3xl text-punk-cream">CHECKOUT TIKET</h1>
                    <p class="font-body text-punk-cream/50 text-sm mt-1">Isi data dengan benar. Nama holder akan tercetak di e-tiket.</p>
                </div>

                @if($errors->any())
                <div class="bg-punk-red/15 border border-punk-red text-punk-cream font-body text-sm p-4 mb-6">
                    <div class="flex items-center gap-2 font-sans font-bold text-punk-red text-sm tracking-wider uppercase mb-2">
                        <svg class="w-4 h-4 text-punk-red shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Gagal Melakukan Checkout</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-xs text-punk-cream/80">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @php
                    $selectedQty = (int) old('quantity', 1);
                @endphp

                <form action="{{ route('booking.process') }}" method="POST" id="checkout-form" class="space-y-6">
                    @csrf
                    <input type="hidden" name="tier_id" value="{{ $tier->id }}">

                    <!-- Quantity selector -->
                    <div>
                        <label class="block font-sans text-punk-cream/80 text-xs tracking-widest uppercase mb-2">
                            Jumlah Tiket <span class="text-punk-red">*</span>
                        </label>
                        <div class="flex gap-2">
                            @for($i = 1; $i <= min(4, $tier->available_stock); $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="quantity" value="{{ $i }}" class="sr-only peer"
                                       {{ ($selectedQty == $i) ? 'checked' : '' }}>
                                <div class="w-12 h-12 flex items-center justify-center border border-punk-gray/50 font-sans font-bold text-punk-cream/60
                                            peer-checked:border-punk-red peer-checked:text-punk-red peer-checked:bg-punk-red/10
                                            hover:border-punk-cream/60 hover:text-punk-cream transition-all">
                                    {{ $i }}
                                </div>
                            </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Contact info -->
                    <div class="space-y-4">
                        <p class="font-sans text-punk-cream/50 text-xs tracking-widest uppercase">Data Pemesan</p>

                        <div>
                            <label for="customer_name" class="block font-body text-punk-cream/60 text-xs mb-1.5">Nama Lengkap *</label>
                            <input type="text" id="customer_name" name="customer_name"
                                   value="{{ old('customer_name') }}"
                                   placeholder="Nama lengkap kamu"
                                   class="w-full bg-punk-charcoal border {{ $errors->has('customer_name') ? 'border-punk-red' : 'border-punk-gray/50' }} focus:border-punk-red text-punk-cream font-body text-sm px-4 py-3 outline-none transition-colors placeholder:text-punk-cream/20"
                                   required>
                            @error('customer_name')
                            <p class="text-punk-red-light text-xs mt-1 font-body">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_email" class="block font-body text-punk-cream/60 text-xs mb-1.5">Email Aktif *</label>
                            <input type="email" id="customer_email" name="customer_email"
                                   value="{{ old('customer_email') }}"
                                   placeholder="email@kamu.com"
                                   class="w-full bg-punk-charcoal border {{ $errors->has('customer_email') ? 'border-punk-red' : 'border-punk-gray/50' }} focus:border-punk-red text-punk-cream font-body text-sm px-4 py-3 outline-none transition-colors placeholder:text-punk-cream/20"
                                   required>
                            @error('customer_email')
                            <p class="text-punk-red-light text-xs mt-1 font-body">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_phone" class="block font-body text-punk-cream/60 text-xs mb-1.5">Nomor WhatsApp *</label>
                            <input type="tel" id="customer_phone" name="customer_phone"
                                   value="{{ old('customer_phone') }}"
                                   placeholder="081234567890"
                                   class="w-full bg-punk-charcoal border {{ $errors->has('customer_phone') ? 'border-punk-red' : 'border-punk-gray/50' }} focus:border-punk-red text-punk-cream font-body text-sm px-4 py-3 outline-none transition-colors placeholder:text-punk-cream/20"
                                   required>
                            @error('customer_phone')
                            <p class="text-punk-red-light text-xs mt-1 font-body">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Individual holder names -->
                    <div>
                        <p class="font-sans text-punk-cream/50 text-xs tracking-widest uppercase mb-3">Nama Holder Tiket</p>
                        <p class="font-body text-punk-cream/40 text-xs mb-4">Nama masing-masing pemegang tiket (untuk mencegah resale). Sesuaikan dengan identitas KTP/ID.</p>
                        <div id="holders-container" class="space-y-3">
                            @for($i = 0; $i < min(4, $tier->available_stock); $i++)
                            <div class="holder-field {{ $i >= $selectedQty ? 'hidden' : '' }}" data-index="{{ $i }}">
                                <label for="holder_{{ $i }}" class="block font-body text-punk-cream/50 text-xs mb-1.5">
                                    Holder Tiket {{ $i + 1 }} {{ $i === 0 ? '*' : '' }}
                                    @if($i === 0)
                                    <span class="text-punk-cream/30 text-[10px]">(otomatis terisi dari nama pemesan)</span>
                                    @endif
                                </label>
                                <input type="text" id="holder_{{ $i }}" name="holders[]"
                                       value="{{ old('holders.' . $i) }}"
                                       placeholder="Nama pemegang tiket ke-{{ $i + 1 }}"
                                       class="w-full bg-punk-charcoal border {{ $errors->has('holders.' . $i) ? 'border-punk-red' : 'border-punk-gray/50' }} focus:border-punk-red text-punk-cream font-body text-sm px-4 py-3 outline-none transition-colors placeholder:text-punk-cream/20"
                                       {{ $i >= $selectedQty ? 'disabled' : 'required' }}>
                                @error('holders.' . $i)
                                <p class="text-punk-red-light text-xs mt-1 font-body">{{ $message }}</p>
                                @enderror
                            </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="bg-punk-charcoal/50 border border-punk-gray/30 p-4">
                        <p class="font-body text-punk-cream/40 text-xs leading-relaxed">
                            <strong class="text-punk-cream/60">Sandbox Mode:</strong> Pembayaran langsung dikonfirmasi (instant paid simulation). Untuk keperluan demo & pengembangan. Tiket tidak dapat dikembalikan atau dipindahtangankan.
                        </p>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                            id="submit-btn"
                            class="w-full bg-punk-red hover:bg-punk-red-dark text-white font-sans font-bold py-4 text-sm tracking-widest uppercase transition-all duration-200 hover:scale-[1.01] glow-red cursor-pointer">
                        KONFIRMASI & BAYAR SEKARANG
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const tierPrice = {{ $tier->price }};
    const quantityRadios = document.querySelectorAll('input[name="quantity"]');
    const holderFields = document.querySelectorAll('.holder-field');
    const totalEl = document.getElementById('total-amount');
    const customerNameInput = document.getElementById('customer_name');
    const firstHolderInput = document.getElementById('holder_0');

    let firstHolderManuallyEdited = false;

    if (firstHolderInput && customerNameInput) {
        if (firstHolderInput.value && firstHolderInput.value !== customerNameInput.value) {
            firstHolderManuallyEdited = true;
        }

        customerNameInput.addEventListener('input', function() {
            if (!firstHolderManuallyEdited && firstHolderInput) {
                firstHolderInput.value = this.value;
            }
        });

        firstHolderInput.addEventListener('input', function() {
            if (this.value !== customerNameInput.value) {
                firstHolderManuallyEdited = true;
            }
        });
    }

    function updateForm() {
        const qty = parseInt(document.querySelector('input[name="quantity"]:checked')?.value || 1);

        // Update total price
        if (totalEl) {
            totalEl.textContent = 'Rp ' + (tierPrice * qty).toLocaleString('id-ID');
        }

        // Show/hide holder fields and toggle disabled/required
        holderFields.forEach((field, idx) => {
            const input = field.querySelector('input');
            if (!input) return;

            if (idx < qty) {
                field.classList.remove('hidden');
                input.disabled = false;
                input.required = true;
            } else {
                field.classList.add('hidden');
                input.disabled = true;
                input.required = false;
            }
        });
    }

    quantityRadios.forEach(radio => {
        radio.addEventListener('change', updateForm);
    });

    updateForm();
</script>
@endsection
