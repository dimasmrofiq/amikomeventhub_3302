<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Menampilkan Seluruh Log Riwayat Transaksi Penjualan Tiket
     */
    public function index()
    {
        // Mengambil transaksi terbaru beserta data relasi nama event-nya (Eager Loading)
        $transactions = Transaction::with('event')->latest()->paginate(10);

        return view('admin.transactions.index', compact('transactions'));
    }
}