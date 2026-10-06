<x-app-layout>
    <x-slot name="title">{{ $supplier->name }} — Tedarikçi Detayı</x-slot>

    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('suppliers.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 flex items-center justify-center transition-colors">
                    ←
                </a>
                <div>
                    <h2 class="text-xl font-black text-slate-900">{{ $supplier->name }}</h2>
                    <p class="text-xs text-slate-500">Tedarikçi Profili ve Sağlanan Parçalar</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('suppliers.edit', $supplier) }}" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold px-3.5 py-2 rounded-xl border border-indigo-200 transition-colors">
                    ✏️ Düzenle
                </a>
                <a href="{{ route('parts.create') }}?supplier={{ urlencode($supplier->name) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-3.5 py-2 rounded-xl shadow-xs transition-colors">
                    + Bu Tedarikçiye Parça Ekle
                </a>
            </div>
        </div>

        <!-- Tedarikçi Bilgi Özeti -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-xs space-y-3">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">İletişim Bilgileri</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Yetkili:</span>
                        <strong class="text-slate-800">{{ $supplier->contact_person ?: '-' }}</strong>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Telefon:</span>
                        <span class="font-mono font-bold text-slate-800">{{ $supplier->phone ?: '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">E-Posta:</span>
                        <span class="text-slate-800">{{ $supplier->email ?: '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between py-1">
                        <span class="text-slate-500">Adres:</span>
                        <span class="text-slate-800 text-right max-w-[60%]">{{ $supplier->address ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-xs space-y-3">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Vergi & Fatura Bilgileri</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Vergi Dairesi:</span>
                        <strong class="text-slate-800">{{ $supplier->tax_office ?: '-' }}</strong>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Vergi Numarası:</span>
                        <span class="font-mono font-bold text-slate-800">{{ $supplier->tax_no ?: '-' }}</span>
                    </div>
                    <div class="pt-2">
                        <span class="text-slate-500 block mb-1">Özel Notlar:</span>
                        <p class="text-slate-700 bg-slate-50 p-2 rounded-xl text-[11px]">{{ $supplier->notes ?: 'Ek not bulunmuyor.' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-3">Stok Durumu & Envanter</h3>
                    <div class="space-y-3">
                        <div class="bg-indigo-50/60 border border-indigo-100 rounded-2xl p-3">
                            <span class="text-indigo-600 block text-[11px] font-bold">Toplam Stoktaki Parça</span>
                            <span class="text-2xl font-black text-indigo-950 font-mono">{{ number_format($totalStock, 0) }} Adet</span>
                        </div>
                        <div class="bg-emerald-50/60 border border-emerald-100 rounded-2xl p-3">
                            <span class="text-emerald-700 block text-[11px] font-bold">Toplam Alış Maliyeti Değeri</span>
                            <span class="text-2xl font-black text-emerald-950 font-mono">₺{{ number_format($totalValue, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bu Tedarikçiden Alınan Parçalar -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h3 class="text-sm font-black text-slate-800">Tedarikçiye Ait Parça Kataloğu</h3>
                <span class="text-xs text-slate-400 font-bold">{{ $parts->total() }} Kalem Parça</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-extrabold uppercase text-[10px] border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3">Parça Adı</th>
                            <th class="px-5 py-3">Kod / OEM</th>
                            <th class="px-5 py-3">Kategori</th>
                            <th class="px-5 py-3 text-right">Alış Fiyatı</th>
                            <th class="px-5 py-3 text-right">Satış Fiyatı</th>
                            <th class="px-5 py-3 text-center">Mevcut Stok</th>
                            <th class="px-5 py-3 text-right">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($parts as $part)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3 font-bold text-slate-900">
                                    <a href="{{ route('parts.show', $part) }}" class="hover:text-indigo-600">
                                        {{ $part->name }}
                                    </a>
                                </td>
                                <td class="px-5 py-3 font-mono text-slate-500">
                                    {{ $part->code ?: '-' }}
                                    @if($part->oem_code) <span class="text-[10px] text-slate-400 block">OEM: {{ $part->oem_code }}</span> @endif
                                </td>
                                <td class="px-5 py-3 text-slate-600">{{ $part->category ?: '-' }}</td>
                                <td class="px-5 py-3 text-right font-mono text-slate-700">₺{{ number_format($part->buy_price, 2, ',', '.') }}</td>
                                <td class="px-5 py-3 text-right font-mono font-bold text-emerald-600">₺{{ number_format($part->sell_price, 2, ',', '.') }}</td>
                                <td class="px-5 py-3 text-center">
                                    <span class="px-2.5 py-0.5 rounded-lg font-bold font-mono text-[11px] {{ $part->stock <= $part->min_stock ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-800' }}">
                                        {{ $part->stock }} Adet
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('parts.show', $part) }}" class="text-indigo-600 font-bold hover:underline">İncele →</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-slate-400">
                                    Bu tedarikçiye ait kayıtlı parça bulunamadı.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($parts->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $parts->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
