<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Notification;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function receive(Request $request)
    {
        // Optional: Log isi notifikasi (debugging)
        Log::info('Midtrans callback: ', $request->all());

        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        $trx = Transaction::where('order_id', $orderId)->first();

        if (!$trx) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Update status sesuai notifikasi
        if ($transaction == 'capture') {
            $trx->status = ($fraud == 'challenge') ? 'challenge' : 'success';
        } elseif ($transaction == 'settlement') {
            $trx->status = 'success';
        } elseif (in_array($transaction, ['deny', 'cancel', 'expire'])) {
            $trx->status = 'failed';
        } elseif ($transaction == 'pending') {
            $trx->status = 'pending';
        }

        $trx->save();
        \Log::info('Webhook received', $request->all());

        return response()->json(['message' => 'Notification processed']);
    }
}
