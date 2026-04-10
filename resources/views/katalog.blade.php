@extends('layout')

@section('title', 'Katalog Event')

@section('content')
<div class="mb-6 border-b pb-4">
    <h1 class="text-3xl font-bold text-gray-800">AmikomEventHub</h1>
    <p class="text-gray-500 mt-1">Temukan event dan workshop menarik di kampus.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md hover:border-blue-300 transition duration-300 flex flex-col">
        <h3 class="font-bold text-lg text-gray-800 mb-1">Workshop Web Design</h3>
        <span class="text-xs font-semibold text-blue-600 bg-blue-50 inline-block w-max px-2 py-1 rounded mb-3">10 Mei 2026</span>
        <p class="text-sm text-gray-600 flex-grow mb-4">Belajar merancang antarmuka web modern dari nol hingga bisa.</p>
        <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 rounded-lg text-sm font-medium transition">Detail Event</button>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md hover:border-blue-300 transition duration-300 flex flex-col">
        <h3 class="font-bold text-lg text-gray-800 mb-1">Seminar Artificial Intelligence</h3>
        <span class="text-xs font-semibold text-blue-600 bg-blue-50 inline-block w-max px-2 py-1 rounded mb-3">15 Mei 2026</span>
        <p class="text-sm text-gray-600 flex-grow mb-4">Penerapan AI dalam dunia industri kreatif saat ini.</p>
        <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 rounded-lg text-sm font-medium transition">Detail Event</button>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md hover:border-blue-300 transition duration-300 flex flex-col">
        <h3 class="font-bold text-lg text-gray-800 mb-1">Lomba UI/UX Design</h3>
        <span class="text-xs font-semibold text-blue-600 bg-blue-50 inline-block w-max px-2 py-1 rounded mb-3">20 Mei 2026</span>
        <p class="text-sm text-gray-600 flex-grow mb-4">Tunjukkan karyamu dan menangkan total hadiah jutaan rupiah.</p>
        <button class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 rounded-lg text-sm font-medium transition">Detail Event</button>
    </div>
</div>
@endsection