<x-app-layout>
    <x-slot name="title">Yeni Müşteri</x-slot>

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('customers.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-xl font-bold text-gray-900">Yeni Müşteri Ekle</h2>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <form method="POST" action="{{ route('customers.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ad Soyad *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ahmet Yılmaz">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="0532 123 4567">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Adres</label>
                    <textarea name="address" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Mahalle, ilçe...">{{ old('address') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notlar</label>
                    <textarea name="notes" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        Kaydet
                    </button>
                    <a href="{{ route('customers.index') }}"
                       class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                        İptal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
