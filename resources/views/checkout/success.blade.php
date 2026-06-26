@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<main class="max-w-3xl mx-auto px-6 py-20 text-center">
    <div class="bg-white rounded-3xl border border-slate-200 p-12 shadow-sm inline-block w-full max-w-md">
        
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
</main>
@endsection