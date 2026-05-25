<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // Menampilkan halaman e-ticket dinamis berdasarkan order_id
    public function show($order_id)
    {
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
        return view('ticket', compact('transaction'));
    }
}