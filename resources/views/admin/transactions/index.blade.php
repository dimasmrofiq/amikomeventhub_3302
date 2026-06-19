@extends('layouts.admin') 

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Laporan Riwayat Transaksi</h1>
        <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full border border-indigo-100">
            Total Log Terdata
        </span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 text-sm font-semibold">
                        <th class="px-6 py-4">Order ID</th>
                        <th class="px-6 py-4">Nama Pelanggan</th>
                        <th class="px-6 py-4">Event</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4">Total Bayar</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-mono font-bold text-indigo-600">{{ $trx->order_id }}</td>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $trx->customer_name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $trx->event->title ?? 'Event Dihapus' }}</td>
                        <td class="px-6 py-4 text-slate-500">
                            <span class="block">{{ $trx->customer_email }}</span>
                            <span class="text-xs text-slate-400">{{ $trx->customer_phone }}</span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-900">
                            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                {{ ucfirst($trx->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            Belum ada data transaksi masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection