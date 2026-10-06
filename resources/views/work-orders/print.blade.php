<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servis Fişi #{{ str_pad($workOrder->id, 5, '0', STR_PAD_LEFT) }} — {{ $workOrder->vehicle->plate }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .plate-box {
            font-family: 'JetBrains Mono', monospace;
            border: 2px solid #000;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            overflow: hidden;
            background: #fff;
        }
        .plate-tr {
            background-color: #003399;
            color: #fff;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        .plate-text {
            padding: 4px 12px;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 2px;
            color: #000;
        }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-4 sm:py-8 px-2 sm:px-4 text-slate-800">

    <!-- Üst Kontrol Çubuğu (Yazdırmada Gizlenir) -->
    <div class="no-print max-w-4xl mx-auto mb-4 sm:mb-6 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 bg-white p-3 sm:p-4 rounded-xl sm:rounded-2xl shadow-sm border border-slate-200">
        <a href="{{ route('work-orders.show', $workOrder) }}" class="inline-flex items-center justify-center sm:justify-start gap-2 text-xs sm:text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            İş Emrine Geri Dön
        </a>

        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs sm:text-sm px-4 sm:px-5 py-2.5 rounded-xl shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Yazdır / PDF Kaydet
            </button>
        </div>
    </div>

    <!-- FİŞ / FATURA FORMU -->
    <div class="print-container max-w-4xl mx-auto bg-white rounded-xl sm:rounded-2xl shadow-xl border border-slate-200 p-4 sm:p-8 lg:p-12">
        
        <!-- BAŞLIK BÖLÜMÜ -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 pb-6 border-b-2 border-slate-900">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-2xl font-black tracking-tight text-slate-900">Sanayi<span class="text-indigo-600">Pro</span></span>
                    <span class="text-xs bg-slate-900 text-white font-semibold px-2 py-0.5 rounded">
                        {{ $workOrder->vehicle->branch->name ?? 'Oto Servis' }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium">Profesyonel Araç Bakım & Onarım Hizmetleri</p>
                <p class="text-xs text-slate-400 mt-1">Tel: (0212) 555 01 02 • Sanayi Sitesi 4. Blok No: 12</p>
            </div>

            <div class="sm:text-right">
                <h1 class="text-lg font-black text-slate-900 uppercase tracking-wide">SERVİS İŞ EMRİ FORMU</h1>
                <div class="mt-1 font-mono text-sm text-slate-700 font-bold">Fiş No: #{{ str_pad($workOrder->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div class="text-xs text-slate-500 mt-0.5">Tarih: {{ $workOrder->date->format('d.m.Y') }}</div>
                <div class="mt-2 inline-block px-2.5 py-0.5 rounded text-xs font-bold uppercase
                    {{ $workOrder->status === 'odendi' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                    Durum: {{ $workOrder->status_label }}
                </div>
            </div>
        </div>

        <!-- MÜŞTERİ VE ARAÇ BİLGİLERİ KUTUSU -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 my-6 p-4 bg-slate-50 rounded-xl border border-slate-200">
            
            <!-- Araç Bilgileri -->
            <div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Araç Bilgileri</div>
                <div class="mb-3">
                    <div class="plate-box shadow-sm">
                        <div class="plate-tr">TR</div>
                        <div class="plate-text">{{ $workOrder->vehicle->plate }}</div>
                    </div>
                </div>
                <div class="text-sm font-bold text-slate-900">{{ $workOrder->vehicle->brand }} {{ $workOrder->vehicle->model }}</div>
                <div class="text-xs text-slate-600 mt-0.5">Model Yılı: {{ $workOrder->vehicle->year ?? '—' }} | Renk: {{ $workOrder->vehicle->color ?? '—' }}</div>
            </div>

            <!-- Müşteri Bilgileri -->
            <div class="sm:border-l sm:border-slate-200 sm:pl-6">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Müşteri / Ruhsat Sahibi</div>
                <div class="text-sm font-bold text-slate-900">{{ $workOrder->vehicle->customer->name }}</div>
                <div class="text-xs text-slate-600 mt-1 flex items-center gap-1.5">
                    <span>📞</span> {{ $workOrder->vehicle->customer->phone ?? 'Telefon belirtilmedi' }}
                </div>
                @if($workOrder->vehicle->customer->address)
                    <div class="text-xs text-slate-500 mt-1">{{ $workOrder->vehicle->customer->address }}</div>
                @endif
            </div>

        </div>

        <!-- YAPILAN İŞLEMLER VE DEĞİŞEN PARÇALAR TABLOSU -->
        <div class="my-6">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Yapılan İşlemler ve Kullanılan Parçalar</div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse min-w-[500px]">
                    <thead>
                        <tr class="border-b-2 border-slate-300 text-xs font-bold text-slate-600 uppercase bg-slate-100">
                            <th class="py-2.5 px-3">#</th>
                            <th class="py-2.5 px-3">Açıklama / Kalem</th>
                            <th class="py-2.5 px-3 text-center">Tür</th>
                            <th class="py-2.5 px-3 text-center">Miktar</th>
                            <th class="py-2.5 px-3 text-right">Birim Fiyat</th>
                            <th class="py-2.5 px-3 text-right">Toplam Tutar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800">
                        @foreach($workOrder->items as $index => $item)
                            <tr class="{{ $loop->even ? 'bg-slate-50/50' : '' }}">
                                <td class="py-3 px-3 text-xs text-slate-400 font-mono">{{ $index + 1 }}</td>
                                <td class="py-3 px-3 font-semibold text-slate-900">{{ $item->name }}</td>
                                <td class="py-3 px-3 text-center">
                                    <span class="text-[11px] font-medium px-2 py-0.5 rounded {{ $item->type === 'part' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' }}">
                                        {{ $item->type === 'part' ? 'Yedek Parça' : 'İşçilik' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-center font-medium">{{ $item->quantity }} Adet</td>
                                <td class="py-3 px-3 text-right text-slate-600 font-mono">₺{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                <td class="py-3 px-3 text-right font-bold text-slate-900 font-mono">₺{{ number_format($item->total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- HESAP ÖZETİ -->
        <div class="flex flex-col sm:flex-row justify-between gap-6 pt-4 border-t-2 border-slate-300">
            <div class="flex-1">
                @if($workOrder->notes)
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-xs text-amber-900">
                        <span class="font-bold">Servis Notu:</span> {{ $workOrder->notes }}
                    </div>
                @endif
                <div class="mt-4 text-[11px] text-slate-400 space-y-1">
                    <p>• Servisimizde takılan orijinal yedek parçalar üretici garantisi altındadır.</p>
                    <p>• Yapılan işçilikler 90 gün boyunca servisimiz garantisindedir.</p>
                    <p>• Araç teslim tutanağı yerine geçer.</p>
                </div>
            </div>

            <!-- Tutar Tablosu -->
            <div class="w-full sm:w-72 space-y-2 text-sm">
                <div class="flex justify-between text-slate-600">
                    <span>Parça Toplamı:</span>
                    <span class="font-mono font-medium">₺{{ number_format($workOrder->total_parts, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>İşçilik Toplamı:</span>
                    <span class="font-mono font-medium">₺{{ number_format($workOrder->total_labor, 2, ',', '.') }}</span>
                </div>
                @if($workOrder->discount > 0)
                    <div class="flex justify-between text-red-600">
                        <span>Uygulanan İndirim:</span>
                        <span class="font-mono font-medium">-₺{{ number_format($workOrder->discount, 2, ',', '.') }}</span>
                    </div>
                @endif
                <div class="pt-2 border-t-2 border-slate-900 flex justify-between items-baseline text-slate-900 font-black">
                    <span class="text-base uppercase">Genel Toplam:</span>
                    <span class="text-xl text-indigo-700 font-mono">₺{{ number_format($workOrder->grand_total, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- İMZA ALANLARI -->
        <div class="grid grid-cols-2 gap-8 mt-12 pt-8 border-t border-slate-200">
            <div class="text-center">
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Teslim Eden (Servis Yetkilisi)</div>
                <div class="text-sm font-semibold text-slate-800">{{ auth()->user()->name ?? 'Usta' }}</div>
                <div class="mt-10 border-b border-dashed border-slate-400 w-48 mx-auto"></div>
                <div class="text-[10px] text-slate-400 mt-1">İmza / Kaşe</div>
            </div>

            <div class="text-center">
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Teslim Alan (Müşteri)</div>
                <div class="text-sm font-semibold text-slate-800">{{ $workOrder->vehicle->customer->name }}</div>
                <div class="mt-10 border-b border-dashed border-slate-400 w-48 mx-auto"></div>
                <div class="text-[10px] text-slate-400 mt-1">İmza</div>
            </div>
        </div>

    </div>

</body>
</html>
