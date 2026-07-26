<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Jika Superadmin, ambil semua. Jika Organizer, filter berdasarkan user_id mereka.
        $query = Event::query();
        if (!$user->isSuperAdmin()) {
            $query->where('user_id', $user->id);
        }

        $totalEvents = (clone $query)->count();
        $totalTicketsSold = (clone $query)->sum(DB::raw('quota - stock'));
        $totalRevenue = (clone $query)->sum(DB::raw('(quota - stock) * price'));
        $totalTransactions = $totalTicketsSold; 

        $categoryData = (clone $query)
            ->select('category_id', DB::raw('count(*) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->get();
            
        $chartCategoryLabels = $categoryData->map(function ($item) {
            return $item->category ? $item->category->name : 'Tanpa Kategori';
        });
        $chartCategoryData = $categoryData->map->total;

        $topEvents = (clone $query)
            ->select('title', DB::raw('((quota - stock) * price) as revenue'))
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        $chartEventLabels = $topEvents->pluck('title');
        $chartEventRevenue = $topEvents->pluck('revenue');

        $chartLabels = $chartCategoryLabels; 
        $chartData = $chartCategoryData;

        // --- PERBAIKAN: Menghapus relasi 'user' yang tidak ada ---
        $recentTransactions = \App\Models\Transaction::with(['event'])
            ->when(!$user->isSuperAdmin(), function ($q) use ($user) {
                // Jika bukan superadmin, hanya tampilkan transaksi dari event miliknya
                $q->whereHas('event', function ($eventQuery) use ($user) {
                    $eventQuery->where('user_id', $user->id);
                });
            })
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalEvents',
            'totalTicketsSold',
            'totalRevenue',
            'totalTransactions',
            'chartCategoryLabels',
            'chartCategoryData',
            'chartEventLabels',
            'chartEventRevenue',
            'chartLabels',
            'chartData',
            'recentTransactions' 
        ));
    }
}