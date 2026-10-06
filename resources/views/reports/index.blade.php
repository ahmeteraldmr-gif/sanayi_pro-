<x-app-layout>
    <x-slot name="title">Raporlar & İşletme Analizi</x-slot>

    <div class="space-y-6">

        <!-- ============================================================
             1. BUGÜN İŞLETME DURUMU (TODAY HIGHLIGHT CARDS - LIGHT MODERN)
        ============================================================ -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                    <h2 class="text-xs font-black uppercase tracking-widest text-slate-500">BUGÜNÜN ANLIK DURUMU ({{ today()->translatedFormat('d F Y') }})</h2>
                </div>
                <span class="text-[11px] font-mono text-slate-400">Canlı İşletme Verileri</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
                <!-- 💰 Ciro -->
                <div class="bg-white border border-slate-200 rounded-3xl p-4 shadow-xs hover:border-emerald-400 transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">💰 Ciro</span>
                        <span class="text-[10px] bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded font-mono font-bold">Bugün</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-emerald-600 font-mono mt-2 tracking-tight">
                        ₺{{ number_format($todayRevenue, 2, ',', '.') }}
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Bugün kasaya giren net ciro</p>
                </div>

                <!-- 🔧 Tamamlanan İş -->
                <div class="bg-white border border-slate-200 rounded-3xl p-4 shadow-xs hover:border-indigo-400 transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">🔧 Tamamlanan İş</span>
                        <span class="text-[10px] bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded font-mono font-bold">Bugün</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-slate-900 font-mono mt-2 tracking-tight">
                        {{ $todayCompletedCount }} <span class="text-xs font-normal text-slate-400">İş</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Bugün teslim edilen araçlar</p>
                </div>

                <!-- 🚗 Servisteki Araç -->
                <div class="bg-white border border-slate-200 rounded-3xl p-4 shadow-xs hover:border-blue-400 transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">🚗 Servisteki Araç</span>
                        <span class="text-[10px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-mono font-bold">Aktif</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-blue-600 font-mono mt-2 tracking-tight">
                        {{ $inServiceCount }} <span class="text-xs font-normal text-slate-400">Araç</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Şu an atölyede işlemde</p>
                </div>

                <!-- 📦 Kritik Stok -->
                <div class="bg-white border border-slate-200 rounded-3xl p-4 shadow-xs hover:border-amber-400 transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">📦 Kritik Stok</span>
                        @if($lowStockCount > 0)
                            <span class="text-[10px] bg-amber-50 text-amber-700 px-2 py-0.5 rounded font-bold animate-pulse">Uyarı</span>
                        @endif
                    </div>
                    <div class="text-2xl sm:text-3xl font-black {{ $lowStockCount > 0 ? 'text-amber-600' : 'text-slate-800' }} font-mono mt-2 tracking-tight">
                        {{ $lowStockCount }} <span class="text-xs font-normal text-slate-400">Parça</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Siparişi verilmesi gerekenler</p>
                </div>

                <!-- 💳 Bekleyen Ödeme -->
                <div class="bg-white border border-slate-200 rounded-3xl p-4 shadow-xs hover:border-rose-400 transition-colors col-span-2 sm:col-span-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">💳 Bekleyen Ödeme</span>
                        <span class="text-[10px] bg-rose-50 text-rose-700 px-2 py-0.5 rounded font-mono font-bold">Alacak</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-rose-600 font-mono mt-2 tracking-tight">
                        ₺{{ number_format($pendingPaymentTotal, 2, ',', '.') }}
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Teslim edilen / bekleyen tahsilat</p>
                </div>
            </div>
        </div>

        <!-- ============================================================
             2. DÖNEM SEÇİCİ VE AYLIK FİNANSAL METRİKLER
        ============================================================ -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs">
            <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4 mb-5">
                <div>
                    <h2 class="text-lg font-black text-slate-900 tracking-tight">Dönemsel Finans & Kâr Analizi</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Seçilen ay için ciro, parça kârı ve işçilik gelirleri</p>
                </div>
                <div class="flex items-center gap-2">
                    <select name="month" class="bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3 py-2 text-xs font-bold focus:ring-2 focus:ring-indigo-600 focus:bg-white focus:outline-none">
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->locale('tr')->monthName }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year" class="bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-3 py-2 text-xs font-bold focus:ring-2 focus:ring-indigo-600 focus:bg-white focus:outline-none">
                        @foreach(range(now()->year, now()->year - 3) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-colors shadow-md shadow-indigo-600/20">
                        Raporla
                    </button>
                </div>
            </form>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Aylık Toplam Ciro</span>
                    <div class="text-2xl font-black text-emerald-600 font-mono mt-1">₺{{ number_format($totalIncome, 2, ',', '.') }}</div>
                    <span class="text-[11px] text-slate-500 block mt-1">{{ $monthOrders->count() }} Tamamlanan İş Emri</span>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Parça Satışı & Net Kâr</span>
                    <div class="text-2xl font-black text-blue-600 font-mono mt-1">₺{{ number_format($totalParts, 2, ',', '.') }}</div>
                    <span class="text-[11px] text-emerald-600 font-bold block mt-1">Net Parça Kârı: ₺{{ number_format($partProfit, 2, ',', '.') }}</span>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">İşçilik Geliri (%100 Net)</span>
                    <div class="text-2xl font-black text-purple-600 font-mono mt-1">₺{{ number_format($totalLabor, 2, ',', '.') }}</div>
                    <span class="text-[11px] text-slate-500 block mt-1">İşçilik Payı: %{{ $totalIncome > 0 ? round($totalLabor / $totalIncome * 100) : 0 }}</span>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Uygulanan İndirimler</span>
                    <div class="text-2xl font-black text-rose-600 font-mono mt-1">₺{{ number_format($totalDiscount, 2, ',', '.') }}</div>
                    <span class="text-[11px] text-slate-500 block mt-1">Müşterilere Yapılan İskonto</span>
                </div>
            </div>
        </div>

        <!-- ============================================================
             3. GRAFİKLER (GÜNLÜK TREND & AYLIK KARŞILAŞTIRMA)
        ============================================================ -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Günlük Ciro Trend Grafiği -->
            <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <span>📈</span> Günlük Ciro Dağılımı ({{ \Carbon\Carbon::create()->month($month)->locale('tr')->monthName }})
                    </h3>
                    <span class="text-[11px] font-mono font-bold text-slate-400">₺ Gelir</span>
                </div>
                <div class="h-64">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>

            <!-- Son 6 Ay Karşılaştırma Grafiği -->
            <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs">
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <span>📊</span> Son 6 Ay Karşılaştırmalı Gelir Trendi
                    </h3>
                    <span class="text-[11px] font-mono font-bold text-slate-400">Aylık Karşılaştırma</span>
                </div>
                <div class="h-64">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- ============================================================
             4. USTA BAZLI GELİR VE KATKI ANALİZİ
        ============================================================ -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-lg">👨‍🔧</span>
                    <div>
                        <h3 class="text-sm font-black text-slate-900">Usta Bazlı Gelir & İş Dağılımı</h3>
                        <p class="text-[11px] text-slate-500">Atölyedeki ustaların tamamladığı işler ve ürettiği ciro</p>
                    </div>
                </div>
                <span class="text-xs font-mono font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-200">
                    {{ $mechanicStats->count() }} Ustadan Kayıt
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3">Usta Adı</th>
                            <th class="px-4 py-3 text-center">Tamamlanan İş</th>
                            <th class="px-4 py-3 text-right">İşçilik Geliri</th>
                            <th class="px-4 py-3 text-right">Üretilen Toplam Ciro</th>
                            <th class="px-4 py-3">Ciro Payı</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($mechanicStats as $stat)
                            @php
                                $percent = $totalIncome > 0 ? round(($stat['revenue'] / $totalIncome) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 font-bold text-slate-900 flex items-center gap-2">
                                    <span>👨‍🔧</span>
                                    <span>{{ $stat['name'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center font-mono font-bold text-slate-800">
                                    {{ $stat['count'] }} İş
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-bold text-purple-700">
                                    ₺{{ number_format($stat['labor'], 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-black text-emerald-600">
                                    ₺{{ number_format($stat['revenue'], 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 min-w-[140px]">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-200">
                                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                                        </div>
                                        <span class="text-[11px] font-mono font-bold text-slate-600">{{ $percent }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">Bu dönem için usta kaydı bulunamadı.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================================
             5. İKİLİ ANALİZ: EN ÇOK YAPILAN İŞLEMLER & EN ÇOK KULLANILAN PARÇALAR
        ============================================================ -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- En Çok Yapılan İşlemler -->
            <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-purple-700 flex items-center gap-2">
                        <span>🛠️</span> En Çok Yapılan İşlemler & Hizmetler
                    </h3>
                    <span class="text-[10px] font-mono text-slate-400">Adet / Hacim</span>
                </div>
                <div class="space-y-3">
                    @forelse($topLabors as $labor)
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-200">
                            <div>
                                <span class="font-bold text-slate-900 text-xs block">{{ $labor->name }}</span>
                                <span class="text-[10px] text-slate-500 font-mono">{{ $labor->job_count }} Kez Yapıldı</span>
                            </div>
                            <div class="text-right font-mono font-bold text-purple-700 text-xs">
                                ₺{{ number_format($labor->total_revenue, 2, ',', '.') }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 text-xs">Bu ay henüz tamamlanmış işçilik bulunmuyor.</div>
                    @endforelse
                </div>
            </div>

            <!-- En Çok Kullanılan Parçalar -->
            <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-blue-700 flex items-center gap-2">
                        <span>📦</span> En Çok Kullanılan Parçalar & Sarf Malzemeleri
                    </h3>
                    <span class="text-[10px] font-mono text-slate-400">Adet / Ciro</span>
                </div>
                <div class="space-y-3">
                    @forelse($topParts as $partItem)
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-200">
                            <div>
                                <span class="font-bold text-slate-900 text-xs block">{{ $partItem->name }}</span>
                                <span class="text-[10px] text-slate-500 font-mono">{{ $partItem->total_qty }} Adet Tüketildi</span>
                            </div>
                            <div class="text-right font-mono font-bold text-blue-700 text-xs">
                                ₺{{ number_format($partItem->total_revenue, 2, ',', '.') }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 text-xs">Bu ay henüz parça kullanımı bulunmuyor.</div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- ============================================================
             6. BEKLEYEN ÖDEMELER DEFLERİ (TAHSİLAT BEKLEYEN İŞLER)
        ============================================================ -->
        @if($pendingOrders->count() > 0)
            <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-xs">
                <div class="p-5 border-b border-rose-100 bg-rose-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">💳</span>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">Tahsil Edilmeyi Bekleyen İş Emirleri</h3>
                            <p class="text-xs text-rose-700">İşlemleri bittiği halde henüz ödemesi alınmamış araçlar</p>
                        </div>
                    </div>
                    <span class="text-xs font-mono font-bold text-rose-700 bg-rose-100 px-3 py-1 rounded-full border border-rose-200">
                        Toplam Alacak: ₺{{ number_format($pendingPaymentTotal, 2, ',', '.') }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-200">
                            <tr>
                                <th class="px-5 py-3"># Emri</th>
                                <th class="px-5 py-3">Tarih</th>
                                <th class="px-5 py-3">Müşteri</th>
                                <th class="px-5 py-3">Plaka & Araç</th>
                                <th class="px-5 py-3 text-right">Tutar</th>
                                <th class="px-5 py-3 text-right">Tahsil Et</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($pendingOrders as $po)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-5 py-3.5 font-mono text-slate-500 font-bold">#{{ str_pad($po->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-5 py-3.5 text-slate-600 font-medium">{{ $po->date->format('d/m/Y') }}</td>
                                    <td class="px-5 py-3.5 font-bold text-slate-900">{{ $po->vehicle->customer->name }}</td>
                                    <td class="px-5 py-3.5">
                                        <span class="font-mono text-slate-900 font-bold bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-lg">
                                            {{ $po->vehicle->plate }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-mono font-black text-rose-600 text-sm">
                                        ₺{{ number_format($po->grand_total, 2, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <form method="POST" action="{{ route('work-orders.status', $po) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="odendi">
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3.5 py-1.5 rounded-xl transition-colors shadow-sm">
                                                💳 Ödendi Yap (Kasaya Al)
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Günlük Grafik (Sleek Light Modern)
        const dailyCtx = document.getElementById('dailyChart');
        if (dailyCtx) {
            new Chart(dailyCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($dailyData->keys()->map(fn($d) => $d . '/'. str_pad($month, 2, '0', STR_PAD_LEFT))) !!},
                    datasets: [{
                        label: 'Günlük Ciro (₺)',
                        data: {!! json_encode($dailyData->values()) !!},
                        borderColor: '#059669',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#059669',
                        pointRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            ticks: { color: '#64748b', callback: v => '₺' + v.toLocaleString('tr-TR') }, 
                            grid: { color: '#f1f5f9' } 
                        },
                        x: { 
                            ticks: { color: '#64748b' },
                            grid: { display: false } 
                        }
                    }
                }
            });
        }

        // Aylık Karşılaştırma Grafiği (Sleek Light Modern)
        const monthlyCtx = document.getElementById('monthlyChart');
        if (monthlyCtx) {
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($monthlyData->pluck('label')) !!},
                    datasets: [{
                        label: 'Aylık Gelir (₺)',
                        data: {!! json_encode($monthlyData->pluck('total')) !!},
                        backgroundColor: 'rgba(79, 70, 229, 0.75)',
                        borderColor: '#4f46e5',
                        borderWidth: 2,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            ticks: { color: '#64748b', callback: v => '₺' + v.toLocaleString('tr-TR') }, 
                            grid: { color: '#f1f5f9' } 
                        },
                        x: { 
                            ticks: { color: '#64748b' },
                            grid: { display: false } 
                        }
                    }
                }
            });
        }
    });
    </script>
</x-app-layout>
