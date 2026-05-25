@extends('layouts.admin')

@section('page_title', 'Dashboard Ringkasan')
@section('page_subtitle', 'Selamat datang kembali, Admin!')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
    <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs mb-2">Total Pendapatan</p>
        <h3 class="text-4xl font-black text-indigo-600">Rp 12.450.000</h3>
        <p class="text-green-500 text-sm font-bold mt-2">↑ 12% dari bulan lalu</p>
    </div>
    <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs mb-2">Tiket Terjual</p>
        <h3 class="text-4xl font-black text-slate-900">1,284</h3>
        <p class="text-indigo-500 text-sm font-bold mt-2">Tiket terbit</p>
    </div>
    <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs mb-2">Event Aktif</p>
        <h3 class="text-4xl font-black text-slate-900">12</h3>
        <p class="text-slate-400 text-sm font-bold mt-2">Dalam 30 hari kedepan</p>
    </div>
</div>

<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b border-slate-50 flex justify-between items-center">
        <h3 class="font-black text-xl">Transaksi Terbaru</h3>
        <a href="#" class="text-indigo-600 font-bold text-sm">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-widest">
                <tr>
                    <th class="px-8 py-4">Pembeli</th>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-right">Nominal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6">
                        <p class="font-bold uppercase tracking-wide text-sm">Donni Prabowo</p>
                        <p class="text-xs text-slate-400">donni@example.com</p>
                    </td>
                    <td class="px-8 py-6 font-medium text-slate-600">Jazz Night 2024</td>
                    <td class="px-8 py-6">
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase">Success</span>
                    </td>
                    <td class="px-8 py-6 text-right font-black text-indigo-600">Rp 155.000</td>
                </tr>
                </tbody>
        </table>
    </div>
</div>
@endsection