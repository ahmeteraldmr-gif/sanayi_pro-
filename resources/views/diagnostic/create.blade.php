<x-app-layout>
    <x-slot name="title">🤖 Akıllı Arıza Asistanı — Yeni AI Teşhisi</x-slot>

    @php
        $selectedVehicleId = old('vehicle_id', $vehicle->id ?? ($workOrder->vehicle_id ?? ''));
        $selectedWorkOrderId = old('work_order_id', $workOrder->id ?? '');
    @endphp

    <div class="space-y-6" x-data="diagnosticForm({
        initialVehicleId: '{{ $selectedVehicleId }}',
        initialWorkOrderId: '{{ $selectedWorkOrderId }}',
        obdCount: {{ ($session?->obdCodes->count() ?? 0) }}
    })">

        <!-- ============================================================
             1. ÜST HEADER
        ============================================================ -->
        <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ $workOrder ? route('work-orders.show', $workOrder) : route('diagnostic.knowledge-base') }}"
                   class="p-2.5 text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-2xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs bg-indigo-50 border border-indigo-200 text-indigo-700 font-bold px-3 py-1 rounded-full flex items-center gap-1.5">
                            <span>🤖</span>
                            <span>SanayiPro Akıllı Arıza Asistanı</span>
                        </span>
                        @if($workOrder)
                            <span class="text-xs font-mono font-bold text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full border border-slate-200">
                                İş Emri #{{ str_pad($workOrder->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        @endif
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1">
                        Yeni Araç Arıza Teşhisi & AI Analizi
                    </h1>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Belirtileri, OBD kodlarını ve kontrolleri girin; araç geçmişi ve bilgi bankasıyla analiz edin.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ route('diagnostic.knowledge-base') }}"
                   class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl border border-slate-200 transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>📚 Arıza Bilgi Bankası</span>
                </a>
            </div>
        </div>

        <!-- ============================================================
             2. ARAÇ SEÇİMİ VE DETAY KARTLARI
        ============================================================ -->
        <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center">1</span>
                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider">Teşhis Edilecek Araç</h2>
                </div>
                <template x-if="vehicleSelected">
                    <button type="button" @click="resetVehicleSelection()"
                            class="text-xs text-indigo-600 hover:underline font-bold">
                        🔄 Başka Araç Seç
                    </button>
                </template>
            </div>

            <!-- Araç Arama / Seçim Kutusu (Eğer henüz seçilmediyse veya değiştirilmek istenirse) -->
            <div x-show="!vehicleSelected" class="space-y-3">
                <label class="block text-xs font-bold text-slate-700">
                    Sistemdeki Araçlardan Seçin (Plaka veya Müşteri Adı ile Arayın):
                </label>
                <div class="relative max-w-xl">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text"
                           x-model="searchQuery"
                           @input.debounce.250ms="filterVehicles()"
                           placeholder="Plaka veya müşteri adı yazın... (örn: 34, BMW, Mehmet)"
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all">

                    <!-- Arama Dropdown Sonuçları -->
                    <div x-show="showDropdown && filteredVehicles.length > 0"
                         @click.outside="showDropdown = false"
                         class="absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-xl z-30 max-h-64 overflow-y-auto divide-y divide-slate-100">
                        <template x-for="v in filteredVehicles" :key="v.id">
                            <div @click="selectVehicle(v.id)"
                                 class="p-3 hover:bg-indigo-50/60 cursor-pointer transition-colors flex items-center justify-between">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded text-xs" x-text="v.plate"></span>
                                        <strong class="text-xs text-slate-900" x-text="(v.brand || '') + ' ' + (v.model || '')"></strong>
                                        <span class="text-[11px] text-slate-400" x-text="v.year ? '(' + v.year + ')' : ''"></span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">
                                        👤 <span x-text="v.customer ? v.customer.name : 'Müşteri Yok'"></span>
                                        <span x-show="v.customer && v.customer.phone" class="font-mono text-slate-400" x-text="'• ' + v.customer.phone"></span>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">Seç →</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Seçilen Araç Bilgi Kartları (Otomatik Dolar) -->
            <div x-show="vehicleSelected" class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                <!-- Temel Araç Künyesi -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-1">Araç Künyesi</span>
                    <div class="font-mono font-black text-blue-700 text-2xl tracking-wider" x-text="vehicleData.plate"></div>
                    <div class="text-sm font-bold text-slate-900 mt-0.5" x-text="(vehicleData.brand || '') + ' ' + (vehicleData.model || '')"></div>
                    <div class="text-xs text-slate-600 mt-2 space-y-1">
                        <div>Model Yılı: <strong class="text-slate-800" x-text="vehicleData.year || '—'"></strong></div>
                        <div>Motor: <strong class="text-slate-800" x-text="vehicleData.engine || '—'"></strong></div>
                        <div>Güncel Km: <strong class="text-slate-800 font-mono" x-text="vehicleData.mileage ? Number(vehicleData.mileage).toLocaleString('tr-TR') + ' km' : '—'"></strong></div>
                    </div>
                </div>

                <!-- Müşteri Bilgisi -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-1">Araç Sahibi</span>
                        <div class="text-sm font-bold text-slate-900" x-text="vehicleData.customer ? vehicleData.customer.name : 'Kayıtlı Değil'"></div>
                        <div class="text-xs text-slate-600 mt-1 font-mono flex items-center gap-1">
                            <span>📞</span>
                            <span x-text="vehicleData.customer && vehicleData.customer.phone ? vehicleData.customer.phone : 'Telefon belirtilmemiş'"></span>
                        </div>
                    </div>

                    <template x-if="vehicleData.similar_count > 0">
                        <div class="mt-3 bg-indigo-50 border border-indigo-200 rounded-xl p-2.5 text-xs text-indigo-900">
                            💡 Bu araç modelinde <strong><span x-text="vehicleData.similar_count"></span> onaylı çözüm</strong> bilgi bankasında mevcut.
                        </div>
                    </template>
                </div>

                <!-- Servis Geçmişi & Daha Önce Değişen Parçalar -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-1">Servis Geçmişi & Değişenler</span>

                    <template x-if="vehicleData.past_orders && vehicleData.past_orders.length > 0">
                        <div class="space-y-2">
                            <div class="text-xs font-bold text-slate-700">
                                Son <span x-text="vehicleData.past_orders.length"></span> Servis Girişi:
                            </div>
                            <div class="space-y-1 max-h-24 overflow-y-auto pr-1">
                                <template x-for="po in vehicleData.past_orders.slice(0, 3)" :key="po.id">
                                    <div class="text-[11px] text-slate-600 flex items-center justify-between border-b border-slate-200/60 pb-1">
                                        <span x-text="po.date"></span>
                                        <span class="font-mono text-slate-700" x-text="po.mileage + ' km'"></span>
                                        <span class="text-indigo-600 font-semibold" x-text="po.status_label"></span>
                                    </div>
                                </template>
                            </div>

                            <template x-if="vehicleData.past_parts && vehicleData.past_parts.length > 0">
                                <div class="pt-1 text-[11px] text-slate-500">
                                    <strong class="text-slate-700">Önceki Parçalar:</strong>
                                    <span x-text="vehicleData.past_parts.slice(0, 4).join(', ')"></span>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="!vehicleData.past_orders || vehicleData.past_orders.length === 0">
                        <div class="text-xs text-slate-400 py-3 text-center">
                            Aracın geçmiş servis kaydı bulunmuyor (İlk işlem).
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- ============================================================
             3. ANA TEŞHİS FORMU (ARIZA ANALİZİ)
        ============================================================ -->
        <form method="POST" action="{{ route('diagnostic.store-standalone') }}" @submit="handleSubmit($event)" class="space-y-6">
            @csrf
            <input type="hidden" name="vehicle_id" :value="vehicleData.id">
            <input type="hidden" name="work_order_id" :value="workOrderId">

            <!-- 1. ARIZA BELİRTİLERİ -->
            <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-xl bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center">2</span>
                    <div>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider">Araçta Hangi Belirtiler Var?</h2>
                        <p class="text-xs text-slate-400">Birden fazla belirtiyi seçebilir ve ustanın açıklamasını ekleyebilirsiniz.</p>
                    </div>
                </div>

                @php
                    $selectedSymptoms = old('symptoms', $session?->symptoms ?? []);
                    $symptomsList = [
                        'Araç çalışmıyor',
                        'Zor çalışıyor',
                        'Tekleme',
                        'Rölanti düzensizliği',
                        'Performans kaybı',
                        'Fazla yakıt tüketimi',
                        'Motor arıza lambası',
                        'Hararet',
                        'Duman',
                        'Ses / vuruntu',
                        'Titreşim',
                        'Şarj problemi',
                        'Elektrik problemi',
                        'Fren problemi',
                        'Klima problemi',
                        'Çekiş düşüklüğü',
                        'Turbo problemi',
                    ];
                @endphp

                <!-- Belirti Seçim Chips / Checkboxes -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2.5">
                    @foreach($symptomsList as $symptom)
                        <label class="flex items-center gap-2.5 p-3 min-h-[44px] border border-slate-200 rounded-2xl cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/40 transition-all text-xs font-medium
                            {{ in_array($symptom, $selectedSymptoms) ? 'border-indigo-400 bg-indigo-50/80 text-indigo-950 font-bold shadow-2xs' : 'text-slate-700 bg-slate-50/60' }}">
                            <input type="checkbox"
                                   name="symptoms[]"
                                   value="{{ $symptom }}"
                                   {{ in_array($symptom, $selectedSymptoms) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span>{{ $symptom }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Diğer Belirtiler / Ustanın Açıklaması -->
                <div class="pt-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Diğer Belirtiler / Ustanın Açıklaması:
                    </label>
                    <textarea name="custom_symptom" rows="2"
                              placeholder="Listede yer almayan belirtileri veya ses/vuruntunun ne zaman olduğunu detaylandırın... (Örn: Sabahları ilk marşta 5 saniye beyaz duman atıyor, sonrasında kesiliyor)"
                              class="w-full text-xs sm:text-sm border border-slate-200 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-indigo-600 focus:outline-none resize-none bg-slate-50/50 focus:bg-white transition-all">{{ old('custom_symptom') }}</textarea>
                </div>

                <!-- Müşteri Şikayeti (Opsiyonel) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Müşteri Şikayeti (Müşterinin İfadesi):
                    </label>
                    <input type="text" name="complaint"
                           value="{{ old('complaint', $session?->complaint ?? ($workOrder?->notes ?? '')) }}"
                           placeholder="Müşterinin aktardığı sorun özeti..."
                           class="w-full text-xs sm:text-sm border border-slate-200 rounded-2xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-600 focus:outline-none bg-slate-50/50 focus:bg-white transition-all">
                </div>
            </div>

            <!-- 2. OBD HATA KODLARI -->
            <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-xl bg-rose-100 text-rose-700 font-bold text-xs flex items-center justify-center">3</span>
                        <div>
                            <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider">OBD Hata Kodları (İsteğe Bağlı)</h2>
                            <p class="text-xs text-slate-400">Arıza tespit cihazından okunan hata kodlarını ekleyin (örn: P0401, P0101, P0299).</p>
                        </div>
                    </div>
                    <button type="button" @click="addObdCode()"
                            class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-3.5 py-2 rounded-xl transition-all shadow-xs flex items-center gap-1.5">
                        <span>+ Kod Ekle</span>
                    </button>
                </div>

                <!-- Dinamik OBD Satırları -->
                <div id="obd-codes-container" class="space-y-3">
                    @php $existingObds = $session?->obdCodes ?? collect(); @endphp
                    @if($existingObds->isNotEmpty())
                        @foreach($existingObds as $i => $obd)
                            <div class="obd-row grid grid-cols-1 sm:grid-cols-4 gap-2.5 p-3.5 bg-slate-50 border border-slate-200 rounded-2xl">
                                <input type="text" name="obd_codes[{{ $i }}][code]"
                                       value="{{ $obd->code }}"
                                       placeholder="P0401"
                                       class="font-mono uppercase font-bold text-xs sm:text-sm border border-slate-300 rounded-xl px-3.5 py-2 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                                <input type="text" name="obd_codes[{{ $i }}][description]"
                                       value="{{ $obd->description }}"
                                       placeholder="Açıklama (EGR Akış Yetersiz)"
                                       class="text-xs border border-slate-300 rounded-xl px-3.5 py-2 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                                <input type="text" name="obd_codes[{{ $i }}][system]"
                                       value="{{ $obd->system }}"
                                       placeholder="Sistem (Egzost / Yakıt)"
                                       class="text-xs border border-slate-300 rounded-xl px-3.5 py-2 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                                <div class="flex gap-2">
                                    <input type="text" name="obd_codes[{{ $i }}][usta_note]"
                                           value="{{ $obd->usta_note }}"
                                           placeholder="Usta Notu (Örn: Valf tıkalı olabilir)"
                                           class="flex-1 text-xs border border-slate-300 rounded-xl px-3.5 py-2 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                                    <button type="button" onclick="this.closest('.obd-row').remove()"
                                            class="text-rose-500 hover:text-rose-700 p-2 hover:bg-rose-50 rounded-xl transition-colors font-bold">×</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="text-[11px] text-slate-400 flex items-center justify-between">
                    <span>💡 OBD kodu yoksa bu bölümü boş bırakarak analize devam edebilirsiniz.</span>
                </div>
            </div>

            <!-- 3. ŞİMDİYE KADAR YAPILAN KONTROLLER & ÖLÇÜMLER -->
            <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <span class="w-7 h-7 rounded-xl bg-amber-100 text-amber-800 font-bold text-xs flex items-center justify-center">4</span>
                    <div>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-wider">Şimdiye Kadar Neleri Kontrol Ettin?</h2>
                        <p class="text-xs text-slate-400">Kontrol ettiğiniz parçaları seçin; AI aynı şeyleri tekrar önermez, sonraki adıma geçer.</p>
                    </div>
                </div>

                @php
                    $checksPerformedList = [
                        'Akü kontrol edildi',
                        'Sigortalar kontrol edildi',
                        'Yakıt basıncı kontrol edildi',
                        'Sensörler kontrol edildi',
                        'Enjektör kontrol edildi',
                        'Ateşleme sistemi kontrol edildi',
                        'Hava sistemi kontrol edildi',
                        'EGR kontrol edildi',
                        'Turbo kontrol edildi',
                    ];
                    $selectedChecks = old('checks_performed', $session?->checks_performed ?? []);
                @endphp

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                    @foreach($checksPerformedList as $checkItem)
                        <label class="flex items-center gap-2.5 p-3 border border-slate-200 rounded-2xl cursor-pointer hover:border-amber-300 hover:bg-amber-50/30 transition-all text-xs font-medium
                            {{ in_array($checkItem, $selectedChecks) ? 'border-amber-400 bg-amber-50/70 text-amber-950 font-bold shadow-2xs' : 'text-slate-700 bg-slate-50/60' }}">
                            <input type="checkbox"
                                   name="checks_performed[]"
                                   value="{{ $checkItem }}"
                                   {{ in_array($checkItem, $selectedChecks) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                            <span>{{ $checkItem }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Ölçüm Sonuçları & Usta Notları -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Ustanın Yaptığı Ölçümler (Volt, Bar, Basınç vb.):
                        </label>
                        <textarea name="measurements" rows="3"
                                  placeholder="Örn: Akü 12.6V, marş sırasında 10.8V&#10;Yakıt ray basıncı rölantide 290 bar&#10;Kompresyon: 1-13, 2-13, 3-12, 4-9 bar"
                                  class="w-full text-xs sm:text-sm border border-slate-200 rounded-2xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-600 focus:outline-none resize-none bg-slate-50/50 focus:bg-white">{{ old('measurements', $session?->measurements) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Daha Önce Yapılan İşlemler & Ek Notlar:
                        </label>
                        <textarea name="previous_work" rows="3"
                                  placeholder="Örn: 2 ay önce triger seti ve yakıt filtresi yenilendi&#10;Araç lpg'li, benzinde de aynı sorunu yapıyor"
                                  class="w-full text-xs sm:text-sm border border-slate-200 rounded-2xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-600 focus:outline-none resize-none bg-slate-50/50 focus:bg-white">{{ old('previous_work', $session?->previous_work ?? $session?->usta_notes) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- ============================================================
                 4. BÜYÜK ANALİZ ET BUTONU & SPAM KORUMASI
            ============================================================ -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 shadow-xl border border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                <div>
                    <h3 class="text-base font-black text-white">Yapay Zeka Analizini Başlat</h3>
                    <p class="text-xs text-slate-300 mt-0.5">
                        Girdiğiniz belirtiler, kontroller ve aracın servis geçmişi değerlendirilerek olası nedenler ve kontrol sırası oluşturulacaktır.
                    </p>
                </div>

                <button type="submit"
                        :disabled="isSubmitting || !vehicleData.id"
                        class="w-full sm:w-auto bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-black text-sm px-8 py-4 rounded-2xl shadow-xl shadow-indigo-600/30 transition-all flex items-center justify-center gap-2.5 shrink-0 active:scale-95">
                    <span x-show="!isSubmitting">🤖 ARIZAYI ANALİZ ET</span>
                    <span x-show="isSubmitting" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Analiz Ediliyor...</span>
                    </span>
                    <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>

    </div>

    <!-- Alpine.js Diagnostic Form Controller -->
    <script>
    function diagnosticForm(config) {
        return {
            searchQuery: '',
            showDropdown: false,
            vehicleSelected: false,
            isSubmitting: false,
            workOrderId: config.initialWorkOrderId || '',
            obdIndex: config.obdCount || 0,
            vehicleData: {
                id: '',
                plate: '',
                brand: '',
                model: '',
                year: '',
                engine: '',
                mileage: '',
                color: '',
                customer: null,
                past_orders: [],
                past_parts: [],
                similar_count: 0
            },
            allVehicles: @json($vehicles ?? []),
            filteredVehicles: [],

            init() {
                this.filteredVehicles = this.allVehicles;

                if (config.initialVehicleId) {
                    this.selectVehicle(config.initialVehicleId);
                }
            },

            filterVehicles() {
                const q = this.searchQuery.trim().toLowerCase();
                if (!q) {
                    this.filteredVehicles = this.allVehicles;
                    this.showDropdown = false;
                    return;
                }
                this.filteredVehicles = this.allVehicles.filter(v => {
                    const plate = (v.plate || '').toLowerCase();
                    const brand = (v.brand || '').toLowerCase();
                    const model = (v.model || '').toLowerCase();
                    const cust = (v.customer?.name || '').toLowerCase();
                    return plate.includes(q) || brand.includes(q) || model.includes(q) || cust.includes(q);
                });
                this.showDropdown = true;
            },

            async selectVehicle(id) {
                if (!id) return;
                try {
                    const res = await fetch('/diagnostic/vehicle-details/' + id);
                    const data = await res.json();
                    if (data.success) {
                        this.vehicleData = {
                            id: data.vehicle.id,
                            plate: data.vehicle.plate,
                            brand: data.vehicle.brand,
                            model: data.vehicle.model,
                            year: data.vehicle.year,
                            engine: data.vehicle.engine,
                            mileage: data.vehicle.mileage,
                            color: data.vehicle.color,
                            customer: data.customer,
                            past_orders: data.past_orders,
                            past_parts: data.past_parts,
                            similar_count: data.similar_count
                        };
                        this.vehicleSelected = true;
                        this.showDropdown = false;
                    }
                } catch (e) {
                    console.error('Araç verisi alınamadı:', e);
                }
            },

            resetVehicleSelection() {
                this.vehicleSelected = false;
                this.vehicleData.id = '';
                this.searchQuery = '';
                this.showDropdown = false;
            },

            addObdCode() {
                const container = document.getElementById('obd-codes-container');
                const idx = this.obdIndex++;
                const html = `
                    <div class="obd-row grid grid-cols-1 sm:grid-cols-4 gap-2.5 p-3.5 bg-slate-50 border border-slate-200 rounded-2xl">
                        <input type="text" name="obd_codes[${idx}][code]"
                               placeholder="P0401"
                               class="font-mono uppercase font-bold text-xs sm:text-sm border border-slate-300 rounded-xl px-3.5 py-2 focus:ring-2 focus:ring-indigo-600 focus:outline-none"
                               oninput="this.value=this.value.toUpperCase()">
                        <input type="text" name="obd_codes[${idx}][description]"
                               placeholder="Açıklama (EGR Akış Yetersiz)"
                               class="text-xs border border-slate-300 rounded-xl px-3.5 py-2 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        <input type="text" name="obd_codes[${idx}][system]"
                               placeholder="Sistem (Egzost / Yakıt)"
                               class="text-xs border border-slate-300 rounded-xl px-3.5 py-2 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        <div class="flex gap-2">
                            <input type="text" name="obd_codes[${idx}][usta_note]"
                                   placeholder="Usta Notu"
                                   class="flex-1 text-xs border border-slate-300 rounded-xl px-3.5 py-2 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                            <button type="button" onclick="this.closest('.obd-row').remove()"
                                    class="text-rose-500 hover:text-rose-700 p-2 hover:bg-rose-50 rounded-xl transition-colors font-bold">×</button>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
            },

            handleSubmit(e) {
                if (!this.vehicleData.id) {
                    e.preventDefault();
                    alert('Lütfen önce teşhis edilecek aracı seçin.');
                    return;
                }
                this.isSubmitting = true;
            }
        }
    }
    </script>
</x-app-layout>
