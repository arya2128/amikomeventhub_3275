@extends('layout')

@section('title', 'Kontak Kami')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-8 border border-gray-200 hover:shadow-md transition duration-300">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Hubungi Kami</h2>
        <p class="text-gray-500 mt-2 text-sm">Punya pertanyaan atau masukan terkait event? Kirimkan pesan melalui form di bawah ini.</p>
    </div>

    <form action="#" method="POST" class="space-y-5">
        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" id="nama" placeholder="Masukkan nama Anda" 
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
            <input type="email" id="email" placeholder="email@contoh.com" 
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200">
        </div>

        <div>
            <label for="pesan" class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
            <textarea id="pesan" rows="4" placeholder="Tulis pesan Anda di sini..." 
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200"></textarea>
        </div>

        <button type="button" 
            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-lg shadow-sm transition duration-200 mt-2">
            Kirim Pesan
        </button>
    </form>

    <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row justify-center gap-6 text-sm text-gray-600">
        <div class="flex items-center gap-2">
            <span class="font-bold text-gray-800">Email:</span> info@amikomevent.com
        </div>
        <div class="flex items-center gap-2">
            <span class="font-bold text-gray-800">WhatsApp:</span> +62 812-3456-7890
        </div>
    </div>
</div>
@endsection