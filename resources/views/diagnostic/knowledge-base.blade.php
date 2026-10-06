<x-app-layout>
    <x-slot name="title">📚 Arıza Bilgi Bankası — SanayiPro</x-slot>

    <div class="space-y-6">

        <!-- ============================================================
             1. ÜST HEADER & ÖZET KARTLAR
        ============================================================ -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 shadow-xl border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-bold border border-indigo-500/30 mb-2">
                    <span>🧠 KOLEKTİF SERVİS AKLI</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight">ARIZA BİLGİ BANKASI & GEÇMİŞ ÇÖZÜMLER</h1>
                <p class="text-xs text-indigo-200 mt-1">Ustaların teşhis edip çözdüğü gerçek arızalar, işlemler ve değiştirilen parçalar arşivi</p>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('diagnostic.new') }}"
                   class="bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2">
                    <span>🤖 Yeni AI Teşhisi</span>
                    <span class="bg-white/20 text-white text-[10px] px-1.5 py-0.5 rounded-md font-mono">+ Başlat</span>
                </a>
                <a href="{{ route('work-orders.index') }}"
                   class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition-all flex items-center gap-2">
                    <span>🔧 İş Emirlerine Git</span>
                </a>
            </div>
        </div>

        <!-- İstatistik Özet Kartları -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Toplam Çözümlü Vaka</span>
                <div class="text-2xl font-black text-emerald-600 font-mono">{{ number_format($totalSolutions, 0) }}</div>
                <span class="text-[11px] text-slate-400 mt-1 block">Sisteme kaydedilen onaylı çözümler</span>
            </div>

            <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Toplam Teşhis Oturumu</span>
                <div class="text-2xl font-black text-indigo-600 font-mono">{{ number_format($totalSessions, 0) }}</div>
                <span class="text-[11px] text-slate-400 mt-1 block">AI destekli arıza incelemeleri</span>
            </div>

            <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-xs">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">En Çok Çözülen Markalar</span>
                <div class="flex items-center gap-1.5 flex-wrap mt-2">
                    @forelse($topBrands as $tb)
                        <span class="text-xs font-bold bg-slate-100 text-slate-700 px-2 py-0.5 rounded-lg border border-slate-200">
                            {{ $tb->vehicle_brand }} ({{ $tb->cnt }})
                        </span>
                    @empty
                        <span class="text-xs text-slate-400">Henüz kayıt yok.</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ============================================================
             2. ARAMA VE FİLTRELEME ÇUBUĞU
        ============================================================ -->
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-xs">
            <form method="GET" action="{{ route('diagnostic.knowledge-base') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Araç markası, model, bulunan sorun, yapılan işlem ara... (örn: Kızdırma, BMW, EGR)"
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white">
                </div>

                <div class="flex items-center gap-2">
                    <input type="text" name="brand" value="{{ request('brand') }}"
                           placeholder="Marka (örn: Ford)"
                           class="w-36 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white">

                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition-colors shadow-xs">
                        Filtrele
                    </button>

                    @if(request('search') || request('brand'))
                        <a href="{{ route('diagnostic.knowledge-base') }}"
                           class="text-xs text-slate-400 hover:text-slate-700 underline px-2">
                            Temizle
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- ============================================================
             3. ÇÖZÜMLER LİSTESİ / TABLO
        ============================================================ -->
        <div class="space-y-4">
            @forelse($solutions as $sol)
                <div class="bg-white border border-slate-200 hover:border-indigo-300 rounded-3xl p-5 sm:p-6 shadow-xs transition-all">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 mb-3 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-700 font-bold flex items-center justify-center text-lg shrink-0">
                                🚗
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900">
                                    {{ $sol->vehicle_brand }} {{ $sol->vehicle_model }}
                                    @if($sol->vehicle_year)<span class="text-xs font-normal text-slate-400">({{ $sol->vehicle_year }})</span>@endif
                                </h3>
                                <div class="text-xs text-slate-400 flex items-center gap-2 mt-0.5">
                                    @if($sol->vehicle_engine)<span>Motor: {{ $sol->vehicle_engine }}</span> •@endif
                                    @if($sol->mileage)<span>Km: {{ number_format($sol->mileage, 0, ',', '.') }}</span> •@endif
                                    <span>Tarih: {{ $sol->created_at->format('d.m.Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 self-start sm:self-auto">
                            <span class="text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full">
                                ✓ Onaylı Çözüm
                            </span>
                            @if($sol->work_order_id)
                                <a href="{{ route('work-orders.show', $sol->work_order_id) }}"
                                   class="text-xs text-indigo-600 hover:underline font-bold">
                                    İş Emri #{{ str_pad($sol->work_order_id, 5, '0', STR_PAD_LEFT) }} →
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Çözüm Detay Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                        <!-- Gerçek Sorun -->
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 block mb-1">🎯 Bulunan Gerçek Sorun</span>
                            <div class="font-bold text-slate-900 leading-relaxed text-sm">
                                {{ $sol->root_cause }}
                            </div>
                        </div>

                        <!-- Yapılan İşlem -->
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 block mb-1">🛠️ Yapılan İşlem & Onarım</span>
                            <p class="text-slate-700 leading-relaxed">
                                {{ $sol->action_taken }}
                            </p>
                        </div>

                        <!-- Değişen Parçalar & Usta -->
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-500 block mb-1">🔩 Değiştirilen Parçalar</span>
                                @if(!empty($sol->parts_replaced))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($sol->parts_replaced as $partName)
                                            <span class="bg-white border border-slate-200 text-slate-800 font-semibold px-2 py-0.5 rounded text-[11px]">
                                                {{ $partName }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-slate-400 text-xs">Parça değişimi yapılmadı</span>
                                @endif
                            </div>

                            <div class="mt-3 pt-2 border-t border-slate-200/50 flex items-center justify-between text-[11px] text-slate-400">
                                <span>👨‍🔧 Çözen: <strong class="text-slate-700">{{ $sol->solvedBy->name ?? 'Usta' }}</strong></span>
                                @if($sol->result)<span class="text-emerald-700 font-bold">{{ $sol->result }}</span>@endif
                            </div>
                        </div>
                    </div>

                    <!-- Belirtiler & OBD Kodları (Varsa) -->
                    @if(!empty($sol->symptoms) || !empty($sol->obd_codes))
                        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center gap-2 flex-wrap text-xs">
                            @if(!empty($sol->symptoms))
                                <span class="text-slate-400 font-bold text-[10px] uppercase">Belirtiler:</span>
                                @foreach($sol->symptoms as $s)
                                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-[11px]">{{ $s }}</span>
                                @endforeach
                            @endif

                            @if(!empty($sol->obd_codes))
                                <span class="text-slate-400 font-bold text-[10px] uppercase ml-2">OBD:</span>
                                @foreach($sol->obd_codes as $code)
                                    <span class="bg-rose-50 text-rose-700 font-mono font-bold px-2 py-0.5 rounded text-[10px] border border-rose-200">{{ $code }}</span>
                                @endforeach
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center text-slate-400 border border-slate-200">
                    <span class="text-3xl block mb-2">🔍</span>
                    <h3 class="font-bold text-slate-700 text-sm">Kayıt Bulunamadı</h3>
                    <p class="text-xs text-slate-400 mt-1">Arama kriterlerinize uygun arıza çözümü bulunamadı veya henüz kayıt eklenmedi.</p>
                </div>
            @endforelse

            <!-- Sayfalama -->
            @if($solutions->hasPages())
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                    {{ $solutions->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
