@extends('layout')

@section('title', 'Profil Praktikan')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-2xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition duration-300">
    <div class="bg-blue-500 h-24"></div>
    <div class="px-6 pb-6 text-center -mt-12">
        <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto border-4 border-white flex items-center justify-center text-gray-400 font-bold mb-4">
            FOTO
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Nama Kamu Disini</h2>
        <p class="text-blue-500 font-medium mb-1">NIM: 24.12.3275</p>
        <p class="text-gray-500 text-sm mb-4">Program Studi Sistem Informasi</p>
        
        <p class="text-gray-600 text-sm leading-relaxed">
            Halo! Saya adalah praktikan untuk mata kuliah ini. Ini adalah halaman profil sederhana yang dibuat menggunakan Laravel dan Tailwind CSS.
        </p>
    </div>
</div>
@endsection