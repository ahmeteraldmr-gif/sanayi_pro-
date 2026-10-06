<x-app-layout>
    <x-slot name="title">Tedarikçiler & Yedek Parça Sağlayıcıları</x-slot>

    <div class="space-y-6">

        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 shadow-xl border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-2xl">🏢</span>
                    <h2 class="text-xl font-black tracking-tight">TEDARİKÇİLER & PARÇA SAĞLAYICILARI</h2>
                </div>
                <p class="text-xs text-indigo-200 mt-1">Parça alımı yaptığınız tedarikçi firmalar, iletişim bilgileri ve stok hacimleri</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('suppliers.create') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md transition-all flex items-center gap-1.5">
                    <span>➕ Yeni Tedarikçi Ekle</span>
                </a>
            </div>
        </div>

        <!-- Arama ve Filtreleme -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
            <form method="GET" action="{{ route('suppliers.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Tedarikçi adı, yetkili, telefon veya e-posta ile ara..."
                           class="w-full text-xs rounded-xl border border-slate-300 py-2.5 px-3.5 pl-9 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <span class="absolute left-3 top-2.5 text-slate-400 text-sm">🔍</span>
                </div>
                <button type="submit" class="w-full sm:w-auto bg-slate-900 text-white text-xs font-bold px-5 py-2.5 rounded-xl hover:bg-slate-800 transition-colors">
                    Filtrele
                </button>
                @if(request()->filled('search'))
                    <a href="{{ route('suppliers.index') }}" class="text-xs text-slate-500 hover:text-slate-800 font-bold">Temizle</a>
                @endif
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($suppliers as $supplier)
                <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between hover:border-indigo-400 transition-colors group">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-lg group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                🏢
                            </div>
                            <span class="text-xs font-bold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-full">
                                {{ $supplier->total_parts_count ?? 0 }} Kalem Parça
                            </span>
                        </div>

                        <h3 class="text-base font-black text-slate-900 mb-1">
                            <a href="{{ route('suppliers.show', $supplier) }}" class="hover:text-indigo-600 transition-colors">
                                {{ $supplier->name }}
                            </a>
                        </h3>
                        @if($supplier->contact_person)
                            <div class="text-xs text-slate-600 flex items-center gap-1 mb-1">
                                <span>👤 Yetkili:</span> <strong class="text-slate-800">{{ $supplier->contact_person }}</strong>
                            </div>
                        @endif
                        @if($supplier->phone)
                            <div class="text-xs text-slate-600 flex items-center gap-1 mb-3">
                                <span>📞</span> <span class="font-mono">{{ $supplier->phone }}</span>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50 p-3 rounded-2xl border border-slate-100">
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold">Toplam Stok</span>
                                <span class="font-mono font-bold text-slate-800 text-sm">{{ number_format($supplier->total_stock ?? 0, 0) }} Adet</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold">Stok Değeri</span>
                                <span class="font-mono font-bold text-emerald-600 text-sm">₺{{ number_format($supplier->total_inventory_value ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="text-slate-500 hover:text-indigo-600 font-bold">
                                ✏️ Düzenle
                            </a>
                        </div>
                        <a href="{{ route('suppliers.show', $supplier) }}" class="text-indigo-600 font-bold hover:underline">
                            Detay & Parçalar →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-3xl p-12 text-center text-slate-400 border border-dashed border-slate-200">
                    <span class="text-3xl block mb-2">🏢</span>
                    <p class="font-bold text-slate-700">Henüz tedarikçi kaydı bulunmuyor.</p>
                    <p class="text-xs text-slate-400 mt-1">Parça aldığınız toptancı ve yetkili satıcıları ekleyerek stok takibini kolaylaştırın.</p>
                    <a href="{{ route('suppliers.create') }}" class="inline-block mt-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all">
                        + İlk Tedarikçiyi Ekle
                    </a>
                </div>
            @endforelse
        </div>

        @if($suppliers->hasPages())
            <div class="mt-4">
                {{ $suppliers->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
