@extends('layouts.app')

@section('content')
<div class="min-h-screen pt-20 pb-20">
    <div class="max-w-2xl mx-auto px-4">

        <!-- Header -->
        <div class="mt-8 mb-8 text-center">
            <div class="inline-flex items-center gap-2 bg-punk-green/10 border border-punk-green/30 rounded px-4 py-2 mb-4">
                <div class="w-2 h-2 bg-punk-green rounded-full animate-pulse"></div>
                <span class="font-sans text-punk-green text-xs tracking-[0.3em] uppercase font-medium">Gate Scanner</span>
            </div>
            <h1 class="font-sans font-bold text-4xl text-punk-cream">GATE CREW</h1>
            <p class="font-body text-punk-cream/50 text-sm mt-2">Scan QR tiket atau input kode manual untuk verifikasi masuk.</p>
        </div>

        <!-- Scanner card -->
        <div class="bg-punk-charcoal border border-punk-gray/40 overflow-hidden">
            <!-- Red top bar -->
            <div class="h-1 bg-gradient-to-r from-punk-red via-punk-red-light to-punk-red"></div>

            <!-- Camera scanner area -->
            <div class="p-6">
                <div id="scanner-container" class="relative bg-punk-black aspect-square max-w-xs mx-auto border border-punk-gray/40 overflow-hidden mb-6">
                    <!-- Scan line animation -->
                    <div class="scan-line"></div>

                    <!-- Corner brackets -->
                    <div class="absolute top-3 left-3 w-6 h-6 border-t-2 border-l-2 border-punk-green"></div>
                    <div class="absolute top-3 right-3 w-6 h-6 border-t-2 border-r-2 border-punk-green"></div>
                    <div class="absolute bottom-3 left-3 w-6 h-6 border-b-2 border-l-2 border-punk-green"></div>
                    <div class="absolute bottom-3 right-3 w-6 h-6 border-b-2 border-r-2 border-punk-green"></div>

                    <!-- Video element for camera -->
                    <video id="qr-video" class="w-full h-full object-cover hidden"></video>

                    <!-- Placeholder icon when camera not active -->
                    <div id="camera-placeholder" class="flex flex-col items-center justify-center h-full text-center p-6">
                        <svg class="w-16 h-16 text-punk-green/30 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="font-body text-punk-cream/30 text-xs">Klik tombol di bawah untuk<br>mengaktifkan kamera</p>
                    </div>
                </div>

                <!-- Camera toggle button -->
                <div class="flex justify-center mb-6">
                    <button id="camera-btn"
                            class="inline-flex items-center gap-2 bg-punk-green/10 border border-punk-green/40 hover:border-punk-green text-punk-green font-body text-sm px-6 py-3 transition-all duration-200 hover:bg-punk-green/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Aktifkan Kamera
                    </button>
                </div>

                <!-- Divider -->
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex-1 h-px bg-punk-gray/40"></div>
                    <span class="font-body text-punk-cream/30 text-xs uppercase tracking-widest">atau input manual</span>
                    <div class="flex-1 h-px bg-punk-gray/40"></div>
                </div>

                <!-- Manual input form -->
                <form id="manual-scan-form" class="space-y-3">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text"
                               id="ticket-code-input"
                               placeholder="TKT-OD-XXXXXXXXXX"
                               class="flex-1 bg-punk-black border border-punk-gray/50 focus:border-punk-green text-punk-cream font-mono text-sm px-4 py-3 outline-none transition-colors placeholder:text-punk-cream/20 uppercase tracking-widest">
                        <button type="submit"
                                class="bg-punk-green hover:bg-punk-green-dark text-punk-black font-sans font-bold text-sm px-6 py-3 tracking-widest uppercase transition-colors">
                            SCAN
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Result display area -->
        <div id="result-area" class="mt-6 hidden">
            <!-- Filled dynamically by JS -->
        </div>

        <!-- Recent scan log -->
        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <p class="font-sans text-punk-cream/50 text-xs tracking-widest uppercase">Log Scan Terkini</p>
                <button id="clear-log" class="font-body text-punk-cream/30 hover:text-punk-red text-xs transition-colors">Hapus Log</button>
            </div>
            <div id="scan-log" class="space-y-2 max-h-64 overflow-y-auto">
                <p class="font-body text-punk-cream/20 text-xs text-center py-8">Belum ada scan</p>
            </div>
        </div>

        <!-- Navigation for crew -->
        <div class="mt-8 flex justify-center gap-4 text-xs font-body">
            <a href="{{ route('admin.dashboard') }}" class="text-punk-cream/40 hover:text-punk-gold transition-colors">
                ← Admin Dashboard
            </a>
            <span class="text-punk-gray/50">•</span>
            <a href="{{ route('home') }}" class="text-punk-cream/40 hover:text-punk-red transition-colors">
                Halaman Utama
            </a>
        </div>
    </div>
</div>

