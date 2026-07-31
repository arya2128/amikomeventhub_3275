<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class MyTicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /*
         * Transaksi dicari menggunakan email akun Google.
         * Hanya transaksi berhasil yang ditampilkan.
         */
        $transactions = Transaction::with(['event.category'])
            ->where('customer_email', $user->email)
            ->whereIn('status', [
                'success',
                'SUCCESS',
                'settlement',
                'SETTLEMENT',
                'completed',
                'COMPLETED',
            ])
            ->latest()
            ->get();

        $reviewedEventIds = \App\Models\Review::where('user_id', $user->id)
            ->pluck('event_id')
            ->toArray();

        return view('my-ticket', compact('transactions', 'reviewedEventIds'));
    }
}