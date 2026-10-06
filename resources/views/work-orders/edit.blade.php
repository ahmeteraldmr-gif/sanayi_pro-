<x-app-layout>
    <x-slot name="title">İş Emri Düzenle #{{ $workOrder->id }}</x-slot>

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('work-orders.show', $workOrder) }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h2 class="text-xl font-bold text-gray-900">İş Emri Düzenle #{{ $workOrder->id }}</h2>
    </div>

    <div x-data="editForm()" class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="space-y-4">
            <!-- Araç Bilgisi (sadece görüntüle) -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="font-mono font-bold text-blue-800 text-lg">{{ $workOrder->vehicle->plate }}</div>
                <div class="text-sm text-blue-700">{{ $workOrder->vehicle->brand }} {{ $workOrder->vehicle->model }}</div>
                <div class="text-xs text-blue-500">{{ $workOrder->vehicle->customer->name }}</div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Genel Bilgiler</h3>
                <form id="edit-form" method="POST" action="{{ route('work-orders.update', $workOrder) }}" class="space-y-3">
                    @csrf @method('PUT')
                    <input type="hidden" name="vehicle_id" value="{{ $workOrder->vehicle_id }}">
                    <div>
                        <label class="text-xs font-medium text-gray-500">Tarih</label>
                        <input type="date" name="date" value="{{ old('date', $workOrder->date->format('Y-m-d')) }}" required
                               class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">Durum</label>
                        <select name="status" class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach(['beklemede' => 'Beklemede', 'devam_ediyor' => 'Devam Ediyor', 'tamamlandi' => 'Tamamlandı', 'odendi' => 'Ödendi'] as $s => $l)
                                <option value="{{ $s }}" {{ $workOrder->status === $s ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">İndirim (₺)</label>
                        <input type="number" name="discount" value="{{ old('discount', $workOrder->discount) }}" min="0" step="0.01"
                               x-model.number="discount"
                               class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500">Notlar</label>
                        <textarea name="notes" rows="3"
                                  class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes', $workOrder->notes) }}</textarea>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Tutar Özeti</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Parçalar</span><span>₺<span x-text="totalParts.toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>İşçilik</span><span>₺<span x-text="totalLabor.toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between text-red-500">
                        <span>İndirim</span><span>-₺<span x-text="discount.toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-900 text-base border-t border-gray-200 pt-2">
                        <span>Genel Toplam</span><span>₺<span x-text="grandTotal.toFixed(2)"></span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">İşlem Kalemleri</h3>

                <div class="space-y-3 mb-4">
                    <template x-for="(item, idx) in items" :key="idx">
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs font-bold px-2 py-1 rounded"
                                      :class="item.type === 'part' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'"
                                      x-text="item.type === 'part' ? '🔩 Parça' : '👷 İşçilik'"></span>
                                <button type="button" @click="removeItem(idx)" class="ml-auto text-red-400 hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                                <div class="sm:col-span-5">
                                    <input type="text" :name="`items[${idx}][name]`" x-model="item.name" required form="edit-form"
                                           class="w-full border border-gray-300 rounded-lg px-2.5 py-1.5 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="İşlem / Parça Adı">
                                    <input type="hidden" :name="`items[${idx}][type]`" :value="item.type" form="edit-form">
                                    <input type="hidden" :name="`items[${idx}][part_id]`" :value="item.part_id" form="edit-form">
                                </div>
                                <div class="grid grid-cols-3 gap-2 sm:contents">
                                    <div class="sm:col-span-2">
                                        <input type="number" :name="`items[${idx}][quantity]`" x-model.number="item.quantity" min="1" form="edit-form"
                                               class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-center sm:text-left">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <input type="number" :name="`items[${idx}][unit_price]`" x-model.number="item.unit_price" min="0" step="0.01" form="edit-form"
                                               class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <div class="px-2 py-1.5 bg-white border border-gray-200 rounded-lg text-sm font-bold text-gray-900 truncate text-center sm:text-left">
                                            ₺<span x-text="(item.quantity * item.unit_price).toFixed(2)"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Ekleme Paneli -->
                <div class="border-t border-gray-200 pt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
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
                                    class="text-[11px] font-semibold text-purple-700 hover:text-purple-900 bg-purple-100 hover:bg-purple-200 px-2 py-0.5 rounded-lg transition-colors flex items-center gap-1">
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

                <div class="flex gap-3 mt-6">
                    <button type="submit" form="edit-form"
                            class="flex-1 bg-blue-600 text-white py-3 rounded-lg text-sm font-bold hover:bg-blue-700">
                        Değişiklikleri Kaydet
                    </button>
                    <a href="{{ route('work-orders.show', $workOrder) }}"
                       class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg text-sm hover:bg-gray-50">İptal</a>
                </div>
            </div>
        </div>
    </div>

    <script>
    function editForm() {
        return {
            discount: {{ $workOrder->discount }},
            items: @json($workOrder->items->map(fn($i) => ['type' => $i->type, 'part_id' => $i->part_id, 'name' => $i->name, 'quantity' => $i->quantity, 'unit_price' => (float)$i->unit_price])),
            partSearch: '',
            partResults: [],
            laborName: '',
            laborPrice: 0,
            laborTemplates: JSON.parse(localStorage.getItem('sanayi_labor_templates') || 'null') || [
                { name: 'Balata Değişimi', price: 300 },
                { name: 'Yağ Değişimi', price: 250 },
                { name: 'Disk Değişimi', price: 400 },
                { name: 'Akü Değişimi', price: 200 },
                { name: 'Muayene', price: 150 },
                { name: 'Rot/Balans', price: 350 },
            ],
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
            addLaborTemplate(tpl) {
                this.items.push({ type: 'labor', part_id: null, name: tpl.name, quantity: 1, unit_price: tpl.price });
            },
            get totalParts() { return this.items.filter(i => i.type==='part').reduce((s,i)=>s+i.quantity*i.unit_price,0); },
            get totalLabor() { return this.items.filter(i => i.type==='labor').reduce((s,i)=>s+i.quantity*i.unit_price,0); },
            get grandTotal() { return Math.max(0, this.totalParts+this.totalLabor-this.discount); },
            async searchParts() {
                if (this.partSearch.length < 2) { this.partResults = []; return; }
                const res = await fetch(`/parts/ajax-search?q=${encodeURIComponent(this.partSearch)}`);
                this.partResults = await res.json();
            },
            addPart(p) { this.items.push({type:'part',part_id:p.id,name:p.name,quantity:1,unit_price:parseFloat(p.sell_price)}); this.partSearch=''; this.partResults=[]; },
            addManualPart() { this.items.push({type:'part',part_id:null,name:'',quantity:1,unit_price:0}); },
            addLabor() { if(!this.laborName){alert('İşçilik adını girin.');return;} this.items.push({type:'labor',part_id:null,name:this.laborName,quantity:1,unit_price:this.laborPrice}); this.laborName=''; this.laborPrice=0; },
            removeItem(idx) { this.items.splice(idx,1); },
        }
    }
    </script>
</x-app-layout>
