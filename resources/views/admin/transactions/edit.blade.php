@extends('layouts.admin')

@section('title', 'Edit Status Transaksi - ' . $transaction->order_id)
@section('page_title', 'Ubah Status Transaksi')
@section('page_subtitle', 'Perbarui status pembayaran untuk pesanan #' . $transaction->order_id)

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="inline-flex items-center gap-2 text-indigo-600 font-bold text-sm hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Detail
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
        <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Order ID</label>
                <input type="text" value="{{ $transaction->order_id }}" disabled class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-mono font-bold text-slate-600 cursor-not-allowed">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nama Pembeli</label>
                    <input type="text" value="{{ $transaction->customer_name }}" disabled class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-600 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Bayar</label>
                    <input type="text" value="Rp {{ number_format($transaction->total_price, 0, ',', '.') }}" disabled class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-indigo-600 cursor-not-allowed">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Pembayaran</label>
                <select name="status" class="w-full px-5 py-4 bg-white border-2 border-slate-200 rounded-2xl font-bold text-slate-800 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10 outline-none transition" required>
                    <option value="pending" {{ strtolower($transaction->status) == 'pending' ? 'selected' : '' }}>Pending (Menunggu Pembayaran)</option>
                    <option value="success" {{ in_array(strtolower($transaction->status), ['success', 'settlement', 'completed']) ? 'selected' : '' }}>Success / Completed (Lunas Berhasil)</option>
                    <option value="failed" {{ strtolower($transaction->status) == 'failed' ? 'selected' : '' }}>Failed (Gagal / Expired)</option>
                    <option value="cancelled" {{ strtolower($transaction->status) == 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                </select>
                <p class="text-xs text-slate-400 mt-2 font-medium">Ubah status menjadi <strong>Success</strong> jika pembayaran manual telah diverifikasi.</p>
            </div>

            <div class="pt-4 flex gap-4">
                <button type="submit" class="flex-1 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-base shadow-lg shadow-indigo-100 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="px-6 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-base transition flex items-center justify-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
