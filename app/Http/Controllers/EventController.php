<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // Menampilkan halaman detail event
    public function show($id)
    {
        $event = Event::with('category')->findOrFail($id);
        return view('event-detail', compact('event'));
    }

    // Menampilkan halaman checkout
    public function checkout($id)
    {
        $event = Event::findOrFail($id);
        return view('checkout', compact('event'));
    }

    // Memproses transaksi checkout
    public function storeTransaction(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        if ($event->stock <= 0) {
            return back()->withErrors(['stock' => 'Maaf, tiket untuk event ini sudah habis.']);
        }

        // Kurangi stok event
        $event->decrement('stock');

        // Buat Order ID unik
        $orderId = 'TRX-' . strtoupper(uniqid());

        // Simpan transaksi
        // Karena ini checkout offline/langsung, kita set statusnya 'completed' agar e-ticket langsung terbit
        $transaction = Transaction::create([
            'event_id'       => $event->id,
            'order_id'       => $orderId,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price'    => $event->price + 5000, // Harga tiket + biaya layanan Rp 5.000
            'status'         => 'completed',
        ]);

        return redirect()->route('ticket.show', $transaction->order_id)
                         ->with('success', 'Pemesanan tiket berhasil!');
    }
}