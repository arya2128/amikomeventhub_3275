@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Ubah Kategori</h1>
    </div>

    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Kategori</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600" required>
            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-md">
                    Perbarui Kategori
                </button>
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 font-semibold rounded-xl hover:bg-slate-200 transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection