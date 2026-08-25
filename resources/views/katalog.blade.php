@extends('layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-6 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-black text-slate-800">Katalog Event AmikomEventHub</h1>
            <p class="text-slate-500 mt-3 text-lg">Temukan konser, workshop, seminar, dan acara menarik lainnya.</p>
        </div>

        <!-- Pencarian -->
        <div class="mb-12">
            <form action="{{ route('katalog') }}" method="GET" class="flex flex-col sm:flex-row gap-4 max-w-2xl mx-auto bg-white p-4 rounded-3xl shadow-sm border border-slate-100">
                <div class="flex-1 relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama event, deskripsi, atau lokasi..." 
                        class="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none font-medium text-slate-700">
                    <svg class="w-5 h-5 text-slate-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200 transition-all">
                    Cari Event
                </button>
            </form>
            @if(request('search'))
                <p class="text-center text-sm text-slate-500 mt-4">
                    Menampilkan hasil pencarian untuk: <strong class="text-indigo-600">"{{ request('search') }}"</strong> 
                    <a href="{{ route('katalog') }}" class="text-rose-500 hover:underline ml-2">Hapus Pencarian</a>
                </p>
            @endif
        </div>

        <!-- Daftar Event -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($events as $event)
                <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col justify-between">
                    <div>
                            <img src="{{ $event->poster_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" onerror="this.onerror=null;this.src='/assets/concert.png';">
                            <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600">
                                {{ $event->category->name ?? 'Umum' }}
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 group-hover:text-indigo-600 transition">{{ $event->title }}</h3>
                            
                            <div class="flex items-center gap-2 text-slate-500 text-sm mb-3">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y, H:i') }}</span>
                            </div>

                            <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $event->location }}</span>
                            </div>

                            <p class="text-slate-600 line-clamp-2 text-sm leading-relaxed mb-4">
                                {{ $event->description }}
                            </p>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="flex justify-between items-center pt-4 border-t">
                            <span class="text-2xl font-black text-indigo-600">
                                @if($event->price > 0)
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                @else
                                    Gratis
                                @endif
                            </span>
                            <a href="{{ route('event.show', $event->id) }}" class="px-5 py-2.5 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition-all duration-300">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-slate-400 font-medium py-16 bg-white rounded-[2.5rem] border border-dashed border-slate-200">
                    Tidak ada event yang ditemukan untuk pencarian ini.
                </div>
            @endforelse
        </div>
    </main>
@endsection