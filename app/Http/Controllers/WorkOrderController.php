<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\PartMovement;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use App\Models\WorkOrderNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkOrder::with('vehicle.customer');

        if ($request->search) {
            $s = $request->search;
            $query->whereHas('vehicle', function ($q) use ($s) {
                $q->where('plate', 'like', "%{$s}%")
                  ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', "%{$s}%"));
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $orders = $query->latest('date')->paginate(20);

        $statusCounts = WorkOrder::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('work-orders.index', compact('orders', 'statusCounts'));
    }

    public function create(Request $request)
    {
        $parts = Part::where('stock', '>', 0)->orderBy('name')->get();
        $vehicles = Vehicle::with('customer')->latest()->get();
        $vehicle = $request->vehicle_id ? Vehicle::with('customer')->find($request->vehicle_id) : null;

        $vehiclesList = $vehicles->map(function ($v) {
            return [
                'id' => $v->id,
                'plate' => $v->plate,
                'brand' => $v->brand ?? '',
                'model' => $v->model ?? '',
                'year'  => $v->year ?? '',
                'customer' => [
                    'name'  => $v->customer->name ?? 'Müşterisiz',
                    'phone' => $v->customer->phone ?? ''
                ]
            ];
        });

        return view('work-orders.create', compact('parts', 'vehicles', 'vehiclesList', 'vehicle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'date'       => 'required|date',
            'status'     => 'required|in:arac_kabul,beklemede,ariza_tespiti,islem_basladi,devam_ediyor,parca_bekleniyor,kontrol,tamamlandi,teslim_edildi,odendi',
            'notes'      => 'nullable|string',
            'discount'   => 'nullable|numeric|min:0',
            'items'      => 'required|array|min:1',
            'items.*.type'       => 'required|in:part,labor',
            'items.*.name'       => 'required|string',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.part_id'    => 'nullable|exists:parts,id',
        ]);

        DB::transaction(function () use ($request) {
            $workOrder = WorkOrder::create([
                'vehicle_id' => $request->vehicle_id,
                'date'       => $request->date,
                'status'     => $request->status,
                'discount'   => $request->discount ?? 0,
                'notes'      => $request->notes,
                'total_parts'=> 0,
                'total_labor'=> 0,
            ]);

            foreach ($request->items as $item) {
                $total = $item['quantity'] * $item['unit_price'];

                WorkOrderItem::create([
                    'work_order_id' => $workOrder->id,
                    'part_id'       => $item['part_id'] ?? null,
                    'type'          => $item['type'],
                    'name'          => $item['name'],
                    'quantity'      => $item['quantity'],
                    'unit_price'    => $item['unit_price'],
                    'total'         => $total,
                ]);

                // Parça ise stoktan düş
                if ($item['type'] === 'part' && !empty($item['part_id'])) {
                    $part = Part::find($item['part_id']);
                    if ($part) {
                        $part->decrement('stock', $item['quantity']);
                        PartMovement::create([
                            'part_id'       => $part->id,
                            'work_order_id' => $workOrder->id,
                            'vehicle_id'    => $workOrder->vehicle_id,
                            'type'          => 'out',
                            'quantity'      => $item['quantity'],
                            'unit_price'    => $item['unit_price'],
                            'note'          => "İş emri #{$workOrder->id}",
                        ]);
                    }
                }
            }

            $workOrder->recalculate();
        });

        return redirect()->route('work-orders.index')
            ->with('success', 'İş emri oluşturuldu.');
    }

    public function show(WorkOrder $workOrder)
    {
        $workOrder->load(['vehicle.customer', 'items.part', 'tasks', 'user', 'notifications' => fn($q) => $q->latest()]);
        return view('work-orders.show', compact('workOrder'));
    }

    // Müşteriye bildirim şablonu önizleme
    public function notificationPreview(Request $request, WorkOrder $workOrder)
    {
        $statusKey = $request->status ?: $workOrder->status;
        $message = NotificationService::getMessageForStatus($workOrder, $statusKey);
        $phone = $workOrder->vehicle?->customer?->phone ?? '';
        $whatsappUrl = NotificationService::generateWhatsAppUrl($phone, $message);

        return response()->json([
            'success'      => true,
            'status_key'   => $statusKey,
            'phone'        => $phone,
            'message'      => $message,
            'whatsapp_url' => $whatsappUrl,
        ]);
    }

    // Gönderilen bildirimi veritabanına kaydetme
    public function logNotification(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'channel'    => 'required|string',
            'status_key' => 'nullable|string',
            'message'    => 'required|string',
        ]);

        $phone = $workOrder->vehicle?->customer?->phone ?? '';

        $notification = NotificationService::logNotification(
            $workOrder,
            $request->channel,
            $request->status_key ?: $workOrder->status,
            $phone,
            $request->message
        );

        return response()->json(['success' => true, 'notification' => $notification]);
    }

    public function addTask(Request $request, WorkOrder $workOrder)
    {
        $request->validate(['title' => 'required|string|max:255']);
        $task = $workOrder->tasks()->create([
            'title' => $request->title,
            'is_completed' => false,
        ]);
        return response()->json(['success' => true, 'task' => $task]);
    }

    public function toggleTask(\App\Models\WorkOrderTask $task)
    {
        $task->update([
            'is_completed' => !$task->is_completed,
            'completed_at' => !$task->is_completed ? now() : null,
        ]);
        return response()->json(['success' => true, 'is_completed' => $task->is_completed]);
    }

    public function deleteTask(\App\Models\WorkOrderTask $task)
    {
        $task->delete();
        return response()->json(['success' => true]);
    }

    public function edit(WorkOrder $workOrder)
    {
        $workOrder->load(['vehicle.customer', 'items.part']);
        $parts = Part::orderBy('name')->get();
        return view('work-orders.edit', compact('workOrder', 'parts'));
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'status'   => 'required|in:arac_kabul,beklemede,ariza_tespiti,islem_basladi,devam_ediyor,parca_bekleniyor,kontrol,tamamlandi,teslim_edildi,odendi',
            'date'     => 'required|date',
            'notes'    => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
            'items'    => 'required|array|min:1',
            'items.*.type'       => 'required|in:part,labor',
            'items.*.name'       => 'required|string',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.part_id'    => 'nullable|exists:parts,id',
        ]);

        DB::transaction(function () use ($request, $workOrder) {
            // Eski parça stokları geri yükle
            foreach ($workOrder->items as $item) {
                if ($item->type === 'part' && $item->part_id) {
                    Part::where('id', $item->part_id)->increment('stock', $item->quantity);
                }
            }

            // Eski hareket kayıtlarını sil
            $workOrder->partMovements()->delete();
            $workOrder->items()->delete();

            $workOrder->update([
                'date'     => $request->date,
                'status'   => $request->status,
                'discount' => $request->discount ?? 0,
                'notes'    => $request->notes,
            ]);

            // Yeni kalemleri ekle
            foreach ($request->items as $item) {
                $total = $item['quantity'] * $item['unit_price'];
                WorkOrderItem::create([
                    'work_order_id' => $workOrder->id,
                    'part_id'       => $item['part_id'] ?? null,
                    'type'          => $item['type'],
                    'name'          => $item['name'],
                    'quantity'      => $item['quantity'],
                    'unit_price'    => $item['unit_price'],
                    'total'         => $total,
                ]);

                if ($item['type'] === 'part' && !empty($item['part_id'])) {
                    $part = Part::find($item['part_id']);
                    if ($part) {
                        $part->decrement('stock', $item['quantity']);
                        PartMovement::create([
                            'part_id'       => $part->id,
                            'work_order_id' => $workOrder->id,
                            'vehicle_id'    => $workOrder->vehicle_id,
                            'type'          => 'out',
                            'quantity'      => $item['quantity'],
                            'unit_price'    => $item['unit_price'],
                            'note'          => "İş emri #{$workOrder->id} (güncelleme)",
                        ]);
                    }
                }
            }

            $workOrder->recalculate();
        });

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'İş emri güncellendi.');
    }

    public function destroy(WorkOrder $workOrder)
    {
        DB::transaction(function () use ($workOrder) {
            // Stokları geri yükle
            foreach ($workOrder->items as $item) {
                if ($item->type === 'part' && $item->part_id) {
                    Part::where('id', $item->part_id)->increment('stock', $item->quantity);
                }
            }
            $workOrder->partMovements()->delete();
            $workOrder->items()->delete();
            $workOrder->delete();
        });

        return redirect()->route('work-orders.index')
            ->with('success', 'İş emri silindi.');
    }

    public function updateStatus(Request $request, WorkOrder $workOrder)
    {
        $request->validate(['status' => 'required|in:arac_kabul,beklemede,ariza_tespiti,islem_basladi,devam_ediyor,parca_bekleniyor,kontrol,tamamlandi,teslim_edildi,odendi']);

        $data = ['status' => $request->status];
        if (($request->status === 'odendi' || $request->status === 'teslim_edildi') && $workOrder->date->lt(today())) {
            $data['date'] = today();
        }

        $workOrder->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status'  => $workOrder->status,
                'step'    => $workOrder->status_step,
                'label'   => $workOrder->status_label,
            ]);
        }

        return redirect()->back()
            ->with('success', 'İş emri durumu "' . $workOrder->status_label . '" olarak güncellendi.');
    }

    public function print(WorkOrder $workOrder)
    {
        $workOrder->load(['vehicle.customer', 'items.part', 'branch']);
        return view('work-orders.print', compact('workOrder'));
    }
}
