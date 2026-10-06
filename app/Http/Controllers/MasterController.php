<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function index()
    {
        $masters = User::all()->map(function ($u) {
            $workOrders = WorkOrder::where('user_id', $u->id)->get();
            $completedWO = $workOrders->where('status', 'tamamlandi');

            return [
                'user' => $u,
                'total_jobs' => $workOrders->count(),
                'completed_jobs' => $completedWO->count(),
                'active_jobs' => $workOrders->whereIn('status', ['beklemede', 'devam_ediyor'])->count(),
                'total_revenue' => $completedWO->sum(fn($wo) => (float)$wo->grand_total),
                'total_labor_revenue' => $completedWO->sum(fn($wo) => (float)$wo->labor_total),
            ];
        });

        return view('masters.index', compact('masters'));
    }

    public function updateRole(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isManager()) {
            abort(403, 'Rol değiştirmek için yetkiniz bulunmamaktadır.');
        }

        $request->validate([
            'role' => 'required|in:admin,manager,usta,cirak,accounting',
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', "{$user->name} kullanıcısının rolü güncellendi.");
    }
}
