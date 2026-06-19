<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Menampilkan Form Isi Data Pemesan Tiket
     */
    public function create(Event $event)
    {
        // Pastikan stok masih ada sebelum menampilkan form
        if ($event->stock <= 0) {
            return back()->with('error', 'Maaf, tiket untuk event ini sudah habis!');
        }

        return view('checkout.create', compact('event'));
    }

    /**
     * Memproses Pengurangan Stok dan Menyimpan Transaksi
     */
    public function store(Request $request, Event $event)
    {
        // 1. Validasi Input Data Pengunjung
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // 2. State Handling: Cek Ketersediaan Stok Kembali
        if ($event->stock <= 0) {
            return redirect()->route('home')->with('error', 'Transaksi gagal! Tiket baru saja habis.');
        }

        // 3. Kurangi Stok Tiket Event (Decrement)
        $event->decrement('stock');

        // 4. Buat Order ID Unik (Contoh: EVH-ABC12D)
        $orderId = 'EVH-' . strtoupper(Str::random(6));

        // 5. Simpan Data ke Tabel Transaksi
        Transaction::create([
            'order_id'       => $orderId,
            'event_id'       => $event->id,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price'    => $event->price,
            'status'         => 'success', // Langsung success untuk guest checkout praktikum
        ]);

        // 6. Alihkan ke halaman sukses e-ticket bawaan modul
        return redirect()->route('ticket.index')->with('success', 'Pembelian tiket berhasil! Nomor Pesanan Anda: ' . $orderId);
    }
}