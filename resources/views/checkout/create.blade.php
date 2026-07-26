@extends('layouts.app') 

@section('title', 'Konfirmasi Pemesanan Tiket') 

@section('content')
<main class="max-w-6xl mx-auto px-6 py-12">
    <h1 class="text-center text-2xl font-black mb-8 text-slate-800">Konfirmasi Pemesanan Tiket</h1>
    
    <!-- BAGIAN ATAS: DETAIL EVENT & FORM PEMESANAN -->
    <div class="flex flex-col md:flex-row gap-8 justify-center items-start mb-12">
        
        <!-- CARD DETAIL EVENT -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm w-full md:w-80">
            <h3 class="font-bold text-slate-700 mb-4 border-b pb-2">Detail Event</h3>
            <p class="text-indigo-600 font-bold text-lg mb-2">{{ $event->title }}</p>
            <p class="text-sm text-slate-500 mb-1">📅 {{ $event->date }}</p>
            <p class="text-sm text-slate-500 mb-4">📍 {{ $event->location }}</p>
            
            <div class="flex justify-between items-center pt-4 border-t border-dashed">
                <span class="text-sm text-slate-400">Total Harga:</span>
                <span class="font-black text-slate-800">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- CARD FORM PEMESANAN TIKET -->
        <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm w-full max-w-xl">
            <h3 class="font-bold text-slate-700 mb-6 border-b pb-2">Informasi Data Pemesan</h3>
            
            <form action="{{ route('checkout.store', $event->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Nama Lengkap</label>
                    <input type="text" name="customer_name" required placeholder="Masukkan nama sesuai KTP" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Alamat Email</label>
                    <input type="email" name="customer_email" required placeholder="nama@email.com" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Nomor WhatsApp</label>
                    <input type="text" name="customer_phone" required placeholder="08XXXXXXXXXX" 
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 transition">
                </div>

                <button type="submit" class="w-full py-4 mt-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                    Bayar & Ambil Tiket Sekarang
                </button>
            </form>
        </div>
    </div>


    <!-- ========================================== -->
    <!-- FITUR 2: HANYA MENAMPILKAN ULASAN PESERTA  -->
    <!-- ========================================== -->
    <div class="max-w-4xl mx-auto border-t border-slate-200 pt-10">
        <h2 class="text-xl font-bold text-slate-800 mb-6 text-center">Ulasan & Rating Acara</h2>

        <!-- DAFTAR ULASAN PESERTA (Diperlebar karena form input sudah dihapus) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="font-bold text-slate-700">Apa kata mereka yang sudah membeli?</h3>
                <span class="text-amber-500 font-bold text-sm bg-amber-50 px-3 py-1 rounded-full border border-amber-200">
                    ★ {{ method_exists($event, 'averageRating') ? $event->averageRating() : '5.0' }}
                </span>
            </div>

            <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                @forelse($event->reviews ?? [] as $review)
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-slate-800 text-sm">{{ $review->user->name ?? 'Pembeli Tiket' }}</span>
                            <span class="text-amber-500 font-bold text-xs">
                                @for($s = 1; $s <= $review->rating; $s++) ★ @endfor
                            </span>
                        </div>
                        <p class="text-slate-600 text-sm">{{ $review->comment ?? 'Tidak ada komentar tertulis.' }}</p>
                        <span class="text-[11px] text-slate-400 mt-2 block">{{ $review->created_at ? $review->created_at->diffForHumans() : '' }}</span>
                    </div>
                @empty
                    <div class="text-center py-10 flex flex-col items-center justify-center">
                        <span class="text-4xl mb-3">⭐</span>
                        <p class="text-slate-500 font-medium text-sm">Belum ada ulasan untuk event ini.</p>
                        <p class="text-slate-400 text-xs mt-1">Jadilah yang pertama memberikan ulasan setelah membeli tiket!</p>
                    </div>
                @endforelse
           
            </div>
       
        </div>
    </div>
</main>
@endsection