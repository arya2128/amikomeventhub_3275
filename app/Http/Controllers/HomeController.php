<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\User;
use App\Models\Review;

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

    public function katalog(Request $request)
    {
        $query = Event::with('category')->latest();

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        $events = $query->get();

        return view('katalog', compact('events'));
    }

    /**
     * Menampilkan profil publik penyelenggara (organizer) beserta ulasan dan event miliknya.
     */
    public function organizerProfile(User $user)
    {
        // Pastikan role-nya adalah admin atau organizer
        if (!in_array($user->role, ['admin', 'organizer'])) {
            abort(404);
        }

        // Ambil semua event yang dibuat oleh organizer ini
        $events = Event::where('user_id', $user->id)->latest()->get();

        // Ambil semua ulasan untuk event yang dibuat oleh organizer ini
        $reviews = Review::whereHas('event', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('user', 'event')->latest()->get();

        // Hitung rata-rata rating
        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        return view('organizer-profile', compact('user', 'events', 'reviews', 'averageRating', 'totalReviews'));
    }
}