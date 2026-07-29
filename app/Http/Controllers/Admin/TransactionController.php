<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display all transactions scoped by tenant.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Transaction::with('event.category')->latest();

        // Isolasi transaksi per-tenant (HIMA/Organizer)
        if ($user->role !== 'admin') {
            $query->whereHas('event', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan pencarian order_id atau customer_name
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('order_id', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'LIKE', '%' . $request->search . '%');
            });
        }

        $transactions = $query->paginate(15);

        // Statistics counts scoped by tenant
        if ($user->role === 'admin') {
            $stats = [
                'total' => Transaction::count(),
                'pending' => Transaction::where('status', 'pending')->count(),
                'completed' => Transaction::where('status', 'completed')->count(),
                'failed' => Transaction::where('status', 'failed')->count(),
                'cancelled' => Transaction::where('status', 'cancelled')->count(),
            ];
        } else {
            $stats = [
                'total' => Transaction::whereHas('event', function ($q) use ($user) { $q->where('user_id', $user->id); })->count(),
                'pending' => Transaction::where('status', 'pending')->whereHas('event', function ($q) use ($user) { $q->where('user_id', $user->id); })->count(),
                'completed' => Transaction::where('status', 'completed')->whereHas('event', function ($q) use ($user) { $q->where('user_id', $user->id); })->count(),
                'failed' => Transaction::where('status', 'failed')->whereHas('event', function ($q) use ($user) { $q->where('user_id', $user->id); })->count(),
                'cancelled' => Transaction::where('status', 'cancelled')->whereHas('event', function ($q) use ($user) { $q->where('user_id', $user->id); })->count(),
            ];
        }

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    /**
     * Display transaction details (SHOW) with authorization.
     */
    public function show($id)
    {
        $transaction = Transaction::with('event.category')->findOrFail($id);
        $user = auth()->user();

        // Proteksi Otoritas Tenant
        if ($user->role !== 'admin' && ($transaction->event->user_id ?? null) !== $user->id) {
            abort(403, 'Akses Ditolak! Anda tidak berwenang melihat transaksi ini.');
        }

        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Show form to edit transaction status with authorization.
     */
    public function edit($id)
    {
        $transaction = Transaction::with('event')->findOrFail($id);
        $user = auth()->user();

        // Proteksi Otoritas Tenant
        if ($user->role !== 'admin' && ($transaction->event->user_id ?? null) !== $user->id) {
            abort(403, 'Akses Ditolak! Anda tidak berwenang mengedit transaksi ini.');
        }

        $statuses = ['pending', 'completed', 'failed', 'cancelled'];
        
        return view('admin.transactions.edit', compact('transaction', 'statuses'));
    }

    /**
     * Update transaction status (UPDATE) with authorization.
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $user = auth()->user();

        // Proteksi Otoritas Tenant
        if ($user->role !== 'admin' && ($transaction->event->user_id ?? null) !== $user->id) {
            abort(403, 'Akses Ditolak! Anda tidak berwenang memperbarui transaksi ini.');
        }

        $request->validate([
            'status' => 'required|in:pending,completed,failed,cancelled',
        ]);

        $oldStatus = $transaction->status;
        $newStatus = $request->status;

        $transaction->update([
            'status' => $newStatus,
        ]);

        // Log status change
        Log::info("Transaction {$transaction->order_id} status changed from {$oldStatus} to {$newStatus}");

        return redirect()->route('admin.transactions.show', $transaction->id)
                       ->with('success', "Status transaksi berhasil diperbarui dari {$oldStatus} menjadi {$newStatus}!");
    }

    /**
     * Delete transaction (DESTROY) with authorization.
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $user = auth()->user();

        // Proteksi Otoritas Tenant
        if ($user->role !== 'admin' && ($transaction->event->user_id ?? null) !== $user->id) {
            abort(403, 'Akses Ditolak! Anda tidak berwenang menghapus transaksi ini.');
        }

        $orderId = $transaction->order_id;
        $transaction->delete();

        return redirect()->route('admin.transactions.index')
                       ->with('success', "Transaksi {$orderId} berhasil dihapus!");
    }

    /**
     * Bulk action untuk update multiple transactions with authorization.
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'integer|exists:transactions,id',
            'status' => 'required|in:pending,completed,failed,cancelled',
        ]);

        $user = auth()->user();

        // Filter transaction_ids yang boleh dimodifikasi oleh tenant
        if ($user->role === 'admin') {
            $count = Transaction::whereIn('id', $request->transaction_ids)
                               ->update(['status' => $request->status]);
        } else {
            $count = Transaction::whereIn('id', $request->transaction_ids)
                               ->whereHas('event', function($q) use ($user) {
                                   $q->where('user_id', $user->id);
                               })
                               ->update(['status' => $request->status]);
        }

        return redirect()->route('admin.transactions.index')
                       ->with('success', "{$count} transaksi berhasil diperbarui!");
    }
}
