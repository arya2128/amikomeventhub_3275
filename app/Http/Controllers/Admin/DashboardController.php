<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard statistik admin.
     */
    public function index()
    {
        // 1. Menjumlahkan nominal total_price dari Transaksi Lunas / Success / Settlement
        $totalRevenue = Transaction::whereIn('status', ['settlement', 'success', 'completed'])->sum('total_price');

        // 2. Menghitung Berapa tiket yang terjual
        $ticketsSold = Transaction::whereIn('status', ['settlement', 'success', 'completed'])->count();

        // 3. Menghitung Jumlah Acara Mendatang yang aktif
        $activeEvents = Event::where('date', '>=', now())->count();

        // 4. Menghitung Transaksi Pending
        $pendingOrders = Transaction::where('status', 'pending')->count();

        // 5. Mengambil 5 transaksi terbaru beserta event terkait
        $recentTransactions = Transaction::with('event')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 
            'ticketsSold', 
            'activeEvents', 
            'pendingOrders', 
            'recentTransactions'
        ));
    }
}