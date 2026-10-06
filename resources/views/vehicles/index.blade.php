<x-app-layout>
    <x-slot name="title">Araçlar</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Araçlar</h2>
            <p class="text-sm text-gray-500">Plakaya göre kayıtlı araçlar</p>
        </div>
        <a href="{{ route('vehicles.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Yeni Araç
        </a>
    </div>

    <form class="mb-4">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Plaka, marka veya müşteri adı ara..."
                   class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
        </div>
    </form>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <!-- Masaüstü Tablo -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm min-w-[540px]">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plaka</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Araç Bilgisi</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sahibi</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Telefon</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($vehicles as $vehicle)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <span class="font-mono font-bold text-blue-700 bg-blue-50 px-3 py-1 rounded text-sm">
                                {{ $vehicle->plate }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="font-medium text-gray-900">{{ $vehicle->brand }} {{ $vehicle->model }}</div>
                            <div class="text-xs text-gray-400">{{ $vehicle->year }} {{ $vehicle->color ? '· ' . $vehicle->color : '' }}</div>
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('customers.show', $vehicle->customer) }}"
                               class="font-medium text-gray-900 hover:text-blue-600">
                                {{ $vehicle->customer->name }}
                            </a>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $vehicle->customer->phone ?? '-' }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2 justify-end">
                                <a href="{{ route('work-orders.create', ['vehicle_id' => $vehicle->id]) }}"
                                   class="text-xs bg-green-50 text-green-700 px-2 py-1 rounded hover:bg-green-100">+ İş Emri</a>
                                <a href="{{ route('vehicles.show', $vehicle) }}"
                                   class="text-xs text-blue-600 hover:text-blue-800 px-2 py-1 rounded hover:bg-blue-50">Detay</a>
                                <a href="{{ route('vehicles.edit', $vehicle) }}"
                                   class="text-xs text-gray-600 px-2 py-1 rounded hover:bg-gray-100">Düzenle</a>
                                <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}"
                                      onsubmit="return confirm('Bu aracı silmek istediğinizden emin misiniz?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 px-2 py-1 rounded hover:bg-red-50">Sil</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-gray-400">Araç bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        <!-- Mobil Kartlar Listesi -->
        <div class="md:hidden divide-y divide-gray-100">
            @forelse($vehicles as $vehicle)
                <div class="p-4 space-y-3 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="font-mono font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-lg text-xs inline-block mb-1">
                                {{ $vehicle->plate }}
                            </span>
                            <div class="font-bold text-gray-900 text-sm">
                                {{ $vehicle->brand }} {{ $vehicle->model }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $vehicle->year }} {{ $vehicle->color ? '· ' . $vehicle->color : '' }}
                            </div>
                        </div>
                        <a href="{{ route('work-orders.create', ['vehicle_id' => $vehicle->id]) }}"
                           class="bg-emerald-600 text-white font-bold text-xs px-2.5 py-1.5 rounded-lg shrink-0 flex items-center gap-1 shadow-xs">
                            <span>+ İş Emri</span>
                        </a>
                    </div>

                    <div class="text-xs py-2 border-y border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-gray-400">Sahibi:</span>
                            <a href="{{ route('customers.show', $vehicle->customer) }}" class="font-bold text-gray-800 hover:text-blue-600">
                                {{ $vehicle->customer->name }}
                            </a>
                        </div>
                        @if($vehicle->customer->phone)
                            <a href="tel:{{ $vehicle->customer->phone }}" class="text-blue-600 font-mono text-[11px] flex items-center gap-1">
                                <span>📞</span> <span>{{ $vehicle->customer->phone }}</span>
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <a href="{{ route('vehicles.show', $vehicle) }}"
                           class="flex-1 text-center bg-blue-50 text-blue-700 font-semibold text-xs py-2 rounded-lg hover:bg-blue-100 transition-colors">
                            Detay & Karne
                        </a>
                        <a href="{{ route('vehicles.edit', $vehicle) }}"
                           class="flex-1 text-center bg-gray-100 text-gray-700 font-semibold text-xs py-2 rounded-lg hover:bg-gray-200 transition-colors">
                            Düzenle
                        </a>
                        <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}"
                              onsubmit="return confirm('Bu aracı silmek istediğinizden emin misiniz?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-50 text-red-600 font-semibold text-xs px-3 py-2 rounded-lg hover:bg-red-100 transition-colors">
                                Sil
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400 text-xs">
                    Araç bulunamadı.
                </div>
            @endforelse
        </div>

        <div class="px-5 py-3 border-t border-gray-100">{{ $vehicles->links() }}</div>
    </div>
</x-app-layout>
