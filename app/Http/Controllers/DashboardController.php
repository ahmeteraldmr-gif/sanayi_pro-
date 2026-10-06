<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Bugünkü iş emirleri
        $todayOrders = WorkOrder::with('vehicle.customer')
            ->whereDate('date', today())
            ->latest()
            ->get();

        // Açık iş emirleri (beklemede + devam ediyor)
        $openOrders = WorkOrder::with('vehicle.customer')
            ->whereIn('status', ['beklemede', 'devam_ediyor'])
            ->count();

        // Bu ay gelir
        $monthIncome = WorkOrder::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->whereIn('status', ['tamamlandi', 'odendi'])
            ->get()
            ->sum('grand_total');

        // Bu ay bekleyen ödemeler
        $pendingPayment = WorkOrder::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->where('status', 'tamamlandi')
            ->get()
            ->sum('grand_total');

        // Kritik stok uyarıları
        $lowStockParts = Part::whereRaw('stock <= min_stock')->get();

        // Son 7 günlük gelir grafiği
        $last7Days = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo)->format('Y-m-d');
            $label = now()->subDays($daysAgo)->format('d/m');
            $total = WorkOrder::whereDate('date', $date)
                ->whereIn('status', ['tamamlandi', 'odendi'])
                ->get()
                ->sum('grand_total');
            return ['label' => $label, 'total' => $total];
        });

        // Son iş emirleri
        $recentOrders = WorkOrder::with('vehicle.customer')
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'todayOrders', 'openOrders', 'monthIncome',
            'pendingPayment', 'lowStockParts', 'last7Days', 'recentOrders'
        ));
    }
}
