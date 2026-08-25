@extends('layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-6 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-black text-slate-800">Tiket Saya</h1>
            <p class="text-slate-500 mt-3 text-lg">Semua riwayat pemesanan tiket event Anda yang berhasil.</p>
        </div>

        <!-- Flash Alert Messages -->
        @if(session('success'))
            <div class="max-w-3xl mx-auto mb-8 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3">
                <svg class="w-6 h-6 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-3xl mx-auto mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl flex items-center gap-3">
                <svg class="w-6 h-6 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold text-sm">{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="max-w-3xl mx-auto mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="font-semibold text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Daftar Tiket / Transaksi -->
        @if($transactions->isEmpty())
            <div class="text-center text-slate-400 font-medium py-20 bg-white rounded-[2.5rem] border border-dashed border-slate-200 shadow-sm max-w-3xl mx-auto">
                <svg class="w-20 h-20 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                </svg>
                <p class="text-lg text-slate-600 mb-6">Anda belum memiliki tiket aktif atau transaksi berhasil.</p>
                <a href="{{ route('katalog') }}" class="inline-block px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200 transition-all">
                    Jelajahi Event
                </a>
            </div>
        @else
            <div class="max-w-3xl mx-auto space-y-8">
                @foreach($transactions as $transaction)
                    @php
                        $event = $transaction->event;
                        $isEndedOneDayAgo = $event && \Carbon\Carbon::parse($event->date)->addDay()->isPast();
                        $hasReviewed = $event && in_array($event->id, $reviewedEventIds);
                    @endphp
                    @if($event)
                        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-all flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                            <!-- Poster -->
                            <div class="w-full sm:w-28 h-40 sm:h-28 shrink-0 overflow-hidden relative rounded-2xl bg-slate-100 border border-slate-100">
                                <img src="{{ $event->poster_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover" onerror="this.onerror=null;this.src='/assets/concert.png';">
                            </div>

                            <!-- Detail Event -->
                            <div class="flex-1 min-w-0 space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold uppercase tracking-wider">
                                        {{ $event->category->name ?? 'Umum' }}
                                    </span>
                                    <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold uppercase tracking-wider">
                                        {{ $transaction->status }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800 truncate leading-tight">{{ $event->title }}</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-1 text-slate-500 text-sm">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ \Carbon\Carbon::parse($event->date)->translatedFormat('d M Y, H:i') }} WIB</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="truncate">{{ $event->location }}</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 col-span-1 md:col-span-2 mt-1">
                                        <span class="text-xs text-slate-400">Order ID: <strong class="font-mono text-slate-600">{{ $transaction->order_id }}</strong></span>
                                        <span class="text-xs text-slate-300 hidden sm:inline">|</span>
                                        <span class="text-xs text-slate-400">Total: <strong class="text-slate-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="w-full sm:w-auto flex flex-row sm:flex-col gap-3 shrink-0 pt-4 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                <a href="{{ route('ticket.show', $transaction->order_id) }}" target="_blank"
                                   class="flex-1 sm:flex-initial text-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm transition-all shadow-md shadow-indigo-100">
                                    Lihat E-Ticket
                                </a>

                                @if($isEndedOneDayAgo && !$hasReviewed)
                                    <button onclick="toggleReviewForm('review-form-{{ $transaction->id }}')"
                                            class="flex-1 sm:flex-initial text-center px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl font-bold text-sm transition-all">
                                        Beri Ulasan
                                    </button>
                                @elseif($isEndedOneDayAgo && $hasReviewed)
                                    <span class="inline-block text-center text-xs font-semibold text-slate-400 py-2.5 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                        Ulasan Terkirim
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Form Ulasan (Hidden by default) -->
                        @if($isEndedOneDayAgo && !$hasReviewed)
                            <div id="review-form-{{ $transaction->id }}" class="hidden bg-slate-50 border border-slate-200 rounded-[1.5rem] p-6 -mt-4 mb-6 shadow-inner transition-all max-w-3xl">
                                <form action="{{ route('event.reviews.store', $event->id) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                        <div>
                                            <h4 class="font-bold text-slate-800 text-sm">Berikan Rating dan Ulasan Anda</h4>
                                            <p class="text-xs text-slate-500">Berikan kontribusi pengalaman Anda untuk event ini.</p>
                                        </div>
                                        
                                        <!-- Rating Selector -->
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-slate-600">Rating:</span>
                                            <div class="flex flex-row-reverse gap-1 justify-center">
                                                @for($i = 5; $i >= 1; $i--)
                                                    <input type="radio" id="star-{{ $i }}-{{ $transaction->id }}" name="rating" value="{{ $i }}" class="peer hidden" required />
                                                    <label for="star-{{ $i }}-{{ $transaction->id }}" class="cursor-pointer text-slate-300 hover:text-amber-400 peer-hover:text-amber-400 peer-checked:text-amber-400 transition-colors">
                                                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                                        </svg>
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Review Text -->
                                    <div class="space-y-1">
                                        <textarea name="review_text" rows="3" maxlength="1000" placeholder="Bagikan kesan, saran, atau pujian Anda untuk event ini (maksimal 1000 karakter)..." 
                                                  class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-slate-700 font-medium text-xs transition-all" required></textarea>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button type="button" onclick="toggleReviewForm('review-form-{{ $transaction->id }}')" 
                                                class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl font-bold text-xs transition-all">
                                            Batal
                                        </button>
                                        <button type="submit" 
                                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs transition-all shadow-md shadow-indigo-100">
                                            Kirim
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        @endif
    </main>

    <script>
        function toggleReviewForm(formId) {
            const form = document.getElementById(formId);
            if (form) {
                if (form.classList.contains('hidden')) {
                    form.classList.remove('hidden');
                    form.classList.add('block');
                } else {
                    form.classList.remove('block');
                    form.classList.add('hidden');
                }
            }
        }
    </script>
@endsection
