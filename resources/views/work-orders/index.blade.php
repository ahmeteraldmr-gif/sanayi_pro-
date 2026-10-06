<x-app-layout>
    <x-slot name="title">İş Emirleri & Servis Takibi</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-white tracking-tight">İŞ EMİRLERİ & SERVİS TAKİBİ</h1>
                <p class="text-xs text-slate-400 mt-1">
                    Aktif araç işlemlerini ve aşamalarını anlık canlı zaman çizgisi ile takip edin
                </p>
            </div>
            <a href="{{ route('work-orders.create') }}"
               class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-lg shadow-indigo-600/20 flex items-center gap-2 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Yeni İş Emri Oluştur</span>
            </a>
        </div>

        <!-- Filtreler -->
        <div class="bg-slate-900 border border-slate-800 p-4 rounded-2xl shadow-xl">
            <form class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <div class="relative sm:col-span-2 lg:col-span-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Plaka veya müşteri adı..."
                           class="w-full pl-3.5 pr-3.5 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <select name="status" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2.5 text-xs sm:text-sm text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="">Tüm Servis Aşamaları</option>
                        <option value="arac_kabul" {{ request('status')=='arac_kabul' || request('status')=='beklemede' ? 'selected' : '' }}>🟢 Araç Kabul</option>
                        <option value="ariza_tespiti" {{ request('status')=='ariza_tespiti' ? 'selected' : '' }}>🔵 Arıza Tespiti</option>
                        <option value="islem_basladi" {{ request('status')=='islem_basladi' || request('status')=='devam_ediyor' ? 'selected' : '' }}>🟣 İşlem Başladı</option>
                        <option value="parca_bekleniyor" {{ request('status')=='parca_bekleniyor' ? 'selected' : '' }}>🟡 Parça Bekleniyor</option>
                        <option value="kontrol" {{ request('status')=='kontrol' || request('status')=='tamamlandi' ? 'selected' : '' }}>🔵 Kontrol & Test</option>
                        <option value="teslim_edildi" {{ request('status')=='teslim_edildi' || request('status')=='odendi' ? 'selected' : '' }}>🟢 Teslim Edildi / Ödendi</option>
                    </select>
                </div>
                <div>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2.5 text-xs sm:text-sm text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2.5 text-xs sm:text-sm text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-colors">
                        Filtrele
                    </button>
                </div>
            </form>
        </div>

        <!-- İş Emirleri Tablosu & Mobil Kartlar -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-xl overflow-hidden">
            <!-- Masaüstü Tablo Görünümü -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-950 text-xs font-semibold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="px-5 py-3.5"># No</th>
                            <th class="px-5 py-3.5">Tarih</th>
                            <th class="px-5 py-3.5">Plaka & Araç</th>
                            <th class="px-5 py-3.5">Müşteri</th>
                            <th class="px-5 py-3.5 text-right">Tutar</th>
                            <th class="px-5 py-3.5 text-center">Görsel Servis Durumu</th>
                            <th class="px-5 py-3.5 text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="px-5 py-4 text-slate-400 font-mono text-xs">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-5 py-4 text-slate-300 text-xs font-medium">{{ $order->date->format('d/m/Y') }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-bold text-indigo-300 bg-indigo-500/10 border border-indigo-500/20 px-2.5 py-1 rounded-lg text-xs tracking-wider">
                                            {{ $order->vehicle->plate }}
                                        </span>
                                        <span class="text-xs text-slate-300">{{ $order->vehicle->brand }} {{ $order->vehicle->model }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 font-semibold text-white">{{ $order->vehicle->customer->name }}</td>
                                <td class="px-5 py-4 text-right font-bold text-emerald-400 font-mono">₺{{ number_format($order->grand_total, 2, ',', '.') }}</td>
                                
                                <!-- Visual Timeline Status Badge -->
                                <td class="px-5 py-4 text-center">
                                    @php
                                        $step = $order->status_step;
                                        $badges = [
                                            1 => ['bg' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30', 'label' => '🟢 Araç Kabul'],
                                            2 => ['bg' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',       'label' => '🔵 Arıza Tespiti'],
                                            3 => ['bg' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30', 'label' => '🟣 İşlem Başladı'],
                                            4 => ['bg' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',    'label' => '🟡 Parça Bekleniyor'],
                                            5 => ['bg' => 'bg-cyan-500/20 text-cyan-300 border-cyan-500/30',       'label' => '🔵 Kontrol & Test'],
                                            6 => ['bg' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30', 'label' => '🟢 Teslim Edildi'],
                                        ];
                                        $b = $badges[$step] ?? $badges[1];
                                    @endphp
                                    <div class="inline-flex flex-col items-center gap-1">
                                        <span class="text-xs font-bold px-3 py-1 rounded-full border {{ $b['bg'] }}">
                                            {{ $b['label'] }}
                                        </span>
                                        <span class="text-[10px] font-mono text-slate-500">Aşama {{ $step }}/6</span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('diagnostic.create', $order) }}"
                                           title="🤖 Akıllı Arıza Teşhisi"
                                           class="text-xs bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 font-semibold px-2 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                                            <span>🤖</span>
                                            <span class="hidden sm:inline">Teşhis</span>
                                        </a>
                                        <a href="{{ route('work-orders.show', $order) }}"
                                           class="text-xs bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-3 py-1.5 rounded-lg transition-colors shadow-xs">
                                            Detay & Timeline &rarr;
                                        </a>
                                        <a href="{{ route('work-orders.edit', $order) }}"
                                           class="text-xs text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors">Düzenle</a>
                                        <form method="POST" action="{{ route('work-orders.destroy', $order) }}"
                                              onsubmit="return confirm('Bu iş emrini silmek istediğinizden emin misiniz?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-400 hover:text-rose-300 p-1.5 rounded-lg hover:bg-slate-800 transition-colors">Sil</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-slate-500 text-sm">Kayıtlı iş emri bulunamadı.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobil Kart Listesi (Telefon Ekranları) -->
            <div class="md:hidden divide-y divide-slate-800/80">
                @forelse($orders as $order)
                    @php
                        $step = $order->status_step;
                        $badges = [
                            1 => ['bg' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30', 'label' => '🟢 Araç Kabul'],
                            2 => ['bg' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',       'label' => '🔵 Arıza Tespiti'],
                            3 => ['bg' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30', 'label' => '🟣 İşlem Başladı'],
                            4 => ['bg' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',    'label' => '🟡 Parça Bekleniyor'],
                            5 => ['bg' => 'bg-cyan-500/20 text-cyan-300 border-cyan-500/30',       'label' => '🔵 Kontrol & Test'],
                            6 => ['bg' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30', 'label' => '🟢 Teslim Edildi'],
                        ];
                        $b = $badges[$step] ?? $badges[1];
                    @endphp
                    <div class="p-4 space-y-3 hover:bg-slate-800/20 transition-colors">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="font-mono font-bold text-indigo-300 bg-indigo-500/10 border border-indigo-500/20 px-2.5 py-1 rounded-lg text-xs tracking-wider inline-block mb-1">
                                    {{ $order->vehicle->plate }}
                                </span>
                                <div class="font-bold text-white text-sm">
                                    {{ $order->vehicle->brand }} {{ $order->vehicle->model }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-mono font-black text-emerald-400 text-sm">
                                    ₺{{ number_format($order->grand_total, 2, ',', '.') }}
                                </div>
                                <span class="text-[10px] text-slate-400 font-mono">
                                    #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }} • {{ $order->date->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-xs py-2 border-y border-slate-800/60">
                            <div class="text-slate-300 truncate max-w-[180px]">
                                <span class="text-slate-500">Müşteri:</span>
                                <strong class="text-white">{{ $order->vehicle->customer->name }}</strong>
                            </div>
                            <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full border {{ $b['bg'] }} shrink-0">
                                {{ $b['label'] }}
                            </span>
                        </div>

                        <!-- Mobil Aksiyon Butonları -->
                        <div class="flex items-center gap-2 pt-1">
                            <a href="{{ route('work-orders.show', $order) }}"
                               class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs py-2.5 rounded-xl text-center shadow-xs transition-colors">
                                Detay &rarr;
                            </a>
                            <a href="{{ route('diagnostic.create', $order) }}"
                               title="🤖 Akıllı Arıza Teşhisi"
                               class="bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 font-semibold px-3 py-2.5 rounded-xl text-xs flex items-center justify-center gap-1 transition-colors">
                                <span>🤖 AI</span>
                            </a>
                            <a href="{{ route('work-orders.edit', $order) }}"
                               class="bg-slate-800 text-slate-300 hover:text-white px-3 py-2.5 rounded-xl text-xs font-semibold">
                                Düzenle
                            </a>
                            <form method="POST" action="{{ route('work-orders.destroy', $order) }}"
                                  onsubmit="return confirm('Bu iş emrini silmek istediğinizden emin misiniz?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-rose-400 hover:text-rose-300 bg-slate-800 px-3 py-2.5 rounded-xl text-xs font-semibold">
                                    Sil
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500 text-xs">
                        Kayıtlı iş emri bulunamadı.
                    </div>
                @endforelse
            </div>

            @if($orders->hasPages())
                <div class="px-5 py-3.5 border-t border-slate-800 bg-slate-950/50">{{ $orders->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
