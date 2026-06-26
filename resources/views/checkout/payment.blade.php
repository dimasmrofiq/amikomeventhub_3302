@extends('layouts.app') 

@section('title', 'Pembayaran - ' . $transaction->event->title) 

@section('content') 
<main class="max-w-3xl mx-auto px-6 py-20 text-center"> 
    <div class="bg-white rounded-3xl border border-slate-200 p-12 shadow-sm inline-block w-full max-w-md animate-bounce-in">
        <h2 class="text-2xl font-black mb-2">Selesaikan Pembayaran</h2>
        <p class="text-slate-500 mb-6">Silakan klik tombol di bawah untuk membuka gerbang pembayaran aman Midtrans.</p>
        
        <div class="bg-slate-50 p-4 rounded-2xl mb-6 text-left">
            <p class="text-xs text-slate-400 uppercase font-bold">Total Tagihan</p>
            <p class="text-2xl font-black text-indigo-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
        </div>

        <button id="pay-button" class="w-full py-4 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
            Munculkan Jendela Pembayaran
        </button>
    </div>
</main>

{{-- AMAN: Memanggil client_key lewat config Laravel agar aman dari deteksi GitHub --}}
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    
    payButton.addEventListener('click', function () {
        // Ambil token dari database
        const snapToken = '{{ $transaction->snap_token }}';
        
        // Deteksi jika token ternyata masih kosong
        if (!snapToken) {
            alert("⚠️ TOKEN KOSONG! Anda sedang membuka transaksi lama. Silakan kembali ke halaman Home dan BUAT PESANAN BARU.");
            return;
        }

        // Memicu jendela popup Midtrans menggunakan Snap Token
        window.snap.pay(snapToken, {
            onSuccess: function (result) {
                window.location.href = "{{ route('checkout.success', $transaction->order_id) }}";
            },
            onPending: function (result) {
                window.location.href = "{{ route('checkout.success', $transaction->order_id) }}";
            },
            onError: function (result) {
                alert("Pembayaran gagal, silakan coba kembali.");
            }
        });
    });
</script>
@endsection