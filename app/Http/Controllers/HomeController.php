<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Partner; // Import model Partner

class HomeController extends Controller
{
    public function index()
    {
        $events = Event::latest()->take(6)->get();
        $partners = Partner::with('category')->get(); 

        return view('welcome', compact('events', 'partners'));
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
}