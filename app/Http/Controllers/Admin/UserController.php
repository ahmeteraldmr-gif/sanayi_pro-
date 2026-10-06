<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('branch')->orderBy('id', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('admin.users.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => ['required', Rule::in(['admin', 'manager', 'usta', 'cirak', 'accounting', 'branch_user'])],
            'branch_id' => 'required_if:role,branch_user|nullable|exists:branches,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'branch_id' => $request->role === 'admin' ? null : $request->branch_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    public function edit(User $user)
    {
        $branches = Branch::all();
        return view('admin.users.edit', compact('user', 'branches'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role' => ['required', Rule::in(['admin', 'manager', 'usta', 'cirak', 'accounting', 'branch_user'])],
            'branch_id' => 'required_if:role,branch_user|nullable|exists:branches,id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'branch_id' => $request->role === 'admin' ? null : $request->branch_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı güncellendi.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kendinizi silemezsiniz.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Kullanıcı silindi.');
    }
}
