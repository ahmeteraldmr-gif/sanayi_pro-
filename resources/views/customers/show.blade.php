<x-app-layout>
    <x-slot name="title">{{ $customer->name }} — Müşteri Profili</x-slot>

    <div class="space-y-6">

        <!-- Üst Başlık & Müşteri Özet Kardeşliği -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('customers.index') }}" class="p-2.5 text-slate-400 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                        <span>👤 {{ $customer->name }}</span>
                        <span class="text-xs bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded-full font-bold border border-slate-200">Müşteri</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">📞 {{ $customer->phone ?? 'Telefon Yok' }} | 📍 {{ $customer->address ?? 'Adres Belirtilmedi' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('vehicles.create', ['customer_id' => $customer->id]) }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-xs flex items-center gap-1">
                    <span>🚗 Yeni Araç Ekle</span>
                </a>
                <a href="{{ route('customers.edit', $customer) }}"
                   class="border border-slate-300 text-slate-700 text-xs font-bold px-3.5 py-2.5 rounded-xl hover:bg-slate-50 transition-colors">
                    Düzenle
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Sol: Müşteri Künye Kartı -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs h-fit space-y-4">
                <h3 class="text-sm font-black text-slate-900 border-b border-slate-100 pb-3">Müşteri Bilgileri</h3>
                
                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-0.5">Ad Soyad</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $customer->name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Telefon</span>
                        <span class="font-bold font-mono text-slate-800">{{ $customer->phone ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Adres</span>
                        <span class="text-slate-700 leading-relaxed">{{ $customer->address ?? '—' }}</span>
                    </div>
                    @if($customer->notes)
                        <div class="bg-amber-50 p-3 rounded-xl border border-amber-200 text-amber-900">
                            <span class="font-bold block mb-1">📝 Özel Notlar:</span>
                            <span>{{ $customer->notes }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sağ: Kayıtlı Araçlar Ve Servis Geçmişleri -->
            <div class="lg:col-span-2 space-y-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <span>🚗 Kayıtlı Araçlar & Servis Geçmişi</span>
                        <span class="text-xs bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded-full font-bold">{{ $customer->vehicles->count() }} Araç</span>
                    </h3>
                </div>

                @forelse($customer->vehicles as $vehicle)
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden hover:border-slate-300 transition-all">
                        
                        <!-- Araç Başlığı -->
                        <div class="bg-slate-900 text-white p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <h4 class="text-base font-black text-white">🚗 {{ $vehicle->brand }} {{ $vehicle->model }}</h4>
                                    <div class="inline-flex items-center bg-white border border-black rounded font-mono font-black text-black text-xs px-2 py-0.5">
                                        <span class="bg-blue-700 text-white text-[8px] font-bold px-1 rounded mr-1">TR</span>
                                        <span>{{ $vehicle->plate }}</span>
                                    </div>
                                </div>
                                <div class="text-xs text-slate-300 mt-1 flex items-center gap-3 flex-wrap">
                                    <span>Yıl: <strong>{{ $vehicle->year ?? '—' }}</strong></span>
                                    <span>•</span>
                                    <span>Motor: <strong>{{ $vehicle->engine ?? 'Belirtilmedi' }}</strong></span>
                                    <span>•</span>
                                    <span>KM: <strong class="text-emerald-400 font-mono">{{ number_format($vehicle->latest_mileage, 0, ',', '.') }} km</strong></span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('vehicles.show', $vehicle) }}"
                                   class="bg-white/10 hover:bg-white/20 text-white text-xs font-bold px-3 py-2 rounded-xl border border-white/10 transition-colors">
                                    📜 Servis Geçmişi →
                                </a>
                                <a href="{{ route('work-orders.create', ['vehicle_id' => $vehicle->id]) }}"
                                   class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-black px-3 py-2 rounded-xl transition-colors">
                                    + İş Emri
                                </a>
                            </div>
                        </div>

                        <!-- Araç Servis Geçmişi Özeti -->
                        <div class="p-4 sm:p-5">
                            @if($vehicle->workOrders->count())
                                <div class="text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Son Servis Kayıtları</div>
                                <div class="divide-y divide-slate-100">
                                    @foreach($vehicle->workOrders->take(4) as $wo)
                                        <div class="py-2.5 flex items-center justify-between text-xs gap-3">
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono font-bold text-slate-800">📅 {{ $wo->date->format('d.m.Y') }}</span>
                                                <span class="text-slate-400">|</span>
                                                <span class="text-slate-600 truncate max-w-xs">
                                                    @foreach($wo->items->take(2) as $it)
                                                        {{ $it->name }}@if(!$loop->last), @endif
                                                    @endforeach
                                                </span>
                                            </div>

                                            <div class="flex items-center gap-3 shrink-0">
                                                <span class="font-mono font-black text-slate-900">₺{{ number_format($wo->grand_total, 2, ',', '.') }}</span>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                                                    @if($wo->status==='odendi') bg-slate-100 text-slate-700
                                                    @elseif($wo->status==='tamamlandi') bg-emerald-100 text-emerald-800
                                                    @else bg-blue-100 text-blue-800 @endif">
                                                    {{ $wo->status_label }}
                                                </span>
                                                <a href="{{ route('work-orders.show', $wo) }}" class="text-indigo-600 font-bold hover:underline">Gör →</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 text-slate-400 text-xs">
                                    Bu araca ait henüz servis kaydı bulunmuyor.
                                </div>
                            @endif
                        </div>

                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center text-slate-400 text-xs">
                        Bu müşteriye ait henüz kayıtlı araç yok.<br>
                        <a href="{{ route('vehicles.create', ['customer_id' => $customer->id]) }}" class="text-indigo-600 font-bold hover:underline inline-block mt-2">
                            + İlk Aracı Ekle
                        </a>
                    </div>
                @endforelse
            </div>

        </div>

    </div>
</x-app-layout>
