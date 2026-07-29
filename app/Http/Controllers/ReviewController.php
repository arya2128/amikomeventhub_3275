<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReviewController extends Controller
{
    /**
     * Menyimpan ulasan rating & komentar dari user terotentikasi.
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'rating'      => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        // Ulasan hanya diizinkan jika tanggal acara sudah lewat/selesai
        if (Carbon::parse($event->date)->isFuture()) {
            return back()->with('error', 'Anda belum bisa memberi ulasan untuk acara yang belum selesai diselenggarakan.');
        }

        Review::create([
            'user_id'     => auth()->id(),
            'event_id'    => $event->id,
            'rating'      => $request->rating,
            'review_text' => $request->review_text,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
