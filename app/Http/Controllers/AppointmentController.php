<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $dateStr = $request->date ?? today()->toDateString();
        $date = Carbon::parse($dateStr);

        $appointments = Appointment::with(['vehicle.customer', 'user', 'workOrder'])
            ->whereDate('start_time', $date)
            ->orderBy('start_time')
            ->get();

        $vehicles = Vehicle::with('customer')->latest()->get();
        $masters = User::where('branch_id', auth()->user()->branch_id)->orderBy('name')->get();

        // 08:00 - 19:00 Saat dilimlerini oluştur
        $timeSlots = [];
        for ($hour = 8; $hour <= 19; $hour++) {
            $slotStr = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
            $timeSlots[$slotStr] = $appointments->filter(function ($app) use ($hour) {
                return $app->start_time->hour == $hour;
            });
        }

        return view('appointments.index', compact(
            'appointments', 'vehicles', 'masters', 'date', 'dateStr', 'timeSlots'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'start_time'  => 'required|date',
            'end_time'    => 'nullable|date|after_or_equal:start_time',
            'vehicle_id'  => 'nullable|exists:vehicles,id',
            'user_id'     => 'nullable|exists:users,id',
            'status'      => 'required|in:randevu,islemde,tamamlandi,iptal',
            'notes'       => 'nullable|string',
        ]);

        if (empty($data['end_time'])) {
            $data['end_time'] = Carbon::parse($data['start_time'])->addHour();
        }

        $appointment = Appointment::create($data);

        return redirect()->back()->with('success', 'Servis randevusu başarıyla takvime eklendi.');
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time'   => 'nullable|date',
            'user_id'    => 'nullable|exists:users,id',
            'status'     => 'required|in:randevu,islemde,tamamlandi,iptal',
            'notes'      => 'nullable|string',
        ]);

        $appointment->update($data);

        return redirect()->back()->with('success', 'Randevu güncellendi.');
    }

    // Sürükle-Bırak (Drag & Drop) saat/tarih güncelleme AJAX
    public function updateTime(Request $request, Appointment $appointment)
    {
        $request->validate([
            'start_time' => 'required|date',
        ]);

        $newStart = Carbon::parse($request->start_time);
        $durationMinutes = $appointment->end_time ? $appointment->start_time->diffInMinutes($appointment->end_time) : 60;
        $newEnd = (clone $newStart)->addMinutes($durationMinutes);

        $appointment->update([
            'start_time' => $newStart,
            'end_time'   => $newEnd,
        ]);

        return response()->json([
            'success'          => true,
            'appointment_id'   => $appointment->id,
            'new_start_time'   => $newStart->format('H:i'),
            'new_date'         => $newStart->format('d.m.Y'),
        ]);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->back()->with('success', 'Randevu takvimden silindi.');
    }
}
