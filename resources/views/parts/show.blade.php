<x-app-layout>
    <x-slot name="title">{{ $part->name }} — Stok & Parça Profili</x-slot>

    <div class="space-y-6">
        <!-- Top Nav & Title -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('parts.index') }}" class="p-2 rounded-xl bg-slate-900 text-slate-400 hover:text-white border border-slate-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-black text-white tracking-tight uppercase">{{ $part->name }}</h1>
                        @if($part->brand)
                            <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                {{ $part->brand }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 font-mono mt-0.5">
                        @if($part->code) KOD: <span class="text-slate-200">{{ $part->code }}</span> @endif
                        @if($part->oem_code) <span class="mx-1">•</span> OEM: <span class="text-indigo-400 font-bold">{{ $part->oem_code }}</span> @endif
                        @if($part->category) <span class="mx-1">•</span> KATEGORİ: <span class="text-slate-300">{{ $part->category }}</span> @endif
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2" x-data="{ openModal: false }">
                <button @click="openModal = true" class="bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-4 py-2 rounded-xl text-sm transition-colors flex items-center gap-2 shadow-lg shadow-emerald-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    + Stok Ekle
                </button>
                <a href="{{ route('parts.edit', $part) }}" class="bg-slate-800 hover:bg-slate-700 text-slate-200 px-4 py-2 rounded-xl text-sm font-medium border border-slate-700 transition-colors">
                    Düzenle
                </a>

                <!-- Quick Add Stock Modal -->
                <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 w-full max-w-md shadow-2xl" @click.stop>
                        <h3 class="font-bold text-white text-lg mb-1">Stok Girişi Yap</h3>
                        <p class="text-xs text-slate-400 mb-4">{{ $part->name }} için yeni parçalar ekleyin.</p>
                        <form method="POST" action="{{ route('parts.add-stock', $part) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-medium text-slate-300 mb-1">Eklenen Miktar *</label>
                                <input type="number" name="quantity" min="1" required placeholder="Örn: 10"
                                       class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-300 mb-1">Birim Alış Fiyatı (₺)</label>
                                <input type="number" name="unit_price" step="0.01" value="{{ $part->buy_price }}"
                                       class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-300 mb-1">Açıklama / Fatura / Tedarikçi</label>
                                <input type="text" name="note" placeholder="Örn: Satın alma (Fatura No: 4082)"
                                       class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            </div>
                            <div class="flex gap-2 pt-2">
                                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-medium py-2.5 rounded-xl text-sm transition-colors">
                                    Kaydet & Stoğa İşle
                                </button>
                                <button type="button" @click="openModal = false" class="px-4 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-sm transition-colors">
                                    Vazgeç
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Cards Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Stok Kartı -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Mevcut Stok</span>
                    @if($part->isLowStock())
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 flex items-center gap-1">
                            ⚠ Kritik Stok
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            Stokta Var
                        </span>
                    @endif
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-4xl font-black text-white tracking-tight">{{ $part->stock }}</span>
                    <span class="text-sm font-medium text-slate-400">Adet</span>
                </div>
                <p class="text-[11px] text-slate-500 mt-2">Kritik stok eşiği: <strong class="text-slate-400">{{ $part->min_stock }} adet</strong></p>
            </div>

            <!-- Alış Fiyatları -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Alış & Son Alış</span>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-slate-200">₺{{ number_format($part->buy_price, 2, ',', '.') }}</span>
                </div>
                <p class="text-xs text-slate-400 mt-2 flex items-center justify-between">
                    <span>Son Alış Fiyatı:</span>
                    <strong class="text-slate-200 font-mono">₺{{ number_format($part->last_buy_price ?: $part->buy_price, 2, ',', '.') }}</strong>
                </p>
            </div>

            <!-- Satış Fiyatı & Kâr -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-400">Satış Fiyatı</span>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl font-black text-emerald-400">₺{{ number_format($part->sell_price, 2, ',', '.') }}</span>
                </div>
                <p class="text-xs text-slate-400 mt-2 flex items-center justify-between">
                    <span>Tahmini Kâr Marjı:</span>
                    <strong class="text-emerald-400 font-bold">%{{ $part->profit_margin }} (₺{{ number_format($part->profit, 2, ',', '.') }})</strong>
                </p>
            </div>

            <!-- Tedarikçi & Uyum -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                <span class="text-xs font-semibold uppercase tracking-wider text-indigo-400">Tedarikçi Bilgisi</span>
                <div class="mt-3">
                    <span class="text-base font-bold text-white block truncate">{{ $part->supplier ?: 'Tedarikçi Belirtilmedi' }}</span>
                </div>
                <p class="text-xs text-slate-400 mt-2 truncate">
                    Marka: <strong class="text-slate-200">{{ $part->brand ?: 'Genel' }}</strong>
                </p>
            </div>
        </div>

        <!-- Uyumlu Araçlar & Notlar Panel -->
        @if($part->compatible_vehicles || $part->notes)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($part->compatible_vehicles)
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-indigo-400 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1m-4 0a1 1 0 01-1-1"/>
                            </svg>
                            Uyumlu Araçlar
                        </h3>
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            @foreach(explode(',', $part->compatible_vehicles) as $v)
                                <span class="bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 px-3 py-1 rounded-xl text-xs font-medium">
                                    🚗 {{ trim($v) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($part->notes)
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-amber-400 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Özel Notlar
                        </h3>
                        <p class="text-xs text-slate-300 leading-relaxed mt-1">{{ $part->notes }}</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Stok Hareket Geçmişi Ledger -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-white text-base">Stok Hareketleri</h3>
                    <p class="text-xs text-slate-400">Satın alma girişleri ve iş emri araç çıkışları</p>
                </div>
                <span class="text-xs font-mono bg-slate-800 text-slate-300 px-3 py-1 rounded-lg border border-slate-700">
                    Toplam {{ $movements->total() }} Hareket
                </span>
            </div>

            <div class="divide-y divide-slate-800/60">
                @forelse($movements as $m)
                    <div class="p-4 hover:bg-slate-800/40 transition-colors flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm border flex-shrink-0
                                {{ $m->type === 'in' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border-rose-500/20' }}">
                                {{ $m->type === 'in' ? '+' : '-' }}{{ $m->quantity }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-white text-sm">
                                        @if($m->type === 'in')
                                            {{ $m->note ?? 'Satın Alma' }}
                                        @else
                                            @php
                                                $vehicleObj = $m->vehicle ?? ($m->workOrder ? $m->workOrder->vehicle : null);
                                            @endphp
                                            @if($vehicleObj)
                                                {{ $vehicleObj->brand }} {{ $vehicleObj->model }}
                                                <span class="text-xs font-mono text-indigo-400 bg-indigo-500/10 px-2 py-0.5 rounded border border-indigo-500/20 ml-1">
                                                    {{ $vehicleObj->plate }}
                                                </span>
                                            @else
                                                İş Emri Kullanımı
                                            @endif
                                        @endif
                                    </span>
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5 flex items-center gap-2">
                                    <span>{{ $m->created_at->translatedFormat('d F Y, H:i') }}</span>
                                    @if($m->workOrder)
                                        <span>•</span>
                                        <a href="{{ route('work-orders.show', $m->workOrder) }}" class="text-indigo-400 hover:underline">
                                            İş Emri #{{ $m->workOrder->id }}
                                        </a>
                                    @endif
                                    @if($m->note && $m->type !== 'in')
                                        <span>•</span>
                                        <span class="text-slate-500">{{ $m->note }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-sm font-bold text-slate-200">
                                ₺{{ number_format($m->unit_price, 2, ',', '.') }}
                            </div>
                            <span class="text-[11px] text-slate-500">Birim Fiyat</span>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-slate-500">
                        <svg class="w-12 h-12 mx-auto text-slate-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="font-medium text-slate-400">Henüz stok hareketi kaydedilmedi.</p>
                        <p class="text-xs text-slate-600 mt-1">Stok girişi butonunu kullanarak satın alma kaydı ekleyebilirsiniz.</p>
                    </div>
                @endforelse
            </div>

            @if($movements->hasPages())
                <div class="p-4 border-t border-slate-800 bg-slate-950/50">
                    {{ $movements->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