<script>
    // Gate scanner functionality
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const resultArea = document.getElementById('result-area');
    const scanLog = document.getElementById('scan-log');
    const logItems = [];

    async function checkIn(ticketCode) {
        if (!ticketCode?.trim()) return;

        try {
            const response = await fetch('{{ route("gate.checkin") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ ticket_code: ticketCode.trim().toUpperCase() }),
            });

            const data = await response.json();
            showResult(data, response.status);
            addToLog(ticketCode, data, response.status);

        } catch (err) {
            showResult({ status: 'ERROR', message: 'Koneksi gagal. Coba lagi.' }, 500);
        }
    }

    function showResult(data, status) {
        resultArea.classList.remove('hidden');

        const configs = {
            VALID: {
                bg: 'bg-punk-green/10',
                border: 'border-punk-green',
                text: 'text-punk-green',
                iconSvg: '<svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                title: 'AKSES DITERIMA',
            },
            USED: {
                bg: 'bg-punk-gold/10',
                border: 'border-punk-gold',
                text: 'text-punk-gold',
                iconSvg: '<svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                title: 'TIKET SUDAH DIGUNAKAN',
            },
            INVALID: {
                bg: 'bg-punk-red/10',
                border: 'border-punk-red',
                text: 'text-punk-red',
                iconSvg: '<svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                title: 'TIKET PALSU / TIDAK TERDAFTAR',
            },
            ERROR: {
                bg: 'bg-punk-muted/20',
                border: 'border-punk-muted',
                text: 'text-punk-cream/60',
                iconSvg: '<svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                title: 'ERROR',
            },
        };

        const cfg = configs[data.status] || configs.ERROR;

        resultArea.innerHTML = `
            <div class="${cfg.bg} border-2 ${cfg.border} p-6 text-center stamp-animation">
                <div class="${cfg.text} mb-3">${cfg.iconSvg}</div>
                <h2 class="font-sans font-bold text-2xl ${cfg.text} mb-2">${cfg.title}</h2>
                ${data.holder ? `<p class="font-body text-punk-cream/80 font-medium">${data.holder}</p>` : ''}
                ${data.tier ? `<p class="font-body text-punk-cream/50 text-sm">${data.tier}</p>` : ''}
                <p class="font-body text-punk-cream/40 text-sm mt-2">${data.message}</p>
            </div>
        `;

        // Auto-clear result after 5 seconds
        setTimeout(() => {
            resultArea.classList.add('hidden');
            resultArea.innerHTML = '';
        }, 5000);
    }

    function addToLog(code, data, status) {
        const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const statusColors = {
            VALID: 'text-punk-green',
            USED: 'text-punk-gold',
            INVALID: 'text-punk-red',
            ERROR: 'text-punk-cream/40',
        };

        if (logItems.length === 0) {
            scanLog.innerHTML = '';
        }

        const item = document.createElement('div');
        item.className = 'flex items-center justify-between bg-punk-charcoal border border-punk-gray/30 px-3 py-2';
        item.innerHTML = `
            <div class="flex items-center gap-3">
                <span class="${statusColors[data.status] || 'text-punk-cream/40'} font-sans font-bold text-xs">${data.status}</span>
                <span class="font-mono text-punk-cream/50 text-xs">${code}</span>
            </div>
            <span class="font-body text-punk-cream/30 text-xs">${time}</span>
        `;

        scanLog.insertBefore(item, scanLog.firstChild);
        logItems.push(item);

        // Keep max 20 log items
        if (logItems.length > 20) {
            const old = logItems.shift();
            old.remove();
        }
    }

    // Manual form handler
    document.getElementById('manual-scan-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const input = document.getElementById('ticket-code-input');
        checkIn(input.value);
        input.value = '';
        input.focus();
    });

    // Clear log
    document.getElementById('clear-log').addEventListener('click', () => {
        scanLog.innerHTML = '<p class="font-body text-punk-cream/20 text-xs text-center py-8">Belum ada scan</p>';
        logItems.length = 0;
    });

    // Camera scanner using jsQR
    let cameraActive = false;
    let videoStream = null;
    let animationId = null;
    const cameraBtn = document.getElementById('camera-btn');
    const video = document.getElementById('qr-video');
    const placeholder = document.getElementById('camera-placeholder');

    cameraBtn.addEventListener('click', async () => {
        if (cameraActive) {
            stopCamera();
        } else {
            await startCamera();
        }
    });

    async function startCamera() {
        try {
            videoStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
            });
            video.srcObject = videoStream;
            video.play();
            video.classList.remove('hidden');
            placeholder.classList.add('hidden');
            cameraBtn.textContent = 'Stop Kamera';
            cameraActive = true;
            scanQRCode();
        } catch (err) {
            alert('Tidak dapat mengakses kamera: ' + err.message);
        }
    }

    function stopCamera() {
        if (videoStream) {
            videoStream.getTracks().forEach(t => t.stop());
            videoStream = null;
        }
        video.classList.add('hidden');
        placeholder.classList.remove('hidden');
        cameraBtn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Aktifkan Kamera`;
        cameraActive = false;
        if (animationId) cancelAnimationFrame(animationId);
    }

    function scanQRCode() {
        if (!cameraActive) return;
        // Use jsQR library if available, else gracefully degrade
        if (typeof jsQR !== 'undefined' && video.readyState === video.HAVE_ENOUGH_DATA) {
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);
            if (code) {
                checkIn(code.data);
                // Small pause before next scan
                setTimeout(() => { animationId = requestAnimationFrame(scanQRCode); }, 2000);
                return;
            }
        }
        animationId = requestAnimationFrame(scanQRCode);
    }
</script>

<!-- jsQR library for QR scanning -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

@endsection
