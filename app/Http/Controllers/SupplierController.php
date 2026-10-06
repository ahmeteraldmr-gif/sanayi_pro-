<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        // Group parts by supplier name
        $partsBySupplier = Part::all()->groupBy(fn($p) => $p->supplier ?: 'Genel Tedarikçiler');

        $suppliers = $partsBySupplier->map(function ($parts, $supplierName) {
            return [
                'name' => $supplierName,
                'total_parts_count' => $parts->count(),
                'total_stock' => $parts->sum('stock'),
                'total_inventory_value' => $parts->sum(fn($p) => (float)$p->stock * (float)($p->buy_price ?? 0)),
                'parts' => $parts,
            ];
        });

        return view('suppliers.index', compact('suppliers'));
    }
}
