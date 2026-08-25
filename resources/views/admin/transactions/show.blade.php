@extends('layouts.admin')

@section('title', 'Detail Transaksi - ' . $transaction->order_id)
@section('page_title', 'Detail Transaksi')
@section('page_subtitle', 'Informasi lengkap mengenai pemesanan tiket #' . $transaction->order_id)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center gap-2 text-indigo-600 font-bold text-sm hover:underline">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali ke Laporan Transaksi
    </a>
</div>

@php
    $st = strtolower($transaction->status);
    $isSuccess = in_array($st, ['success', 'settlement', 'completed']);
    $isPending = in_array($st, ['pending']);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-6 mb-6">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Order ID</span>
                    <h3 class="text-2xl font-mono font-black text-indigo-600">{{ $transaction->order_id }}</h3>
                </div>
                <div>
                    @if($isSuccess)
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-50 text-green-700 rounded-full font-bold text-sm">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            Pembayaran Berhasil
                        </span>
                    @elseif($isPending)
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-50 text-amber-700 rounded-full font-bold text-sm">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Menunggu Pembayaran
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-50 text-rose-700 rounded-full font-bold text-sm">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            {{ ucfirst($transaction->status) }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Customer & Event Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-6 bg-slate-50 rounded-2xl">
                    <p class="text-xs font-bold text-slate-400 uppercase mb-3">Informasi Pembeli</p>
                    <p class="font-black text-lg text-slate-800">{{ $transaction->customer_name }}</p>
                    <p class="text-sm text-slate-500 mt-1">{{ $transaction->customer_email }}</p>
                    <p class="text-sm text-slate-500">{{ $transaction->customer_phone }}</p>
                </div>
                <div class="p-6 bg-slate-50 rounded-2xl">
                    <p class="text-xs font-bold text-slate-400 uppercase mb-3">Event yang Dipesan</p>
                    <p class="font-black text-lg text-slate-800">{{ $transaction->event->title ?? '-' }}</p>
                    <p class="text-sm text-slate-500 mt-1">{{ $transaction->event->category->name ?? '-' }}</p>
                    <p class="text-sm text-slate-500">{{ $transaction->event->location ?? '-' }}</p>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="mt-6 pt-6 border-t border-slate-100 space-y-3">
                <div class="flex justify-between text-sm text-slate-500">
                    <span>Waktu Pemesanan</span>
                    <span class="font-bold text-slate-700">{{ $transaction->created_at ? $transaction->created_at->format('d F Y, H:i:s') : '-' }}</span>
                </div>
                <div class="flex justify-between text-sm text-slate-500">
                    <span>Snap Token Gateway</span>
                    <span class="font-mono text-xs text-slate-400 max-w-xs truncate">{{ $transaction->snap_token ?? '-' }}</span>
                </div>
                <div class="flex justify-between text-xl font-black pt-4 border-t border-slate-100">
                    <span>Total Pembayaran</span>
                    <span class="text-indigo-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Sidebar -->
    <div class="space-y-6">
        <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
            <h4 class="font-extrabold text-lg text-slate-800 mb-6">Tindakan Cepat</h4>
            <div class="space-y-3">
                <a href="{{ route('admin.transactions.edit', $transaction->id) }}" 
                    class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-sm flex items-center justify-center gap-2 shadow-md transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Ubah Status Transaksi
                </a>
                <button onclick="window.print()" class="w-full py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Bukti / Invoice
                </button>
                <form action="{{ route('admin.transactions.destroy', $transaction->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini permanen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-3 px-4 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 transition mt-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
