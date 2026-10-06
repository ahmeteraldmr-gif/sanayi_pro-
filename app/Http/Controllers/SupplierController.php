<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('contact_person', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $suppliers = $query->latest()->paginate(15);

        // Compute aggregate stats per supplier
        $suppliers->getCollection()->transform(function ($supplier) {
            $parts = Part::where('supplier', $supplier->name)->get();
            $supplier->total_parts_count = $parts->count();
            $supplier->total_stock = $parts->sum('stock');
            $supplier->total_inventory_value = $parts->sum(fn($p) => (float)$p->stock * (float)($p->buy_price ?? 0));
            return $supplier;
        });

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:150',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:150',
            'address'        => 'nullable|string|max:500',
            'tax_office'     => 'nullable|string|max:150',
            'tax_no'         => 'nullable|string|max:50',
            'notes'          => 'nullable|string',
        ]);

        Supplier::create($data);

        return redirect()->route('suppliers.index')
            ->with('success', 'Tedarikçi başarıyla eklendi.');
    }

    public function show(Supplier $supplier)
    {
        $parts = Part::where('supplier', $supplier->name)->latest()->paginate(15);
        $totalStock = Part::where('supplier', $supplier->name)->sum('stock');
        $totalValue = Part::where('supplier', $supplier->name)->get()->sum(fn($p) => (float)$p->stock * (float)($p->buy_price ?? 0));

        return view('suppliers.show', compact('supplier', 'parts', 'totalStock', 'totalValue'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:150',
            'phone'          => 'nullable|string|max:30',
            'email'          => 'nullable|email|max:150',
            'address'        => 'nullable|string|max:500',
            'tax_office'     => 'nullable|string|max:150',
            'tax_no'         => 'nullable|string|max:50',
            'notes'          => 'nullable|string',
        ]);

        $oldName = $supplier->name;
        $supplier->update($data);

        // If supplier name changed, optionally update parts supplier name in same branch
        if ($oldName !== $data['name']) {
            Part::where('supplier', $oldName)->update(['supplier' => $data['name']]);
        }

        return redirect()->route('suppliers.index')
            ->with('success', 'Tedarikçi bilgileri güncellendi.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Tedarikçi silindi.');
    }
}
