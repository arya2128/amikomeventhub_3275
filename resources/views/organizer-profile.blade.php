@extends('layouts.app')

@section('title', 'Profil ' . $user->name . ' - AmikomEventHub')

@section('content')
    <main class="max-w-7xl mx-auto px-6 py-12 space-y-12">
        <!-- Header Profil Penyelenggara -->
        <div class="bg-gradient-to-r from-indigo-900 to-indigo-700 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                    <!-- Initial Avatar -->
                    @php
                        $words = explode(' ', $user->name);
                        $initials = '';
                        foreach (array_slice($words, 0, 2) as $w) {
                            $initials .= strtoupper(substr($w, 0, 1));
                        }
                    @endphp
                    <div class="w-24 h-24 bg-white/10 backdrop-blur rounded-[2rem] border-4 border-white/20 flex items-center justify-center font-black text-4xl shadow-inner text-indigo-200">
                        {{ $initials }}
                    </div>
                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                            <h1 class="text-3xl md:text-4xl font-black tracking-tight">{{ $user->name }}</h1>
                            <span class="px-3 py-1 bg-indigo-500/30 border border-indigo-400/20 text-indigo-200 rounded-full text-xs font-bold uppercase tracking-wider">
                                Verified Organizer
                            </span>
                        </div>
                        <p class="text-indigo-200 text-sm max-w-xl font-medium">
                            Menyelenggarakan event-event berkualitas di lingkungan Universitas Amikom Yogyakarta.
                        </p>
                        <div class="flex items-center justify-center md:justify-start gap-4 mt-2">
                            <span class="text-slate-300 text-sm">Terdaftar sejak {{ $user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Rating Stats Card -->
                <div class="bg-white/10 backdrop-blur border border-white/10 p-6 rounded-3xl min-w-[200px] text-center space-y-2">
                    <p class="text-indigo-200 text-xs font-bold uppercase tracking-widest">Reputasi Penyelenggara</p>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-4xl font-black">{{ number_format($averageRating, 1) }}</span>
                        <div class="flex flex-col items-start">
                            <!-- Star SVGs -->
                            <div class="flex text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($averageRating))
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @else
                                        <svg class="w-4 h-4 fill-current text-white/20" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-[10px] text-indigo-200 font-bold uppercase tracking-wide mt-0.5">{{ $totalReviews }} ulasan tuntas</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white opacity-5 rounded-full"></div>
            <div class="absolute -left-10 -top-10 w-32 h-32 bg-indigo-400 opacity-10 rounded-full"></div>
        </div>

        <!-- Grid Utama: Event & Review -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Kolom Kiri: Acara Aktif -->
            <div class="lg:col-span-2 space-y-8">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 flex items-center gap-3">
                         Event Diselenggarakan
                        <span class="px-2.5 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold">{{ $events->count() }}</span>
                    </h2>
                    <p class="text-slate-500 text-sm mt-1">Daftar semua event yang dibuat oleh penyelenggara ini.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($events as $event)
                        <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden">
                            <div class="relative overflow-hidden aspect-[4/3]">
                                <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) ? asset('storage/' . $event->poster_path) : 'https://placehold.co/400x300' }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600">
                                    {{ $event->category->name ?? 'Umum' }}
                                </div>
                            </div>
                            <div class="p-6 space-y-4">
                                <h3 class="font-bold text-lg text-slate-800 line-clamp-1 group-hover:text-indigo-600 transition">{{ $event->title }}</h3>
                                <div class="flex items-center gap-2 text-slate-500 text-xs">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-4 border-t">
                                    <span class="text-lg font-black text-indigo-600">
                                        {{ $event->price == 0 ? 'Gratis' : 'Rp '.number_format($event->price, 0, ',', '.') }}
                                    </span>
                                    <a href="{{ route('event.show', $event) }}" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white text-xs transition">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center text-slate-400 font-medium py-12 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                            Penyelenggara belum mengadakan event.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Kolom Kanan: Ulasan / Rekam Jejak Penilaian -->
            <div class="space-y-8">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 flex items-center gap-3">
                         Rekam Jejak Ulasan
                        <span class="px-2.5 py-0.5 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">{{ $reviews->count() }}</span>
                    </h2>
                    <p class="text-slate-500 text-sm mt-1">Testimoni nyata dari pembeli tiket pasca-acara selesai.</p>
                </div>

                <div class="space-y-6">
                    @forelse($reviews as $review)
                        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm space-y-4">
                            <!-- Reviewer and Rating -->
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-slate-800">{{ $review->user->name }}</h4>
                                        <p class="text-[10px] text-slate-400">Verifed Buyer • {{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex text-amber-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @else
                                            <svg class="w-4 h-4 fill-current text-slate-200" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endif
                                    @endfor
                                </div>
                            </div>

                            <!-- Comment -->
                            <p class="text-sm text-slate-600 italic">
                                "{{ $review->review_text ?? 'Tidak memberikan ulasan tertulis.' }}"
                            </p>

                            <!-- Event associated -->
                            <div class="bg-slate-50 rounded-2xl px-4 py-2 border border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                <span>Event: <strong class="text-indigo-600">{{ $review->event->title }}</strong></span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-slate-400 font-medium py-12 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                            Belum ada ulasan untuk penyelenggara ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
@endsection
