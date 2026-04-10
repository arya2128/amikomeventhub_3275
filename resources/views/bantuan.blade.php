@extends('layout')

@section('title', 'Bantuan / FAQ')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm p-8 border border-gray-200">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Frequently Asked Questions (FAQ)</h2>
    
    <div class="space-y-5">
        <div class="border-b border-gray-100 pb-4">
            <h4 class="font-semibold text-gray-800 text-lg">Bagaimana cara mendaftar event?</h4>
            <p class="text-gray-600 mt-2 text-sm leading-relaxed">Untuk mendaftar, silakan buka halaman <span class="font-semibold">Katalog</span>, pilih event yang Anda minati, dan klik tombol detail untuk mengisi form pendaftaran.</p>
        </div>
        
        <div class="border-b border-gray-100 pb-4">
            <h4 class="font-semibold text-gray-800 text-lg">Apakah event di AmikomEventHub berbayar?</h4>
            <p class="text-gray-600 mt-2 text-sm leading-relaxed">Terdapat event yang gratis dan berbayar. Status biaya akan dicantumkan secara jelas pada deskripsi masing-masing event di halaman katalog.</p>
        </div>
        
        <div>
            <h4 class="font-semibold text-gray-800 text-lg">Siapa yang bisa saya hubungi jika ada kendala?</h4>
            <p class="text-gray-600 mt-2 text-sm leading-relaxed">Anda dapat menuju ke halaman <span class="font-semibold">Kontak</span> untuk mengirim pesan atau langsung menghubungi admin via WhatsApp yang tertera di sana.</p>
        </div>
    </div>
</div>
@endsection