<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use App\Models\Category; // Ditambahkan untuk menu footer/header sesuai modul
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        if ($event->stock <= 0) {
            return back()->with('error', 'Maaf, tiket untuk event ini sudah habis!');
        }
        return view('checkout.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        if ($event->stock <= 0) {
            return redirect()->route('home')->with('error', 'Transaksi gagal! Tiket baru saja habis.');
        }

        $event->decrement('stock');
        $orderId = 'EVH-' . strtoupper(Str::random(6));

        $transaction = Transaction::create([
            'order_id'       => $orderId,
            'event_id'       => $event->id,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price'    => $event->price,
            'status'         => 'pending',
            'snap_token'     => null,
        ]);

        // DISESUAIKAN: Membaca dari config/midtrans.php
        \Midtrans\Config::$serverKey = config('midtrans.server_key'); 
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $transaction->order_id,
                'gross_amount' => (int) $transaction->total_price,
            ],
            'customer_details' => [
                'first_name' => $transaction->customer_name,
                'email' => $transaction->customer_email,
                'phone' => $transaction->customer_phone,
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);
            return redirect()->route('checkout.payment', $transaction->order_id);
        } catch (\Exception $e) {
            $event->increment('stock');
            return back()->with('error', 'Gagal terhubung ke Midtrans: ' . $e->getMessage());
        }
    }

    public function payment($order_id)
    {
        $transaction = Transaction::where('order_id', $order_id)->firstOrFail();
        return view('checkout.payment', compact('transaction'));
    }

    /**
     * Menangani Pengalihan ketika Pembayaran Berhasil (Sesuai Halaman 111)
     */
    public function success($order_id)
    {
        // Ambil daftar kategori untuk keperluan menu footer/header sesuai modul
        $categories = Category::all();
        
        $transaction = Transaction::where('order_id', $order_id)->firstOrFail();

        // DISESUAIKAN: Membaca dari config/midtrans.php agar konsisten
        \Midtrans\Config::$serverKey = config('midtrans.server_key'); 
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);

        try {
            // Validasi status pembayaran asli dari Midtrans
            $midtransStatus = \Midtrans\Transaction::status($order_id);

            if (in_array($midtransStatus->transaction_status, ['capture', 'settlement'])) {
                $transaction->update(['status' => 'success']); 
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses.');
        }
        
        return view('checkout.success', compact('transaction', 'categories'));
    }
}