@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-2">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Manajemen Partner (Admin)</h1>
            <p class="text-slate-500 mt-1">Kelola data rekan kongsi AmikomEventHub di sini.</p>
        </div>
        <a href="{{ route('admin.partners.create') }}" class="px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-md shadow-indigo-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Partner Baru
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm mb-6">
        <form action="{{ route('admin.partners.index') }}" method="GET" class="flex gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama partner dengan sintaks LIKE..." class="w-full pl-4 pr-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 text-sm">
            </div>
            <button type="submit" class="px-6 py-3 bg-slate-800 text-white font-medium rounded-xl hover:bg-slate-900 transition text-sm">
                Cari Data
            </button>
            @if(request('search'))
                <a href="{{ route('admin.partners.index') }}" class="px-4 py-3 bg-rose-50 text-rose-600 font-medium rounded-xl hover:bg-rose-100 transition text-sm flex items-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 text-sm font-semibold">
                        <th class="p-4 pl-6">Logo</th>
                        <th class="p-4">Nama Partner</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 text-sm">
                    @forelse($partners as $partner)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4 pl-6">
                                @if($partner->logo_path)
                                    <img src="{{ asset('storage/' . $partner->logo_path) }}" alt="Logo" class="h-10 w-20 object-contain rounded border border-slate-100 bg-slate-50">
                                @else
                                    <span class="text-xs text-slate-400 italic">No Logo</span>
                                @endif
                            </td>
                            <td class="p-4 font-semibold text-slate-900">{{ $partner->name }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-full font-medium text-xs">
                                    {{ $partner->category->name ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.partners.edit', $partner->id) }}" class="px-3 py-1.5 border border-slate-200 text-slate-600 rounded-lg hover:border-indigo-600 hover:text-indigo-600 transition font-medium text-xs">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Hapus partner ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition font-medium text-xs">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400 italic">
                                Data partner tidak ditemukan atau belum diinput.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection