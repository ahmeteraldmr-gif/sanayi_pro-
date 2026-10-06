@props(['workOrder', 'interactive' => true])

@php
    $steps = \App\Models\WorkOrder::getTimelineSteps();
    $currentStepIndex = $workOrder->status_step;
@endphp

<div x-data="workOrderTimeline({{ $workOrder->id }}, {{ $currentStepIndex }}, '{{ $workOrder->status }}')" 
     class="bg-slate-900 border border-slate-800 rounded-2xl p-5 sm:p-6 shadow-xl relative overflow-hidden">
    
    <!-- Header -->
    <div class="flex items-center justify-between mb-5 border-b border-slate-800/80 pb-3">
        <div class="flex items-center gap-2">
            <span class="text-lg">🚦</span>
            <div>
                <h3 class="font-bold text-white text-sm sm:text-base">Servis & İş Emri Durum Zaman Çizgisi</h3>
                <p class="text-[11px] text-slate-400">Aracın atölyedeki anlık aşamasını takip edin ve güncelleyin</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-mono font-bold px-3 py-1 rounded-lg border transition-all"
                  :class="getStepBadgeClass(activeStep)">
                <span x-text="getStepEmoji(activeStep)"></span>
                <span x-text="getStepLabel(activeStep)"></span>
            </span>
        </div>
    </div>

    <!-- Timeline Track (Desktop & Tablet) -->
    <div class="hidden md:block relative my-4 px-4">
        <!-- Background Connecting Line -->
        <div class="absolute top-1/2 left-10 right-10 -translate-y-1/2 h-1 bg-slate-800 rounded-full -z-0"></div>
        
        <!-- Active Progress Line -->
        <div class="absolute top-1/2 left-10 -translate-y-1/2 h-1 bg-gradient-to-r from-emerald-500 via-indigo-500 to-amber-500 rounded-full transition-all duration-500 -z-0"
             :style="'width: ' + getProgressWidth() + '%'"></div>

        <div class="grid grid-cols-6 gap-2 relative z-10">
            @foreach($steps as $stepNum => $step)
                <button type="button"
                        @if($interactive) @click="changeStatus('{{ $step['key'] }}', {{ $stepNum }})" @endif
                        class="flex flex-col items-center text-center group cursor-pointer focus:outline-none transition-all"
                        :disabled="isSubmitting">
                    
                    <!-- Node Circle -->
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-extrabold text-sm transition-all duration-300 border-2 shadow-lg relative"
                         :class="{
                             'bg-emerald-500 text-slate-950 border-emerald-400 shadow-emerald-500/20 scale-105': {{ $stepNum }} < activeStep,
                             'bg-slate-950 text-white border-indigo-500 ring-4 ring-indigo-500/30 shadow-indigo-500/40 scale-110': {{ $stepNum }} === activeStep,
                             'bg-slate-950 text-slate-500 border-slate-800 hover:border-slate-700': {{ $stepNum }} > activeStep
                         }">
                        <template x-if="{{ $stepNum }} < activeStep">
                            <svg class="w-6 h-6 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </template>
                        <template x-if="{{ $stepNum }} >= activeStep">
                            <span class="text-base">{{ $step['emoji'] }}</span>
                        </template>

                        <!-- Active Indicator Dot -->
                        <template x-if="{{ $stepNum }} === activeStep">
                            <span class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-indigo-400 rounded-full animate-ping"></span>
                        </template>
                    </div>

                    <!-- Label -->
                    <span class="text-xs font-bold mt-2.5 transition-colors"
                          :class="{
                              'text-emerald-400': {{ $stepNum }} < activeStep,
                              'text-white font-extrabold': {{ $stepNum }} === activeStep,
                              'text-slate-500 group-hover:text-slate-300': {{ $stepNum }} > activeStep
                          }">
                        {{ $step['label'] }}
                    </span>

                    <span class="text-[10px] font-mono text-slate-500 mt-0.5">Aşama {{ $stepNum }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Mobile Vertical Stack -->
    <div class="md:hidden space-y-2.5">
        @foreach($steps as $stepNum => $step)
            <button type="button"
                    @if($interactive) @click="changeStatus('{{ $step['key'] }}', {{ $stepNum }})" @endif
                    class="w-full flex items-center justify-between p-3 rounded-xl border transition-all text-left"
                    :class="{
                        'bg-emerald-500/10 border-emerald-500/30 text-emerald-300': {{ $stepNum }} < activeStep,
                        'bg-indigo-600/20 border-indigo-500 text-white font-bold ring-1 ring-indigo-500/50': {{ $stepNum }} === activeStep,
                        'bg-slate-950 border-slate-800 text-slate-500': {{ $stepNum }} > activeStep
                    }">
                <div class="flex items-center gap-3">
                    <span class="text-lg">{{ $step['emoji'] }}</span>
                    <div>
                        <div class="text-xs font-bold">{{ $step['label'] }}</div>
                        <div class="text-[10px] opacity-70">Aşama {{ $stepNum }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <template x-if="{{ $stepNum }} < activeStep">
                        <span class="text-xs font-bold bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded">✓ Tamamlandı</span>
                    </template>
                    <template x-if="{{ $stepNum }} === activeStep">
                        <span class="text-xs font-bold bg-indigo-500/30 text-indigo-200 px-2 py-0.5 rounded animate-pulse">⚡ Aktif Aşama</span>
                    </template>
                    <template x-if="{{ $stepNum }} > activeStep">
                        <span class="text-xs text-slate-600">Bekliyor</span>
                    </template>
                </div>
            </button>
        @endforeach
    </div>

    <!-- WhatsApp Notification Modal -->
    <div x-show="showNotifyModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/75 backdrop-blur-sm p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 w-full max-w-lg shadow-2xl space-y-4" @click.stop>
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">💬</span>
                    <div>
                        <h3 class="font-bold text-white text-base">Müşteriye WhatsApp Bildirimi Gönder</h3>
                        <p class="text-xs text-slate-400">Durum güncellemesi müşteriye bildirilmeye hazır</p>
                    </div>
                </div>
                <button type="button" @click="showNotifyModal = false" class="text-slate-400 hover:text-white">&times;</button>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Müşteri Telefon</label>
                <div class="font-mono text-sm font-bold text-indigo-300 bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2">
                    <span x-text="notifyPhone || 'Telefon Girilmemiş'"></span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-400 mb-1">Hazırlanan Şablon Mesajı</label>
                <textarea x-model="notifyMessage" rows="4"
                          class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white leading-relaxed focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="button" @click="sendWhatsAppAndLog()"
                        class="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-2.5 rounded-xl text-xs transition-colors flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/20">
                    <span>💬 WhatsApp ile Gönder & Kaydet</span>
                </button>
                <button type="button" @click="showNotifyModal = false"
                        class="px-4 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-medium py-2.5 transition-colors">
                    Atla / Kapat
                </button>
            </div>
        </div>
    </div>

</div>

<script>
function workOrderTimeline(workOrderId, initialStep, initialStatus) {
    return {
        workOrderId: workOrderId,
        activeStep: initialStep,
        currentStatus: initialStatus,
        isSubmitting: false,
        showNotifyModal: false,
        notifyStatusKey: '',
        notifyPhone: '',
        notifyMessage: '',
        notifyWhatsAppUrl: '',

        getProgressWidth() {
            if (this.activeStep <= 1) return 0;
            return Math.min(100, Math.round(((this.activeStep - 1) / 5) * 100));
        },

        getStepLabel(step) {
            const labels = {
                1: 'Araç Kabul',
                2: 'Arıza Tespiti',
                3: 'İşlem Başladı',
                4: 'Parça Bekleniyor',
                5: 'Kontrol & Test',
                6: 'Teslim Edildi'
            };
            return labels[step] || 'Araç Kabul';
        },

        getStepEmoji(step) {
            const emojis = { 1: '🟢', 2: '🔵', 3: '🟣', 4: '🟡', 5: '🔵', 6: '🟢' };
            return emojis[step] || '🟢';
        },

        getStepBadgeClass(step) {
            const classes = {
                1: 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40',
                2: 'bg-blue-500/20 text-blue-300 border-blue-500/40',
                3: 'bg-indigo-500/20 text-indigo-300 border-indigo-500/40',
                4: 'bg-amber-500/20 text-amber-300 border-amber-500/40',
                5: 'bg-cyan-500/20 text-cyan-300 border-cyan-500/40',
                6: 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40'
            };
            return classes[step] || 'bg-slate-800 text-slate-300 border-slate-700';
        },

        async changeStatus(statusKey, stepNum) {
            if (this.isSubmitting) return;
            this.isSubmitting = true;

            try {
                const res = await fetch(`/work-orders/${this.workOrderId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: statusKey })
                });

                const data = await res.json();
                if (data.success) {
                    this.activeStep = stepNum;
                    this.currentStatus = statusKey;

                    // Önizleme mesajını al ve modal aç
                    const prevRes = await fetch(`/work-orders/${this.workOrderId}/notification-preview?status=${statusKey}`);
                    const prevData = await prevRes.json();
                    if (prevData.success && prevData.phone) {
                        this.notifyStatusKey = statusKey;
                        this.notifyPhone = prevData.phone;
                        this.notifyMessage = prevData.message;
                        this.notifyWhatsAppUrl = prevData.whatsapp_url;
                        this.showNotifyModal = true;
                    }
                }
            } catch (e) {
                console.error('Status update failed:', e);
            } finally {
                this.isSubmitting = false;
            }
        },

        async sendWhatsAppAndLog() {
            if (!this.notifyMessage) return;

            // WhatsApp Web / App aç
            const cleanPhone = this.notifyPhone.replace(/[^0-9]/g, '');
            const waUrl = `https://api.whatsapp.com/send?phone=${cleanPhone}&text=${encodeURIComponent(this.notifyMessage)}`;
            window.open(waUrl, '_blank');

            // Bildirimi arka planda logla
            try {
                await fetch(`/work-orders/${this.workOrderId}/log-notification`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        channel: 'whatsapp',
                        status_key: this.notifyStatusKey,
                        message: this.notifyMessage
                    })
                });
            } catch (e) {
                console.error('Log notification error:', e);
            }

            this.showNotifyModal = false;
        }
    }
}
</script>
