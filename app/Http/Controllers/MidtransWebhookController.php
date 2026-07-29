<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MidtransWebhookController extends Controller
{
    /**
     * Menangani callback webhook notifikasi dari Midtrans.
     */
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Webhook Callback Payload: ', $payload);

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Cegah proses berulang jika status di database lokal sudah lunas/sukses
        if (in_array(strtolower($transaction->status), ['settlement', 'success'])) {
            return response()->json(['message' => 'Transaction already processed']);
        }

        // Pemetaan status Midtrans ke status lokal database
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $transaction->status = 'challenge';
            } else if ($fraudStatus == 'accept') {
                $transaction->status = 'success';
                $this->processSuccess($transaction);
            }
        } else if ($transactionStatus == 'settlement') {
            $transaction->status = 'success';
            $this->processSuccess($transaction);
        } else if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $transaction->status = 'failed';
        } else if ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
        }

        $transaction->save();

        return response()->json(['message' => 'OK']);
    }

    /**
     * Memproses logika sukses: Mengurangi stok tiket dan mengirim email konfirmasi.
     */
    private function processSuccess(Transaction $transaction)
    {
        $event = $transaction->event;

        if ($event && $event->stock > 0) {
            $event->stock = $event->stock - 1;
            $event->save();

            // Kirim email tiket
            try {
                Mail::to($transaction->customer_email)->send(new \App\Mail\EventTicketMail($transaction));
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email E-Ticket via Webhook: ' . $e->getMessage());
            }
        } else {
            Log::warning('Stok tiket habis atau event tidak valid setelah pembayaran sukses. Order ID: ' . $transaction->order_id);
        }
    }
}
