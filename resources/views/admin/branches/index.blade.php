<x-app-layout>
    <x-slot name="title">Şubeler / Sanayi Dalları</x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Sanayi Dalları</h2>
            <p class="text-sm text-slate-500 mt-0.5">Sistemde tanımlı tüm atölye ve sanayi uzmanlık kollarını yönetin</p>
        </div>
        <a href="{{ route('admin.branches.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>+ Yeni Dal Ekle</span>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($branches as $branch)
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs hover:border-slate-300 transition-all flex flex-col justify-between">
            <div>
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-50 border border-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">
                        {{ $branch->users_count }} Kayıtlı Usta
                    </span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 tracking-tight mb-1">{{ $branch->name }}</h3>
                <p class="text-xs text-slate-400">Bu kola bağlı kayıtlı ustalar bağımsız çalışır.</p>
            </div>
            
            <div class="mt-6 flex items-center justify-between pt-4 border-t border-slate-100">
                <a href="{{ route('admin.branches.edit', $branch) }}"
                   class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-800 text-sm font-semibold hover:bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Düzenle
                </a>

                <form method="POST" action="{{ route('admin.branches.destroy', $branch) }}"
                      onsubmit="return confirm('Bu sanayi dalını silmek istediğinize emin misiniz?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 text-rose-600 hover:text-rose-800 text-sm font-medium hover:bg-rose-50 px-3 py-1.5 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Sil
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</x-app-layout>
