<x-app-layout>
    <x-slot name="title">Servis Takvimi & Randevu Planlayıcı</x-slot>

    <div x-data="appointmentCalendar('{{ $dateStr }}')" class="space-y-6">

        <!-- Top Header & Date Controller (Sleek Light Modern) -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white border border-slate-200 p-5 rounded-3xl shadow-xs">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-100 mb-1">
                    <span>📅 SERVİS PLANLAMA HUB</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span>SERVİS TAKVİMİ & PLANLAYICI</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Günlük araç bakım ve arıza tespit randevularını saat saat planlayın ve ustaya atayın
                </p>
            </div>

            <!-- Tarih Değiştirici -->
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('appointments.index', ['date' => $date->copy()->subDay()->toDateString()]) }}" 
                   class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition-colors">
                    &larr; Önceki Gün
                </a>
                
                <form method="GET" action="{{ route('appointments.index') }}" class="flex items-center gap-2">
                    <input type="date" name="date" value="{{ $dateStr }}" onchange="this.form.submit()"
                           class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </form>

                <a href="{{ route('appointments.index', ['date' => today()->toDateString()]) }}" 
                   class="px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold transition-colors">
                    Bugün
                </a>

                <a href="{{ route('appointments.index', ['date' => $date->copy()->addDay()->toDateString()]) }}" 
                   class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition-colors">
                    Sonraki Gün &rarr;
                </a>

                <!-- Yeni Randevu Ekle Button -->
                <button type="button" @click="showAddModal = true"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition-colors shadow-md shadow-indigo-600/20 flex items-center gap-1.5 ml-auto md:ml-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Randevu Ekle</span>
                </button>
            </div>
        </div>

        <!-- Seçili Gün Özeti -->
        <div class="flex items-center justify-between px-1">
            <h2 class="text-base font-black text-slate-900 tracking-tight">
                🗓️ {{ $date->translatedFormat('d F Y, l') }} Randevuları
            </h2>
            <span class="text-xs font-mono font-bold bg-white border border-slate-200 text-slate-700 px-3 py-1 rounded-full shadow-2xs">
                Toplam {{ $appointments->count() }} Servis Kaydı
            </span>
        </div>

        <!-- ============================================================
             2. MOBİL GÜNLÜK AJANDA LİSTESİ (TELEFON EKRANLARI)
        ============================================================ -->
        <div class="block md:hidden space-y-3">
            @forelse($appointments->sortBy('start_time') as $app)
                <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs space-y-2.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-mono font-black text-indigo-700 text-sm bg-indigo-50 border border-indigo-100 px-2.5 py-1 rounded-lg">
                                {{ $app->start_time->format('H:i') }}
                            </span>
                            @if($app->vehicle)
                                <span class="font-mono font-bold text-slate-900 text-xs bg-slate-100 border border-slate-200 px-2 py-1 rounded-lg">
                                    {{ $app->vehicle->plate }}
                                </span>
                            @endif
                        </div>

                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border shrink-0
                            @if($app->status==='randevu') bg-emerald-50 text-emerald-700 border-emerald-200
                            @elseif($app->status==='islemde') bg-blue-50 text-blue-700 border-blue-200
                            @elseif($app->status==='tamamlandi') bg-indigo-50 text-indigo-700 border-indigo-200
                            @else bg-rose-50 text-rose-700 border-rose-200 @endif">
                            {{ $app->status_label }}
                        </span>
                    </div>

                    <div class="font-bold text-slate-900 text-sm">
                        {{ $app->title }}
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                        <div>
                            <span class="text-slate-400 block text-[10px]">👨‍🔧 Usta</span>
                            <strong class="text-slate-800">{{ $app->user->name ?? 'Atanmadı' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">👤 Müşteri</span>
                            <strong class="text-slate-800 truncate block">
                                {{ $app->customer->name ?? ($app->vehicle->customer->name ?? '—') }}
                            </strong>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1 border-t border-slate-100">
                        <span class="text-[11px] text-slate-500 font-mono">
                            ⏰ {{ $app->start_time->format('H:i') }} - {{ $app->end_time ? $app->end_time->format('H:i') : '' }}
                        </span>

                        <form method="POST" action="{{ route('appointments.destroy', $app) }}" 
                              onsubmit="return confirm('Bu randevuyu takvimden silmek istediğinize emin misiniz?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-rose-600 font-bold hover:underline">
                                Sil &times;
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-slate-200 rounded-2xl p-8 text-center text-slate-400 text-xs">
                    <span class="text-2xl block mb-1">📅</span>
                    Bu tarihe ait kayıtlı randevu bulunmuyor.
                </div>
            @endforelse
        </div>

        <!-- ============================================================
             2. SAAT BAZLI TAKVİM VE SÜRÜKLE-BIRAK ALANI (DESKTOP GRID)
        ============================================================ -->
        <div class="hidden md:block bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-xs divide-y divide-slate-100">
            @foreach($timeSlots as $slotTime => $slotAppointments)
                <div class="flex flex-col md:flex-row items-stretch min-h-[85px] transition-colors"
                     @dragover.prevent
                     @dragenter.prevent
                     @drop="dropAppointment($event, '{{ $dateStr }} {{ $slotTime }}')">
                    
                    <!-- Sol Saat Sütunu -->
                    <div class="w-full md:w-32 bg-slate-50/80 p-3.5 border-b md:border-b-0 md:border-r border-slate-100 flex md:flex-col items-center justify-between md:justify-center flex-shrink-0">
                        <span class="font-mono text-base font-black text-indigo-700 tracking-wider">{{ $slotTime }}</span>
                        <span class="text-[10px] text-slate-400 font-mono mt-0.5 uppercase tracking-wider">Aralık</span>
                    </div>

                    <!-- Sağ Randevu Kartları Alanı -->
                    <div class="flex-1 p-3 flex flex-wrap items-center gap-3 bg-white hover:bg-slate-50/50 transition-colors">
                        @forelse($slotAppointments as $app)
                            <div draggable="true"
                                 @dragstart="dragStart($event, {{ $app->id }})"
                                 class="bg-white border border-slate-200 hover:border-indigo-400 rounded-2xl p-4 shadow-xs w-full sm:w-80 cursor-grab active:cursor-grabbing transition-all hover:shadow-md group relative">
                                
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <div>
                                        <div class="font-bold text-slate-900 text-xs sm:text-sm group-hover:text-indigo-600 transition-colors">
                                            {{ $app->title }}
                                        </div>
                                        @if($app->vehicle)
                                            <span class="font-mono font-bold text-slate-900 text-xs bg-slate-100 border border-slate-200 px-2 py-0.5 rounded inline-block mt-1">
                                                {{ $app->vehicle->plate }}
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border flex-shrink-0
                                        @if($app->status==='randevu') bg-emerald-50 text-emerald-700 border-emerald-200
                                        @elseif($app->status==='islemde') bg-blue-50 text-blue-700 border-blue-200
                                        @elseif($app->status==='tamamlandi') bg-indigo-50 text-indigo-700 border-indigo-200
                                        @else bg-rose-50 text-rose-700 border-rose-200 @endif">
                                        {{ $app->status_label }}
                                    </span>
                                </div>

                                <div class="text-xs text-slate-600 mt-2 space-y-1 pt-2 border-t border-slate-100">
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-400">👨‍🔧 Usta:</span>
                                        <strong class="text-slate-800">{{ $app->user->name ?? 'Atanmadı' }}</strong>
                                    </div>
                                    @if($app->customer || ($app->vehicle && $app->vehicle->customer))
                                        <div class="flex items-center justify-between">
                                            <span class="text-slate-400">👤 Müşteri:</span>
                                            <strong class="text-slate-800 truncate max-w-[140px]">
                                                {{ $app->customer->name ?? $app->vehicle->customer->name }}
                                            </strong>
                                        </div>
                                    @endif
                                    <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1">
                                        <span>⏰ {{ $app->start_time->format('H:i') }} - {{ $app->end_time ? $app->end_time->format('H:i') : '' }}</span>
                                        <span class="text-[10px] text-indigo-600 font-bold opacity-0 group-hover:opacity-100 transition-opacity">↕ Sürükle</span>
                                    </div>
                                </div>

                                <!-- Silme / İptal Aksiyonu -->
                                <form method="POST" action="{{ route('appointments.destroy', $app) }}" 
                                      onsubmit="return confirm('Bu randevuyu takvimden silmek istediğinize emin misiniz?')"
                                      class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-600 p-1 text-xs">
                                        &times;
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="text-xs text-slate-400 font-mono py-1.5 px-3 border border-dashed border-slate-200 rounded-xl bg-slate-50/50">
                                Boş Saat Dilimi (Randevu taşımak için buraya sürükleyin)
                            </div>
                        @endforelse
                    </div>

                </div>
            @endforeach
        </div>

        <!-- ============================================================
             3. YENİ RANDEVU EKLEME MODALI
        ============================================================ -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
            <div class="bg-white border border-slate-200 rounded-3xl p-6 w-full max-w-lg shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                        <span>📅</span> Yeni Servis Randevusu Oluştur
                    </h3>
                    <button type="button" @click="showAddModal = false" class="text-slate-400 hover:text-slate-700 text-lg font-bold">&times;</button>
                </div>

                <form method="POST" action="{{ route('appointments.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Başlık / Yapılacak İşlem *</label>
                        <input type="text" name="title" required placeholder="Örn: BMW 320d — Yağ Bakımı"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:ring-2 focus:ring-indigo-600 focus:bg-white focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Başlangıç Zamanı *</label>
                            <input type="datetime-local" name="start_time" value="{{ $dateStr }}T09:00" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:ring-2 focus:ring-indigo-600 focus:bg-white focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Sorumlu Usta</label>
                            <select name="user_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:ring-2 focus:ring-indigo-600 focus:bg-white focus:outline-none">
                                <option value="">Usta Seçin...</option>
                                @foreach($masters as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">İlişkili Araç (Opsiyonel)</label>
                            <select name="vehicle_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:ring-2 focus:ring-indigo-600 focus:bg-white focus:outline-none">
                                <option value="">Araç Seçin...</option>
                                @foreach($vehicles as $v)
                                    <option value="{{ $v->id }}">{{ $v->plate }} ({{ $v->brand }} {{ $v->model }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Durum</label>
                            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:ring-2 focus:ring-indigo-600 focus:bg-white focus:outline-none">
                                <option value="randevu">🟢 Randevu Alındı</option>
                                <option value="islemde">🔵 İşlem Başladı</option>
                                <option value="tamamlandi">✅ Tamamlandı</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Özel Notlar</label>
                        <textarea name="notes" rows="2" placeholder="Örn: Müşteri sabah 09:00'da aracı bırakacak"
                                  class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-900 focus:ring-2 focus:ring-indigo-600 focus:bg-white focus:outline-none"></textarea>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-xs transition-colors shadow-md shadow-indigo-600/20">
                            Takvime Kaydet
                        </button>
                        <button type="button" @click="showAddModal = false" class="px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold py-2.5 transition-colors border border-slate-200">
                            Vazgeç
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Drag & Drop Script -->
    <script>
    function appointmentCalendar(currentDate) {
        return {
            showAddModal: false,

            dragStart(event, appointmentId) {
                event.dataTransfer.setData('text/plain', appointmentId);
            },

            async dropAppointment(event, newStartDateTime) {
                const appointmentId = event.dataTransfer.getData('text/plain');
                if (!appointmentId) return;

                try {
                    const res = await fetch(`/appointments/${appointmentId}/update-time`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ start_time: newStartDateTime })
                    });

                    const data = await res.json();
                    if (data.success) {
                        window.location.reload();
                    }
                } catch (e) {
                    console.error('Reschedule failed:', e);
                }
            }
        }
    }
    </script>
</x-app-layout>
