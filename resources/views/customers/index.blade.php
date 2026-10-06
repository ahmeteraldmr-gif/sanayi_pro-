<x-app-layout>
    <x-slot name="title">Müşteriler</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Müşteriler</h2>
            <p class="text-sm text-gray-500">Tüm kayıtlı müşteriler</p>
        </div>
        <a href="{{ route('customers.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Yeni Müşteri
        </a>
    </div>

    <!-- Arama -->
    <form class="mb-4">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="İsim veya telefon ara..."
                   class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
        </div>
    </form>

    <!-- Tablo & Mobil Kartlar -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <!-- Masaüstü Tablo -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm min-w-[540px]">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Müşteri</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Telefon</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Araç Sayısı</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plakalar</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-sm">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                                    @if($customer->address)
                                        <div class="text-xs text-gray-400">{{ Str::limit($customer->address, 40) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $customer->phone ?? '-' }}</td>
                        <td class="px-5 py-3">
                            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded-full">
                                {{ $customer->vehicles_count }} araç
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach($customer->vehicles->take(3) as $v)
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded font-mono">{{ $v->plate }}</span>
                                @endforeach
                                @if($customer->vehicles->count() > 3)
                                    <span class="text-xs text-gray-400">+{{ $customer->vehicles->count() - 3 }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2 justify-end">
                                <a href="{{ route('customers.show', $customer) }}"
                                   class="text-blue-600 hover:text-blue-800 text-xs font-medium px-2 py-1 rounded hover:bg-blue-50">Detay</a>
                                <a href="{{ route('customers.edit', $customer) }}"
                                   class="text-gray-600 hover:text-gray-800 text-xs font-medium px-2 py-1 rounded hover:bg-gray-100">Düzenle</a>
                                <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                                      onsubmit="return confirm('Bu müşteriyi silmek istediğinizden emin misiniz?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-red-500 hover:text-red-700 text-xs font-medium px-2 py-1 rounded hover:bg-red-50">Sil</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                            <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Müşteri bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        <!-- Mobil Kartlar Listesi -->
        <div class="md:hidden divide-y divide-gray-100">
            @forelse($customers as $customer)
                <div class="p-4 space-y-3 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-sm shrink-0">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 text-sm">{{ $customer->name }}</div>
                                @if($customer->phone)
                                    <a href="tel:{{ $customer->phone }}" class="text-xs font-mono text-blue-600 flex items-center gap-1 mt-0.5">
                                        <span>📞</span> <span>{{ $customer->phone }}</span>
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">Telefon yok</span>
                                @endif
                            </div>
                        </div>
                        <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-0.5 rounded-full shrink-0">
                            {{ $customer->vehicles_count }} Araç
                        </span>
                    </div>

                    @if($customer->vehicles->isNotEmpty())
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            @foreach($customer->vehicles as $v)
                                <span class="bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded font-mono font-medium border border-gray-200">
                                    {{ $v->plate }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                        <a href="{{ route('customers.show', $customer) }}"
                           class="flex-1 text-center bg-blue-50 text-blue-700 font-semibold text-xs py-2 rounded-lg hover:bg-blue-100 transition-colors">
                            Detay
                        </a>
                        <a href="{{ route('customers.edit', $customer) }}"
                           class="flex-1 text-center bg-gray-100 text-gray-700 font-semibold text-xs py-2 rounded-lg hover:bg-gray-200 transition-colors">
                            Düzenle
                        </a>
                        <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                              onsubmit="return confirm('Bu müşteriyi silmek istediğinizden emin misiniz?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-50 text-red-600 font-semibold text-xs px-3 py-2 rounded-lg hover:bg-red-100 transition-colors">
                                Sil
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400 text-xs">
                    Müşteri bulunamadı.
                </div>
            @endforelse
        </div>

        <div class="px-5 py-3 border-t border-gray-100">
            {{ $customers->links() }}
        </div>
    </div>
</x-app-layout>
