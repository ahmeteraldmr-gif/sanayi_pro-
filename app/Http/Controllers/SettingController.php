<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $branch = $user->branch;
        return view('settings.index', compact('user', 'branch'));
    }

    public function update(Request $request)
    {
        $branch = auth()->user()->branch;

        if (!$branch) {
            return back()->with('error', 'Kullanıcıya atanmış bir atölye/branş bulunamadı.');
        }

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'title'            => 'nullable|string|max:255',
            'description'      => 'nullable|string|max:500',
            'phone'            => 'nullable|string|max:30',
            'address'          => 'nullable|string|max:500',
            'tax_office'       => 'nullable|string|max:150',
            'tax_no'           => 'nullable|string|max:50',
            'receipt_footer'   => 'nullable|string|max:500',
            'whatsapp_message' => 'nullable|string|max:500',
            'currency'         => 'nullable|string|max:10',
        ]);

        $branch->update($data);

        return back()->with('success', 'Atölye ve sistem ayarlarınız başarıyla kaydedildi.');
    }
}
