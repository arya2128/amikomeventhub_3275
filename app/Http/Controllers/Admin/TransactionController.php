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
     * Display all transactions (INDEX)
     */
    public function index(Request $request)
    {
        // Query dasar dengan relasi ke Event dan Category
        $query = Transaction::with('event.category')->latest();

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

        // Statistics
        $stats = [
            'total' => Transaction::count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'completed' => Transaction::where('status', 'completed')->count(),
            'failed' => Transaction::where('status', 'failed')->count(),
            'cancelled' => Transaction::where('status', 'cancelled')->count(),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    /**
     * Display transaction details (SHOW)
     */
    public function show($id)
    {
        $transaction = Transaction::with('event.category')->findOrFail($id);
        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Show form to edit transaction status
     */
    public function edit($id)
    {
        $transaction = Transaction::with('event')->findOrFail($id);
        $statuses = ['pending', 'completed', 'failed', 'cancelled'];
        
        return view('admin.transactions.edit', compact('transaction', 'statuses'));
    }

    /**
     * Update transaction status (UPDATE)
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

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
     * Delete transaction (DESTROY)
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $orderId = $transaction->order_id;

        $transaction->delete();

        return redirect()->route('admin.transactions.index')
                       ->with('success', "Transaksi {$orderId} berhasil dihapus!");
    }

    /**
     * Bulk action untuk update multiple transactions
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'integer|exists:transactions,id',
            'status' => 'required|in:pending,completed,failed,cancelled',
        ]);

        $count = Transaction::whereIn('id', $request->transaction_ids)
                           ->update(['status' => $request->status]);

        return redirect()->route('admin.transactions.index')
                       ->with('success', "{$count} transaksi berhasil diperbarui!");
    }
}

