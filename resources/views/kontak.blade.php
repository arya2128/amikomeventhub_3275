<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-gray-800 font-sans min-h-screen p-8">
    
    <nav class="flex flex-wrap justify-center gap-4 mb-10">
        <a href="{{ url('/') }}" class="px-6 py-2 bg-white text-indigo-600 font-semibold rounded-lg shadow-md hover:bg-indigo-50 hover:-translate-y-1 transition-all duration-300">Home</a>
        <a href="{{ url('/profil') }}" class="px-6 py-2 bg-white text-indigo-600 font-semibold rounded-lg shadow-md hover:bg-indigo-50 hover:-translate-y-1 transition-all duration-300">Profil</a>
        <a href="{{ url('/katalog') }}" class="px-6 py-2 bg-white text-indigo-600 font-semibold rounded-lg shadow-md hover:bg-indigo-50 hover:-translate-y-1 transition-all duration-300">Katalog</a>
        <a href="{{ url('/bantuan') }}" class="px-6 py-2 bg-white text-indigo-600 font-semibold rounded-lg shadow-md hover:bg-indigo-50 hover:-translate-y-1 transition-all duration-300">Bantuan</a>
        <a href="{{ url('/kontak') }}" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 hover:-translate-y-1 transition-all duration-300">Kontak</a>
    </nav>

    <div class="max-w-xl mx-auto bg-white p-8 rounded-2xl shadow-lg border border-gray-100 text-center">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Hubungi Kami</h1>
        <p class="text-gray-600 mb-6">Punya pertanyaan lebih lanjut? Kirimkan pesan melalui email ke: <br><strong class="text-indigo-600">dimasmrofiq@amikomeventhub.id</strong></p>
    </div>

    
</body>
</html>