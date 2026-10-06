<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\PartMovement;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function index(Request $request)
    {
        $query = Part::query();

        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('brand', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
                  ->orWhere('oem_code', 'like', "%{$s}%")
                  ->orWhere('category', 'like', "%{$s}%")
                  ->orWhere('supplier', 'like', "%{$s}%")
                  ->orWhere('compatible_vehicles', 'like', "%{$s}%");
            });
        }

        if ($request->low_stock) {
            $query->whereRaw('stock <= min_stock');
        }

        $parts = $query->orderBy('name')->paginate(25);
        $lowStockCount = Part::whereRaw('stock <= min_stock')->count();

        return view('parts.index', compact('parts', 'lowStockCount'));
    }

    public function create()
    {
        return view('parts.create');
    }

    public function store(Request $request)
    {
        $branchId = auth()->user()->branch_id;

        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'brand'               => 'nullable|string|max:100',
            'code'                => [
                'nullable',
                'string',
                'max:100',
                \Illuminate\Validation\Rule::unique('parts', 'code')
                    ->where(fn($q) => $branchId ? $q->where('branch_id', $branchId) : $q),
            ],
            'oem_code'            => 'nullable|string|max:100',
            'category'            => 'nullable|string|max:100',
            'supplier'            => 'nullable|string|max:150',
            'compatible_vehicles' => 'nullable|string',
            'buy_price'           => 'nullable|numeric|min:0',
            'last_buy_price'      => 'nullable|numeric|min:0',
            'sell_price'          => 'nullable|numeric|min:0',
            'stock'               => 'nullable|integer|min:0',
            'min_stock'           => 'nullable|integer|min:0',
            'notes'               => 'nullable|string',
        ], [
            'name.required' => 'Parça adı alanı zorunludur.',
            'code.unique'   => 'Bu parça kodu atölyenizde zaten başka bir parçada kullanılıyor.',
        ]);

        if (!isset($data['last_buy_price']) || $data['last_buy_price'] == 0) {
            $data['last_buy_price'] = $data['buy_price'] ?? 0;
        }

        $part = Part::create($data);

        // İlk stok tanımını stok hareketi olarak kaydet
        if (!empty($part->stock) && $part->stock > 0) {
            PartMovement::create([
                'part_id'    => $part->id,
                'type'       => 'in',
                'quantity'   => $part->stock,
                'unit_price' => $part->buy_price ?? 0,
                'note'       => 'İlk Stok Tanımlama / Girişi',
            ]);
        }

        return redirect()->route('parts.index')
            ->with('success', 'Parça ve stok kaydı başarıyla eklendi.');
    }

    public function show(Part $part)
    {
        $movements = $part->movements()->with(['workOrder.vehicle.customer', 'vehicle'])->latest()->paginate(25);
        return view('parts.show', compact('part', 'movements'));
    }

    public function edit(Part $part)
    {
        return view('parts.edit', compact('part'));
    }

    public function update(Request $request, Part $part)
    {
        $branchId = auth()->user()->branch_id;

        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'brand'               => 'nullable|string|max:100',
            'code'                => [
                'nullable',
                'string',
                'max:100',
                \Illuminate\Validation\Rule::unique('parts', 'code')
                    ->where(fn($q) => $branchId ? $q->where('branch_id', $branchId) : $q)
                    ->ignore($part->id),
            ],
            'oem_code'            => 'nullable|string|max:100',
            'category'            => 'nullable|string|max:100',
            'supplier'            => 'nullable|string|max:150',
            'compatible_vehicles' => 'nullable|string',
            'buy_price'           => 'nullable|numeric|min:0',
            'last_buy_price'      => 'nullable|numeric|min:0',
            'sell_price'          => 'nullable|numeric|min:0',
            'min_stock'           => 'nullable|integer|min:0',
            'notes'               => 'nullable|string',
        ], [
            'name.required' => 'Parça adı alanı zorunludur.',
            'code.unique'   => 'Bu parça kodu atölyenizde zaten başka bir parçada kullanılıyor.',
        ]);

        $part->update($data);
        return redirect()->route('parts.index')
            ->with('success', 'Parça bilgileri güncellendi.');
    }

    public function destroy(Part $part)
    {
        $part->delete();
        return redirect()->route('parts.index')
            ->with('success', 'Parça silindi.');
    }

    // Stok girişi (Satın Alma / Ekleme)
    public function addStock(Request $request, Part $part)
    {
        $data = $request->validate([
            'quantity'   => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'note'       => 'nullable|string',
        ]);

        $buyPrice = $data['unit_price'] ?? $part->buy_price;

        $part->increment('stock', $data['quantity']);

        // Son alış fiyatını ve alış fiyatını güncelle
        if (!empty($data['unit_price'])) {
            $part->update([
                'last_buy_price' => $data['unit_price'],
                'buy_price'      => $data['unit_price'],
            ]);
        }

        PartMovement::create([
            'part_id'    => $part->id,
            'type'       => 'in',
            'quantity'   => $data['quantity'],
            'unit_price' => $buyPrice,
            'note'       => $data['note'] ?? 'Satın Alma / Stok Girişi',
        ]);

        return redirect()->back()->with('success', 'Stok girişi kaydedildi.');
    }

    // AJAX: parça arama (iş emri formunda)
    public function ajaxSearch(Request $request)
    {
        $query = Part::query();

        if ($request->filled('q')) {
            $s = $request->q;
            $query->where(function($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('brand', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
                  ->orWhere('oem_code', 'like', "%{$s}%")
                  ->orWhere('category', 'like', "%{$s}%")
                  ->orWhere('compatible_vehicles', 'like', "%{$s}%");
            });
        }

        $parts = $query->orderBy('name')
            ->select('id', 'name', 'brand', 'code', 'oem_code', 'sell_price', 'stock')
            ->limit(30)
            ->get();

        return response()->json($parts);
    }
}
