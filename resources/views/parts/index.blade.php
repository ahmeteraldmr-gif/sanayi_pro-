<x-app-layout>
    <x-slot name="title">Parçalar & Stok Yönetimi</x-slot>

    <div class="space-y-6">
        <!-- Top Header & Stats -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-white tracking-tight">PARÇALAR & STOK YÖNETİMİ</h1>
                <p class="text-xs text-slate-400 mt-1">
                    Atölye envanteri, kritik stok takibi, OEM kodları ve araç uyumluluk rehberi
                </p>
            </div>
            <div class="flex items-center gap-3">
                @if($lowStockCount > 0)
                    <a href="{{ route('parts.index', ['low_stock' => 1]) }}"
                       class="bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 text-amber-300 px-3.5 py-2 rounded-xl text-xs font-bold transition-colors flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                        ⚠ {{ $lowStockCount }} Kritik Stok Uyarısı
                    </a>
                @endif
                <a href="{{ route('parts.create') }}"
                   class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium px-4 py-2 rounded-xl text-sm transition-colors flex items-center gap-2 shadow-lg shadow-indigo-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Yeni Parça Ekle
                </a>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 shadow-xl">
            <form method="GET" action="{{ route('parts.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Parça adı, Marka, OEM kodu, İç kod, Tedarikçi veya Uyumlu Araç ara..."
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2 text-xs font-medium text-slate-300 bg-slate-950 border border-slate-800 px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-800 transition-colors">
                        <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }}
                               class="rounded border-slate-700 bg-slate-900 text-amber-500 focus:ring-amber-500">
                        <span>Sadece Kritik Stok</span>
                    </label>
                    <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-slate-200 px-5 py-2.5 rounded-xl text-sm font-medium border border-slate-700 transition-colors">
                        Ara
                    </button>
                    @if(request('search') || request('low_stock'))
                        <a href="{{ route('parts.index') }}" class="text-xs text-slate-400 hover:text-white underline">Temizle</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Parts Table & Mobil Kartlar -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
            <!-- Masaüstü Tablo -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-950 text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="px-5 py-3.5">Parça / Marka</th>
                            <th class="px-5 py-3.5">OEM & Kodlar</th>
                            <th class="px-5 py-3.5">Tedarikçi & Uyum</th>
                            <th class="px-5 py-3.5 text-right">Alış ₺</th>
                            <th class="px-5 py-3.5 text-right">Satış ₺</th>
                            <th class="px-5 py-3.5 text-center">Stok Durumu</th>
                            <th class="px-5 py-3.5 text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($parts as $part)
                            <tr class="hover:bg-slate-800/40 transition-colors {{ $part->isLowStock() ? 'bg-amber-500/5' : '' }}">
                                <!-- Parça & Marka -->
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('parts.show', $part) }}" class="font-bold text-white hover:text-indigo-400 transition-colors">
                                            {{ $part->name }}
                                        </a>
                                        @if($part->brand)
                                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                                {{ $part->brand }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($part->category)
                                        <span class="text-xs text-slate-400 mt-0.5 block">{{ $part->category }}</span>
                                    @endif
                                </td>

                                <!-- OEM & Kodlar -->
                                <td class="px-5 py-3.5">
                                    @if($part->oem_code)
                                        <div class="font-mono text-xs font-semibold text-indigo-300">
                                            <span class="text-slate-500 font-normal">OEM:</span> {{ $part->oem_code }}
                                        </div>
                                    @endif
                                    @if($part->code)
                                        <div class="font-mono text-xs text-slate-400 mt-0.5">
                                            <span class="text-slate-500 font-normal">KOD:</span> {{ $part->code }}
                                        </div>
                                    @endif
                                    @if(!$part->oem_code && !$part->code)
                                        <span class="text-xs text-slate-600">-</span>
                                    @endif
                                </td>

                                <!-- Tedarikçi & Uyum -->
                                <td class="px-5 py-3.5">
                                    @if($part->supplier)
                                        <div class="text-xs text-slate-300 font-medium">{{ $part->supplier }}</div>
                                    @endif
                                    @if($part->compatible_vehicles)
                                        <div class="text-[11px] text-slate-400 truncate max-w-[200px]" title="{{ $part->compatible_vehicles }}">
                                            🚗 {{ $part->compatible_vehicles }}
                                        </div>
                                    @endif
                                    @if(!$part->supplier && !$part->compatible_vehicles)
                                        <span class="text-xs text-slate-600">-</span>
                                    @endif
                                </td>

                                <!-- Alış Fiyatı -->
                                <td class="px-5 py-3.5 text-right font-mono text-xs text-slate-400">
                                    ₺{{ number_format($part->buy_price, 2, ',', '.') }}
                                    @if($part->last_buy_price && $part->last_buy_price != $part->buy_price)
                                        <div class="text-[10px] text-slate-500" title="Son Alış Fiyatı">
                                            Son: ₺{{ number_format($part->last_buy_price, 2, ',', '.') }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Satış Fiyatı -->
                                <td class="px-5 py-3.5 text-right">
                                    <div class="font-bold text-emerald-400 font-mono text-sm">
                                        ₺{{ number_format($part->sell_price, 2, ',', '.') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400">
                                        %{{ $part->profit_margin }} Marj
                                    </div>
                                </td>

                                <!-- Stok Durumu -->
                                <td class="px-5 py-3.5 text-center">
                                    @if($part->stock == 0)
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30 inline-flex items-center gap-1">
                                            Stok Yok (0)
                                        </span>
                                    @elseif($part->isLowStock())
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 inline-flex items-center gap-1">
                                            ⚠ Kritik: {{ $part->stock }} <span class="text-[10px] opacity-75">(min {{ $part->min_stock }})</span>
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 inline-flex items-center gap-1">
                                            {{ $part->stock }} Adet
                                        </span>
                                    @endif
                                </td>

                                <!-- İşlemler -->
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1.5" x-data="{ openAddStock: false }">
                                        <!-- Hızlı Stok Ekle Modal -->
                                        <button @click="openAddStock = true" title="Stok Ekle"
                                                class="bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-2.5 py-1 rounded-lg text-xs font-bold transition-colors">
                                            + Stok
                                        </button>

                                        <div x-show="openAddStock" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 text-left">
                                            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 w-full max-w-sm shadow-2xl" @click.stop>
                                                <h3 class="font-bold text-white text-base mb-1">Stok Girişi: {{ $part->name }}</h3>
                                                <p class="text-xs text-slate-400 mb-4">Parça stoğunu artırın ve alış fiyatını kaydedin.</p>
                                                <form method="POST" action="{{ route('parts.add-stock', $part) }}" class="space-y-3.5">
                                                    @csrf
                                                    <div>
                                                        <label class="block text-xs font-medium text-slate-300 mb-1">Eklenecek Miktar *</label>
                                                        <input type="number" name="quantity" min="1" required placeholder="Örn: 20"
                                                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-slate-300 mb-1">Alış Fiyatı (₺)</label>
                                                        <input type="number" name="unit_price" step="0.01" value="{{ $part->buy_price }}"
                                                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-slate-300 mb-1">Not / Fatura</label>
                                                        <input type="text" name="note" placeholder="Örn: Satın Alma"
                                                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                                    </div>
                                                    <div class="flex gap-2 pt-2">
                                                        <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-medium py-2 rounded-xl text-sm transition-colors">Stok Ekle</button>
                                                        <button type="button" @click="openAddStock = false" class="px-4 bg-slate-800 text-slate-300 rounded-xl text-sm hover:bg-slate-700 transition-colors">Vazgeç</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <a href="{{ route('parts.show', $part) }}" title="Stok Hareketleri" class="p-1.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </a>

                                        <a href="{{ route('parts.edit', $part) }}" title="Düzenle" class="p-1.5 text-slate-400 hover:text-indigo-400 hover:bg-slate-800 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <form method="POST" action="{{ route('parts.destroy', $part) }}" onsubmit="return confirm('Bu parçayı silmek istediğinizden emin misiniz?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Sil" class="p-1.5 text-slate-400 hover:text-rose-400 hover:bg-slate-800 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-slate-500">
                                    <svg class="w-12 h-12 mx-auto text-slate-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <p class="font-medium text-slate-400">Aranan kriterlere uygun parça bulunamadı.</p>
                                    <a href="{{ route('parts.create') }}" class="text-xs text-indigo-400 hover:underline mt-2 inline-block">Yeni parça ekleyin &rarr;</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobil Parça Kartları -->
            <div class="md:hidden divide-y divide-slate-800/80">
                @forelse($parts as $part)
                    <div class="p-4 space-y-3 hover:bg-slate-800/30 transition-colors {{ $part->isLowStock() ? 'bg-amber-500/5' : '' }}" x-data="{ openMobAddStock: false }">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <div class="font-bold text-white text-sm">
                                    {{ $part->name }}
                                </div>
                                <div class="flex items-center gap-1.5 flex-wrap mt-0.5">
                                    @if($part->brand)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                            {{ $part->brand }}
                                        </span>
                                    @endif
                                    @if($part->category)
                                        <span class="text-[11px] text-slate-400">{{ $part->category }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Stok Durum Rozeti -->
                            @if($part->stock == 0)
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30 shrink-0">
                                    Stok Yok (0)
                                </span>
                            @elseif($part->isLowStock())
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 shrink-0">
                                    ⚠ Kritik: {{ $part->stock }}
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 shrink-0">
                                    {{ $part->stock }} Adet
                                </span>
                            @endif
                        </div>

                        <!-- OEM & Fiyat Özeti -->
                        <div class="grid grid-cols-2 gap-2 text-xs bg-slate-950/60 p-2.5 rounded-xl border border-slate-800/80">
                            <div>
                                <span class="text-slate-500 text-[10px] block uppercase font-bold">Kod / OEM</span>
                                <span class="font-mono text-slate-300 text-xs truncate block">
                                    {{ $part->oem_code ?: ($part->code ?: '—') }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-slate-500 text-[10px] block uppercase font-bold">Satış Fiyatı</span>
                                <span class="font-mono font-bold text-emerald-400 text-sm">
                                    ₺{{ number_format($part->sell_price, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Mobil Aksiyonlar -->
                        <div class="flex items-center gap-2 pt-1">
                            <button type="button" @click="openMobAddStock = true"
                                    class="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs py-2 rounded-xl transition-colors text-center">
                                + Stok Ekle
                            </button>
                            <a href="{{ route('parts.show', $part) }}"
                               class="bg-slate-800 text-slate-300 hover:text-white px-3 py-2 rounded-xl text-xs font-semibold">
                                Hareketler
                            </a>
                            <a href="{{ route('parts.edit', $part) }}"
                               class="bg-slate-800 text-slate-300 hover:text-white px-3 py-2 rounded-xl text-xs font-semibold">
                                Düzenle
                            </a>
                        </div>

                        <!-- Mobil Hızlı Stok Ekle Modal -->
                        <div x-show="openMobAddStock" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 text-left">
                            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 w-full max-w-sm shadow-2xl max-h-[90vh] overflow-y-auto" @click.stop>
                                <h3 class="font-bold text-white text-sm mb-1">Stok Girişi: {{ $part->name }}</h3>
                                <p class="text-xs text-slate-400 mb-4">Parça stoğunu artırın ve alış fiyatını kaydedin.</p>
                                <form method="POST" action="{{ route('parts.add-stock', $part) }}" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-medium text-slate-300 mb-1">Eklenecek Miktar *</label>
                                        <input type="number" name="quantity" min="1" required placeholder="Örn: 20"
                                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-base sm:text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-300 mb-1">Alış Fiyatı (₺)</label>
                                        <input type="number" name="unit_price" step="0.01" value="{{ $part->buy_price }}"
                                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-base sm:text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-300 mb-1">Not / Fatura</label>
                                        <input type="text" name="note" placeholder="Örn: Satın Alma"
                                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-base sm:text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                    </div>
                                    <div class="flex gap-2 pt-2">
                                        <button type="submit" class="flex-1 bg-emerald-600 text-white font-bold py-2.5 rounded-xl text-xs">Stok Ekle</button>
                                        <button type="button" @click="openMobAddStock = false" class="px-4 bg-slate-800 text-slate-300 rounded-xl text-xs">Vazgeç</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500 text-xs">
                        Aranan kriterlere uygun parça bulunamadı.
                    </div>
                @endforelse
            </div>

            @if($parts->hasPages())
                <div class="p-4 border-t border-slate-800 bg-slate-950/50">
                    {{ $parts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
