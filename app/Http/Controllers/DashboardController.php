<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Part;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $branch = $user?->branch;
        $quickActions = $branch?->quick_actions ?? Branch::getBranchQuickActions(null);

        // Bugünkü iş emirleri
        $todayOrders = WorkOrder::with('vehicle.customer')
            ->whereDate('date', today())
            ->latest()
            ->get();

        // Açık iş emirleri (beklemede + devam ediyor)
        $openOrders = WorkOrder::with('vehicle.customer')
            ->whereIn('status', ['beklemede', 'devam_ediyor', 'islem_basladi', 'parca_bekleniyor', 'ariza_tespiti'])
            ->count();

        // Tamamlanan iş sayısı
        $completedOrdersCount = WorkOrder::whereIn('status', ['tamamlandi', 'teslim_edildi', 'odendi'])->count();
        $pendingOrdersCount = WorkOrder::where('status', 'beklemede')->count();

        // Bu ay gelir
        $monthIncome = WorkOrder::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->whereIn('status', ['tamamlandi', 'odendi', 'teslim_edildi'])
            ->get()
            ->sum('grand_total');

        // Bu ay bekleyen ödemeler
        $pendingPayment = WorkOrder::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->whereIn('status', ['tamamlandi', 'teslim_edildi'])
            ->get()
            ->sum('grand_total');

        // Bugünkü randevular
        $todayAppointments = Appointment::with('vehicle.customer')
            ->whereDate('start_time', today())
            ->orderBy('start_time')
            ->get();

        // Kritik stok uyarıları
        $lowStockParts = Part::whereRaw('stock <= min_stock')->get();

        // Son 7 günlük gelir grafiği
        $last7Days = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo)->format('Y-m-d');
            $label = now()->subDays($daysAgo)->format('d/m');
            $total = WorkOrder::whereDate('date', $date)
                ->whereIn('status', ['tamamlandi', 'odendi', 'teslim_edildi'])
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
            'branch', 'quickActions',
            'todayOrders', 'openOrders', 'completedOrdersCount', 'pendingOrdersCount',
            'monthIncome', 'pendingPayment', 'todayAppointments', 'lowStockParts',
            'last7Days', 'recentOrders'
        ));
    }
}
