@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Tambah Partner Baru</h1>
        <p class="text-slate-500 mt-1">Masukkan maklumat dan logo rakan kongsi baharu.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                        Nama Partner <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Gojek, Tokopedia..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600">
                    @error('name')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-2">Kategori (Pilihan)</label>
                    <select name="category_id" id="category_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600">
                        <option value="">-- Kategori Umum --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="logo" class="block text-sm font-semibold text-slate-700 mb-2">Logo Partner (Pilihan, Max: 2MB)</label>
                    <input type="file" name="logo" id="logo" accept="image/*"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('logo')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition">
                    Simpan Partner
                </button>
                <a href="{{ route('admin.partners.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 font-semibold rounded-xl hover:bg-slate-200 transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection