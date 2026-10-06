<x-app-layout>
    <x-slot name="title">🔍 Arıza Teşhis Raporu — {{ $vehicle?->plate ?? ($workOrder?->vehicle?->plate ?? ($session->vehicle?->plate ?? 'SanayiPro')) }}</x-slot>

    @php
        $targetVehicle = $vehicle ?? ($session->vehicle ?? ($workOrder->vehicle ?? null));
        $targetWorkOrder = $workOrder ?? ($session->workOrder ?? null);
    @endphp

    <div class="space-y-6" x-data="{
        solutionModalOpen: false,
        aiFeedback: '{{ $session->ai_feedback ?? '' }}',
        solutionStatus: '{{ $session->solution_status ?? 'tamamen_cozuldu' }}',
        isSubmittingFeedback: false,

        async sendAiFeedback(type) {
            this.isSubmittingFeedback = true;
            try {
                const res = await fetch('{{ route('diagnostic.save-ai-feedback', $session) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ai_feedback: type })
                });
                const data = await res.json();
                if (data.success) {
                    this.aiFeedback = type;
                }
            } catch (e) {
                console.error(e);
            }
            this.isSubmittingFeedback = false;
        }
    }">

        <!-- ============================================================
             1. ÜST HEADER
        ============================================================ -->
        <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ $targetWorkOrder ? route('work-orders.show', $targetWorkOrder) : route('diagnostic.knowledge-base') }}"
                   class="p-2.5 text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-2xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs bg-indigo-50 border border-indigo-200 text-indigo-700 font-bold px-3 py-1 rounded-full flex items-center gap-1.5">
                            <span>🤖</span>
                            <span>SanayiPro Arıza Teşhis Raporu</span>
                        </span>
                        <span class="text-xs font-mono font-bold text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full border border-slate-200">
                            Teşhis #{{ str_pad($session->id, 5, '0', STR_PAD_LEFT) }}
                        </span>
                        @if($session->solution_status)
                            <span class="text-xs font-bold px-3 py-0.5 rounded-full border
                                {{ $session->solution_status === 'tamamen_cozuldu' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-amber-100 text-amber-800 border-amber-200' }}">
                                {{ $session->solution_status === 'tamamen_cozuldu' ? '✅ Sorun Tamamen Çözüldü' : ($session->solution_status === 'kismen_cozuldu' ? '⚠️ Kısmen Çözüldü' : '⏳ Devam Ediyor') }}
                            </span>
                        @endif
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1">
                        {{ $targetVehicle?->brand }} {{ $targetVehicle?->model }}
                        <span class="text-indigo-600 font-mono">({{ $targetVehicle?->plate }})</span>
                    </h1>
                    <p class="text-xs text-slate-400 mt-0.5">
                        Analiz Tarihi: <strong class="text-slate-600">{{ $session->ai_analyzed_at ? $session->ai_analyzed_at->format('d.m.Y H:i') : now()->format('d.m.Y H:i') }}</strong>
                        @if($targetWorkOrder)
                            · İş Emri #{{ str_pad($targetWorkOrder->id, 5, '0', STR_PAD_LEFT) }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ route('diagnostic.new', array_filter(['work_order_id' => $targetWorkOrder?->id, 'vehicle_id' => $targetVehicle?->id])) }}"
                   class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl border border-slate-200 transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Formu Düzenle</span>
                </a>
                <button type="button" @click="solutionModalOpen = true"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
                    <span>✅ Sorunu Çözdüm</span>
                </button>
            </div>
        </div>

        <!-- ============================================================
             2. ARAÇ KÜNYESİ & BELİRTİLER ÖZETİ
        ============================================================ -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Araç Bilgisi -->
            <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-1.5">Araç & Kilometre</span>
                <div class="font-mono font-black text-blue-700 text-2xl tracking-wider">{{ $targetVehicle?->plate }}</div>
                <div class="text-sm font-bold text-slate-900 mt-0.5">{{ $targetVehicle?->brand }} {{ $targetVehicle?->model }}</div>
                <div class="mt-2 pt-2 border-t border-slate-100 text-xs text-slate-600 space-y-1">
                    <div>Yıl: <strong class="text-slate-800">{{ $targetVehicle?->year ?? '—' }}</strong></div>
                    <div>Motor: <strong class="text-slate-800">{{ $targetVehicle?->engine ?? '—' }}</strong></div>
                    <div>Kilometre: <strong class="text-slate-800 font-mono">{{ $session->mileage ? number_format($session->mileage, 0, ',', '.') : ($targetVehicle?->mileage ? number_format($targetVehicle->mileage, 0, ',', '.') : '—') }} km</strong></div>
                </div>
            </div>

            <!-- Bildirilen Belirtiler -->
            <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-1.5">🔍 Bildirilen Belirtiler</span>
                @if(!empty($session->symptoms))
                    <div class="flex flex-wrap gap-1.5 max-h-28 overflow-y-auto pr-1">
                        @foreach($session->symptoms as $sym)
                            <span class="bg-indigo-50/80 border border-indigo-200 text-indigo-900 text-[11px] font-semibold px-2.5 py-1 rounded-lg">
                                • {{ $sym }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <span class="text-xs text-slate-400">Belirti seçilmedi.</span>
                @endif

                @if($session->complaint)
                    <div class="mt-2.5 pt-2 border-t border-slate-100 text-[11px] text-slate-600 italic">
                        "{{ $session->complaint }}"
                    </div>
                @endif
            </div>

            <!-- OBD Hata Kodları -->
            <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-1.5">📟 OBD Hata Kodları</span>
                @if($session->obdCodes->isNotEmpty())
                    <div class="space-y-1.5 max-h-32 overflow-y-auto pr-1">
                        @foreach($session->obdCodes as $obd)
                            <div class="flex items-center gap-2 text-xs bg-rose-50/80 border border-rose-200/80 px-2.5 py-1.5 rounded-xl">
                                <span class="font-mono font-black text-rose-700">{{ $obd->code }}</span>
                                <span class="text-[11px] text-slate-700 truncate" title="{{ $obd->description }}">{{ $obd->description ?: 'Tanımsız' }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-xs text-slate-400 py-3">OBD hata kodu girilmedi.</div>
                @endif
            </div>

            <!-- Yapılan Kontroller & Ölçümler -->
            <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-1.5">🛠️ Şimdiye Kadarki Kontroller</span>
                @if(!empty($session->checks_performed))
                    <div class="flex flex-wrap gap-1 mb-2">
                        @foreach($session->checks_performed as $chk)
                            <span class="bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-semibold px-2 py-0.5 rounded">
                                ✓ {{ $chk }}
                            </span>
                        @endforeach
                    </div>
                @endif

                @if($session->measurements)
                    <div class="text-[11px] text-slate-600 bg-slate-50 p-2 rounded-xl border border-slate-200/60 line-clamp-2" title="{{ $session->measurements }}">
                        <strong>Ölçümler:</strong> {{ $session->measurements }}
                    </div>
                @elseif(empty($session->checks_performed))
                    <span class="text-xs text-slate-400">Ön kontrol belirtilmedi.</span>
                @endif
            </div>
        </div>

        <!-- ============================================================
             3. PROFESYONEL AI TEŞHİS RAPORU (COCKPIT LAYOUT)
        ============================================================ -->
        <div class="bg-white border-2 border-indigo-100 rounded-3xl shadow-sm overflow-hidden">
            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold text-2xl shadow-md shrink-0">
                        🤖
                    </div>
                    <div>
                        <div class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-300">SanayiPro Otomotiv Teşhis Sistemi</div>
                        <h2 class="text-lg sm:text-xl font-black text-white">Yapay Zeka Arıza Analiz Raporu</h2>
                    </div>
                </div>
                <div class="bg-slate-800/90 border border-slate-700 text-slate-300 text-xs px-3.5 py-1.5 rounded-full flex items-center gap-2 self-start sm:self-auto">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>AI Analizi Tamamlandı</span>
                </div>
            </div>

            <!-- Genel Değerlendirme & Teşhis Özeti -->
            @if($session->ai_summary)
                <div class="bg-indigo-50/70 border-b border-indigo-100 p-5 sm:p-6">
                    <div class="flex items-start gap-3">
                        <span class="text-indigo-600 text-xl shrink-0 mt-0.5">📌</span>
                        <div>
                            <span class="text-xs font-black uppercase tracking-wider text-indigo-950 block mb-1">Genel Değerlendirme & Teşhis Özeti</span>
                            <p class="text-sm text-slate-800 leading-relaxed font-medium">
                                {{ $session->ai_summary }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="p-5 sm:p-6 space-y-6">

                <!-- ============================================================
                     8. ÖNERİLEN KONTROL SIRASI (STEP BY STEP)
                ============================================================ -->
                @php $sequence = $session->recommended_sequence; @endphp
                @if(!empty($sequence))
                    <div class="bg-gradient-to-br from-slate-900 to-indigo-950 text-white rounded-3xl p-5 sm:p-6 shadow-md border border-indigo-900/60">
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-indigo-800/50">
                            <div class="flex items-center gap-2">
                                <span class="text-lg">🧭</span>
                                <div>
                                    <h3 class="text-sm font-black uppercase tracking-wider text-indigo-200">ÖNERİLEN KONTROL SIRASI</h3>
                                    <p class="text-[11px] text-slate-400">Basitten karmaşığa doğru tavsiye edilen teşhis aşamaları (Yardımcı öneridir)</p>
                                </div>
                            </div>
                            <span class="text-[10px] bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 px-2.5 py-1 rounded-full font-bold">
                                Adım Adım İlerleme
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            @foreach($sequence as $idx => $step)
                                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-4 flex flex-col justify-between hover:border-indigo-400 transition-colors group">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-mono font-black text-xs text-indigo-400 bg-indigo-500/20 px-2 py-0.5 rounded-md">
                                                ADIM {{ $idx + 1 }}
                                            </span>
                                            <span class="text-slate-500 group-hover:text-indigo-300 transition-colors">→</span>
                                        </div>
                                        <p class="text-xs text-slate-200 leading-relaxed font-medium">
                                            {{ preg_replace('/^\d+\s*[→\-:]\s*/u', '', $step) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- ============================================================
                     7. OLASI NEDENLER (YÜKSEK / ORTA / DÜŞÜK OLASILIK KARTLARI)
                ============================================================ -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                            <span>🔍</span>
                            <span>Olası Arıza Nedenleri ve Kontrol Adımları</span>
                        </h3>
                        <span class="text-xs text-slate-400 font-semibold">Olasılık seviyesine göre gruplanmıştır</span>
                    </div>

                    @php $causes = $session->possible_causes; @endphp

                    @if(!empty($causes))
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($causes as $index => $cause)
                                @php
                                    $prob = mb_strtolower($cause['probability'] ?? 'orta');
                                    $isHigh = str_contains($prob, 'yüksek') || str_contains($prob, 'high');
                                    $isLow  = str_contains($prob, 'düşük') || str_contains($prob, 'low');

                                    $badgeClass = $isHigh
                                        ? 'bg-rose-100 text-rose-800 border-rose-200'
                                        : ($isLow ? 'bg-slate-100 text-slate-700 border-slate-200' : 'bg-amber-100 text-amber-800 border-amber-200');
                                    $borderClass = $isHigh
                                        ? 'border-rose-200 hover:border-rose-300 bg-rose-50/20'
                                        : ($isLow ? 'border-slate-200 hover:border-slate-300' : 'border-amber-200 hover:border-amber-300 bg-amber-50/20');
                                    $bulletIcon = $isHigh ? '🔴' : ($isLow ? '⚪' : '🟠');
                                @endphp

                                <div class="border rounded-2xl p-5 transition-all shadow-xs {{ $borderClass }}">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-7 h-7 rounded-xl bg-slate-900 text-white font-mono font-bold text-xs flex items-center justify-center shrink-0">
                                                {{ $index + 1 }}
                                            </span>
                                            <h4 class="text-base font-black text-slate-900">
                                                {{ $cause['title'] ?? 'Belirlenen Neden' }}
                                            </h4>
                                        </div>
                                        <span class="text-xs font-bold px-3 py-1 rounded-full border self-start sm:self-auto flex items-center gap-1.5 {{ $badgeClass }}">
                                            <span>{{ $bulletIcon }}</span>
                                            <span>Olasılık: {{ $cause['probability'] ?? 'Orta' }}</span>
                                        </span>
                                    </div>

                                    @if(!empty($cause['explanation']))
                                        <p class="text-xs text-slate-600 mb-3.5 leading-relaxed pl-9">
                                            {{ $cause['explanation'] }}
                                        </p>
                                    @endif

                                    <!-- Kontrol Edilmesi Önerilenler -->
                                    @if(!empty($cause['checks']))
                                        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 ml-0 sm:ml-9 space-y-2">
                                            <div class="text-[11px] font-black uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                                <span>🛠️</span>
                                                <span>Ustanın Kontrol Etmesi Önerilenler:</span>
                                            </div>
                                            <ul class="space-y-1.5 text-xs text-slate-700">
                                                @foreach($cause['checks'] as $check)
                                                    <li class="flex items-start gap-2">
                                                        <span class="text-indigo-600 font-bold">✓</span>
                                                        <span>{{ $check }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Fallback / Ham Metin -->
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 text-xs text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ $session->ai_result['raw_text'] ?? 'AI analiz sonucu yapılandırılamadı.' }}
                        </div>
                    @endif
                </div>

                <!-- Önemli Güvenlik ve Yasal Uyarı Notu -->
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
                    <span class="text-amber-600 text-xl mt-0.5">⚠️</span>
                    <div class="text-xs text-amber-900 leading-relaxed">
                        <strong>Önemli Hatırlatma:</strong> Yapay zeka sonucu kesin teşhis değildir. Fiziksel kontrol, ölçüm ve ustanın tecrübesi esastır. Sistem fren, direksiyon veya diğer güvenlik açısından kritik aksamlarda doğrulanmamış bir işlemi kesin çözüm olarak sunamaz; fiziki test ve güvenlik kontrolleri zorunludur.
                    </div>
                </div>

                <!-- ============================================================
                     12. AI GERİ BİLDİRİMİ (HELPFUL / UNHELPFUL / DIFFERENT)
                ============================================================ -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider">AI Teşhis Önerileri Hakkında Ne Düşünüyorsunuz?</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Geri bildiriminiz AI modelinin araç arıza öğrenme kalitesini artırır.</p>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <button type="button" @click="sendAiFeedback('helpful')"
                                :class="aiFeedback === 'helpful' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                                class="text-xs font-bold px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5">
                            <span>👍</span>
                            <span>Öneri Yardımcı Oldu</span>
                        </button>

                        <button type="button" @click="sendAiFeedback('not_helpful')"
                                :class="aiFeedback === 'not_helpful' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                                class="text-xs font-bold px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5">
                            <span>👎</span>
                            <span>Yardımcı Olmadı</span>
                        </button>

                        <button type="button" @click="sendAiFeedback('different_cause')"
                                :class="aiFeedback === 'different_cause' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                                class="text-xs font-bold px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5">
                            <span>🔧</span>
                            <span>Sorun Farklı Çıktı</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- ============================================================
                 10 & 11. SORUNU ÇÖZDÜM BAR
            ============================================================ -->
            <div class="border-t border-slate-200 bg-slate-50 p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h4 class="text-sm font-black text-slate-900">Arızayı Çözdünüz mü?</h4>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Bulduğunuz gerçek arızayı kaydedin; vaka otomatik olarak <strong>Arıza Bilgi Bankası</strong> arşivine eklensin.
                        </p>
                    </div>

                    <button type="button" @click="solutionModalOpen = true"
                            class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-6 py-3 rounded-2xl shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2">
                        <span>✅ SORUNU ÇÖZDÜM (BİLGİ BANKASINA KAYDET)</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ============================================================
             9. BENZER GEÇMİŞ VAKALAR (BİLGİ BANKASI EŞLEŞMELERİ)
        ============================================================ -->
        @if($similarCases->isNotEmpty())
            <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-xs">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                            <span>📚</span>
                            <span>BENZER GEÇMİŞ VAKALAR</span>
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Sistem arşivinde bu arıza ve araçla ilgili <strong>{{ $similarCases->count() }} benzer geçmiş servis vakası</strong> bulundu.
                        </p>
                    </div>
                    <a href="{{ route('diagnostic.knowledge-base', ['brand' => $targetVehicle?->brand]) }}"
                       class="text-xs text-indigo-600 font-bold hover:underline">
                        Tüm Arşivi Gör →
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($similarCases as $case)
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between hover:border-indigo-300 transition-colors">
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="font-bold text-slate-900 text-xs">{{ $case->vehicle_brand }} {{ $case->vehicle_model }}</span>
                                    @if($case->mileage)
                                        <span class="font-mono text-[10px] text-slate-400">{{ number_format($case->mileage, 0, ',', '.') }} km</span>
                                    @endif
                                </div>

                                @if(!empty($case->symptoms))
                                    <div class="text-[11px] text-slate-500 mb-2">
                                        <strong>Belirtiler:</strong> {{ implode(', ', array_slice($case->symptoms, 0, 3)) }}
                                    </div>
                                @endif

                                @if(!empty($case->obd_codes))
                                    <div class="flex flex-wrap gap-1 mb-2">
                                        @foreach($case->obd_codes as $code)
                                            <span class="bg-rose-50 text-rose-700 font-mono font-bold px-2 py-0.5 rounded text-[10px] border border-rose-200">
                                                OBD: {{ $code }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="bg-white p-2.5 rounded-xl border border-slate-200/80 mb-2 text-xs">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase">Gerçek Bulunan Sorun:</div>
                                    <div class="font-bold text-emerald-700 mt-0.5">{{ $case->root_cause }}</div>
                                </div>

                                <div class="text-[11px] text-slate-600">
                                    <strong>Yapılan İşlem:</strong> {{ $case->action_taken }}
                                </div>
                            </div>

                            <div class="mt-3 pt-2 border-t border-slate-200/60 flex items-center justify-between text-[10px] text-slate-400">
                                <span>📅 {{ $case->created_at->format('d.m.Y') }}</span>
                                <span>👨‍🔧 {{ $case->solvedBy->name ?? 'Usta' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- ============================================================
             10 & 11. SORUNU ÇÖZDÜM FORMU MODALI
        ============================================================ -->
        <div x-show="solutionModalOpen" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
            <div class="bg-white border border-slate-200 rounded-3xl p-6 w-full max-w-lg shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                        <span>✅</span>
                        <span>Gerçek Çözümü Kaydet & Bilgi Bankasına Ekle</span>
                    </h3>
                    <button type="button" @click="solutionModalOpen = false" class="text-slate-400 hover:text-slate-700 text-xl font-bold">&times;</button>
                </div>

                <form method="POST" action="{{ route('diagnostic.save-solution', $session) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Bulunan Gerçek Arıza *
                        </label>
                        <input type="text" name="root_cause" required
                               placeholder="Örn: 2 numaralı kızdırma bujisi patlak, röle kontakları paslıydı"
                               class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Yapılan İşlem *
                        </label>
                        <textarea name="action_taken" rows="2" required
                                  placeholder="Örn: 4 adet Bosch kızdırma bujisi yenilendi, besleme rölesi ve soket temizlendi"
                                  class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-600 focus:outline-none resize-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Değiştirilen Parçalar (Virgülle ayırın)
                        </label>
                        <input type="text" name="parts_replaced"
                               placeholder="Örn: Bosch Kızdırma Bujisi, Kızdırma Rölesi"
                               class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Ek Not / Usta Görüşü
                        </label>
                        <textarea name="extra_note" rows="2"
                                  placeholder="İleride aynı araç geldiğinde faydalı olabilecek ek not..."
                                  class="w-full text-xs sm:text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-600 focus:outline-none resize-none"></textarea>
                    </div>

                    <!-- Sonuç Durumu -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Sonuç Durumu:
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="flex items-center gap-1.5 p-2.5 border rounded-xl cursor-pointer text-xs font-semibold
                                {{ ($session->solution_status ?? 'tamamen_cozuldu') === 'tamamen_cozuldu' ? 'bg-emerald-50 border-emerald-300 text-emerald-800' : 'bg-slate-50 border-slate-200 text-slate-700' }}">
                                <input type="radio" name="solution_status" value="tamamen_cozuldu" checked class="text-emerald-600 focus:ring-emerald-500">
                                <span>Tamamen Çözüldü</span>
                            </label>

                            <label class="flex items-center gap-1.5 p-2.5 border rounded-xl cursor-pointer text-xs font-semibold
                                {{ ($session->solution_status ?? '') === 'kismen_cozuldu' ? 'bg-amber-50 border-amber-300 text-amber-800' : 'bg-slate-50 border-slate-200 text-slate-700' }}">
                                <input type="radio" name="solution_status" value="kismen_cozuldu" class="text-amber-600 focus:ring-amber-500">
                                <span>Kısmen Çözüldü</span>
                            </label>

                            <label class="flex items-center gap-1.5 p-2.5 border rounded-xl cursor-pointer text-xs font-semibold
                                {{ ($session->solution_status ?? '') === 'devam_ediyor' ? 'bg-rose-50 border-rose-300 text-rose-800' : 'bg-slate-50 border-slate-200 text-slate-700' }}">
                                <input type="radio" name="solution_status" value="devam_ediyor" class="text-rose-600 focus:ring-rose-500">
                                <span>Devam Ediyor</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                        <button type="button" @click="solutionModalOpen = false"
                                class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                            Vazgeç
                        </button>
                        <button type="submit"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition-colors shadow-xs">
                            Kaydet & Arşive Ekle
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
