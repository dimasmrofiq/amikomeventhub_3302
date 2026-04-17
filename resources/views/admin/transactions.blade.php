@extends('layouts.admin')

@section('page_title', 'Laporan Transaksi')
@section('page_subtitle', 'Daftar semua pembayaran yang masuk')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center">
        <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest mb-2">Bulan Ini</p>
        <p class="text-2xl font-black text-indigo-600">Rp 5.2M</p>
    </div>
    </div>

<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-widest">
                <tr>
                    <th class="px-8 py-4">Pembeli</th>
                    <th class="px-8 py-4">Nama Event</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6">
                        <p class="font-bold text-sm uppercase">Donni Prabowo</p>
                        <p class="text-xs text-slate-400">donni@example.com</p>
                    </td>
                    <td class="px-8 py-6 font-medium text-slate-600">Jazz Night 2024</td>
                    <td class="px-8 py-6">
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase">Success</span>
                    </td>
                    <td class="px-8 py-6 text-right font-black text-indigo-600">Rp 155.000</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="px-8 py-6 bg-slate-50/50 border-t flex justify-between items-center">
        <p class="text-sm text-slate-500 font-medium">Menampilkan 1 dari 10 transaksi</p>
        <div class="flex gap-2">
            <button class="px-4 py-2 border rounded-xl hover:bg-white transition text-sm font-bold opacity-50">Previous</button>
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl shadow-md text-sm font-bold">1</button>
            <button class="px-4 py-2 border rounded-xl hover:bg-white transition text-sm font-bold">Next</button>
        </div>
    </div>
</div>
@endsection