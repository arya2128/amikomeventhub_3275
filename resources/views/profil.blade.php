<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Praktikan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-gray-800 font-sans min-h-screen p-8">
    
    <nav class="flex flex-wrap justify-center gap-4 mb-10">
        <a href="{{ url('/') }}" class="px-6 py-2 bg-white text-indigo-600 font-semibold rounded-lg shadow-md hover:bg-indigo-50 hover:-translate-y-1 transition-all duration-300">Home</a>
        <a href="{{ url('/profil') }}" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 hover:-translate-y-1 transition-all duration-300">Profil</a>
        <a href="{{ url('/katalog') }}" class="px-6 py-2 bg-white text-indigo-600 font-semibold rounded-lg shadow-md hover:bg-indigo-50 hover:-translate-y-1 transition-all duration-300">Katalog</a>
        <a href="{{ url('/bantuan') }}" class="px-6 py-2 bg-white text-indigo-600 font-semibold rounded-lg shadow-md hover:bg-indigo-50 hover:-translate-y-1 transition-all duration-300">Bantuan</a>
        <a href="{{ url('/kontak') }}" class="px-6 py-2 bg-white text-indigo-600 font-semibold rounded-lg shadow-md hover:bg-indigo-50 hover:-translate-y-1 transition-all duration-300">Kontak</a>
    </nav>

    <div class="max-w-md mx-auto bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
        <div class="bg-indigo-600 h-24"></div>
        <div class="px-6 py-8 relative text-center">
            <div class="w-24 h-24 bg-gray-300 rounded-full mx-auto -mt-16 border-4 border-white shadow-md flex items-center justify-center text-4xl text-gray-500 font-bold">
                P
            </div>
            <h2 class="text-2xl font-bold mt-4 text-gray-800">Dimas Muhamad Rofiq</h2>
            <p class="text-indigo-600 font-medium">24.12.3302</p>
            <p class="text-gray-500 mt-4 px-4 text-sm">Mahasiswa Amikom Yogyakarta Mata Kuliah Digital bussines</p>
        </div>
    </div>

    
</body>
</html>