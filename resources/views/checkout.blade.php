@extends('layouts.app')

@section('content')
    <main class="max-w-3xl mx-auto px-6 py-20">
        <div class="mb-12">
            <a href="{{ route('event.show', $event->id) }}" class="text-indigo-600 font-bold flex items-center gap-2 mb-6 hover:text-indigo-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Event
            </a>
            <h1 class="text-4xl font-extrabold">Checkout</h1>
            <p class="text-slate-500 mt-2">Lengkapi data Anda untuk mendapatkan tiket.</p>
        </div>

        @if ($errors->any())
            <div class="mb-8 p-6 bg-rose-50 border-2 border-rose-100 rounded-2xl text-rose-700">
                <p class="font-bold mb-2">Terjadi kesalahan:</p>
                <ul class="list-disc pl-5 space-y-1 text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8">
            <!-- Rincian Pesanan -->
            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                <h3 class="text-xl font-bold mb-6 border-b pb-4">Pesanan Anda</h3>
                    <img src="{{ $event->poster_url }}" alt="{{ $event->title }}" class="w-24 h-24 rounded-2xl object-cover aspect-square shadow-sm" onerror="this.onerror=null;this.src='/assets/concert.png';">
                    <div>
                        <h4 class="font-extrabold text-lg">{{ $event->title }}</h4>
                        <p class="text-slate-500 text-sm mt-1">
                            {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d M Y') }} • {{ $event->location }}
                        </p>
                        <p class="text-indigo-600 font-bold mt-2">
                            1 x Rp {{ number_format($event->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t space-y-3">
                    <div class="flex justify-between text-slate-500">
                        <span>Harga Tiket</span>
                        <span>Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Biaya Layanan</span>
                        <span>Rp 5.000</span>
                    </div>
                    <div class="flex justify-between text-2xl font-black mt-4 pt-4 border-t">
                        <span>Total Bayar</span>
                        <span class="text-indigo-600">Rp {{ number_format($event->price + 5000, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Form Data Pemesan -->
            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                <h3 class="text-xl font-bold mb-6 italic text-indigo-600 underline underline-offset-8">📦 Data Pemesan</h3>

                @if(!Auth::check())
                    <div class="mb-6 p-4 bg-indigo-50 border border-indigo-100 rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <p class="font-bold text-indigo-900 text-sm">Mau pesan lebih cepat?</p>
                            <p class="text-xs text-indigo-700">Masuk dengan Google untuk mengisi data secara instan.</p>
                        </div>
                        <a href="{{ route('auth.google', ['redirect' => request()->fullUrl()]) }}" class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl font-bold text-xs flex items-center gap-2 transition shadow-sm whitespace-nowrap">
                            <svg class="w-4 h-4" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.85z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.85c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Continue with Google
                        </a>
                    </div>
                @else
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center font-bold">✓</div>
                        <div>
                            <p class="font-bold text-emerald-900 text-sm">Terhubung sebagai {{ Auth::user()->name }}</p>
                            <p class="text-xs text-emerald-700">Formulir telah diisi otomatis menggunakan data profil Anda.</p>
                        </div>
                    </div>
                @endif

                <form id="checkout-form" action="{{ route('checkout.store', $event->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Lengkap</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name', Auth::check() ? Auth::user()->name : '') }}" placeholder="Masukkan nama sesuai identitas"
                            class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                            required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Email Aktif</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', Auth::check() ? Auth::user()->email : '') }}" placeholder="contoh@gmail.com"
                                class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                                required>
                            <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-tighter">*E-Ticket akan dikirim ke email ini</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">No. WhatsApp</label>
                            <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="08xxxxxxx"
                                class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                                required>
                        </div>
                    </div>

                    <button type="button" onclick="showMidtrans()"
                        class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black text-xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all">
                        Bayar Sekarang
                    </button>
                    <p class="text-center text-xs text-slate-400">Dengan menekan tombol di atas, Anda menyetujui Syarat & Ketentuan kami.</p>
                </form>
            </div>
        </div>
    </main>

    <!-- Overlay Midtrans Sandbox -->
    <div id="midtrans-overlay"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-6">
        <div class="bg-white w-full max-w-sm rounded-[2rem] overflow-hidden shadow-2xl animate-bounce-in">
            <div class="bg-slate-50 p-6 flex justify-between items-center border-b">
                <img src="https://midtrans.com/assets/img/logo-dark.png" alt="Midtrans Logo" class="h-6">
                <button onclick="hideMidtrans()" class="p-2 hover:bg-slate-200 rounded-full transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-8 text-center">
                <p class="text-slate-500 font-medium">Total Tagihan</p>
                <h2 class="text-3xl font-black text-indigo-700 my-2">Rp {{ number_format($event->price + 5000, 0, ',', '.') }}</h2>
                <p class="text-xs text-slate-400 font-bold">AmikomEventHub Secure payment</p>

                <div class="mt-8 space-y-4">
                    <button onclick="submitCheckoutForm()"
                        class="w-full py-4 border-2 border-indigo-100 rounded-2xl flex justify-between items-center px-6 hover:border-indigo-600 transition group">
                        <span class="font-bold group-hover:text-indigo-600">Simulasi Bayar Instan</span>
                        <span class="text-indigo-400">→</span>
                    </button>
                    <button onclick="submitCheckoutForm()"
                        class="w-full py-4 border-2 border-indigo-100 rounded-2xl flex justify-between items-center px-6 hover:border-indigo-600 transition group">
                        <span class="font-bold group-hover:text-indigo-600">GoPay / QRIS</span>
                        <span class="text-indigo-400">→</span>
                    </button>
                </div>

                <div class="mt-12 flex items-center justify-center gap-2 text-xs text-slate-400 font-bold uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Secure Checkout by Midtrans
                </div>
            </div>
        </div>
    </div>

    <script>
        function showMidtrans() {
            const form = document.getElementById('checkout-form');
            if (form.reportValidity()) {
                const overlay = document.getElementById('midtrans-overlay');
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
            }
        }
        function hideMidtrans() {
            const overlay = document.getElementById('midtrans-overlay');
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
        }
        function submitCheckoutForm() {
            document.getElementById('checkout-form').submit();
        }
    </script>

    <style>
        @keyframes bounce-in {
            0% {
                transform: scale(0.9);
                opacity: 0;
            }
            70% {
                transform: scale(1.05);
                opacity: 1;
            }
            100% {
                transform: scale(1);
            }
        }
        .animate-bounce-in {
            animation: bounce-in 0.4s ease-out forwards;
        }
    </style>
@endsection