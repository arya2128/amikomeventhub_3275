<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Partner;
class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (homepage) pengunjung beserta filter kategori.
     */
    public function index(Request $request)
    {
        // 1. Ambil semua jenis kategori untuk tampilan filter tab button 
        $categories = Category::all();
        
        $partners = Partner::all();
        // 2. Buat kueri dasar untuk mengambil event dengan Eager Loading 'category' (Mencegah N+1 Problem)
        $query = Event::with('category')
                      ->latest();

        // 3. Filter query jika url memiliki parameter pencarian spesifik ?category=slug-kategori
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 4. Eksekusi query untuk mendapatkan data event yang sesuai
        $events = $query->get();
        
        return view('welcome', compact('events', 'categories', 'partners'));
        
    }
}