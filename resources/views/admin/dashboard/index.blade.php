@extends('layouts.admin')

@section('page_title', 'Dashboard Ringkasan')
@section('page_subtitle', 'Selamat datang kembali, Admin!')

@section('content')
<!-- KOTAK STATISTIK (Menggunakan Data Dinamis) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
    <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs mb-2">Total Pendapatan</p>
        <h3 class="text-4xl font-black text-indigo-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        <p class="text-green-500 text-sm font-bold mt-2">Dari seluruh event</p>
    </div>
    <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs mb-2">Tiket Terjual</p>
        <h3 class="text-4xl font-black text-slate-900">{{ number_format($totalTicketsSold, 0, ',', '.') }}</h3>
        <p class="text-indigo-500 text-sm font-bold mt-2">Tiket terbit</p>
    </div>
    <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs mb-2">Total Event</p>
        <h3 class="text-4xl font-black text-slate-900">{{ number_format($totalEvents, 0, ',', '.') }}</h3>
        <p class="text-slate-400 text-sm font-bold mt-2">Event terdaftar</p>
    </div>
</div>

<!-- GRAFIK ANALITIK (Tempat variabel $chartLabels & $chartData bekerja) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
        <h3 class="font-black text-xl mb-4">Statistik Kategori Event</h3>
        <!-- Kanvas Pie Chart -->
        <canvas id="categoryChart" class="max-h-72"></canvas>
    </div>
    
    <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
        <h3 class="font-black text-xl mb-4">Top 5 Event (Pendapatan)</h3>
        <!-- Kanvas Bar Chart -->
        <canvas id="revenueChart" class="max-h-72"></canvas>
    </div>
</div>

<!-- TABEL TRANSAKSI TERBARU -->
<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b border-slate-50 flex justify-between items-center">
        <h3 class="font-black text-xl">Transaksi Terbaru</h3>
        <a href="{{ route('admin.transactions') }}" class="text-indigo-600 font-bold text-sm hover:underline">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-widest">
                <tr>
                    <th class="px-8 py-4">Pembeli</th>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-right">Nominal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($recentTransactions as $transaction)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6">
                        <!-- PERBAIKAN: Menggunakan customer_name dan customer_email secara langsung -->
                        <p class="font-bold uppercase tracking-wide text-sm">{{ $transaction->customer_name }}</p>
                        <p class="text-xs text-slate-400">{{ $transaction->customer_email }}</p>
                    </td>
                    <td class="px-8 py-6 font-medium text-slate-600">
                        {{ $transaction->event->title ?? 'Event Dihapus' }}
                    </td>
                    <td class="px-8 py-6">
                        @if(strtolower($transaction->status) == 'success' || strtolower($transaction->status) == 'paid')
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase">Success</span>
                        @elseif(strtolower($transaction->status) == 'pending')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-bold uppercase">Pending</span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold uppercase">{{ $transaction->status }}</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 text-right font-black text-indigo-600">
                        Rp {{ number_format($transaction->total_price ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-10 text-center text-slate-500 font-medium">
                        Belum ada transaksi terbaru.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- SCRIPT UNTUK MERENDER GRAFIK CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Render Pie Chart (Kategori)
        const ctxCategory = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctxCategory, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                    borderWidth: 0
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // 2. Render Bar Chart (Pendapatan Event)
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartEventLabels) !!},
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($chartEventRevenue) !!},
                    backgroundColor: '#4F46E5',
                    borderRadius: 8
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    });
</script>
@endsection