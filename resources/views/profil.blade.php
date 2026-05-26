@extends('layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-6 py-20 flex flex-col items-center">
        <div class="max-w-md w-full bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-slate-100 transition-all duration-300 hover:shadow-2xl">
            <div class="bg-indigo-600 h-32 relative">
                <div class="absolute -bottom-12 left-1/2 -translate-x-1/2">
                    <div class="w-24 h-24 bg-slate-200 text-indigo-600 rounded-full border-4 border-white shadow-md flex items-center justify-center font-black text-4xl">
                        D
                    </div>
                </div>
            </div>
            <div class="px-8 py-16 text-center">
                <h2 class="text-3xl font-extrabold text-slate-800">Arya Robby Adhyaksa</h2>
                <p class="text-indigo-600 font-bold text-lg mt-1">24.12.3275</p>
                <div class="w-12 h-1 bg-indigo-100 mx-auto my-6 rounded-full"></div>
                <p class="text-slate-500 font-medium text-sm leading-relaxed px-4">
                    Mahasiswa Amikom Yogyakarta
                    <br>Mata Kuliah: Digital Business
                </p>
            </div>
        </div>
    </main>
@endsection