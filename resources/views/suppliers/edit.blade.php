<x-app-layout>
    <x-slot name="title">Tedarikçi Düzenle — {{ $supplier->name }}</x-slot>

    <div class="max-w-3xl mx-auto space-y-6">

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('suppliers.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 flex items-center justify-center transition-colors">
                    ←
                </a>
                <div>
                    <h2 class="text-lg font-black text-slate-900">Tedarikçi Bilgilerini Düzenle</h2>
                    <p class="text-xs text-slate-500">{{ $supplier->name }}</p>
                </div>
            </div>

            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Bu tedarikçiyi silmek istediğinizden emin misiniz? Parça kayıtları korunacaktır.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 font-bold px-3 py-2 rounded-xl transition-colors border border-red-200">
                    🗑️ Tedarikçiyi Sil
                </button>
            </form>
        </div>

        <form action="{{ route('suppliers.update', $supplier) }}" method="POST" class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Firma / Tedarikçi Adı *</label>
                    <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required
                           class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Yetkili Kişi (Plasiyer / Temsilci)</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}"
                           class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Telefon Numarası</label>
                    <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                           class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">E-Posta Adresi</label>
                    <input type="email" name="email" value="{{ old('email', $supplier->email) }}"
                           class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Vergi Dairesi / No</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" name="tax_office" value="{{ old('tax_office', $supplier->tax_office) }}" placeholder="V. Dairesi"
                               class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <input type="text" name="tax_no" value="{{ old('tax_no', $supplier->tax_no) }}" placeholder="Vergi No"
                               class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Adres</label>
                    <textarea name="address" rows="2"
                              class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('address', $supplier->address) }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Notlar / Çalışma Koşulları (Opsiyonel)</label>
                    <textarea name="notes" rows="2"
                              class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes', $supplier->notes) }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                <a href="{{ route('suppliers.index') }}" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-2.5 rounded-xl transition-colors">
                    İptal
                </a>
                <button type="submit" class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-5 py-2.5 rounded-xl shadow-md transition-colors">
                    Değişiklikleri Kaydet
                </button>
            </div>
        </form>

    </div>
</x-app-layout>
