<x-app-layout>
    <x-slot name="title">Usta Paneli — Ana Sayfa</x-slot>

    <div x-data="{ showGuide: localStorage.getItem('sanayi_hide_guide') !== 'true', activeTab: 'tour' }">

        <!-- ============================================================
             1. TANITIM & HIZLI BAŞLANGIÇ REHBERİ (ONBOARDING HERO)
        ============================================================ -->
        <!-- ============================================================
             1. TANITIM & HIZLI BAŞLANGIÇ REHBERİ (ONBOARDING HERO - LIGHT MODERN)
        ============================================================ -->
        <div x-show="showGuide" x-transition
             class="mb-6 rounded-3xl bg-white text-slate-900 p-5 sm:p-6 shadow-xs border border-slate-200 relative overflow-hidden">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-2xl shadow-2xs shrink-0 text-indigo-600 font-bold">
                        🚀
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">SanayiPro Servis Sistemine Hoş Geldiniz!</h1>
                            <span class="bg-indigo-50 border border-indigo-200 text-indigo-700 text-[10px] font-bold px-2 py-0.5 rounded-full">Kullanım Rehberi</span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                            Dükkanınızı ve iş emirlerinizi 4 kolay adımda saniyeler içinde yönetin.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button @click="localStorage.setItem('sanayi_hide_guide', 'true'); showGuide = false" 
                            class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-xl transition-colors border border-slate-200 flex items-center gap-1.5 font-bold">
                        <span>Rehberi Kapat</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- 4 Adımlı Hızlı Kullanım Turu -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 mt-5">
                
                <!-- Adım 1 -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 hover:border-indigo-400 hover:bg-white transition-all group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider bg-blue-100 text-blue-800 px-2 py-0.5 rounded border border-blue-200">1. ADIM</span>
                        <span class="text-xl">👥</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">Müşteri & Araç Kaydı</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Müşterinizin telefonunu ve araç plakasını sisteme tek ekranda kaydedin.
                    </p>
                    <a href="{{ route('customers.create') }}" class="mt-3 inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:underline transition-colors">
                        <span>+ Müşteri Ekle</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- Adım 2 -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 hover:border-indigo-400 hover:bg-white transition-all group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider bg-purple-100 text-purple-800 px-2 py-0.5 rounded border border-purple-200">2. ADIM</span>
                        <span class="text-xl">🛠️</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-purple-600 transition-colors">İş Emri & Fiş Oluşturma</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Plaka arayın, yedek parça ve özel hızlı işçilik şablonları ekleyin.
                    </p>
                    <a href="{{ route('work-orders.create') }}" class="mt-3 inline-flex items-center gap-1 text-xs font-bold text-purple-600 hover:underline transition-colors">
                        <span>+ İş Emri Aç</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- Adım 3 -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 hover:border-indigo-400 hover:bg-white transition-all group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded border border-emerald-200">3. ADIM</span>
                        <span class="text-xl">🔩</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">Stok & Parça Yönetimi</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Kullandığınız parçaları düşün, kritik stok seviyesinde otomatik uyarı alın.
                    </p>
                    <a href="{{ route('parts.index') }}" class="mt-3 inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:underline transition-colors">
                        <span>Parça Listesi</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- Adım 4 -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 hover:border-indigo-400 hover:bg-white transition-all group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-800 px-2 py-0.5 rounded border border-amber-200">4. ADIM</span>
                        <span class="text-xl">🧾</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-amber-600 transition-colors">Yazdır & WhatsApp Gönder</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Servis fişini A4/termal yazdırın veya WhatsApp ile müşterinize iletin.
                    </p>
                    <a href="{{ route('work-orders.index') }}" class="mt-3 inline-flex items-center gap-1 text-xs font-bold text-amber-600 hover:underline transition-colors">
                        <span>Tüm İş Emirleri</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

            </div>
        </div>

        <!-- Rehber Kapalıysa Gösterilecek Hızlı Açma Butonu -->
        <div x-show="!showGuide" class="mb-4 text-right">
            <button @click="localStorage.removeItem('sanayi_hide_guide'); showGuide = true"
                    class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 px-3 py-1.5 rounded-lg transition-colors inline-flex items-center gap-1 font-semibold shadow-xs">
                <span>💡 Kullanım Rehberini Göster</span>
            </button>
        </div>


        <!-- ============================================================
             2. HIZLI PLAKA ARAMA VE EYLEM BAR
        ============================================================ -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs mb-6" 
             x-data="quickPlateSearch()"
             @plate-scanned.window="plate = $event.detail; search();">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                
                <!-- Hızlı Plaka Sorgulama Kutusu -->
                <div class="flex-1 max-w-xl relative">
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold text-slate-600 flex items-center gap-1">
                            <span>🔍 Hızlı Plaka Sorgula & Servis Kaydı Bul</span>
                        </label>
                        <x-plate-scanner />
                    </div>
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <input type="text" x-model="plate" @keyup.enter="search"
                                   class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm uppercase font-mono font-bold tracking-wider focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 bg-slate-50/50"
                                   placeholder="Örn: 34ABC123...">
                            <div x-show="loading" class="absolute right-3 top-3">
                                <svg class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </div>
                        <button type="button" @click="search"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-xs flex items-center gap-1.5 shrink-0">
                            <span>Sorgula</span>
                        </button>
                    </div>

                    <!-- Arama Sonuç Popover -->
                    <div x-show="results !== null" x-cloak @click.outside="results = null"
                         class="absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 overflow-hidden max-h-96 overflow-y-auto">
                        <template x-if="results && results.length > 0">
                            <div class="divide-y divide-slate-100">
                                <div class="px-3.5 py-2 bg-slate-900 text-white flex items-center justify-between text-[11px] font-bold">
                                    <span class="flex items-center gap-1 text-emerald-400">
                                        <span>✓</span> <span>Araç Bulundu</span>
                                    </span>
                                    <span class="text-slate-400" x-text="results.length + ' Sonuç'"></span>
                                </div>

                                <template x-for="v in results" :key="v.id">
                                    <div class="p-3.5 hover:bg-slate-50 transition-colors">
                                        <div class="flex items-start justify-between gap-2 mb-2">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-slate-900 text-sm" x-text="(v.brand || '') + ' ' + (v.model || '')"></span>
                                                    <span x-show="v.year" class="text-xs text-slate-400" x-text="'(' + v.year + ')'"></span>
                                                </div>
                                                <div class="inline-flex items-center bg-blue-700 text-white font-mono font-black text-xs px-2.5 py-0.5 rounded shadow-2xs mt-1" x-text="v.plate"></div>
                                            </div>

                                            <a :href="v.create_work_order_url" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] px-3 py-1.5 rounded-lg transition-colors shadow-2xs shrink-0 flex items-center gap-1">
                                                <span>+ İş Emri Aç</span>
                                            </a>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50 p-2.5 rounded-xl border border-slate-100 mt-2">
                                            <div class="col-span-2 flex items-center gap-1 text-slate-700 font-medium">
                                                <span>👤</span> <strong class="text-slate-900" x-text="v.customer ? v.customer.name : 'Müşteri Yok'"></strong>
                                                <span x-show="v.customer && v.customer.phone" class="text-slate-400 font-mono text-[11px]" x-text="'(' + v.customer.phone + ')'"></span>
                                            </div>
                                            <div class="text-slate-600">
                                                📅 Son Servis: <strong class="text-slate-800" x-text="v.last_service_date"></strong>
                                            </div>
                                            <div class="text-slate-600">
                                                💰 Top. Servis: <strong class="text-emerald-700 font-mono" x-text="v.total_spent_formatted"></strong>
                                            </div>
                                            <div class="col-span-2 text-slate-500 text-[11px] flex items-center justify-between pt-1 border-t border-slate-200/50">
                                                <span class="font-bold text-slate-700" x-text="'🔧 ' + v.total_services_count"></span>
                                                <a :href="v.url" class="text-blue-600 font-bold hover:underline">Servis Geçmişi →</a>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="results && results.length === 0">
                            <div class="text-center py-4 text-slate-500 text-xs p-3">
                                <p class="font-bold text-slate-700">Bu plakaya ait araç bulunamadı.</p>
                                <a href="{{ route('vehicles.create') }}" class="text-blue-600 font-bold hover:underline inline-block mt-1">
                                    + Yeni Araç Olarak Ekle
                                </a>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Hızlı İşlem Kısayolları -->
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('work-orders.create') }}"
                       class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs px-3.5 py-2.5 rounded-xl shadow-xs transition-all flex items-center gap-1.5">
                        <span>➕ Yeni İş Emri</span>
                    </a>
                    <a href="{{ route('customers.create') }}"
                       class="bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs px-3.5 py-2.5 rounded-xl shadow-xs transition-all flex items-center gap-1.5">
                        <span>👤 Müşteri Ekle</span>
                    </a>
                    <a href="{{ route('parts.create') }}"
                       class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-3.5 py-2.5 rounded-xl shadow-xs transition-all flex items-center gap-1.5">
                        <span>🔩 Stok Ekle</span>
                    </a>
                    <a href="{{ route('reports.index') }}"
                       class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 transition-all flex items-center gap-1.5">
                        <span>📊 Raporlar</span>
                    </a>
                </div>

            </div>
        </div>


        <!-- ============================================================
             3. ÖZET İSTATİSTİK KARTLARI
        ============================================================ -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            
            <!-- Aylık Gelir -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-500/5 rounded-full blur-xl group-hover:bg-emerald-500/10 transition-colors"></div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bu Ayki Tahsilat</span>
                    <div class="w-10 h-10 bg-emerald-100/80 rounded-xl flex items-center justify-center text-emerald-700 font-bold">
                        ₺
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900">₺{{ number_format($monthIncome, 2, ',', '.') }}</div>
                <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100 text-xs">
                    <span class="text-slate-400">Tamamlanan işler</span>
                    <span class="text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded">Aktif Kasa</span>
                </div>
            </div>

            <!-- Bekleyen Ödeme -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-24 h-24 bg-amber-500/5 rounded-full blur-xl group-hover:bg-amber-500/10 transition-colors"></div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bekleyen Ödemeler</span>
                    <div class="w-10 h-10 bg-amber-100/80 rounded-xl flex items-center justify-center text-amber-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900">₺{{ number_format($pendingPayment, 2, ',', '.') }}</div>
                <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100 text-xs">
                    <span class="text-slate-400">Teslim bekleyen fişler</span>
                    <span class="text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded">Tahsil Edilecek</span>
                </div>
            </div>

            <!-- Açık İş Emirleri -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-24 h-24 bg-blue-500/5 rounded-full blur-xl group-hover:bg-blue-500/10 transition-colors"></div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Atölyedeki İşler</span>
                    <div class="w-10 h-10 bg-blue-100/80 rounded-xl flex items-center justify-center text-blue-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900">{{ $openOrders }} <span class="text-sm font-semibold text-slate-400">Araç</span></div>
                <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100 text-xs">
                    <span class="text-slate-400">Tamirde / Liftte</span>
                    <a href="{{ route('work-orders.index', ['status' => 'devam_ediyor']) }}" class="text-blue-600 font-bold hover:underline">Listele →</a>
                </div>
            </div>

            <!-- Kritik Stok -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-24 h-24 bg-red-500/5 rounded-full blur-xl group-hover:bg-red-500/10 transition-colors"></div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kritik Stok Uyarısı</span>
                    <div class="w-10 h-10 bg-red-100/80 rounded-xl flex items-center justify-center text-red-700 font-bold">
                        ⚠️
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900">{{ $lowStockParts->count() }} <span class="text-sm font-semibold text-slate-400">Parça</span></div>
                <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100 text-xs">
                    <span class="text-slate-400">Minimum stok altı</span>
                    <a href="{{ route('parts.index', ['low_stock' => 1]) }}" class="text-red-600 font-bold hover:underline">İncele →</a>
                </div>
            </div>

        </div>


        <!-- ============================================================
             4. TANITIM KARTLARI VE KULLANIM İPUÇLARI (FEATURE HIGHLIGHTS)
        ============================================================ -->
        <div class="bg-white rounded-3xl p-5 sm:p-6 mb-6 shadow-xs border border-slate-200">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="text-xl">💡</span>
                    <h2 class="text-base font-black text-slate-900">Pratik Kullanım İpuçları & Öne Çıkan Özellikler</h2>
                </div>
                <span class="text-xs text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-1 rounded-full font-bold">Nasıl Kullanılır?</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <!-- İpucu 1 -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 hover:border-indigo-400 hover:bg-white transition-colors">
                    <div class="flex items-center gap-2.5 mb-2">
                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-sm">
                            ⭐
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">Hızlı İşçilik Şablonları</h3>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        İş emri oluştururken sık yaptığınız işçilikleri (örn. Balata değişimi, Yağ değişimi) tek tıkla şablon butonlarına ekleyebilir ve dilediğiniz zaman silebilirsiniz.
                    </p>
                </div>

                <!-- İpucu 2 -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 hover:border-indigo-400 hover:bg-white transition-colors">
                    <div class="flex items-center gap-2.5 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-sm">
                            📱
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">WhatsApp Fiş Paylaşımı</h3>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Tamamlanan iş emri detayında bulunan <strong class="text-emerald-700">"WhatsApp ile Paylaş"</strong> butonuna basarak müşterinize araç durumunu ve hesap özetini tek tıkla iletin.
                    </p>
                </div>

                <!-- İpucu 3 -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 hover:border-indigo-400 hover:bg-white transition-colors">
                    <div class="flex items-center gap-2.5 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-sm">
                            🖨️
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">Yazıcı Uyumlu Servis Fişi</h3>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Müşterilerinize vermek üzere A4 veya küçük termal fiş yazıcılarına uygun formatta <strong>Servis Teslim Fişi</strong> bastırabilirsiniz.
                    </p>
                </div>

            </div>
        </div>


        <!-- ============================================================
             5. GRAFİK VE KRİTİK STOK UYARILARI
        ============================================================ -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            
            <!-- Son 7 Günlük Gelir Grafiği -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-black text-slate-800">Son 7 Günlük Gelir Trendi</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Tamamlanan ve ödenen iş emirlerinin günlük toplamı</p>
                    </div>
                    <a href="{{ route('reports.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Detaylı Rapor →</a>
                </div>
                <canvas id="incomeChart" height="110"></canvas>
            </div>

            <!-- Kritik Stok Uyarıları -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <h2 class="text-sm font-black text-slate-800 flex items-center gap-2">
                            <span>Kritik Stok Uyarısı</span>
                            <span class="text-[10px] bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-bold">{{ $lowStockParts->count() }} Parça</span>
                        </h2>
                        <a href="{{ route('parts.index', ['low_stock' => 1]) }}" class="text-xs text-blue-600 font-bold hover:underline">Tümü</a>
                    </div>
                    <div class="space-y-2.5 max-h-60 overflow-y-auto">
                        @forelse($lowStockParts->take(6) as $part)
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="min-w-0 pr-2">
                                    <div class="text-xs font-bold text-slate-800 truncate">{{ $part->name }}</div>
                                    <div class="text-[11px] text-slate-400">Kod: <span class="font-mono">{{ $part->code ?? '-' }}</span> | Min: {{ $part->min_stock }}</div>
                                </div>
                                <span class="px-2.5 py-1 text-xs font-extrabold rounded-lg shrink-0
                                    {{ $part->stock == 0 ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                    {{ $part->stock }} Adet
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-2 text-xl">
                                    ✓
                                </div>
                                <p class="text-xs font-bold text-slate-700">Tüm Stoklar Yeterli Seviyede</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Kritik seviyeye düşen parça bulunmuyor.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <a href="{{ route('parts.create') }}" class="mt-4 w-full bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold py-2.5 rounded-xl transition-colors text-center block">
                    + Stok Ekle
                </a>
            </div>

        </div>


        <!-- ============================================================
             6. BUGÜNKÜ İŞLER VE SON İŞ EMİRLERİ
        ============================================================ -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Bugünkü İşler -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <div>
                        <h2 class="text-sm font-black text-slate-800">Bugünkü İşler</h2>
                        <p class="text-xs text-slate-400">Bugün giriş yapılan servis emirleri</p>
                    </div>
                    <a href="{{ route('work-orders.create') }}"
                       class="text-xs bg-blue-600 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-blue-700 transition-colors shadow-xs">
                        + Yeni İş Emri
                    </a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($todayOrders as $order)
                        <a href="{{ route('work-orders.show', $order) }}"
                           class="flex items-center gap-3 px-5 py-3.5 hover:bg-blue-50/50 transition-colors group">
                            <div class="w-12 h-12 bg-blue-50 border border-blue-200 rounded-xl flex items-center justify-center font-mono font-black text-blue-800 text-xs shrink-0 shadow-2xs group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                {{ $order->vehicle->plate }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-bold text-slate-900 truncate">{{ $order->vehicle->customer->name }}</div>
                                <div class="text-[11px] text-slate-400 truncate">{{ $order->vehicle->brand }} {{ $order->vehicle->model }}</div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-xs font-extrabold text-slate-900">₺{{ number_format($order->grand_total, 2, ',', '.') }}</div>
                                @php 
                                    $statusClasses = [
                                        'beklemede' => 'bg-amber-100 text-amber-800',
                                        'devam_ediyor' => 'bg-blue-100 text-blue-800',
                                        'tamamlandi' => 'bg-emerald-100 text-emerald-800',
                                        'odendi' => 'bg-slate-100 text-slate-700'
                                    ]; 
                                    $sc = $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-700'; 
                                @endphp
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full inline-block mt-0.5 {{ $sc }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-10 text-slate-400 text-xs">
                            <span class="text-2xl block mb-1">📅</span>
                            Bugün henüz yeni iş emri açılmadı.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Son İş Emirleri -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <div>
                        <h2 class="text-sm font-black text-slate-800">Son İş Emirleri</h2>
                        <p class="text-xs text-slate-400">Son servis kayıtları ve tutarları</p>
                    </div>
                    <a href="{{ route('work-orders.index') }}" class="text-xs text-blue-600 font-bold hover:underline">Tümünü Gör →</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($recentOrders as $order)
                        <a href="{{ route('work-orders.show', $order) }}"
                           class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50 transition-colors group">
                            <div class="w-12 h-12 bg-slate-100 border border-slate-200 rounded-xl flex items-center justify-center font-mono font-black text-slate-700 text-xs shrink-0 group-hover:bg-slate-800 group-hover:text-white transition-colors">
                                {{ $order->vehicle->plate }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-bold text-slate-900 truncate">{{ $order->vehicle->customer->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $order->date->format('d/m/Y') }}</div>
                            </div>
                            <div class="text-xs font-extrabold text-slate-800 shrink-0">
                                ₺{{ number_format($order->grand_total, 2, ',', '.') }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

    <!-- Hızlı Plaka Arama Scripti -->
    <script>
    function quickPlateSearch() {
        return {
            plate: '',
            loading: false,
            results: null,
            async search() {
                const q = this.plate.trim();
                if (!q) return;
                this.loading = true;
                try {
                    const res = await fetch(`/vehicles/quick-search?q=${encodeURIComponent(q)}`);
                    this.results = await res.json();
                } catch (e) {
                    this.results = [];
                }
                this.loading = false;
            }
        }
    }

    // Chart JS Script
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('incomeChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($last7Days->pluck('label')) !!},
                datasets: [{
                    label: 'Gelir (₺)',
                    data: {!! json_encode($last7Days->pluck('total')) !!},
                    backgroundColor: 'rgba(79, 70, 229, 0.15)',
                    borderColor: 'rgba(79, 70, 229, 0.9)',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(79, 70, 229, 0.3)'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => '₺' + v.toLocaleString('tr-TR')
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    });
    </script>
</x-app-layout>
