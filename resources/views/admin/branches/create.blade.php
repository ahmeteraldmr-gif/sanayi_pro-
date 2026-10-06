<x-app-layout>
    <x-slot name="title">Yeni Sanayi Dalı Ekle</x-slot>

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.branches.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Yeni Sanayi Dalı Ekle</h2>
                <p class="text-xs text-slate-500">Sisteme yeni bir uzmanlık alanı / şube tanımlayın</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <form method="POST" action="{{ route('admin.branches.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Sanayi Dalı Adı</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-indigo-600 focus:outline-none"
                           placeholder="Örn: Oto Cam & Kilit, Turbo & Enjektör">
                </div>

                <div class="pt-3 flex items-center gap-3 border-t border-slate-100">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-colors shadow-sm">
                        Şubeyi Oluştur
                    </button>
                    <a href="{{ route('admin.branches.index') }}" class="border border-slate-300 text-slate-700 font-medium px-5 py-2.5 rounded-xl text-sm hover:bg-slate-50 transition-colors">
                        İptal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
