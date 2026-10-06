<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = (int)($request->month ?? now()->month);
        $year  = (int)($request->year  ?? now()->year);

        $today = today();

        // 1. BUGÜN ÖZET METRİKLERİ (TODAY HIGHLIGHTS)
        $todayOrders = WorkOrder::whereDate('date', $today)->get();

        // 💰 Bugünkü Ciro
        $todayRevenue = $todayOrders->whereIn('status', ['tamamlandi', 'odendi', 'teslim_edildi'])->sum(fn($o) => $o->grand_total);
        if ($todayRevenue == 0) {
            $todayRevenue = $todayOrders->sum(fn($o) => $o->grand_total);
        }

        // 🔧 Tamamlanan İş (Bugün)
        $todayCompletedCount = $todayOrders->whereIn('status', ['tamamlandi', 'odendi', 'teslim_edildi'])->count();

        // 🚗 Servisteki Araç (Aktif İşlemde)
        $inServiceCount = WorkOrder::whereIn('status', ['arac_kabul', 'beklemede', 'ariza_tespiti', 'islem_basladi', 'devam_ediyor', 'parca_bekleniyor', 'kontrol'])->count();

        // 📦 Kritik Stok
        $lowStockCount = Part::whereRaw('stock <= min_stock')->count();

        // 💳 Bekleyen Ödeme
        $pendingOrders = WorkOrder::with('vehicle.customer')
            ->whereIn('status', ['tamamlandi', 'kontrol', 'teslim_edildi'])
            ->latest('date')
            ->get();
        $pendingPaymentTotal = $pendingOrders->sum(fn($o) => $o->grand_total);

        // -------------------------------------------------------------
        // 2. SEÇİLİ AY / DÖNEM DETAYLARI
        // -------------------------------------------------------------
        $monthOrders = WorkOrder::with(['vehicle.customer', 'user', 'items.part'])
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->whereIn('status', ['tamamlandi', 'odendi', 'teslim_edildi'])
            ->get();

        $totalIncome   = $monthOrders->sum(fn($o) => $o->grand_total);
        $totalParts    = $monthOrders->sum('total_parts');
        $totalLabor    = $monthOrders->sum('total_labor');
        $totalDiscount = $monthOrders->sum('discount');

        // Parça Kârı Hesaplama (Satış Fiyatı - Alış Fiyatı)
        $partProfit = 0;
        foreach ($monthOrders as $order) {
            foreach ($order->items as $item) {
                if ($item->type === 'part' && $item->part) {
                    $cost = $item->part->buy_price * $item->quantity;
                    $partProfit += max(0, $item->total - $cost);
                }
            }
        }

        // Günlük Ciro Dağılımı (Grafik)
        $dailyData = $monthOrders->groupBy(fn($o) => $o->date->format('d'))
            ->map(fn($group) => $group->sum(fn($o) => $o->grand_total))
            ->sortKeys();

        // 👨‍🔧 Usta Bazlı Gelir Dağılımı
        $mechanicStats = $monthOrders->groupBy('user_id')->map(function ($orders, $userId) {
            $user = User::find($userId);
            return [
                'name'    => $user ? $user->name : 'Genel Servis',
                'count'   => $orders->count(),
                'revenue' => $orders->sum(fn($o) => $o->grand_total),
                'labor'   => $orders->sum('total_labor'),
            ];
        })->sortByDesc('revenue');

        // ⚙️ En Çok Yapılan İşlemler (Top Labor Items)
        $topLabors = WorkOrderItem::whereHas('workOrder', function ($q) use ($month, $year) {
                $q->whereMonth('date', $month)
                  ->whereYear('date', $year)
                  ->whereIn('status', ['tamamlandi', 'odendi', 'teslim_edildi']);
            })
            ->where('type', 'labor')
            ->select('name', DB::raw('COUNT(*) as job_count'), DB::raw('SUM(total) as total_revenue'))
            ->groupBy('name')
            ->orderByDesc('job_count')
            ->limit(7)
            ->get();

        // 📦 En Çok Kullanılan Parçalar (Top Parts)
        $topParts = WorkOrderItem::whereHas('workOrder', function ($q) use ($month, $year) {
                $q->whereMonth('date', $month)
                  ->whereYear('date', $year)
                  ->whereIn('status', ['tamamlandi', 'odendi', 'teslim_edildi']);
            })
            ->where('type', 'part')
            ->select('name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total) as total_revenue'))
            ->groupBy('name')
            ->orderByDesc('total_qty')
            ->limit(7)
            ->get();

        // 📊 Aylık Karşılaştırma (Son 6 ay)
        $monthlyData = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            $monthGroup = WorkOrder::whereMonth('date', $date->month)
                ->whereYear('date', $date->year)
                ->whereIn('status', ['tamamlandi', 'odendi', 'teslim_edildi'])
                ->get();
            return [
                'label' => $date->translatedFormat('M Y'),
                'total' => $monthGroup->sum(fn($o) => $o->grand_total),
                'count' => $monthGroup->count(),
            ];
        });

        return view('reports.index', compact(
            'todayRevenue', 'todayCompletedCount', 'inServiceCount', 'lowStockCount', 'pendingPaymentTotal',
            'monthOrders', 'totalIncome', 'totalParts', 'totalLabor', 'totalDiscount', 'partProfit',
            'dailyData', 'mechanicStats', 'topLabors', 'topParts', 'monthlyData', 'pendingOrders',
            'month', 'year'
        ));
    }
}
