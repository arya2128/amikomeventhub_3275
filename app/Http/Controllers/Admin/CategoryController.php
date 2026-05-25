<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // <-- Wajib di-import agar fungsi Str::slug bisa digunakan

class CategoryController extends Controller
{
    /**
     * 1. Menampilkan daftar Kategori (Dinamis + Fitur Pencarian LIKE)
     */
    public function index(Request $request)
    {
        // Mengambil kategori sekalian menghitung jumlah partner yang terhubung
        $query = Category::withCount('partners');

        // Fitur Pencarian LIKE untuk Kategori
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $categories = $query->latest()->get();

        // Mengirimkan data $categories ke file index blade
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * 2. Menampilkan form tambah Kategori baru
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * 3. Menyimpan data Kategori baru ke database beserta Slug otomatis
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        // JAWABAN FIX ERROR: Menambahkan field 'slug' otomatis dari data 'name'
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), 
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * 4. Menampilkan form edit Kategori
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * 5. Memperbarui data Kategori di database beserta Slug baru
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        // Perbarui name dan slug sekaligus saat data diubah
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * 6. Menghapus Kategori dari database
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Menghapus kategori terpilih
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}