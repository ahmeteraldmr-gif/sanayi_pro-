<x-app-layout>
    <x-slot name="title">Yeni Araç & Müşteri Ekle</x-slot>

    <div class="max-w-3xl mx-auto" 
         x-data="{ customerMode: '{{ old('customer_mode', old('new_customer_name') ? 'new' : ($selectedCustomer ? 'existing' : 'existing')) }}', plateVal: '{{ old('plate') }}' }"
         @plate-scanned.window="plateVal = $event.detail">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('vehicles.index') }}" class="p-2 text-slate-400 hover:text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Yeni Araç Ekle</h2>
                    <p class="text-xs text-slate-500">Mevcut bir müşteriyi seçebilir veya aynı formda yeni müşteri oluşturabilirsiniz.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('vehicles.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="customer_mode" :value="customerMode">

            <!-- MÜŞTERİ SEÇİM / EKLEME BÖLÜMÜ -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">1</span>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Müşteri Bilgileri</h3>
                            <p class="text-xs text-slate-500">Aracın bağlı olacağı sahibini seçin veya yeni oluşturun</p>
                        </div>
                    </div>

                    <!-- Mode Switcher Segmented Control -->
                    <div class="inline-flex p-1 bg-slate-100 rounded-xl text-xs font-semibold">
                        <button type="button" 
                                @click="customerMode = 'existing'"
                                :class="customerMode === 'existing' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                                class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Mevcut Müşteri
                        </button>
                        <button type="button" 
                                @click="customerMode = 'new'"
                                :class="customerMode === 'new' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                                class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            + Yeni Müşteri Oluştur
                        </button>
                    </div>
                </div>

                <!-- Mode 1: Mevcut Müşteri Seçimi -->
                <div x-show="customerMode === 'existing'" class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-700">Sahip (Müşteri) *</label>
                    <select name="customer_id" 
                            :required="customerMode === 'existing'"
                            class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 bg-white">
                        <option value="">— Müşteri Seçin —</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}"
                                {{ old('customer_id', $selectedCustomer?->id) == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} @if($c->phone)({{ $c->phone }})@endif
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mode 2: Yeni Müşteri Ekleme Formu -->
                <div x-show="customerMode === 'new'" x-cloak class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-indigo-900 uppercase tracking-wider">Hızlı Yeni Müşteri Kaydı</h4>
                        <span class="text-[11px] text-indigo-600 font-medium">Bu müşteri otomatik kaydedilip araca bağlanacak</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-1">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Müşteri Ad Soyad *</label>
                            <input type="text" name="new_customer_name" value="{{ old('new_customer_name') }}"
                                   :required="customerMode === 'new'"
                                   class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 bg-white"
                                   placeholder="Ahmet Yılmaz">
                            @error('new_customer_name')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-1">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Telefon Numarası</label>
                            <input type="text" name="new_customer_phone" value="{{ old('new_customer_phone') }}"
                                   class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 bg-white"
                                   placeholder="0532 123 4567">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Adres</label>
                            <input type="text" name="new_customer_address" value="{{ old('new_customer_address') }}"
                                   class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 bg-white"
                                   placeholder="Mahalle, ilçe, şehir...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ARAÇ BİLGİLERİ BÖLÜMÜ -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-4">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-4">
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">2</span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Araç Bilgileri</h3>
                        <p class="text-xs text-slate-500">Plaka ve araç detaylarını girin</p>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-semibold text-slate-700">Plaka *</label>
                        <x-plate-scanner />
                    </div>
                    <input type="text" name="plate" x-model="plateVal" value="{{ old('plate') }}" required
                           class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-base sm:text-sm font-mono font-bold tracking-wider text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 uppercase"
                           placeholder="34ABC123" style="text-transform:uppercase">
                    @error('plate')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Marka</label>
                        <input type="text" name="brand" value="{{ old('brand') }}"
                               class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600"
                               placeholder="Toyota, Ford, BMW...">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Model</label>
                        <input type="text" name="model" value="{{ old('model') }}"
                               class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600"
                               placeholder="Corolla, Focus, 320i...">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Motor / Yakıt Türü</label>
                        <input type="text" name="engine" value="{{ old('engine') }}"
                               class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600"
                               placeholder="Örn: 2.0 Dizel, 1.6 Benzin...">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Yıl</label>
                        <input type="text" name="year" value="{{ old('year') }}"
                               class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600"
                               placeholder="2018">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Güncel Kilometre (KM)</label>
                        <input type="number" name="mileage" value="{{ old('mileage', 0) }}" min="0"
                               class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600"
                               placeholder="184250">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Renk</label>
                        <input type="text" name="color" value="{{ old('color') }}"
                               class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600"
                               placeholder="Beyaz, Siyah, Gümüş...">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Araç Notları</label>
                    <textarea name="notes" rows="3"
                              class="w-full border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600"
                              placeholder="Özel durumlar, çizikler, araçla ilgili dipnotlar...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-indigo-600 text-white px-8 py-3 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-colors shadow-md shadow-indigo-600/20 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Araç ve Müşteriyi Kaydet
                </button>
                <a href="{{ route('vehicles.index') }}"
                   class="border border-slate-300 text-slate-700 px-6 py-3 rounded-xl text-sm font-medium hover:bg-slate-100 transition-colors">
                    İptal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

