<x-app-layout>
    <x-slot name="title">Ustalar & Usta Performansı — SanayiPro</x-slot>

    <div class="space-y-6">

        <div class="bg-slate-900 text-white rounded-3xl p-6 shadow-xl border border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-bold border border-indigo-500/30 mb-2">
                    <span>👑 SERVİS EKİBİ</span>
                </div>
                <h2 class="text-xl font-black tracking-tight">USTALAR & USTA PERFORMANSI</h2>
                <p class="text-xs text-slate-400 mt-0.5">Ekip üyelerinin iş bitirme oranları, aktif yükleri ve ürettiği işçilik gelirleri</p>
            </div>
            
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.users.index') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md transition-colors">
                    + Yeni Usta / Çalışan Tanımla
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($masters as $m)
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between hover:shadow-md transition-all">
                    <div>
                        <!-- Profil Üst Bilgi -->
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-black text-lg flex items-center justify-center shadow-md">
                                    {{ substr($m['user']->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 text-base leading-tight">{{ $m['user']->name }}</h3>
                                    <p class="text-xs text-slate-400 font-mono">{{ $m['user']->email }}</p>
                                </div>
                            </div>

                            <span class="text-xs font-bold px-2.5 py-1 rounded-full border
                                @if($m['user']->role === 'admin') bg-purple-100 text-purple-800 border-purple-200
                                @elseif($m['user']->role === 'manager') bg-blue-100 text-blue-800 border-blue-200
                                @elseif($m['user']->role === 'usta') bg-emerald-100 text-emerald-800 border-emerald-200
                                @elseif($m['user']->role === 'cirak') bg-amber-100 text-amber-800 border-amber-200
                                @else bg-slate-100 text-slate-700 border-slate-200 @endif">
                                {{ $m['user']->role_label }}
                            </span>
                        </div>

                        <!-- Metrik Grid -->
                        <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50 p-3.5 rounded-2xl border border-slate-100 mb-4">
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold">Tamamlanan İş</span>
                                <span class="font-mono font-black text-emerald-600 text-base">{{ $m['completed_jobs'] }} Adet</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-bold">Aktif Üzerindeki İş</span>
                                <span class="font-mono font-black text-indigo-600 text-base">{{ $m['active_jobs'] }} Adet</span>
                            </div>
                            <div class="col-span-2 pt-2 border-t border-slate-200/60 flex items-center justify-between">
                                <span class="text-slate-500 font-medium">İşçilik Geliri:</span>
                                <span class="font-mono font-bold text-slate-900">₺{{ number_format($m['total_labor_revenue'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Rol Güncelleme (Admin veya Servis Müdürü için) -->
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                        <form method="POST" action="{{ route('masters.update-role', $m['user']) }}" class="pt-3 border-t border-slate-100 flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="role" onchange="this.form.submit()" class="w-full text-xs font-semibold bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5 focus:ring-indigo-500">
                                <option value="admin" {{ $m['user']->role === 'admin' ? 'selected' : '' }}>Admin (Süper Yönetici)</option>
                                <option value="manager" {{ $m['user']->role === 'manager' ? 'selected' : '' }}>Servis Müdürü</option>
                                <option value="usta" {{ $m['user']->role === 'usta' ? 'selected' : '' }}>Usta</option>
                                <option value="cirak" {{ $m['user']->role === 'cirak' ? 'selected' : '' }}>Çırak</option>
                                <option value="accounting" {{ $m['user']->role === 'accounting' ? 'selected' : '' }}>Muhasebe</option>
                            </select>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

    </div>
</x-app-layout>
