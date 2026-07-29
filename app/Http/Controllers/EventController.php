<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Menampilkan halaman detail event secara dinamis.
     */
    public function show(Event $event)
    {
        $categories = Category::all();
        return view('event-detail', compact('event', 'categories'));
    }
}