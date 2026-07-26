@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<main class="max-w-3xl mx-auto px-6 py-20 text-center">
    
    <!-- CARD 1: KONFIRMASI PEMBAYARAN -->
    <div class="bg-white rounded-3xl border border-slate-200 p-12 shadow-sm inline-block w-full max-w-md mb-8">
        <div class="w-24 h-24 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h2 class="text-2xl font-black mb-2">Pembayaran Berhasil!</h2>
        <p class="text-slate-500 mb-6">
            Terima kasih! Pesanan tiket Anda dengan Order ID:<br>
            <span class="font-bold text-indigo-600">{{ $transaction->order_id }}</span><br>
            telah terkonfirmasi statusnya menjadi <span class="badge bg-green-500 text-white px-2 py-0.5 rounded text-sm uppercase font-bold">{{ $transaction->status }}</span>.
        </p>

        <a href="{{ route('home') }}" class="inline-block w-full py-4 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
            Kembali ke Beranda
        </a>
    </div>

    <!-- CARD 2: FORM ULASAN & RATING -->
    <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm inline-block w-full max-w-md text-left align-top">
        <h3 class="text-lg font-bold text-slate-700 mb-4 text-center border-b pb-3">Berikan Ulasan & Rating</h3>
        
        <!-- Pesan Notifikasi Sukses/Error -->
        @if(session('success'))
            <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-xl text-center">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-xl text-center">{{ session('error') }}</div>
        @endif

        <form action="{{ route('review.store', $transaction->event_id) }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-600 mb-3 text-center">Penilaian Bintang</label>
                <div class="flex items-center gap-2 justify-center">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer bg-slate-50 border border-slate-200 px-3 py-2 rounded-lg hover:bg-amber-50 text-sm font-semibold transition">
                            <input type="radio" name="rating" value="{{ $i }}" class="text-amber-500" required>
                            <span class="text-slate-700">{{ $i }} ★</span>
                        </label>
                    @endfor
                </div>
            </div>

            <div>
                <label for="comment" class="block text-sm font-bold text-slate-600 mb-2">Ulasan / Testimoni</label>
                <textarea name="comment" id="comment" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 text-sm transition" placeholder="Bagaimana proses pemesanan tiket ini?"></textarea>
            </div>

            <button type="submit" class="w-full py-4 bg-slate-800 text-white font-bold rounded-xl hover:bg-slate-900 transition shadow-lg">
                Kirim Ulasan
            </button>
        </form>
    </div>
    
</main>
@endsection