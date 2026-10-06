<x-app-layout>
    <x-slot name="title">Kullanıcı Düzenle</x-slot>

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.users.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Kullanıcıyı Düzenle</h2>
                <p class="text-xs text-slate-500">{{ $user->name }} kullanıcısının bilgilerini ve şubesini güncelleyin</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4"
                  x-data="{ role: '{{ old('role', $user->role) }}' }">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ad Soyad</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">E-posta</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Yeni Şifre (Değiştirmek istemiyorsanız boş bırakın)</label>
                    <input type="password" name="password"
                           class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-indigo-600 focus:outline-none"
                           placeholder="••••••••">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Rol</label>
                    <select name="role" x-model="role"
                            class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        <option value="branch_user">Şube Çalışanı (Usta)</option>
                        <option value="admin">Süper Yönetici (Admin)</option>
                    </select>
                </div>

                <div x-show="role === 'branch_user'" x-cloak>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Bağlı Olduğu Şube (Sanayi Dalı)</label>
                    <select name="branch_id"
                            class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        <option value="">-- Şube Seçin --</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-3 flex items-center gap-3 border-t border-slate-100">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-colors shadow-sm">
                        Güncelle
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="border border-slate-300 text-slate-700 font-medium px-5 py-2.5 rounded-xl text-sm hover:bg-slate-50 transition-colors">
                        İptal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
