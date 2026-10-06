<x-app-layout>
    <x-slot name="title">Sistem Ayarları — SanayiPro</x-slot>

    <div class="space-y-6 max-w-4xl">

        <div class="bg-slate-900 text-white rounded-3xl p-6 shadow-xl border border-slate-800">
            <h2 class="text-xl font-black tracking-tight">⚙️ SERVİS & SİSTEM AYARLARI</h2>
            <p class="text-xs text-slate-400 mt-1">Servis bilgileri, bildirim şablonları ve yetki yapılandırması</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-6">
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-1">Şube & Firma Bilgileri</h3>
                <p class="text-xs text-slate-500 mb-4">Faturalarda ve iş emri çıktılarında yer alacak firma unvanı</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Servis / Sanayi Dükkan Adı</label>
                        <input type="text" value="{{ auth()->user()->branch->name ?? 'SanayiPro Oto Servis' }}" class="w-full text-xs rounded-xl border-slate-300 p-2.5" readonly>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Mevcut Rolünüz</label>
                        <input type="text" value="{{ auth()->user()->role_label }}" class="w-full text-xs rounded-xl border-slate-300 p-2.5 font-bold bg-slate-50" readonly>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100">
                <h3 class="text-base font-bold text-slate-900 mb-1">Sistem Yetki Rol Yapısı</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs mt-3">
                    <div class="p-3 bg-purple-50 rounded-2xl border border-purple-200">
                        <strong class="text-purple-900 block font-bold">Admin</strong>
                        <span class="text-purple-700 text-[11px]">Tam Yetki & Şube Yönetimi</span>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-2xl border border-blue-200">
                        <strong class="text-blue-900 block font-bold">Servis Müdürü</strong>
                        <span class="text-blue-700 text-[11px]">Servis, Müşteri & Finans Kontrolü</span>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-2xl border border-emerald-200">
                        <strong class="text-emerald-900 block font-bold">Usta</strong>
                        <span class="text-emerald-700 text-[11px]">İş Emri İşleme & Parça Kullanımı</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
