<x-app-layout>
    <x-slot name="title">Tedarikçiler & Yedek Parça Sağlayıcıları</x-slot>

    <div class="space-y-6">

        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 shadow-xl border border-slate-800 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black tracking-tight">🏢 TEDARİKÇİ & FİRMA LİSTESİ</h2>
                <p class="text-xs text-indigo-200 mt-1">Parça alımı yapılan tedarikçi firmalar ve stok hacimleri</p>
            </div>
            <a href="{{ route('parts.create') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md transition-all">
                + Yeni Parça / Tedarikçi Ekle
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($suppliers as $supplier)
                <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between hover:border-indigo-400 transition-colors">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-lg">
                                🏢
                            </div>
                            <span class="text-xs font-bold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-full">
                                {{ $supplier['total_parts_count'] }} Kalem Parça
                            </span>
                        </div>

                        <h3 class="text-base font-black text-slate-900 mb-1">{{ $supplier['name'] }}</h3>
                        <div class="text-xs text-slate-500 mb-4">Ana Parça Tedarikçisi</div>

                        <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50 p-3 rounded-2xl border border-slate-100">
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold">Toplam Stok</span>
                                <span class="font-mono font-bold text-slate-800 text-sm">{{ number_format($supplier['total_stock'], 0) }} Adet</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold">Stok Değeri</span>
                                <span class="font-mono font-bold text-emerald-600 text-sm">₺{{ number_format($supplier['total_inventory_value'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400 font-medium">Stoktaki Parçalar</span>
                        <a href="{{ route('parts.index', ['search' => $supplier['name']]) }}" class="text-indigo-600 font-bold hover:underline">
                            Parçaları Gör →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-3xl p-12 text-center text-slate-400">
                    Henüz tedarikçi bilgisi girilmedi.
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
