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

        $user = auth()->user();

        // 1. Pengecekan transaksi berhasil untuk event ini
        $hasSuccessfulTransaction = \App\Models\Transaction::where('event_id', $event->id)
            ->where('customer_email', $user->email)
            ->whereIn('status', [
                'success',
                'SUCCESS',
                'settlement',
                'SETTLEMENT',
                'completed',
                'COMPLETED',
            ])
            ->exists();

        if (!$hasSuccessfulTransaction) {
            return back()->with('error', 'Anda harus memiliki transaksi yang berhasil untuk event ini sebelum memberikan ulasan.');
        }

        // 2. Pengecekan event sudah selesai minimal satu hari (24 jam)
        if (Carbon::parse($event->date)->addDay()->isFuture()) {
            return back()->with('error', 'Anda hanya dapat memberikan ulasan minimal satu hari setelah event selesai diselenggarakan.');
        }

        // 3. Pengecekan pengguna belum pernah memberikan review untuk event yang sama
        $hasReviewed = Review::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($hasReviewed) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk event ini.');
        }

        Review::create([
            'user_id'     => $user->id,
            'event_id'    => $event->id,
            'rating'      => $request->rating,
            'review_text' => $request->review_text,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
