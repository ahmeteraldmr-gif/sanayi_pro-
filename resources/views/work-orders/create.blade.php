<x-app-layout>
    <x-slot name="title">Yeni İş Emri</x-slot>

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('work-orders.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h2 class="text-xl font-bold text-gray-900">Yeni İş Emri</h2>
    </div>

    <div x-data="workOrderForm()" @plate-scanned.window="searchPlate = $event.detail; searchVehicle();" class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Sol: Araç & Genel Bilgiler -->
        <div class="space-y-4">
            <!-- Plaka Arama / Araç Seçimi -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700">🚗 Araç Bilgisi</h3>
                    <a href="{{ route('vehicles.create') }}" class="text-xs text-blue-600 font-medium hover:underline">+ Yeni Araç Ekle</a>
                </div>

                <!-- Dropdown Araç Seçimi -->
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Mevcut Araç Listesinden Seç</label>
                    <select x-model="selectedVehicleId" @change="onSelectVehicleChange"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">— Kayıtlı Araç Seçin —</option>
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}" {{ ($vehicle?->id == $v->id) ? 'selected' : '' }}>
                                {{ $v->plate }} — {{ $v->customer->name ?? 'Müşterisiz' }} ({{ $v->brand }} {{ $v->model }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Plaka ile Manuel Arama -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-semibold text-gray-600">veya Plaka Yazıp Ara</label>
                        <x-plate-scanner />
                    </div>
                    <div class="flex gap-2">
                        <input type="text" x-model="searchPlate"
                               @keydown.enter.prevent="searchVehicle"
                               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm uppercase font-mono font-bold focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="34ABC123" style="text-transform:uppercase">
                        <button type="button" @click="searchVehicle"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors">Ara</button>
                    </div>
                    <div x-show="searchMsg" x-cloak class="mt-1.5 text-xs font-medium" :class="found ? 'text-green-600' : 'text-red-500'" x-text="searchMsg"></div>
                </div>

                <!-- Araç Bilgi Kartı -->
                <div x-show="found" x-cloak class="bg-blue-50 border border-blue-200 rounded-xl p-3.5 space-y-1">
                    <div class="flex items-center justify-between">
                        <div class="font-mono font-extrabold text-blue-900 text-lg" x-text="vehicle.plate"></div>
                        <span class="text-[10px] bg-blue-200/60 text-blue-800 font-bold px-2 py-0.5 rounded">Seçili Araç</span>
                    </div>
                    <div class="text-xs font-semibold text-blue-800" x-text="(vehicle.brand || '') + ' ' + (vehicle.model || '') + ' ' + (vehicle.year || '')"></div>
                    <div class="text-xs text-blue-600 border-t border-blue-200/50 pt-1.5 mt-1">
                        Sahip: <span class="font-bold text-blue-900" x-text="customer.name"></span>
                        <span x-show="customer.phone"> — <span class="font-mono" x-text="customer.phone"></span></span>
                    </div>
                </div>

                <div x-show="!found" x-cloak class="text-center py-2 border border-dashed border-gray-200 rounded-lg">
                    <p class="text-xs text-gray-400 mb-1">Araç henüz seçilmedi.</p>
                    <a href="{{ route('vehicles.create') }}" class="text-xs text-blue-600 font-bold hover:underline">+ Yeni Araç ve Müşteri Oluştur</a>
                </div>

                <input type="hidden" name="vehicle_id" x-model="vehicleId" form="work-order-form">
            </div>

            <!-- Genel Bilgiler -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">📋 Genel Bilgiler</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tarih *</label>
                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required form="work-order-form"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Durum</label>
                        <select name="status" form="work-order-form"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="devam_ediyor">Devam Ediyor</option>
                            <option value="beklemede">Beklemede</option>
                            <option value="tamamlandi">Tamamlandı</option>
                            <option value="odendi">Ödendi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">İndirim (₺)</label>
                        <input type="number" name="discount" value="{{ old('discount', 0) }}" min="0" step="0.01"
                               x-model.number="discount" form="work-order-form"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Notlar</label>
                        <textarea name="notes" rows="3" form="work-order-form"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Özel durum, müşteri isteği...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Özet -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">💰 Tutar Özeti</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Parçalar</span>
                        <span>₺<span x-text="totalParts.toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>İşçilik</span>
                        <span>₺<span x-text="totalLabor.toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>İndirim</span>
                        <span class="text-red-500">-₺<span x-text="discount.toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-900 text-base border-t border-gray-200 pt-2 mt-2">
                        <span>Genel Toplam</span>
                        <span>₺<span x-text="grandTotal.toFixed(2)"></span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sağ: Kalemler (Parça + İşçilik) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">🔧 Yapılan İşler & Kullanılan Parçalar</h3>
                </div>

                <form id="work-order-form" method="POST" action="{{ route('work-orders.store') }}">
                    @csrf

                    <!-- Kalemler Listesi -->
                    <div class="space-y-3 mb-4" id="items-list">
                        <template x-for="(item, idx) in items" :key="idx">
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-xs font-bold px-2 py-1 rounded"
                                          :class="item.type === 'part' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'"
                                          x-text="item.type === 'part' ? '🔩 Parça' : '👷 İşçilik'"></span>
                                    <button type="button" @click="removeItem(idx)"
                                            class="ml-auto text-red-400 hover:text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                                    <div class="sm:col-span-5">
                                        <label class="text-xs text-gray-500">İşlem / Parça Adı</label>
                                        <input type="text" :name="`items[${idx}][name]`" x-model="item.name" required
                                               class="w-full mt-1 border border-gray-300 rounded-lg px-2.5 py-1.5 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="Örn: Ön balata değişimi">
                                        <input type="hidden" :name="`items[${idx}][type]`" :value="item.type">
                                        <input type="hidden" :name="`items[${idx}][part_id]`" :value="item.part_id">
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 sm:contents">
                                        <div class="sm:col-span-2">
                                            <label class="text-xs text-gray-500">Miktar</label>
                                            <input type="number" :name="`items[${idx}][quantity]`" x-model.number="item.quantity"
                                                   @input="calcItem(idx)" min="1"
                                                   class="w-full mt-1 border border-gray-300 rounded-lg px-2 py-1.5 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-center sm:text-left">
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="text-xs text-gray-500">Birim (₺)</label>
                                            <input type="number" :name="`items[${idx}][unit_price]`" x-model.number="item.unit_price"
                                                   @input="calcItem(idx)" min="0" step="0.01"
                                                   class="w-full mt-1 border border-gray-300 rounded-lg px-2 py-1.5 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="text-xs text-gray-500">Toplam</label>
                                            <div class="mt-1 px-2 py-1.5 bg-white border border-gray-200 rounded-lg text-sm font-bold text-gray-900 truncate text-center sm:text-left">
                                                ₺<span x-text="(item.quantity * item.unit_price).toFixed(2)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="items.length === 0">
                            <div class="text-center py-8 text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">
                                <p class="text-sm">Henüz kalem eklenmedi.</p>
                                <p class="text-xs mt-1">Aşağıdan parça veya işçilik ekleyin.</p>
                            </div>
                        </template>
                    </div>

                    <!-- Hızlı Ekleme Paneli -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Parça Ekleme -->
                            <div class="border border-blue-200 rounded-lg p-3.5 bg-blue-50">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-xs font-bold text-blue-800">🔩 Stoktan Parça Ekle</p>
                                    <span class="text-[10px] font-semibold text-blue-600 bg-blue-100 px-2 py-0.5 rounded">Fiyat Otomatik Gelir</span>
                                </div>
                                <div class="relative mb-2">
                                    <input type="text" x-model="partSearch"
                                           @focus="searchParts"
                                           @input.debounce.250ms="searchParts"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                                           placeholder="Parça ara veya listelemek için tıklayın...">
                                    <div x-show="partResults.length > 0" x-cloak
                                         @click.outside="partResults = []"
                                         class="absolute top-full left-0 right-0 bg-white border border-blue-200 rounded-lg shadow-xl z-30 mt-1 max-h-56 overflow-y-auto">
                                        <template x-for="p in partResults" :key="p.id">
                                            <button type="button" @click="addPart(p)"
                                                    class="w-full text-left px-3.5 py-2.5 text-sm hover:bg-blue-50 border-b border-gray-100 last:border-0 flex items-center justify-between group transition-colors">
                                                <div>
                                                    <div class="font-bold text-gray-900 group-hover:text-blue-700" x-text="p.name"></div>
                                                    <div class="text-xs text-gray-500">
                                                        <span x-show="p.code" class="font-mono text-gray-600 mr-1" x-text="p.code"></span>
                                                        Stok: <span x-text="p.stock" :class="p.stock > 0 ? 'text-green-600 font-bold' : 'text-red-500 font-bold'"></span> Adet
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="font-extrabold text-blue-700 text-sm">₺<span x-text="Number(p.sell_price).toLocaleString('tr-TR', {minimumFractionDigits: 2})"></span></div>
                                                    <span class="text-[10px] text-blue-500 font-semibold">+ Ekle</span>
                                                </div>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                                <button type="button" @click="addManualPart()"
                                        class="w-full text-xs font-bold border border-blue-300 text-blue-700 py-2 rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center gap-1">
                                    <span>✍️ Manuel Parça Ekle (Stokta Olmayan)</span>
                                </button>
                            </div>

                            <!-- İşçilik Ekleme -->
                            <div class="border border-purple-200 rounded-xl p-4 bg-purple-50/60 space-y-3">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs font-bold text-purple-900">👷 İşçilik / Hizmet Ekle</p>
                                    <button type="button" @click="saveCurrentAsTemplate()" 
                                            x-show="laborName.length > 0" x-cloak
                                            class="text-[11px] font-semibold text-purple-700 hover:text-purple-900 bg-purple-100 hover:bg-purple-200 px-2 py-0.5 rounded-lg transition-colors flex items-center gap-1"
                                            title="Girdiğiniz işçiliği hızlı buton olarak kaydeder">
                                        <span>⭐ Şablon Olarak Kaydet</span>
                                    </button>
                                </div>
                                <div class="space-y-2">
                                    <input type="text" x-model="laborName"
                                           class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white"
                                           placeholder="Balata değişimi, Yağ değişimi...">
                                    <input type="number" x-model.number="laborPrice" min="0" step="0.01"
                                           class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white"
                                           placeholder="Ücret (₺)">
                                    <div class="flex gap-2">
                                        <button type="button" @click="addLabor()"
                                                class="flex-1 bg-purple-600 text-white py-2 rounded-xl text-sm font-semibold hover:bg-purple-700 transition-colors shadow-xs">
                                            + İşçilik Ekle
                                        </button>
                                        <button type="button" @click="saveCurrentAsTemplate()"
                                                class="bg-purple-100 text-purple-700 px-3 py-2 rounded-xl text-xs font-semibold hover:bg-purple-200 transition-colors"
                                                title="Bu işçilik ve fiyatı aşağıdaki hızlı şablon butonlarına ekler">
                                            ⭐ Şablona Ekle
                                        </button>
                                    </div>
                                </div>

                                <!-- Hızlı İşçilik Şablonları -->
                                <div class="pt-2 border-t border-purple-200/60">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-[11px] font-bold text-purple-900 uppercase tracking-wider">Hızlı Şablonlar</span>
                                        <span class="text-[10px] text-purple-600">Silmek için ✕ butonuna basın</span>
                                    </div>
                                    <div class="flex flex-wrap gap-1.5">
                                        <template x-for="(tpl, tIdx) in laborTemplates" :key="tIdx">
                                            <div class="inline-flex items-center bg-purple-100 text-purple-800 border border-purple-200 rounded-lg px-2.5 py-1 text-xs font-medium group hover:bg-purple-200/80 transition-all">
                                                <button type="button" @click="addLaborTemplate(tpl)" class="hover:underline flex items-center gap-1">
                                                    <span x-text="tpl.name"></span>
                                                    <span class="text-[10px] font-bold text-purple-600" x-text="'(₺' + tpl.price + ')'"></span>
                                                </button>
                                                <button type="button" @click.stop="deleteLaborTemplate(tIdx)" title="Bu şablonu sil"
                                                        class="ml-1.5 text-purple-400 hover:text-red-600 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="submit" :disabled="items.length === 0 || !vehicleId"
                                class="flex-1 bg-blue-600 text-white py-3 rounded-lg text-sm font-bold hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed transition-opacity">
                            İş Emrini Kaydet
                        </button>
                        <a href="{{ route('work-orders.index') }}"
                           class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg text-sm hover:bg-gray-50">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function workOrderForm() {
        return {
            allVehicles: {!! json_encode($vehiclesList) !!},
            selectedVehicleId: '{{ $vehicle?->id ?? "" }}',
            searchPlate: '{{ $vehicle?->plate ?? "" }}',
            vehicleId: '{{ $vehicle?->id ?? "" }}',
            found: {{ $vehicle ? 'true' : 'false' }},
            searchMsg: '',
            vehicle: {
                plate: '{{ $vehicle?->plate ?? "" }}',
                brand: '{{ $vehicle?->brand ?? "" }}',
                model: '{{ $vehicle?->model ?? "" }}',
                year:  '{{ $vehicle?->year ?? "" }}',
            },
            customer: {
                name:  '{{ $vehicle?->customer?->name ?? "" }}',
                phone: '{{ $vehicle?->customer?->phone ?? "" }}',
            },
            @php
                $initialItems = [];
                if (request('labor')) {
                    $initialItems[] = [
                        'type' => 'labor',
                        'part_id' => null,
                        'name' => (string)request('labor'),
                        'quantity' => 1,
                        'unit_price' => (float)request('price', 0),
                    ];
                }
                $branchQuickActions = \App\Models\Branch::getBranchQuickActions(auth()->user()?->branch?->name);
                $defaultTemplates = collect($branchQuickActions)->map(fn($qa) => ['name' => $qa['labor'], 'price' => (float)$qa['price']])->values()->toArray();
                if (empty($defaultTemplates)) {
                    $defaultTemplates = [
                        ['name' => 'Periyodik Bakım İşçiliği', 'price' => 750],
                        ['name' => 'Kontrol & Teşhis', 'price' => 350],
                        ['name' => 'Parça Değişim İşçiliği', 'price' => 500],
                    ];
                }
            @endphp
            items: {!! json_encode($initialItems) !!},
            discount: 0,
            partSearch: '',
            partResults: [],
            laborName: '',
            laborPrice: 0,
            laborTemplates: JSON.parse(localStorage.getItem('sanayi_labor_templates') || 'null') || {!! json_encode($defaultTemplates) !!},
            saveLaborTemplates() {
                localStorage.setItem('sanayi_labor_templates', JSON.stringify(this.laborTemplates));
            },
            saveCurrentAsTemplate() {
                const name = this.laborName.trim();
                if (!name) {
                    alert('Lütfen önce bir işçilik adı girin.');
                    return;
                }
                const price = parseFloat(this.laborPrice) || 0;
                const existingIndex = this.laborTemplates.findIndex(t => t.name.toLowerCase() === name.toLowerCase());
                if (existingIndex !== -1) {
                    this.laborTemplates[existingIndex].price = price;
                } else {
                    this.laborTemplates.push({ name: name, price: price });
                }
                this.saveLaborTemplates();
            },
            deleteLaborTemplate(idx) {
                this.laborTemplates.splice(idx, 1);
                this.saveLaborTemplates();
            },

            onSelectVehicleChange() {
                if (!this.selectedVehicleId) {
                    this.found = false;
                    this.vehicleId = '';
                    this.searchMsg = '';
                    return;
                }
                const foundV = this.allVehicles.find(v => v.id == this.selectedVehicleId);
                if (foundV) {
                    this.found = true;
                    this.vehicleId = foundV.id;
                    this.vehicle = foundV;
                    this.customer = foundV.customer;
                    this.searchPlate = foundV.plate;
                    this.searchMsg = '✓ Listeden araç seçildi';
                }
            },

            get totalParts() {
                return this.items.filter(i => i.type === 'part')
                    .reduce((s, i) => s + i.quantity * i.unit_price, 0);
            },
            get totalLabor() {
                return this.items.filter(i => i.type === 'labor')
                    .reduce((s, i) => s + i.quantity * i.unit_price, 0);
            },
            get grandTotal() {
                return Math.max(0, this.totalParts + this.totalLabor - this.discount);
            },

            async searchVehicle() {
                const plate = this.searchPlate.trim().toUpperCase();
                if (!plate) return;
                const res = await fetch(`/vehicles/search?plate=${plate}`, {
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                if (data.found) {
                    this.found = true;
                    this.vehicleId = data.vehicle.id;
                    this.selectedVehicleId = data.vehicle.id;
                    this.vehicle = data.vehicle;
                    this.customer = data.customer;
                    this.searchMsg = '✓ Araç bulundu!';
                } else {
                    this.found = false;
                    this.vehicleId = '';
                    this.selectedVehicleId = '';
                    this.searchMsg = 'Bu plakaya ait araç bulunamadı.';
                }
            },

            async searchParts() {
                if (this.partSearch.length < 2) { this.partResults = []; return; }
                const res = await fetch(`/parts/ajax-search?q=${encodeURIComponent(this.partSearch)}`);
                this.partResults = await res.json();
            },

            addPart(p) {
                this.items.push({ type: 'part', part_id: p.id, name: p.name, quantity: 1, unit_price: parseFloat(p.sell_price) });
                this.partSearch = '';
                this.partResults = [];
            },

            addManualPart() {
                this.items.push({ type: 'part', part_id: null, name: '', quantity: 1, unit_price: 0 });
            },

            addLabor() {
                if (!this.laborName) { alert('İşçilik adını girin.'); return; }
                this.items.push({ type: 'labor', part_id: null, name: this.laborName, quantity: 1, unit_price: this.laborPrice });
                this.laborName = '';
                this.laborPrice = 0;
            },

            addLaborTemplate(tpl) {
                this.items.push({ type: 'labor', part_id: null, name: tpl.name, quantity: 1, unit_price: tpl.price });
            },

            removeItem(idx) {
                this.items.splice(idx, 1);
            },

            calcItem(idx) {
                // reactive calculation is handled by x-text binding
            }
        }
    }
    </script>
</x-app-layout>
