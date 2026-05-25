<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Event - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-gray-800 font-sans min-h-screen p-8">
    
    <nav class="flex flex-wrap justify-center gap-4 mb-10">
        <a href="{{ url('/') }}" class="px-6 py-2 bg-white text-indigo-600 font-semibold rounded-lg shadow-md hover:bg-indigo-50 hover:-translate-y-1 transition-all duration-300">Home</a>
        <a href="{{ url('/profil') }}" class="px-6 py-2 bg-white text-indigo-600 font-semibold rounded-lg shadow-md hover:bg-indigo-50 hover:-translate-y-1 transition-all duration-300">Profil</a>
        <a href="{{ url('/katalog') }}" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 hover:-translate-y-1 transition-all duration-300">Katalog</a>
        <a href="{{ url('/bantuan') }}" class="px-6 py-2 bg-white text-indigo-600 font-semibold rounded-lg shadow-md hover:bg-indigo-50 hover:-translate-y-1 transition-all duration-300">Bantuan</a>
        <a href="{{ url('/kontak') }}" class="px-6 py-2 bg-white text-indigo-600 font-semibold rounded-lg shadow-md hover:bg-indigo-50 hover:-translate-y-1 transition-all duration-300">Kontak</a>
    </nav>

    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Katalog AmikomEventHub</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition-shadow duration-300 border border-gray-100 flex flex-col">
                <img src="{{ asset('images/foto 1.jpg') }}" alt="Workshop Laravel" class="h-40 w-full object-cover rounded-lg mb-4">
                
                <h3 class="text-xl font-bold text-gray-800">Workshop Laravel</h3>
                <p class="text-sm text-gray-500 mb-4 flex-grow">12 Oktober 2026 • Ruang Citra</p>
                <button class="w-full bg-indigo-50 text-indigo-600 font-semibold py-2 rounded hover:bg-indigo-100 transition mt-auto">Detail</button>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition-shadow duration-300 border border-gray-100 flex flex-col">
                <img src="{{ asset('images/foto 2.jpg') }}" alt="Seminar AI" class="h-40 w-full object-cover rounded-lg mb-4">
                
                <h3 class="text-xl font-bold text-gray-800">Seminar AI</h3>
                <p class="text-sm text-gray-500 mb-4 flex-grow">15 Oktober 2026 • Ruang Cinema</p>
                <button class="w-full bg-indigo-50 text-indigo-600 font-semibold py-2 rounded hover:bg-indigo-100 transition mt-auto">Detail</button>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-xl transition-shadow duration-300 border border-gray-100 flex flex-col">
                <img src="{{ asset('images/foto 3.jpg') }}" alt="Lomba UI/UX" class="h-40 w-full object-cover rounded-lg mb-4">
                
                <h3 class="text-xl font-bold text-gray-800">Lomba UI/UX</h3>
                <p class="text-sm text-gray-500 mb-4 flex-grow">20 Oktober 2026 • Lab Komputer</p>
                <button class="w-full bg-indigo-50 text-indigo-600 font-semibold py-2 rounded hover:bg-indigo-100 transition mt-auto">Detail</button>
            </div>
        </div>
    </div>

    
</body>
</html>