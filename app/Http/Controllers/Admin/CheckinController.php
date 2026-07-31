<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    /**
     * Menampilkan halaman HTML5 QR Scanner untuk Penjaga Pintu.
     */
    public function index()
    {
        return view('admin.checkin');
    }

    /**
     * Memvalidasi order_id dari scan QR Code dan mengubah status menjadi 'used'.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        $transaction = Transaction::with('event')->where('order_id', $request->order_id)->first();

        if (!$transaction) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tiket tidak ditemukan!',
            ], 404);
        }

        $user = auth()->user();
        // Proteksi Otoritas Tenant
        if ($user->role !== 'admin' && ($transaction->event->user_id ?? null) !== $user->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akses Ditolak! Anda tidak berwenang memvalidasi tiket event ini.',
            ], 403);
        }

        if (strtolower($transaction->status) === 'used') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tiket sudah pernah digunakan sebelumnya!',
            ], 400);
        }

        // Hanya tiket yang statusnya lunas (success, settlement, completed) yang diizinkan masuk
        if (!in_array(strtolower($transaction->status), ['success', 'settlement', 'completed'])) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tiket belum lunas / pembayaran gagal!',
            ], 400);
        }

        // Tandai tiket sebagai sudah check-in/used
        $transaction->status = 'used';
        $transaction->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Check-in berhasil! Selamat datang, ' . $transaction->customer_name . '.',
        ]);
    }
}
