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
    <!-- FITUR 2: FORM RATING & ULASAN PESERTA     -->
    <!-- ========================================== -->
    <div class="max-w-5xl mx-auto border-t border-slate-200 pt-10">
        <h2 class="text-xl font-bold text-slate-800 mb-6 text-center">Ulasan & Rating Acara</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- FORM RATING & ULASAN -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="font-bold text-slate-700 mb-4 border-b pb-2">Berikan Ulasan</h3>

                <!-- Alert Notifikasi -->
                @if(session('success'))
                    <div class="p-3 mb-4 text-xs text-green-700 bg-green-100 rounded-xl">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="p-3 mb-4 text-xs text-red-700 bg-red-100 rounded-xl">{{ session('error') }}</div>
                @endif

                @auth
                    <form action="{{ route('review.store', $event->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-2">Penilaian Bintang</label>
                            <div class="flex items-center gap-1.5 flex-wrap">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="inline-flex items-center gap-1 cursor-pointer bg-slate-50 border border-slate-200 px-2.5 py-1.5 rounded-lg hover:bg-amber-50 text-xs font-semibold">
                                        <input type="radio" name="rating" value="{{ $i }}" class="text-amber-500 focus:ring-amber-400" required>
                                        <span class="text-slate-700">{{ $i }} ★</span>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <label for="comment" class="block text-xs font-bold text-slate-600 mb-2">Ulasan / Testimoni</label>
                            <textarea name="comment" id="comment" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 text-xs" placeholder="Bagaimana kesan Anda mengikuti acara ini?"></textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-slate-800 text-white font-bold rounded-xl hover:bg-slate-900 transition text-xs">
                            Kirim Ulasan
                        </button>
                    </form>
                @else
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-center text-xs text-slate-600">
                        Silakan <a href="{{ route('login') }}" class="text-indigo-600 font-bold underline">Login</a> terlebih dahulu untuk memberikan ulasan.
                    </div>
                @endauth
            </div>

            <!-- DAFTAR ULASAN PESERTA -->
            <div class="md:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="font-bold text-slate-700">Daftar Ulasan Peserta</h3>
                    <span class="text-amber-500 font-bold text-sm bg-amber-50 px-3 py-1 rounded-full border border-amber-200">
                        ★ {{ method_exists($event, 'averageRating') ? $event->averageRating() : '5.0' }}
                    </span>
                </div>

                <div class="space-y-4 max-h-80 overflow-y-auto pr-2">
                    @forelse($event->reviews ?? [] as $review)
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-bold text-slate-800 text-sm">{{ $review->user->name ?? 'Pembeli Tiket' }}</span>
                                <span class="text-amber-500 font-bold text-xs">
                                    @for($s = 1; $s <= $review->rating; $s++) ★ @endfor
                                </span>
                            </div>
                            <p class="text-slate-600 text-xs">{{ $review->comment ?? 'Tidak ada komentar tertulis.' }}</p>
                            <span class="text-[10px] text-slate-400 mt-2 block">{{ $review->created_at ? $review->created_at->diffForHumans() : '' }}</span>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 text-xs">
                            Belum ada ulasan untuk event ini. Jadilah yang pertama memberikan ulasan!
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</main>
@endsection