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
}