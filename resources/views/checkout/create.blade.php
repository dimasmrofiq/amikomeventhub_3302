<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - {{ $event->title }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <div class="max-w-4xl mx-auto px-4 py-12">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-8">Konfirmasi Pemesanan Tiket</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 md:col-span-1">
                <h2 class="text-xl font-bold mb-4">Detail Event</h2>
                <div class="space-y-3 text-sm">
                    <p class="font-semibold text-indigo-600 text-base">{{ $event->title }}</p>
                    <p class="text-slate-500">📅 {{ $event->date }}</p>
                    <p class="text-slate-500">📍 {{ $event->location }}</p>
                    <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                        <span class="text-slate-600">Total Harga:</span>
                        <span class="text-lg font-bold text-slate-900">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 md:col-span-2">
                <h2 class="text-xl font-bold mb-6">Informasi Data Pemesan</h2>

                <form action="{{ route('checkout.store', $event->id) }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="customer_name" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Masukkan nama sesuai KTP">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Alamat Email</label>
                        <input type="email" name="customer_email" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="nama@email.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nomor WhatsApp</label>
                        <input type="text" name="customer_phone" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="08XXXXXXXXXX">
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-xl transition duration-200 shadow-md shadow-indigo-100 mt-6 cursor-pointer">
                        Bayar & Ambil Tiket Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>