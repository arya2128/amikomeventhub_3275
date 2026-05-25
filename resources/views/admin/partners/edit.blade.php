@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-2">
    <div class="mb-8">
        <a href="{{ route('admin.partners.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 mb-2">
            ← Kembali ke List Partner
        </a>
        <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Edit Partner</h1>
        <p class="text-slate-500 mt-1">Perbarui data rekan kongsi AmikomEventHub.</p>
    </div>

    <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
        <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Partner</label>
                <input type="text" name="name" value="{{ old('name', $partner->name) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 font-medium @error('name') border-rose-500 @enderror">
                @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Kategori Partner</label>
                <select name="category_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 font-medium @error('category_id') border-rose-500 @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $partner->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Logo Partner (Opsional)</label>
                
                @if($partner->logo_url)
                    <div class="mb-4 p-3 border border-slate-100 rounded-xl bg-slate-50 inline-block">
                        <p class="text-[10px] uppercase font-bold text-slate-400 mb-1.5">Logo Saat Ini:</p>
                        <img src="{{ asset('storage/' . $partner->logo_url) }}" class="h-14 w-28 object-contain rounded border border-slate-200 bg-white p-1">
                    </div>
                @endif

                <input type="file" name="logo" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                <p class="text-xs text-slate-400 mt-2">Format yang didukung: JPG, PNG, WebP (Maksimal 2MB)</p>
                @error('logo') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-6 border-t border-slate-50 flex justify-end gap-3">
                <a href="{{ route('admin.partners.index') }}" class="px-5 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition text-sm">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition text-sm shadow-lg shadow-indigo-100">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection