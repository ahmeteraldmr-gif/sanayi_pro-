<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount('users')->get();
        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('admin.branches.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Branch::create(['name' => $request->name]);
        return redirect()->route('admin.branches.index')->with('success', 'Şube (Sanayi Dalı) eklendi.');
    }

    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $branch->update(['name' => $request->name]);
        return redirect()->route('admin.branches.index')->with('success', 'Şube güncellendi.');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->users()->count() > 0) {
            return back()->with('error', 'Bu şubeye kayıtlı ustalar var. Önce kullanıcıları silin veya şubelerini değiştirin.');
        }
        $branch->delete();
        return redirect()->route('admin.branches.index')->with('success', 'Şube silindi.');
    }
}
