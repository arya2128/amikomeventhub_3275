@extends('layouts.app')

@section('content')
    <main class="max-w-4xl mx-auto px-6 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-black text-slate-800">Pusat Bantuan & FAQ</h1>
            <p class="text-slate-500 mt-3 text-lg">Temukan jawaban atas pertanyaan umum seputar reservasi tiket.</p>
        </div>
        
        <div class="space-y-6 max-w-3xl mx-auto">
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:border-indigo-100 transition-all duration-300">
                <h3 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                    <span class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold">1</span>
                    Bagaimana cara memesan tiket event?
                </h3>
                <p class="text-slate-600 mt-4 leading-relaxed pl-11">
                    Jelajahi halaman katalog event kami, pilih event yang Anda minati, klik tombol <strong>Lihat Detail</strong>, lalu klik <strong>Pesan Sekarang</strong>. Isi data pemesan dan selesaikan pembayaran untuk mengunduh e-ticket Anda.
                </p>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:border-indigo-100 transition-all duration-300">
                <h3 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                    <span class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold">2</span>
                    Apakah e-ticket akan dikirim lewat email?
                </h3>
                <p class="text-slate-600 mt-4 leading-relaxed pl-11">
                    Ya, sistem kami secara otomatis mengirimkan rincian reservasi dan tautan e-ticket Anda ke alamat email aktif yang Anda masukkan saat checkout. Pastikan email Anda valid dan aktif.
                </p>
            </div>
            
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:border-indigo-100 transition-all duration-300">
                <h3 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                    <span class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold">3</span>
                    Bagaimana cara melakukan verifikasi tiket di lokasi?
                </h3>
                <p class="text-slate-600 mt-4 leading-relaxed pl-11">
                    Anda hanya perlu menunjukkan e-ticket yang telah diunduh atau dicetak dari AmikomEventHub. Panitia akan memindai (scan) kode QR pada e-ticket Anda di pintu masuk untuk melakukan check-in.
                </p>
            </div>
        </div>
    </main>
@endsection