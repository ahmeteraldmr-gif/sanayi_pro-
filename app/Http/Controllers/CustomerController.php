<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount('vehicles')
            ->with('vehicles');

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
        }

        $customers = $query->latest()->paginate(20);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'notes'   => 'nullable|string',
        ]);

        Customer::create($data);
        return redirect()->route('customers.index')
            ->with('success', 'Müşteri başarıyla eklendi.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['vehicles.workOrders' => function ($q) {
            $q->latest()->limit(5);
        }]);
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'notes'   => 'nullable|string',
        ]);

        $customer->update($data);
        return redirect()->route('customers.index')
            ->with('success', 'Müşteri güncellendi.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->vehicles()->exists()) {
            return back()->with('error', 'Bu müşteriye ait kayıtlı araçlar bulunmaktadır. Müşteriyi silmeden önce lütfen ilişkili araçları başka bir müşteriye aktarın veya silin.');
        }

        $customer->delete();
        return redirect()->route('customers.index')
            ->with('success', 'Müşteri başarıyla silindi.');
    }
}
