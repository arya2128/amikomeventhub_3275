@extends('layouts.admin')

@section('title', 'Penjaga Pintu - Check-in Scanner')
@section('page_title', 'Aplikasi Penjaga Pintu (Check-in Scanner)')
@section('page_subtitle', 'Gunakan kamera handphone atau laptop untuk memindai QR Code tiket peserta.')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Scanner Card -->
    <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 p-8 shadow-sm space-y-6">
        <h3 class="font-black text-xl flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                </path>
            </svg>
            Kamera Pemindai QR
        </h3>

        <!-- Camera Selection dropdown -->
        <div>
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pilih Perangkat Kamera</label>
            <select id="camera-select"
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 text-slate-700 font-medium">
                <option value="">Memuat daftar kamera...</option>
            </select>
        </div>

        <!-- Scanner Reader Window -->
        <div class="relative bg-slate-900 rounded-[2rem] overflow-hidden aspect-[4/3] border-4 border-slate-950 flex items-center justify-center">
            <div id="reader" class="w-full h-full"></div>
            <!-- Laser Overlay effect -->
            <div id="scanner-laser" class="absolute left-0 right-0 h-1 bg-indigo-500 opacity-60 pointer-events-none hidden" style="top: 10%;"></div>
        </div>

        <div class="flex gap-4">
            <button id="start-btn"
                class="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 active:scale-95 transition-all shadow-lg shadow-indigo-150">
                Aktifkan Scanner
            </button>
            <button id="stop-btn" disabled
                class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-bold hover:bg-slate-200 active:scale-95 transition-all cursor-not-allowed">
                Matikan
            </button>
        </div>
    </div>

    <!-- Manual Entry & Logs Card -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Manual Form -->
        <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
            <h4 class="font-black text-lg mb-4">Input Manual</h4>
            <form id="manual-form" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Order ID (TRX-...)</label>
                    <input type="text" id="manual-order-id" placeholder="Masukkan Kode Order ID" required
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500/20 font-mono">
                </div>
                <button type="submit"
                    class="w-full py-4 bg-slate-950 text-white rounded-xl font-bold hover:bg-slate-900 transition-all">
                    Verifikasi Tiket
                </button>
            </form>
        </div>

        <!-- Scanner Result Display -->
        <div id="result-card" class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm hidden transition-all duration-300">
            <div class="text-center space-y-4">
                <div id="result-icon" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto"></div>
                <h4 id="result-title" class="text-xl font-black"></h4>
                <p id="result-message" class="text-slate-500 text-sm leading-relaxed"></p>
            </div>
        </div>
    </div>
</div>

<!-- HTML5 QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let html5QrCode;
    const cameraSelect = document.getElementById('camera-select');
    const startBtn = document.getElementById('start-btn');
    const stopBtn = document.getElementById('stop-btn');
    const laser = document.getElementById('scanner-laser');

    // Load available cameras
    Html5Qrcode.getCameras().then(devices => {
        if (devices && devices.length) {
            cameraSelect.innerHTML = '';
            devices.forEach(device => {
                const option = document.createElement('option');
                option.value = device.id;
                option.text = device.label || `Camera ${cameraSelect.options.length + 1}`;
                cameraSelect.appendChild(option);
            });
        } else {
            cameraSelect.innerHTML = '<option value="">Kamera tidak ditemukan</option>';
        }
    }).catch(err => {
        console.error(err);
        cameraSelect.innerHTML = '<option value="">Gagal mendapatkan kamera</option>';
    });

    startBtn.onclick = function() {
        const cameraId = cameraSelect.value;
        if (!cameraId) {
            alert('Silakan pilih kamera terlebih dahulu!');
            return;
        }

        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            cameraId,
            {
                fps: 15,
                qrbox: { width: 250, height: 250 }
            },
            qrCodeMessage => {
                // Success scan callback
                verifyTicket(qrCodeMessage);
            },
            errorMessage => {
                // parse errors are quiet
            }
        ).then(() => {
            startBtn.disabled = true;
            startBtn.classList.add('cursor-not-allowed', 'opacity-50');
            stopBtn.disabled = false;
            stopBtn.classList.remove('cursor-not-allowed', 'opacity-50', 'bg-slate-100', 'text-slate-500');
            stopBtn.classList.add('bg-rose-600', 'text-white', 'hover:bg-rose-700');
            laser.classList.remove('hidden');
            animateLaser();
        }).catch(err => {
            alert('Gagal menyalakan kamera: ' + err);
        });
    };

    stopBtn.onclick = function() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                startBtn.disabled = false;
                startBtn.classList.remove('cursor-not-allowed', 'opacity-50');
                stopBtn.disabled = true;
                stopBtn.classList.remove('bg-rose-600', 'text-white', 'hover:bg-rose-700');
                stopBtn.classList.add('bg-slate-100', 'text-slate-500', 'cursor-not-allowed');
                laser.classList.add('hidden');
            });
        }
    };

    // Laser scanning animation
    let direction = 1;
    function animateLaser() {
        if (laser.classList.contains('hidden')) return;
        let top = parseFloat(laser.style.top || 10);
        top += 2 * direction;
        if (top >= 90) direction = -1;
        if (top <= 10) direction = 1;
        laser.style.top = top + '%';
        requestAnimationFrame(animateLaser);
    }

    // Manual Submit verification
    document.getElementById('manual-form').onsubmit = function(e) {
        e.preventDefault();
        const orderId = document.getElementById('manual-order-id').value;
        verifyTicket(orderId);
    };

    // AJAX verification request
    function verifyTicket(orderId) {
        // Hentikan scan sementara agar tidak spam request
        if (html5QrCode && html5QrCode.isScanning) {
            stopBtn.click();
        }

        fetch("{{ route('admin.checkin.verify') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ order_id: orderId })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            const resultCard = document.getElementById('result-card');
            const resultIcon = document.getElementById('result-icon');
            const resultTitle = document.getElementById('result-title');
            const resultMessage = document.getElementById('result-message');

            resultCard.classList.remove('hidden');

            if (res.status === 200) {
                // Success check-in
                resultIcon.className = "w-16 h-16 rounded-full flex items-center justify-center mx-auto bg-green-100 text-green-500 animate-bounce";
                resultIcon.innerHTML = `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>`;
                resultTitle.innerText = "Check-in Berhasil!";
                resultTitle.className = "text-xl font-black text-green-600";
                resultMessage.innerText = res.body.message;
            } else {
                // Error (Duplicate checkin / Ticket not found)
                resultIcon.className = "w-16 h-16 rounded-full flex items-center justify-center mx-auto bg-rose-100 text-rose-500 animate-shake";
                resultIcon.innerHTML = `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>`;
                resultTitle.innerText = "Check-in Gagal!";
                resultTitle.className = "text-xl font-black text-rose-600";
                resultMessage.innerText = res.body.message;
            }
        })
        .catch(err => {
            alert("Terjadi kesalahan jaringan!");
            console.error(err);
        });
    }
</script>

<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-6px); }
        75% { transform: translateX(6px); }
    }
    .animate-shake { animation: shake 0.3s ease-in-out; }
</style>
@endsection
