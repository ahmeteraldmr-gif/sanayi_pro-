<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::with('customer');

        if ($request->search) {
            $search = $request->search;
            $query->where('plate', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $vehicles = $query->latest()->paginate(20);
        return view('vehicles.index', compact('vehicles'));
    }

    public function create(Request $request)
    {
        $customers = Customer::orderBy('name')->get();
        $selectedCustomer = $request->customer_id
            ? Customer::find($request->customer_id)
            : null;
        return view('vehicles.create', compact('customers', 'selectedCustomer'));
    }

    public function store(Request $request)
    {
        $customerMode = $request->input('customer_mode', 'existing');

        if ($customerMode === 'new' || $request->filled('new_customer_name')) {
            $custData = $request->validate([
                'new_customer_name'    => 'required|string|max:255',
                'new_customer_phone'   => 'nullable|string|max:20',
                'new_customer_address' => 'nullable|string|max:500',
            ], [
                'new_customer_name.required' => 'Yeni müşteri ad soyad alanı zorunludur.',
            ]);

            $customer = Customer::create([
                'name'    => $custData['new_customer_name'],
                'phone'   => $custData['new_customer_phone'] ?? null,
                'address' => $custData['new_customer_address'] ?? null,
            ]);
            $customerId = $customer->id;
        } else {
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
            ], [
                'customer_id.required' => 'Lütfen mevcut bir müşteri seçin veya yeni müşteri oluşturun.',
            ]);
            $customerId = $request->customer_id;
        }

        $branchId = auth()->user()->branch_id;

        $data = $request->validate([
            'plate'       => ['required', 'string', 'max:20', Rule::unique('vehicles', 'plate')->where(fn($q) => $branchId ? $q->where('branch_id', $branchId) : $q->where('user_id', auth()->id()))],
            'brand'       => 'nullable|string|max:100',
            'model'       => 'nullable|string|max:100',
            'engine'      => 'nullable|string|max:100',
            'year'        => 'nullable|string|max:4',
            'mileage'     => 'nullable|integer|min:0',
            'color'       => 'nullable|string|max:50',
            'notes'       => 'nullable|string',
        ], [
            'plate.required' => 'Plaka alanı zorunludur.',
            'plate.unique'   => 'Bu plaka atölyenizde zaten kayıtlı.',
        ]);

        $data['customer_id'] = $customerId;
        $data['plate']       = strtoupper(trim($data['plate']));

        Vehicle::create($data);

        return redirect()->route('vehicles.index')
            ->with('success', 'Araç ve müşteri kaydı başarıyla oluşturuldu.');
    }

    public function show(Vehicle $vehicle, \App\Services\VehicleHealthService $healthService)
    {
        $vehicle->load(['customer', 'workOrders' => function ($q) {
            $q->with(['items', 'user'])->latest('date');
        }]);

        $healthReport = $healthService->generateHealthReport($vehicle);

        return view('vehicles.show', compact('vehicle', 'healthReport'));
    }

    public function edit(Vehicle $vehicle)
    {
        $customers = Customer::orderBy('name')->get();
        return view('vehicles.edit', compact('vehicle', 'customers'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $branchId = auth()->user()->branch_id;

        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'plate'       => ['required', 'string', 'max:20', Rule::unique('vehicles', 'plate')->where(fn($q) => $branchId ? $q->where('branch_id', $branchId) : $q->where('user_id', auth()->id()))->ignore($vehicle->id)],
            'brand'       => 'nullable|string|max:100',
            'model'       => 'nullable|string|max:100',
            'engine'      => 'nullable|string|max:100',
            'year'        => 'nullable|string|max:4',
            'mileage'     => 'nullable|integer|min:0',
            'color'       => 'nullable|string|max:50',
            'notes'       => 'nullable|string',
        ]);

        $data['plate'] = strtoupper($data['plate']);
        $vehicle->update($data);

        return redirect()->route('vehicles.index')
            ->with('success', 'Araç güncellendi.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('vehicles.index')
            ->with('success', 'Araç silindi.');
    }

    // AJAX: plaka ile ara
    public function search(Request $request)
    {
        $plate = strtoupper(trim($request->plate));
        $vehicle = Vehicle::with('customer')
            ->where('plate', $plate)
            ->first();

        if (!$vehicle) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found'    => true,
            'vehicle'  => [
                'id'    => $vehicle->id,
                'plate' => $vehicle->plate,
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'year'  => $vehicle->year,
                'color' => $vehicle->color,
            ],
            'customer' => $vehicle->customer ? [
                'id'    => $vehicle->customer->id,
                'name'  => $vehicle->customer->name,
                'phone' => $vehicle->customer->phone,
            ] : null,
        ]);
    }

    public function quickSearch(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $vehicles = Vehicle::with(['customer', 'workOrders'])
            ->where(function ($query) use ($q) {
                $query->where('plate', 'like', "%{$q}%")
                      ->orWhere('brand', 'like', "%{$q}%")
                      ->orWhere('model', 'like', "%{$q}%")
                      ->orWhereHas('customer', fn($sub) => $sub->where('name', 'like', "%{$q}%"));
            })
            ->limit(5)
            ->get();

        return response()->json($vehicles->map(function ($v) {
            $lastWo = $v->workOrders->sortByDesc('date')->first();
            $totalSpent = $v->workOrders->sum(fn($wo) => (float)$wo->grand_total);

            return [
                'id' => $v->id,
                'plate' => $v->plate,
                'brand' => $v->brand,
                'model' => $v->model,
                'year'  => $v->year,
                'customer' => $v->customer ? [
                    'id'    => $v->customer->id,
                    'name'  => $v->customer->name,
                    'phone' => $v->customer->phone,
                    'url'   => route('customers.show', $v->customer),
                ] : null,
                'last_service_date' => $lastWo ? $lastWo->date->format('d.m.Y') : 'Henüz Yok',
                'total_spent_formatted' => '₺' . number_format($totalSpent, 0, ',', '.'),
                'total_services_count' => $v->workOrders->count() . ' işlem',
                'url' => route('vehicles.show', $v),
                'create_work_order_url' => route('work-orders.create', ['vehicle_id' => $v->id]),
            ];
        }));
    }
}
