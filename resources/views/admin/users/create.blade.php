<x-app-layout>
    <x-slot name="title">Yeni Kullanıcı</x-slot>

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="text-xl font-bold text-gray-900">Yeni Kullanıcı Ekle</h2>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4" x-data="{ role: '{{ old('role', 'branch_user') }}' }">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ad Soyad</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-posta</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Şifre</label>
                    <input type="password" name="password" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                    <select name="role" x-model="role" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="branch_user">Şube Çalışanı (Usta)</option>
                        <option value="admin">Süper Yönetici (Admin)</option>
                    </select>
                </div>
                <div x-show="role === 'branch_user'" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bağlı Olduğu Şube</label>
                    <select name="branch_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Şube Seçin --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="pt-2 flex gap-3">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">Kaydet</button>
                    <a href="{{ route('admin.users.index') }}" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-50">İptal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
