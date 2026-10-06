<x-app-layout>
    <x-slot name="title">Araç Düzenle</x-slot>

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('vehicles.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-xl font-bold text-gray-900">Araç Düzenle: {{ $vehicle->plate }}</h2>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <form method="POST" action="{{ route('vehicles.update', $vehicle) }}" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sahip (Müşteri) *</label>
                    <select name="customer_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id', $vehicle->customer_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Plaka *</label>
                    <input type="text" name="plate" value="{{ old('plate', $vehicle->plate) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Marka</label>
                        <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                        <input type="text" name="model" value="{{ old('model', $vehicle->model) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Yıl</label>
                        <input type="text" name="year" value="{{ old('year', $vehicle->year) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Renk</label>
                        <input type="text" name="color" value="{{ old('color', $vehicle->color) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notlar</label>
                    <textarea name="notes" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes', $vehicle->notes) }}</textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">Güncelle</button>
                    <a href="{{ route('vehicles.index') }}" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-50">İptal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
