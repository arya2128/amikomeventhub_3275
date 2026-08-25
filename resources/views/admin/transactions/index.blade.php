@extends('layouts.admin')

@section('title', 'Laporan Transaksi')
@section('page_title', 'Laporan & Riwayat Transaksi')
@section('page_subtitle', 'Pantau seluruh transaksi tiket, status pembayaran, dan rekap pendapatan.')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <!-- Total Transaksi -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Transaksi</span>
            <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">#</div>
        </div>
        <h3 class="text-2xl font-black text-slate-800">{{ $stats['total'] }}</h3>
    </div>

    <!-- Berhasil / Lunas -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-green-600">Berhasil / Lunas</span>
            <span class="w-3 h-3 rounded-full bg-green-500"></span>
        </div>
        <h3 class="text-2xl font-black text-green-600">{{ $stats['completed'] }}</h3>
    </div>

    <!-- Menunggu Pembayaran -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-500">Pending</span>
            <span class="w-3 h-3 rounded-full bg-amber-400"></span>
        </div>
        <h3 class="text-2xl font-black text-amber-500">{{ $stats['pending'] }}</h3>
    </div>

    <!-- Gagal / Batal -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-rose-500">Gagal / Batal</span>
            <span class="w-3 h-3 rounded-full bg-rose-500"></span>
        </div>
        <h3 class="text-2xl font-black text-rose-500">{{ $stats['failed'] + $stats['cancelled'] }}</h3>
    </div>

    <!-- Total Pendapatan -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm sm:col-span-2 lg:col-span-1">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Total Pemasukan</span>
            <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <h3 class="text-xl font-black text-indigo-600">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
    </div>
</div>

<!-- Filter & Search Bar -->
<div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm mb-8">
    <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="flex flex-1 flex-col sm:flex-row gap-4 w-full">
            <!-- Search Input -->
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Order ID, Nama Pembeli, Email, atau Event..." 
                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition">
            </div>

            <!-- Status Filter -->
            <select name="status" class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition">
                <option value="">Semua Status</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Berhasil (Completed / Settlement)</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu Bayar)</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal / Kadaluarsa</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <div class="flex gap-2 w-full md:w-auto">
            <button type="submit" class="flex-1 md:flex-none px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold text-sm shadow-md hover:bg-indigo-700 transition">
                Terapkan Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.transactions.index') }}" class="px-4 py-3 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-2xl font-bold text-sm transition flex items-center justify-center">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Transactions Table Card -->
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center flex-wrap gap-4">
        <div>
            <h3 class="font-extrabold text-xl text-slate-800">Daftar Transaksi Tiket</h3>
            <p class="text-xs text-slate-400 font-medium mt-1">Menampilkan {{ $transactions->total() }} catatan transaksi</p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak / PDF
        </button>
    </div>

    @if($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-4 px-6">Order ID</th>
                        <th class="py-4 px-6">Nama Pembeli</th>
                        <th class="py-4 px-6">Event</th>
                        <th class="py-4 px-6">Total Bayar</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Waktu Transaksi</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($transactions as $transaction)
                        @php
                            $st = strtolower($transaction->status);
                            $isSuccess = in_array($st, ['success', 'settlement', 'completed']);
                            $isPending = in_array($st, ['pending']);
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-4 px-6 font-mono font-bold text-indigo-600">
                                {{ $transaction->order_id }}
                            </td>
                            <td class="py-4 px-6">
                                <p class="font-extrabold text-slate-800">{{ $transaction->customer_name }}</p>
                                <p class="text-xs text-slate-400">{{ $transaction->customer_email }}</p>
                                <p class="text-xs text-slate-400">{{ $transaction->customer_phone }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <p class="font-bold text-slate-700 max-w-xs truncate">{{ $transaction->event->title ?? 'Event Tidak Ditemukan' }}</p>
                                <span class="text-[10px] text-slate-400 uppercase font-bold">{{ $transaction->event->category->name ?? '-' }}</span>
                            </td>
                            <td class="py-4 px-6 font-black text-slate-800">
                                Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6">
                                @if($isSuccess)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 rounded-full font-bold text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Berhasil
                                    </span>
                                @elseif($isPending)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 rounded-full font-bold text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-700 rounded-full font-bold text-xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-500">
                                {{ $transaction->created_at ? $transaction->created_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-4 px-6 text-right space-x-1">
                                <a href="{{ route('admin.transactions.show', $transaction->id) }}" 
                                    class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl font-bold text-xs transition">
                                    Detail
                                </a>
                                <a href="{{ route('admin.transactions.edit', $transaction->id) }}" 
                                    class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition">
                                    Edit Status
                                </a>
                                <form action="{{ route('admin.transactions.destroy', $transaction->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl font-bold text-xs transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t border-slate-100">
            {{ $transactions->links() }}
        </div>
    @else
        <div class="p-16 text-center">
            <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-3xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h4 class="font-extrabold text-lg text-slate-700 mb-1">Belum Ada Catatan Transaksi</h4>
            <p class="text-sm text-slate-400">Transaksi pembelian tiket dari pengunjung akan otomatis dicatat di sini.</p>
        </div>
    @endif
</div>
@endsection
