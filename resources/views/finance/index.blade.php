<x-app-layout>
    <x-slot name="title">Finans Yönetimi — Gelir, Gider, Tahsilatlar & Borçlar</x-slot>

    <div class="space-y-6">

        <!-- Üst Finansal Özet Kartları -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Toplam Gelir -->
            <div class="bg-slate-900 text-white rounded-3xl p-5 border border-slate-800 shadow-xl">
                <div class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Toplam Servis Geliri</div>
                <div class="text-2xl font-mono font-black text-emerald-400">₺{{ number_format($totalGelir, 2, ',', '.') }}</div>
                <div class="text-[11px] text-slate-400 mt-2 flex items-center justify-between">
                    <span>Parça: ₺{{ number_format($totalPartRevenue, 0, ',', '.') }}</span>
                    <span>İşçilik: ₺{{ number_format($totalLaborRevenue, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Tahsil Edilen -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-xs">
                <div class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Tahsil Edilen (Kasa)</div>
                <div class="text-2xl font-mono font-black text-emerald-600">₺{{ number_format($tahsilEdilen, 2, ',', '.') }}</div>
                <div class="text-[11px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full inline-block mt-2 font-semibold">
                    ✓ Ödemesi Alınmış Servisler
                </div>
            </div>

            <!-- Bekleyen Borçlar -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-xs">
                <div class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Bekleyen Müşteri Alacakları</div>
                <div class="text-2xl font-mono font-black text-amber-600">₺{{ number_format($bekleyenBorclar, 2, ',', '.') }}</div>
                <div class="text-[11px] text-amber-800 bg-amber-50 px-2 py-0.5 rounded-full inline-block mt-2 font-semibold">
                    ⏳ Tahsilat Bekleyen Borçlar
                </div>
            </div>

            <!-- Net Kâr -->
            <div class="bg-gradient-to-br from-indigo-900 to-slate-900 text-white rounded-3xl p-5 border border-indigo-800/50 shadow-xl">
                <div class="text-xs text-indigo-300 font-bold uppercase tracking-wider mb-1">Hesaplanan Net Kâr</div>
                <div class="text-2xl font-mono font-black text-indigo-200">₺{{ number_format($netKar, 2, ',', '.') }}</div>
                <div class="text-[11px] text-indigo-300 mt-2">
                    Stok Giderleri: ₺{{ number_format($totalGider, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Tab Navigasyonu -->
        <div class="bg-white rounded-2xl border border-slate-200 p-2 flex items-center gap-2 overflow-x-auto">
            <a href="{{ route('finance.index', ['tab' => 'gelir']) }}"
               class="px-4 py-2.5 rounded-xl font-bold text-xs transition-colors flex items-center gap-2 shrink-0 {{ $tab === 'gelir' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>💰 Gelir Kalemleri</span>
            </a>
            <a href="{{ route('finance.index', ['tab' => 'gider']) }}"
               class="px-4 py-2.5 rounded-xl font-bold text-xs transition-colors flex items-center gap-2 shrink-0 {{ $tab === 'gider' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>📦 Stok & Giderler</span>
            </a>
            <a href="{{ route('finance.index', ['tab' => 'tahsilatlar']) }}"
               class="px-4 py-2.5 rounded-xl font-bold text-xs transition-colors flex items-center gap-2 shrink-0 {{ $tab === 'tahsilatlar' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>✅ Tahsilatlar (Tamamlanan)</span>
            </a>
            <a href="{{ route('finance.index', ['tab' => 'borclar']) }}"
               class="px-4 py-2.5 rounded-xl font-bold text-xs transition-colors flex items-center gap-2 shrink-0 {{ $tab === 'borclar' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>🔴 Açık Borçlar / Alacak Listesi</span>
            </a>
        </div>

        <!-- Tab İçeriği -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
            @if($tab === 'gelir' || $tab === 'tahsilatlar')
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-900 text-sm">
                        {{ $tab === 'tahsilatlar' ? 'Tahsil Edilen İş Emirleri' : 'Tamamlanan Servis Gelirleri' }}
                    </h3>
                </div>

                <!-- Masaüstü Tablo -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-3.5">İş Emri</th>
                                <th class="p-3.5">Tarih</th>
                                <th class="p-3.5">Müşteri</th>
                                <th class="p-3.5">Parça Tutarı</th>
                                <th class="p-3.5">İşçilik</th>
                                <th class="p-3.5">Toplam</th>
                                <th class="p-3.5">Durum</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($completedWorkOrders->when($tab === 'tahsilatlar', fn($q) => $q->where('is_paid', true)) as $wo)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-3.5 font-mono font-bold text-slate-900">#{{ str_pad($wo->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td class="p-3.5 font-mono text-slate-600">{{ $wo->date->format('d.m.Y') }}</td>
                                    <td class="p-3.5 font-bold text-slate-800">{{ $wo->customer->name ?? '—' }}</td>
                                    <td class="p-3.5 font-mono">₺{{ number_format($wo->parts_total, 2, ',', '.') }}</td>
                                    <td class="p-3.5 font-mono">₺{{ number_format($wo->labor_total, 2, ',', '.') }}</td>
                                    <td class="p-3.5 font-mono font-black text-slate-900">₺{{ number_format($wo->grand_total, 2, ',', '.') }}</td>
                                    <td class="p-3.5">
                                        @if($wo->is_paid)
                                            <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full text-[10px]">Ödendi</span>
                                        @else
                                            <span class="bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded-full text-[10px]">Bekliyor</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-6 text-center text-slate-400">Kayıt bulunamadı.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobil Kartlar -->
                <div class="md:hidden divide-y divide-slate-100">
                    @forelse($completedWorkOrders->when($tab === 'tahsilatlar', fn($q) => $q->where('is_paid', true)) as $wo)
                        <div class="p-4 space-y-2.5 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <span class="font-mono font-bold text-slate-700 text-xs bg-slate-100 px-2 py-0.5 rounded">
                                        #{{ str_pad($wo->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <div class="font-bold text-slate-900 text-sm mt-1">
                                        {{ $wo->customer->name ?? 'Müşteri Yok' }}
                                    </div>
                                    <span class="text-[11px] text-slate-400 font-mono">{{ $wo->date->format('d.m.Y') }}</span>
                                </div>
                                <div class="text-right">
                                    <div class="font-mono font-black text-emerald-600 text-sm">
                                        ₺{{ number_format($wo->grand_total, 2, ',', '.') }}
                                    </div>
                                    @if($wo->is_paid)
                                        <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full text-[10px] inline-block mt-1">Ödendi</span>
                                    @else
                                        <span class="bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded-full text-[10px] inline-block mt-1">Bekliyor</span>
                                    @endif
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50 p-2 rounded-xl border border-slate-100">
                                <div>
                                    <span class="text-slate-400 text-[10px] block">Parça</span>
                                    <span class="font-mono text-slate-700">₺{{ number_format($wo->parts_total, 2, ',', '.') }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 text-[10px] block">İşçilik</span>
                                    <span class="font-mono text-slate-700">₺{{ number_format($wo->labor_total, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 text-xs">
                            Kayıt bulunamadı.
                        </div>
                    @endforelse
                </div>

            @elseif($tab === 'borclar')
                <div class="p-5 border-b border-slate-100">
                    <h3 class="font-bold text-amber-900 text-sm">Ödemesi Henüz Yapılmamış Servis Kayıtları</h3>
                </div>

                <!-- Masaüstü Tablo -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-3.5">İş Emri</th>
                                <th class="p-3.5">Tarih</th>
                                <th class="p-3.5">Müşteri</th>
                                <th class="p-3.5">Telefon</th>
                                <th class="p-3.5">Kalan Borç</th>
                                <th class="p-3.5">Aksiyon</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($completedWorkOrders->where('is_paid', false) as $wo)
                                <tr class="hover:bg-amber-50/40">
                                    <td class="p-3.5 font-mono font-bold text-slate-900">#{{ str_pad($wo->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td class="p-3.5 font-mono text-slate-600">{{ $wo->date->format('d.m.Y') }}</td>
                                    <td class="p-3.5 font-bold text-slate-900">{{ $wo->customer->name ?? '—' }}</td>
                                    <td class="p-3.5 font-mono text-slate-600">{{ $wo->customer->phone ?? '—' }}</td>
                                    <td class="p-3.5 font-mono font-black text-amber-700">₺{{ number_format($wo->grand_total, 2, ',', '.') }}</td>
                                    <td class="p-3.5">
                                        <a href="{{ route('work-orders.show', $wo) }}" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-2.5 py-1 rounded-lg text-[11px]">
                                            Ödeme Al →
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-emerald-600 font-bold">🎉 Harika! Ödenmemiş açık borç kalmadı.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobil Borç Kartları -->
                <div class="md:hidden divide-y divide-amber-100">
                    @forelse($completedWorkOrders->where('is_paid', false) as $wo)
                        <div class="p-4 space-y-2.5 hover:bg-amber-50/30 transition-colors">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <span class="font-mono font-bold text-slate-700 text-xs bg-slate-100 px-2 py-0.5 rounded">
                                        #{{ str_pad($wo->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <div class="font-bold text-slate-900 text-sm mt-1">
                                        {{ $wo->customer->name ?? 'Müşteri Yok' }}
                                    </div>
                                    @if($wo->customer && $wo->customer->phone)
                                        <a href="tel:{{ $wo->customer->phone }}" class="text-[11px] font-mono text-blue-600 flex items-center gap-1 mt-0.5">
                                            <span>📞</span> <span>{{ $wo->customer->phone }}</span>
                                        </a>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] text-slate-400 block uppercase font-bold">Kalan Borç</span>
                                    <div class="font-mono font-black text-amber-700 text-base">
                                        ₺{{ number_format($wo->grand_total, 2, ',', '.') }}
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $wo->date->format('d.m.Y') }}</span>
                                </div>
                            </div>
                            <div class="pt-2">
                                <a href="{{ route('work-orders.show', $wo) }}" class="block w-full text-center bg-amber-600 hover:bg-amber-700 text-white font-bold py-2.5 rounded-xl text-xs shadow-xs">
                                    Ödeme Al / Fişe Git →
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-emerald-600 font-bold text-xs">
                            🎉 Harika! Ödenmemiş açık borç kalmadı.
                        </div>
                    @endforelse
                </div>

            @elseif($tab === 'gider')
                <div class="p-5 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 text-sm">Stok Giriş ve Alım Giderleri</h3>
                </div>

                <!-- Masaüstü Tablo -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-3.5">Tarih</th>
                                <th class="p-3.5">Parça</th>
                                <th class="p-3.5">Adet</th>
                                <th class="p-3.5">Birim Alış Fiyatı</th>
                                <th class="p-3.5">Toplam Gider</th>
                                <th class="p-3.5">Açıklama</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($stockPurchases as $m)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-3.5 font-mono text-slate-600">{{ $m->created_at->format('d.m.Y H:i') }}</td>
                                    <td class="p-3.5 font-bold text-slate-900">{{ $m->part->name ?? '—' }}</td>
                                    <td class="p-3.5 font-mono font-bold text-emerald-600">+{{ $m->quantity }}</td>
                                    <td class="p-3.5 font-mono">₺{{ number_format($m->unit_price ?? $m->part->buy_price ?? 0, 2, ',', '.') }}</td>
                                    <td class="p-3.5 font-mono font-bold text-red-600">
                                        ₺{{ number_format($m->quantity * ($m->unit_price ?? $m->part->buy_price ?? 0), 2, ',', '.') }}
                                    </td>
                                    <td class="p-3.5 text-slate-500">{{ $m->notes ?? 'Satın Alma' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-slate-400">Henüz kaydedilmiş stok alım gideri yok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobil Gider Kartları -->
                <div class="md:hidden divide-y divide-slate-100">
                    @forelse($stockPurchases as $m)
                        <div class="p-4 space-y-2 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="font-bold text-slate-900 text-sm">{{ $m->part->name ?? '—' }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $m->created_at->format('d.m.Y H:i') }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="font-mono font-black text-red-600 text-sm">
                                        -₺{{ number_format($m->quantity * ($m->unit_price ?? $m->part->buy_price ?? 0), 2, ',', '.') }}
                                    </span>
                                    <span class="font-mono font-bold text-emerald-600 text-xs block">
                                        +{{ $m->quantity }} Adet
                                    </span>
                                </div>
                            </div>
                            @if($m->notes)
                                <div class="text-xs text-slate-500 bg-slate-50 p-2 rounded-lg border border-slate-100">
                                    {{ $m->notes }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 text-xs">
                            Henüz kaydedilmiş stok alım gideri yok.
                        </div>
                    @endforelse
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
