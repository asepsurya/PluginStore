<?php

namespace App\Http\Controllers;

use Midtrans\Notification;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Transaction as MidtransTransaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->get();
        return view('transactions.index', compact('transactions'));
    }

    public function success(){

        return view('payment.success');
    }
    public function callback(Request $request)
    {
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    
        $notif = new \Midtrans\Notification();
    
        $transaction = $notif->transaction_status;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;
    
        $trx = Transaction::where('order_id', $orderId)->first();
    
        if (!$trx) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
    
        if ($transaction == 'capture') {
            $trx->status = ($fraud == 'challenge') ? 'challenge' : 'success';
        } elseif ($transaction == 'settlement') {
            $trx->status = 'success';
        } elseif (in_array($transaction, ['deny', 'cancel', 'expire'])) {
            $trx->status = 'failed';
        } elseif ($transaction == 'pending') {
            $trx->status = 'pending';
        }
    
        $trx->save(); // ⬅️ WAJIB! Simpan ke DB
    
        return response()->json(['message' => 'Notification handled'], 200);
    }
    public function notify(Request $request)
    {
        // Log isi notifikasi (bisa simpan ke DB juga)
        Log::info('GoPay connect notification received: ', $request->all());

        // Contoh: ambil data user GoPay yang dihubungkan
        $status = $request->input('status'); // "success", "failed", "cancelled"
        $gopayUserId = $request->input('gopay_user_id');
        $externalId = $request->input('external_id'); // ID user kamu
        $timestamp = $request->input('timestamp');

        // Lakukan penyimpanan atau update status koneksi akun di database kamu
        // Misal: User::where('id', $externalId)->update(['gopay_status' => $status]);

        return response()->json(['message' => 'Notification received']);
    }
}
