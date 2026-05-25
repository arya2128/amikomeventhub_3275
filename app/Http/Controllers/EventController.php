<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    // Menampilkan halaman detail event
    public function show()
    {
        return view('event-detail');
    }

    // Menampilkan halaman checkout
    public function checkout()
    {
        return view('checkout');
    }
    
    protected $fillable = [
        'category_id', 'title', 'description', 'date', 
        'location', 'price', 'stock', 'poster_path'
    ];
}