<x-app-layout>
    <x-slot name="title">İş Emri #{{ str_pad($workOrder->id, 5, '0', STR_PAD_LEFT) }} — {{ $workOrder->vehicle->plate }}</x-slot>

    <div x-data="workOrderHub({{ $workOrder->id }}, {{ json_encode($workOrder->tasks) }})" class="space-y-6">

        <!-- ============================================================
             1. ÜST BAŞLIK VE HIZLI EYLEMLER
        ============================================================ -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <div class="flex items-center gap-4">
                <a href="{{ route('work-orders.index') }}" class="p-2.5 text-slate-400 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-mono font-black text-slate-900 text-lg sm:text-xl">İş Emri #{{ str_pad($workOrder->id, 5, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-xs px-2.5 py-1 rounded-full font-bold
                            @if($workOrder->status==='beklemede') bg-amber-100 text-amber-800 border border-amber-200
                            @elseif($workOrder->status==='devam_ediyor') bg-blue-100 text-blue-800 border border-blue-200
                            @elseif($workOrder->status==='tamamlandi') bg-emerald-100 text-emerald-800 border border-emerald-200
                            @else bg-slate-800 text-white @endif">
                            {{ $workOrder->status_label }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Kayıt Tarihi: <strong class="text-slate-700">{{ $workOrder->date->format('d.m.Y') }}</strong> | Atölye Servis Kaydı
                    </p>
                </div>
            </div>

            <!-- Sağ Butonlar -->
            <div class="flex flex-wrap items-center gap-2">
                
                <!-- WhatsApp İle Gönder -->
                @if($workOrder->vehicle->customer->phone)
                    @php
                        $rawPhone = preg_replace('/[^0-9]/', '', $workOrder->vehicle->customer->phone);
                        if (str_starts_with($rawPhone, '0')) { $rawPhone = '90' . substr($rawPhone, 1); }
                        elseif (!str_starts_with($rawPhone, '90')) { $rawPhone = '90' . $rawPhone; }

                        $msgLines = [
                            "Sayın " . $workOrder->vehicle->customer->name . ",",
                            $workOrder->vehicle->plate . " (" . $workOrder->vehicle->brand . " " . $workOrder->vehicle->model . ") aracınızın servis detayları:",
                            "Durum: " . $workOrder->status_label,
                            "",
                            "🔧 Yapılan İşlemler:"
                        ];
                        foreach($workOrder->items as $item) {
                            $msgLines[] = "• " . $item->name . " (" . $item->quantity . "x) - ₺" . number_format($item->total, 2, ',', '.');
                        }
                        if ($workOrder->discount > 0) {
                            $msgLines[] = "İndirim: -₺" . number_format($workOrder->discount, 2, ',', '.');
                        }
                        $msgLines[] = "";
                        $msgLines[] = "💰 TOPLAM TUTAR: ₺" . number_format($workOrder->grand_total, 2, ',', '.');
                        $msgLines[] = "İş Emri No: #" . str_pad($workOrder->id, 5, '0', STR_PAD_LEFT);
                        $msgLines[] = "";
                        $msgLines[] = "SanayiPro Oto Servis";
                        $waUrl = "https://api.whatsapp.com/send?phone=" . $rawPhone . "&text=" . urlencode(implode("\n", $msgLines));
                    @endphp
                    <a href="{{ $waUrl }}" target="_blank"
                       class="bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs">
                        <span>💬 WhatsApp</span>
                    </a>
                @endif

                <!-- Yazdır -->
                <a href="{{ route('work-orders.print', $workOrder) }}" target="_blank"
                   class="bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs">
                    <span>🖨️ Fiş Yazdır</span>
                </a>

                <!-- 🤖 AI ile Arıza Analizi -->
                <a href="{{ route('diagnostic.new', ['work_order_id' => $workOrder->id]) }}"
                   class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm shadow-indigo-600/20">
                    <span>🤖 AI ile Arıza Analizi</span>
                </a>

                <!-- Düzenle -->
                <a href="{{ route('work-orders.edit', $workOrder) }}"
                   class="border border-slate-300 text-slate-700 px-3.5 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-100 transition-colors">
                    ✏️ Düzenle
                </a>

                <!-- Durum Değiştirme Popover -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs">
                        <span>⚡ Durum Güncelle</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak @click.outside="open = false"
                         class="absolute right-0 top-full mt-2 bg-slate-900 border border-slate-800 rounded-2xl shadow-xl z-30 py-2 w-56">
                        @foreach([
                            'arac_kabul'       => '🟢 Araç Kabul',
                            'ariza_tespiti'    => '🔵 Arıza Tespiti',
                            'islem_basladi'    => '🟣 İşlem Başladı',
                            'parca_bekleniyor' => '🟡 Parça Bekleniyor',
                            'kontrol'          => '🔵 Kontrol & Test',
                            'teslim_edildi'    => '🟢 Teslim Edildi / Ödendi'
                        ] as $s => $label)
                            <form method="POST" action="{{ route('work-orders.status', $workOrder) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $s }}">
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-semibold hover:bg-slate-800 transition-colors flex items-center justify-between
                                    {{ $workOrder->status === $s ? 'text-indigo-400 font-bold bg-slate-800/80' : 'text-slate-200' }}">
                                    <span>{{ $label }}</span>
                                    @if($workOrder->status === $s)<span class="text-indigo-400 font-bold">✓</span>@endif
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <!-- Visual Timeline Component -->
        <x-work-order-status-timeline :work-order="$workOrder" />

        <!-- ============================================================
             2. İLİŞKİLİ MERKEZİ EKOSİSTEM (ARAÇ -> MÜŞTERİ -> USTA)
        ============================================================ -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Araç Bilgisi -->
            <a href="{{ route('vehicles.show', $workOrder->vehicle) }}"
               class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:border-blue-300 hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider bg-blue-100 text-blue-800 px-2 py-0.5 rounded-md">Araç Kaydı</span>
                    <span class="text-xs text-blue-600 font-bold group-hover:underline">Detay →</span>
                </div>
                <div class="font-mono font-black text-2xl text-blue-700 tracking-wider mb-1">{{ $workOrder->vehicle->plate }}</div>
                <div class="text-sm font-bold text-slate-900">{{ $workOrder->vehicle->brand }} {{ $workOrder->vehicle->model }}</div>
                <div class="text-xs text-slate-400 mt-0.5">Yıl: {{ $workOrder->vehicle->year ?? '—' }} | Renk: {{ $workOrder->vehicle->color ?? '—' }}</div>
            </a>

            <!-- Müşteri Bilgisi -->
            <a href="{{ route('customers.show', $workOrder->vehicle->customer) }}"
               class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:border-purple-300 hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider bg-purple-100 text-purple-800 px-2 py-0.5 rounded-md">Araç Sahibi</span>
                    <span class="text-xs text-purple-600 font-bold group-hover:underline">Müşteri Profili →</span>
                </div>
                <div class="font-bold text-slate-900 text-lg mb-1 truncate">{{ $workOrder->vehicle->customer->name }}</div>
                <div class="text-xs font-mono text-slate-600 flex items-center gap-1">
                    <span>📞</span> <span>{{ $workOrder->vehicle->customer->phone ?? 'Telefon Yok' }}</span>
                </div>
                <div class="text-xs text-slate-400 mt-1 truncate">{{ $workOrder->vehicle->customer->address ?? 'Adres girilmemiş' }}</div>
            </a>

            <!-- Sorumlu Usta & Şube -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-md">Servis Yetkilisi</span>
                    <span class="text-[10px] font-bold text-slate-400">Atölye</span>
                </div>
                <div class="font-bold text-slate-900 text-lg mb-1 flex items-center gap-1.5">
                    <span>👨‍🔧</span>
                    <span>{{ $workOrder->user->name ?? auth()->user()->name }}</span>
                </div>
                <div class="text-xs text-slate-500">
                    Şube: <strong class="text-slate-800">{{ $workOrder->branch->name ?? 'Merkez Servis' }}</strong>
                </div>
                <div class="text-xs text-slate-400 mt-1">
                    Son İşlem: {{ $workOrder->updated_at->format('d.m.Y H:i') }}
                </div>
            </div>

        </div>

        <!-- ============================================================
             🤖 AKILLI ARIZA ASİSTANI BANNER (ÖZEL TEŞHİS KARTI)
        ============================================================ -->
        @php
            $latestDiagnostic = \App\Models\DiagnosticSession::where('work_order_id', $workOrder->id)->latest()->first();
        @endphp
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-5 sm:p-6 shadow-xl border border-indigo-900/40 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
                <div class="flex items-start sm:items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 border border-indigo-400/30 flex items-center justify-center text-2xl shadow-lg shadow-indigo-600/30 shrink-0">
                        🤖
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 px-2 py-0.5 rounded-md">
                                AI DESTEKLİ TEŞHİS
                            </span>
                            <h3 class="text-base font-black text-white">SanayiPro Akıllı Arıza Asistanı</h3>
                        </div>
                        <p class="text-xs text-slate-300 mt-1 max-w-2xl leading-relaxed">
                            Araçtaki arıza belirtilerini, OBD kodlarını ve ölçümlerinizi girin. Yapay zeka, aracın geçmiş servis kayıtlarıyla birlikte olası nedenleri ve kontrol adımlarını önersin.
                        </p>
                        @if($latestDiagnostic && $latestDiagnostic->is_analyzed)
                            <div class="mt-2 text-xs flex items-center gap-2 text-emerald-400 font-semibold flex-wrap">
                                <span>✓ Bu araç için analiz tamamlandı</span>
                                <span class="text-slate-400 font-normal">({{ $latestDiagnostic->ai_analyzed_at->format('d.m.Y H:i') }})</span>
                                @if($latestDiagnostic->feedback !== 'pending')
                                    <span class="text-[11px] bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 px-2.5 py-0.5 rounded-full">
                                        {{ $latestDiagnostic->feedback_label }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2.5 shrink-0 self-start md:self-auto">
                    @if($latestDiagnostic && $latestDiagnostic->is_analyzed)
                        <a href="{{ route('diagnostic.session-analyze', $latestDiagnostic) }}"
                           class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-4 py-3 rounded-2xl transition-all shadow-md shadow-emerald-600/30 flex items-center gap-2">
                            <span>📊 Teşhis Raporunu İncele</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <a href="{{ route('diagnostic.new', ['work_order_id' => $workOrder->id]) }}"
                           class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-5 py-3 rounded-2xl transition-all shadow-lg shadow-indigo-600/30 flex items-center gap-2">
                            <span>🤖 AI ile Arıza Analizi</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- ============================================================
             3. PROJENİN KALBİ: YAPILACAKLAR (CHECKLIST) VE KALEMLER
        ============================================================ -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Sol Kolon: Yapılacaklar (Checklist) + Notlar -->
            <div class="space-y-6">
                
                <!-- 🔧 YAPILACAKLAR (KONTROL LİSTESİ) -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">🔧</span>
                            <div>
                                <h3 class="text-sm font-black text-slate-900">Yapılacaklar Listesi</h3>
                                <p class="text-[11px] text-slate-400">İş adımlarını işaretleyin</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg"
                              x-text="completedTaskCount + '/' + tasks.length + ' Tamamlandı'"></span>
                    </div>

                    <!-- İlerleme Çubuğu -->
                    <div class="w-full bg-slate-100 rounded-full h-2 mb-4 overflow-hidden">
                        <div class="bg-emerald-500 h-2 transition-all duration-300 rounded-full"
                             :style="'width: ' + progressPercent + '%'"></div>
                    </div>

                    <!-- Görev Ekleme Kutusu -->
                    <div class="flex gap-2 mb-4">
                        <input type="text" x-model="newTaskTitle" @keyup.enter="addTask()"
                               class="flex-1 border border-slate-300 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-600 bg-slate-50/50"
                               placeholder="Örn: Ön takım kontrolü, Yağ filtresi...">
                        <button type="button" @click="addTask()"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-3 py-2 rounded-xl transition-colors shrink-0">
                            + Ekle
                        </button>
                    </div>

                    <!-- Yapılacak Maddeler Listesi -->
                    <div class="space-y-2 max-h-80 overflow-y-auto pr-1">
                        <template x-for="(task, idx) in tasks" :key="task.id">
                            <div class="flex items-center justify-between p-2.5 rounded-xl border transition-all"
                                 :class="task.is_completed ? 'bg-emerald-50/60 border-emerald-200 text-slate-500 line-through' : 'bg-slate-50/60 border-slate-200 text-slate-900 font-medium'">
                                
                                <label class="flex items-center gap-2.5 cursor-pointer flex-1 min-w-0">
                                    <input type="checkbox" :checked="task.is_completed" @change="toggleTask(task)"
                                           class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                                    <span class="text-xs truncate" x-text="task.title"></span>
                                </label>

                                <button type="button" @click="deleteTask(task, idx)"
                                        class="text-slate-400 hover:text-red-600 p-1 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </template>

                        <template x-if="tasks.length === 0">
                            <div class="text-center py-6 border border-dashed border-slate-200 rounded-xl text-xs text-slate-400">
                                Henüz yapılacak iş maddesi eklenmedi.<br>Yukarıdan ekleyebilirsiniz.
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Notlar -->
                @if($workOrder->notes)
                    <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-4 text-xs text-amber-900 shadow-xs">
                        <div class="font-bold flex items-center gap-1.5 mb-1.5 text-amber-950">
                            <span>📝</span> <span>Usta / Servis Notları</span>
                        </div>
                        <p class="leading-relaxed text-amber-900/90 whitespace-pre-line">{{ $workOrder->notes }}</p>
                    </div>
                @endif

                <!-- 📱 MÜŞTERİ BİLDİRİM GEÇMİŞİ & KONTROL PANELİ -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-800 mb-3">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">💬</span>
                            <div>
                                <h3 class="text-xs font-bold text-white uppercase tracking-wider">SMS / WhatsApp Bildirim Geçmişi</h3>
                                <p class="text-[11px] text-slate-400">Müşteriye gönderilen anlık aşama mesajları</p>
                            </div>
                        </div>
                        <span class="text-[11px] font-mono text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-2 py-0.5 rounded">
                            {{ $workOrder->notifications->count() }} Gönderim
                        </span>
                    </div>

                    <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                        @forelse($workOrder->notifications as $notif)
                            <div class="p-2.5 rounded-xl bg-slate-950 border border-slate-800/80 text-xs space-y-1">
                                <div class="flex items-center justify-between text-slate-400">
                                    <span class="font-bold text-emerald-400 flex items-center gap-1">
                                        <span>💬 WhatsApp</span>
                                        <span class="text-[10px] text-slate-500 font-mono">({{ $notif->recipient_phone }})</span>
                                    </span>
                                    <span class="text-[10px] font-mono text-slate-500">{{ $notif->sent_at ? $notif->sent_at->format('d.m.Y H:i') : '' }}</span>
                                </div>
                                <p class="text-slate-300 leading-snug">{{ $notif->message }}</p>
                            </div>
                        @empty
                            <div class="text-center py-5 text-xs text-slate-500 border border-dashed border-slate-800 rounded-xl">
                                Henüz kaydedilmiş bildirim bulunmuyor.<br>Yukarıdaki zaman çizgisinden aşama değiştirdiğinizde bildirim gönderebilirsiniz.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Sağ Kolon (2 Kolon Genişlik): Parçalar, İşçilikler ve Finans -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- 📦 PARÇALAR VE İŞÇİLİKLER TABLOSU -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">📦</span>
                            <h3 class="text-sm font-black text-slate-900">Kullanılan Parçalar & İşçilik Detayı</h3>
                        </div>
                        <span class="text-xs text-slate-400 font-bold">{{ $workOrder->items->count() }} Kalem</span>
                    </div>

                    <!-- Desktop Table -->
                    <div class="hidden sm:block overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-5 py-3 text-left font-bold text-slate-500 uppercase">Kalem Adı</th>
                                    <th class="px-4 py-3 text-center font-bold text-slate-500 uppercase">Tür</th>
                                    <th class="px-4 py-3 text-center font-bold text-slate-500 uppercase">Adet</th>
                                    <th class="px-5 py-3 text-right font-bold text-slate-500 uppercase">Birim Fiyat</th>
                                    <th class="px-5 py-3 text-right font-bold text-slate-500 uppercase">Toplam</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium">
                                @foreach($workOrder->items as $item)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="px-5 py-3.5 text-slate-900 font-bold flex items-center gap-2">
                                            @if($item->type === 'part')
                                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                            @else
                                                <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                                            @endif
                                            <span>{{ $item->name }}</span>
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full
                                                {{ $item->type === 'part' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-purple-100 text-purple-800 border border-purple-200' }}">
                                                {{ $item->type === 'part' ? 'Parça' : 'İşçilik' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 text-center font-mono font-bold text-slate-700">{{ $item->quantity }}x</td>
                                        <td class="px-5 py-3.5 text-right font-mono text-slate-600">₺{{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                        <td class="px-5 py-3.5 text-right font-mono font-extrabold text-slate-900">₺{{ number_format($item->total, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="sm:hidden divide-y divide-slate-100">
                        @foreach($workOrder->items as $item)
                            <div class="p-4 space-y-2">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="font-bold text-slate-900 text-xs flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full shrink-0 {{ $item->type === 'part' ? 'bg-blue-500' : 'bg-purple-500' }}"></span>
                                        <span>{{ $item->name }}</span>
                                    </div>
                                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full shrink-0
                                        {{ $item->type === 'part' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-purple-100 text-purple-800 border border-purple-200' }}">
                                        {{ $item->type === 'part' ? 'Parça' : 'İşçilik' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-xs pt-1">
                                    <span class="text-slate-500 font-mono">{{ $item->quantity }} adet × ₺{{ number_format($item->unit_price, 2, ',', '.') }}</span>
                                    <span class="font-mono font-extrabold text-slate-900">₺{{ number_format($item->total, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 💰 FİNANSAL ÖZET KARTI -->
                <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-950 text-white rounded-2xl p-6 shadow-xl border border-slate-800">
                    <div class="flex items-center justify-between pb-3 border-b border-indigo-900/60 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">💰</span>
                            <h3 class="text-base font-black text-white">Finansal Hesap Özeti</h3>
                        </div>
                        <span class="text-xs text-indigo-300 bg-indigo-900/60 border border-indigo-700/50 px-2.5 py-1 rounded-full font-bold">Net Hesap</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
                        <div class="bg-white/5 border border-white/10 rounded-xl p-3.5">
                            <div class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1">Top. Parça Tutarı</div>
                            <div class="text-lg font-mono font-bold text-blue-300">₺{{ number_format($workOrder->total_parts, 2, ',', '.') }}</div>
                        </div>

                        <div class="bg-white/5 border border-white/10 rounded-xl p-3.5">
                            <div class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1">Top. İşçilik Tutarı</div>
                            <div class="text-lg font-mono font-bold text-purple-300">₺{{ number_format($workOrder->total_labor, 2, ',', '.') }}</div>
                        </div>

                        <div class="bg-white/5 border border-white/10 rounded-xl p-3.5">
                            <div class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-1">Uygulanan İndirim</div>
                            <div class="text-lg font-mono font-bold text-red-300">-₺{{ number_format($workOrder->discount, 2, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-4 border-t border-indigo-900/80 gap-3">
                        <div>
                            <span class="text-xs text-slate-400 uppercase font-bold tracking-wider">Tahsil Edilecek Genel Toplam</span>
                            <div class="text-3xl font-mono font-black text-emerald-400 mt-0.5">₺{{ number_format($workOrder->grand_total, 2, ',', '.') }}</div>
                        </div>

                        @if($workOrder->status !== 'odendi')
                            <form method="POST" action="{{ route('work-orders.status', $workOrder) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="odendi">
                                <button type="submit" class="w-full sm:w-auto bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-sm px-6 py-3 rounded-xl transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 cursor-pointer">
                                    <span>💳 Ödemeyi Al & Ödendi Yap</span>
                                </button>
                            </form>
                        @else
                            <div class="bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
                                <span>✓ Ödeme Alındı & Kasa Kaydı Tamamlandı</span>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Alpine.js Work Order Central Hub Logic -->
    <script>
    function workOrderHub(workOrderId, initialTasks) {
        return {
            workOrderId: workOrderId,
            tasks: initialTasks || [],
            newTaskTitle: '',

            get completedTaskCount() {
                return this.tasks.filter(t => t.is_completed).length;
            },

            get progressPercent() {
                if (this.tasks.length === 0) return 0;
                return Math.round((this.completedTaskCount / this.tasks.length) * 100);
            },

            async addTask() {
                const title = this.newTaskTitle.trim();
                if (!title) return;

                try {
                    const res = await fetch(`/work-orders/${this.workOrderId}/tasks`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ title: title })
                    });
                    const data = await res.json();
                    if (data.success) {
                        this.tasks.push(data.task);
                        this.newTaskTitle = '';
                    }
                } catch (e) {
                    alert('Görev eklenirken bir hata oluştu.');
                }
            },

            async toggleTask(task) {
                task.is_completed = !task.is_completed;
                try {
                    await fetch(`/work-order-tasks/${task.id}/toggle`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                } catch (e) {
                    task.is_completed = !task.is_completed;
                }
            },

            async deleteTask(task, idx) {
                if (!confirm('Bu adımı silmek istediğinize emin misiniz?')) return;
                try {
                    const res = await fetch(`/work-order-tasks/${task.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    const data = await res.json();
                    if (data.success) {
                        this.tasks.splice(idx, 1);
                    }
                } catch (e) {
                    alert('Silinirken hata oluştu.');
                }
            }
        }
    }
    </script>
</x-app-layout>
