<x-app-layout>
    <x-slot name="title">{{ $vehicle->plate }} — Araç Servis Geçmişi ve Profili</x-slot>

    <div class="space-y-6">

        <!-- ============================================================
             1. ÜST ARAÇ KİMLİK KARTI (VEHICLE PROFILE HERO)
        ============================================================ -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-950 text-white rounded-3xl p-6 sm:p-7 shadow-xl border border-slate-800 relative overflow-hidden">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                
                <!-- Sol: Araç Başlık & Künye -->
                <div class="flex items-start sm:items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-600/30 border border-indigo-400/30 flex items-center justify-center text-3xl shadow-inner shrink-0">
                        🚗
                    </div>
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                                {{ $vehicle->brand }} {{ $vehicle->model }}
                            </h1>
                            <div class="inline-flex items-center bg-white border-2 border-black rounded-lg font-mono font-black text-black text-sm px-2.5 py-0.5 shadow-sm">
                                <span class="bg-blue-700 text-white text-[9px] font-bold px-1 rounded mr-1">TR</span>
                                <span>{{ $vehicle->plate }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 text-xs text-indigo-200/80 mt-2 flex-wrap">
                            <span>📅 Yıl: <strong class="text-white">{{ $vehicle->year ?? '—' }}</strong></span>
                            <span>•</span>
                            <span>⚙️ Motor/Yakıt: <strong class="text-white">{{ $vehicle->engine ?? 'Belirtilmedi' }}</strong></span>
                            <span>•</span>
                            <span>🎨 Renk: <strong class="text-white">{{ $vehicle->color ?? '—' }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Sağ Butonlar -->
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    <a href="{{ route('work-orders.create', ['vehicle_id' => $vehicle->id]) }}"
                       class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs px-4 py-3 rounded-xl transition-all shadow-lg shadow-emerald-500/20 flex items-center gap-1.5 cursor-pointer">
                        <span>➕ Bu Araca İş Emri Aç</span>
                    </a>
                    <a href="{{ route('vehicles.edit', $vehicle) }}"
                       class="bg-white/10 hover:bg-white/20 text-white font-bold text-xs px-3.5 py-3 rounded-xl transition-colors border border-white/10 flex items-center gap-1">
                        <span>✏️ Düzenle</span>
                    </a>
                </div>

            </div>

            <!-- Özet İstatistik Barı (Son Servis & KM) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-5 border-t border-white/10 text-xs">
                
                <div class="bg-white/5 border border-white/10 rounded-2xl p-3.5">
                    <div class="text-[10px] text-indigo-300 font-bold uppercase tracking-wider mb-1">Güncel Kilometre</div>
                    <div class="text-base sm:text-lg font-mono font-black text-white">
                        {{ number_format($vehicle->latest_mileage, 0, ',', '.') }} <span class="text-xs text-indigo-300 font-normal">km</span>
                    </div>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-3.5">
                    <div class="text-[10px] text-indigo-300 font-bold uppercase tracking-wider mb-1">Son Servis Tarihi</div>
                    <div class="text-base sm:text-lg font-mono font-bold text-emerald-400">
                        {{ $vehicle->last_service_date ? $vehicle->last_service_date->format('d.m.Y') : 'Henüz Yok' }}
                    </div>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-3.5">
                    <div class="text-[10px] text-indigo-300 font-bold uppercase tracking-wider mb-1">Toplam Servis Sayısı</div>
                    <div class="text-base sm:text-lg font-mono font-bold text-white">
                        {{ $vehicle->workOrders->count() }} Kayıt
                    </div>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-3.5">
                    <div class="text-[10px] text-indigo-300 font-bold uppercase tracking-wider mb-1">Araç Sahibi</div>
                    <a href="{{ route('customers.show', $vehicle->customer) }}" class="text-sm font-bold text-blue-300 hover:text-white transition-colors truncate block">
                        👤 {{ $vehicle->customer->name }}
                    </a>
                </div>

            </div>

        </div>


        <!-- ============================================================
             1.5. ARAÇ SAĞLIK KARNESİ (VEHICLE HEALTH REPORT CARD)
        ============================================================ -->
        <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-xs border border-slate-200 relative overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-200 mb-1">
                        <span>🔥 ÖZEL SİSTEM</span>
                        <span>•</span>
                        <span>{{ $healthReport['overall_score'] }}</span>
                    </div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <span>📋</span> <span>ARAÇ SAĞLIK KARNESİ</span>
                    </h2>
                    <p class="text-xs text-slate-500">Servis ve bakım geçmişinden otomatik olarak oluşturulan teşhis karnesi</p>
                </div>
                
                <button onclick="window.print()" class="hidden sm:inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-3.5 py-2 rounded-xl border border-slate-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Karnesi Yazdır</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-3.5 mb-6">
                <!-- Motor -->
                <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200 flex flex-col justify-between hover:border-indigo-400 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Motor</span>
                        <span class="text-lg">{{ $healthReport['motor']['badge'] }}</span>
                    </div>
                    <div class="text-base font-bold text-slate-900 mb-1">
                        {{ $healthReport['motor']['label'] }}
                    </div>
                    <div class="text-[11px] text-slate-500 leading-tight">
                        {{ $healthReport['motor']['description'] }}
                    </div>
                </div>

                <!-- Fren -->
                <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200 flex flex-col justify-between hover:border-indigo-400 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Fren Sistemi</span>
                        <span class="text-lg">{{ $healthReport['fren']['badge'] }}</span>
                    </div>
                    <div class="text-base font-bold text-slate-900 mb-1">
                        {{ $healthReport['fren']['label'] }}
                    </div>
                    <div class="text-[11px] text-slate-500 leading-tight">
                        {{ $healthReport['fren']['description'] }}
                    </div>
                </div>

                <!-- Akü -->
                <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200 flex flex-col justify-between hover:border-indigo-400 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Akü & Elektrik</span>
                        <span class="text-lg">{{ $healthReport['aku']['badge'] }}</span>
                    </div>
                    <div class="text-base font-bold text-slate-900 mb-1">
                        {{ $healthReport['aku']['label'] }}
                    </div>
                    <div class="text-[11px] text-slate-500 leading-tight">
                        {{ $healthReport['aku']['description'] }}
                    </div>
                </div>

                <!-- Lastikler -->
                <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200 flex flex-col justify-between hover:border-indigo-400 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Lastikler</span>
                        <span class="text-lg">{{ $healthReport['lastikler']['badge'] }}</span>
                    </div>
                    <div class="text-base font-bold text-slate-900 mb-1">
                        {{ $healthReport['lastikler']['label'] }}
                    </div>
                    <div class="text-[11px] text-slate-500 leading-tight">
                        {{ $healthReport['lastikler']['description'] }}
                    </div>
                </div>

                <!-- Klima -->
                <div class="bg-slate-50/80 rounded-2xl p-4 border border-slate-200 flex flex-col justify-between hover:border-indigo-400 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Klima</span>
                        <span class="text-lg">{{ $healthReport['klima']['badge'] }}</span>
                    </div>
                    <div class="text-base font-bold text-slate-900 mb-1">
                        {{ $healthReport['klima']['label'] }}
                    </div>
                    <div class="text-[11px] text-slate-500 leading-tight">
                        {{ $healthReport['klima']['description'] }}
                    </div>
                </div>
            </div>

            <!-- Bakım Tarihleri Alt Takvim Barı -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                <div class="flex items-center gap-6 text-xs w-full sm:w-auto justify-around sm:justify-start">
                    <div>
                        <span class="text-slate-400 block text-[10px] font-bold uppercase">Son Bakım Tarihi</span>
                        <span class="text-sm font-mono font-bold text-emerald-600">
                            {{ $healthReport['last_service_date'] ? $healthReport['last_service_date']->format('d.m.Y') : 'Servis Kaydı Bulunmuyor' }}
                        </span>
                    </div>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <div>
                        <span class="text-slate-400 block text-[10px] font-bold uppercase">Sonraki Bakım Hedefi</span>
                        <span class="text-sm font-mono font-bold text-indigo-600">
                            {{ $healthReport['next_service_date'] ? $healthReport['next_service_date']->format('d.m.Y') : '—' }}
                        </span>
                    </div>
                </div>

                @if($vehicle->customer && $vehicle->customer->phone)
                    @php
                        $waMsg = rawurlencode("Sayın {$vehicle->customer->name}, {$vehicle->plate} plakalı {$vehicle->brand} {$vehicle->model} aracınızın Güncel Sağlık Karnesi:\n".
                        "🟢 Motor: {$healthReport['motor']['label']}\n".
                        "🟡 Fren: {$healthReport['fren']['label']}\n".
                        "🟢 Akü: {$healthReport['aku']['label']}\n".
                        "🔴 Lastikler: {$healthReport['lastikler']['label']}\n".
                        "🟢 Klima: {$healthReport['klima']['label']}\n\n".
                        "Sonraki Bakım Tarihiniz: " . ($healthReport['next_service_date'] ? $healthReport['next_service_date']->format('d.m.Y') : 'Belirtilmedi') . "\nSanayiPro Servis Yönetimi");
                        $cleanPhone = preg_replace('/[^0-9]/', '', $vehicle->customer->phone);
                        if (str_starts_with($cleanPhone, '0')) $cleanPhone = '90' . substr($cleanPhone, 1);
                        if (!str_starts_with($cleanPhone, '90') && strlen($cleanPhone) === 10) $cleanPhone = '90' . $cleanPhone;
                    @endphp
                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waMsg }}" target="_blank"
                       class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-sm flex items-center justify-center gap-2 shrink-0">
                        <span>💬 Karnedeki Durumu Müşteriye WhatsApp'la Gönder</span>
                    </a>
                @endif
            </div>

        </div>

        <!-- ============================================================
             2. SERVİS GEÇMİŞİ LOG LİSTESİ (CHRONOLOGICAL SERVICE LEDGER)
        ============================================================ -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
            
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <span>📜</span> <span>ARAÇ SERVİS GEÇMİŞİ</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Bu araca yapılan tüm bakım, parça değişimi ve tamir kayıtları</p>
                </div>
                <span class="text-xs font-bold bg-slate-100 text-slate-700 px-3 py-1 rounded-full">
                    {{ $vehicle->workOrders->count() }} Servis
                </span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($vehicle->workOrders as $wo)
                    <div class="p-5 sm:p-6 hover:bg-slate-50/70 transition-colors group">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            
                            <!-- Tarih, KM ve Durum -->
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-mono font-black text-slate-900 text-base">
                                        📅 {{ $wo->date->format('d.m.Y') }}
                                    </span>
                                    <span class="text-xs font-mono font-bold bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded-lg border border-slate-200">
                                        ⏱️ {{ number_format($wo->mileage ?? $vehicle->mileage, 0, ',', '.') }} km
                                    </span>
                                    <span class="text-xs px-2.5 py-0.5 rounded-full font-bold
                                        @if($wo->status==='beklemede') bg-amber-100 text-amber-800
                                        @elseif($wo->status==='devam_ediyor') bg-blue-100 text-blue-800
                                        @elseif($wo->status==='tamamlandi') bg-emerald-100 text-emerald-800
                                        @else bg-slate-800 text-white @endif">
                                        {{ $wo->status_label }}
                                    </span>
                                </div>
                                
                                <div class="text-xs text-slate-400">
                                    İş Emri No: <strong class="text-slate-600">#{{ str_pad($wo->id, 5, '0', STR_PAD_LEFT) }}</strong> | 
                                    Usta: <strong class="text-slate-600">{{ $wo->user->name ?? 'Merkez Usta' }}</strong>
                                </div>
                            </div>

                            <!-- Yapılan İşlemler Özeti (Maddeler) -->
                            <div class="flex-1 max-w-lg">
                                <div class="text-xs font-bold text-slate-500 mb-1">Yapılan İşlemler & Değişen Parçalar:</div>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($wo->items as $item)
                                        <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-lg font-medium border
                                            {{ $item->type === 'part' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-purple-50 text-purple-800 border-purple-200' }}">
                                            <span>{{ $item->type === 'part' ? '🔩' : '👷' }}</span>
                                            <span>{{ $item->name }}</span>
                                            @if($item->quantity > 1)<span class="font-bold">({{ $item->quantity }}x)</span>@endif
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Tutar ve İncele Butonu -->
                            <div class="text-right shrink-0 flex md:flex-col items-center md:items-end justify-between md:justify-center gap-2 pt-2 md:pt-0 border-t md:border-0 border-slate-100">
                                <div>
                                    <div class="text-xs text-slate-400">Servis Tutarı</div>
                                    <div class="text-lg font-mono font-black text-slate-900">₺{{ number_format($wo->grand_total, 2, ',', '.') }}</div>
                                </div>
                                <a href="{{ route('work-orders.show', $wo) }}"
                                   class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs px-3.5 py-2 rounded-xl border border-indigo-200 transition-colors inline-flex items-center gap-1">
                                    <span>İş Emrini Gör →</span>
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <span class="text-3xl block mb-2">🚗</span>
                        <p class="text-sm font-bold text-slate-700">Henüz Servis Kaydı Bulunmuyor</p>
                        <p class="text-xs text-slate-400 mt-1">Bu araç için ilk iş emrini yukarıdaki butonla oluşturabilirsiniz.</p>
                    </div>
                @endforelse
            </div>

        </div>

    </div>
</x-app-layout>
