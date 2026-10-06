<x-app-layout>
    <x-slot name="title">Parça Düzenle — {{ $part->name }}</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('parts.index') }}" class="text-slate-400 hover:text-slate-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-white">Parça Düzenle: {{ $part->name }}</h2>
                <p class="text-xs text-slate-400">Ürün ve stok detaylarını güncelleyin</p>
            </div>
        </div>

        <div class="bg-slate-900 rounded-2xl border border-slate-800 shadow-xl p-6">
            <form method="POST" action="{{ route('parts.update', $part) }}" class="space-y-6">
                @csrf @method('PUT')

                <!-- Temel Bilgiler -->
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-indigo-400 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Parça Kimliği & Kodlar
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-slate-300 mb-1">Parça Adı *</label>
                            <input type="text" name="name" value="{{ old('name', $part->name) }}" required
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">Marka</label>
                            <input type="text" name="brand" value="{{ old('brand', $part->brand) }}"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="Mann-Filter, Bosch...">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">Kategori</label>
                            <input type="text" name="category" value="{{ old('category', $part->category) }}"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="Filtre, Fren, Motor...">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">Parça Kodu (İç Kodu)</label>
                            <input type="text" name="code" value="{{ old('code', $part->code) }}"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">OEM / Orijinal Kod</label>
                            <input type="text" name="oem_code" value="{{ old('oem_code', $part->oem_code) }}"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <hr class="border-slate-800">

                <!-- Tedarikçi & Uyumlu Araçlar -->
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-emerald-400 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Tedarikçi & Uyumlu Araçlar
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">Tedarikçi Firma</label>
                            <input type="text" name="supplier" value="{{ old('supplier', $part->supplier) }}"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">Uyumlu Araçlar</label>
                            <input type="text" name="compatible_vehicles" value="{{ old('compatible_vehicles', $part->compatible_vehicles) }}"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                   placeholder="BMW 320d, Audi A4...">
                        </div>
                    </div>
                </div>

                <hr class="border-slate-800">

                <!-- Fiyatlandırma & Stok Seviyeleri -->
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-amber-400 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Fiyatlandırma & Kritik Stok
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">Alış Fiyatı (₺)</label>
                            <input type="number" name="buy_price" value="{{ old('buy_price', $part->buy_price) }}" step="0.01" min="0"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">Son Alış Fiyatı (₺)</label>
                            <input type="number" name="last_buy_price" value="{{ old('last_buy_price', $part->last_buy_price) }}" step="0.01" min="0"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">Satış Fiyatı (₺) *</label>
                            <input type="number" name="sell_price" value="{{ old('sell_price', $part->sell_price) }}" step="0.01" min="0" required
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white font-semibold text-emerald-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">Kritik Stok Seviyesi (Uyarı Eşiği)</label>
                            <input type="number" name="min_stock" value="{{ old('min_stock', $part->min_stock) }}" min="0"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-300 mb-1">Ek Notlar</label>
                            <input type="text" name="notes" value="{{ old('notes', $part->notes) }}"
                                   class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-4 flex items-center justify-between text-xs text-amber-300">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Stok miktarını değiştirmek için "Stok Girişi" veya "İş Emri" hareketlerini kullanın.</span>
                    </div>
                    <span class="font-bold bg-amber-500/20 px-2.5 py-1 rounded-lg text-amber-200">Mevcut Stok: {{ $part->stock }} Adet</span>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-slate-800">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-6 py-2.5 rounded-xl text-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Güncelle
                    </button>
                    <a href="{{ route('parts.index') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-6 py-2.5 rounded-xl text-sm font-medium transition-colors">
                        İptal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
