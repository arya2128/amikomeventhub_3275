<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Menampilkan daftar semua event di halaman admin.
     */
    public function index()
    {
        $events = Event::with('category')->latest()->get();
        return view('admin.events.index', compact('events'));
    }

    /**
     * Menampilkan form untuk membuat event baru.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }

    /**
     * Menyimpan data event baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'date'        => 'required',
            'location'    => 'required|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:0',
            'poster'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        Event::create([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'date'        => $request->date,
            'location'    => $request->location,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'poster_path' => $posterPath,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit untuk event tertentu.
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $categories = Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    /**
     * Memperbarui data event di database.
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'date'        => 'required',
            'location'    => 'required|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:0',
            'poster'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $posterPath = $event->poster_path;
        
        if ($request->hasFile('poster')) {
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        $event->update([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'date'        => $request->date,
            'location'    => $request->location,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'poster_path' => $posterPath,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Data event berhasil diperbarui!');
    }

    /**
     * Menghapus event dari database beserta berkas posternya.
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        
        if ($event->poster_path) {
            Storage::disk('public')->delete($event->poster_path);
        }
        
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus secara permanen!');
    }
}