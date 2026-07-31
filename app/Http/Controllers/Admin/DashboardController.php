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
        $user = auth()->user();

        if ($user->role === 'admin') {
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
        } else {
            // Organizer hanya melihat statistik miliknya sendiri
            // 1. Menjumlahkan nominal total_price dari Transaksi Lunas / Success / Settlement
            $totalRevenue = Transaction::whereIn('status', ['settlement', 'success', 'completed'])
                ->whereHas('event', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->sum('total_price');

            // 2. Menghitung Berapa tiket yang terjual
            $ticketsSold = Transaction::whereIn('status', ['settlement', 'success', 'completed'])
                ->whereHas('event', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->count();

            // 3. Menghitung Jumlah Acara Mendatang yang aktif
            $activeEvents = Event::where('user_id', $user->id)
                ->where('date', '>=', now())
                ->count();

            // 4. Menghitung Transaksi Pending
            $pendingOrders = Transaction::where('status', 'pending')
                ->whereHas('event', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->count();

            // 5. Mengambil 5 transaksi terbaru beserta event terkait
            $recentTransactions = Transaction::with('event')
                ->whereHas('event', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->latest()
                ->take(5)
                ->get();
        }

        return view('admin.dashboard', compact(
            'totalRevenue', 
            'ticketsSold', 
            'activeEvents', 
            'pendingOrders', 
            'recentTransactions'
        ));
    }
}