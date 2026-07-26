<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        // 1. Validasi input bintang & komentar
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // 2. CEK PEMBELIAN TIKET BERDASARKAN EMAIL PEMESAN
        // Menggunakan 'customer_email' sesuai struktur tabel transactions Anda
        $hasPurchasedTicket = Transaction::where('customer_email', Auth::user()->email)
            ->where('event_id', $event->id)
            ->whereIn('status', ['SUCCESS', 'settlement', 'paid', 'success']) 
            ->exists();

        if (!$hasPurchasedTicket) {
            return back()->with('error', 'Gagal: Email akun Anda belum terdaftar memiliki tiket untuk acara ini.');
        }

        // 3. Cek apakah user sudah pernah memberi ulasan di event ini
        $existingReview = Review::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk event ini.');
        }

        // 4. Simpan review baru
        Review::create([
            'user_id'  => Auth::id(),
            'event_id' => $event->id,
            'rating'   => $request->rating,
            'comment'  => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan dan penilaian Anda!');
    }
}
