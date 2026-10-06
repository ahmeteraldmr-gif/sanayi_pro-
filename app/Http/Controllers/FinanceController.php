<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\PartMovement;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'gelir');

        // Tamamlanan / Teslim Edilen / Ödenen İş Emirleri
        $completedWorkOrders = WorkOrder::whereIn('status', ['tamamlandi', 'teslim_edildi', 'odendi'])
            ->with(['vehicle.customer', 'user', 'items'])
            ->latest('date')
            ->get();

        $totalGelir = $completedWorkOrders->sum(fn($wo) => (float)$wo->grand_total);
        $totalPartRevenue = $completedWorkOrders->sum(fn($wo) => (float)$wo->total_parts);
        $totalLaborRevenue = $completedWorkOrders->sum(fn($wo) => (float)$wo->total_labor);

        // Tahsil Edilen (status = odendi) vs Bekleyen Borçlar
        $tahsilEdilen = WorkOrder::where('status', 'odendi')->get()->sum(fn($wo) => (float)$wo->grand_total);
        $bekleyenBorclar = WorkOrder::whereIn('status', ['tamamlandi', 'teslim_edildi'])->get()->sum(fn($wo) => (float)$wo->grand_total);

        // Stok Alım Giderleri
        $stockPurchases = PartMovement::where('type', 'in')->with('part')->latest()->get();
        $totalGider = $stockPurchases->sum(fn($m) => (float)($m->quantity * ($m->unit_price ?? $m->part?->buy_price ?? 0)));

        $netKar = $totalGelir - $totalGider;

        return view('finance.index', compact(
            'tab',
            'completedWorkOrders',
            'totalGelir',
            'totalPartRevenue',
            'totalLaborRevenue',
            'tahsilEdilen',
            'bekleyenBorclar',
            'stockPurchases',
            'totalGider',
            'netKar'
        ));
    }
}

