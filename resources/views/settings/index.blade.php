<x-app-layout>
    <x-slot name="title">Atölye & Sistem Ayarları — SanayiPro</x-slot>

    <div class="space-y-6 max-w-4xl mx-auto">

        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 shadow-xl border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-2xl">⚙️</span>
                    <h2 class="text-xl font-black tracking-tight">ATÖLYE & SİSTEM AYARLARI</h2>
                </div>
                <p class="text-xs text-indigo-200 mt-1">Servis bilgileri, uzmanlık tanımı, fiş altlığı ve WhatsApp bildirim ayarları</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="bg-indigo-500/30 text-indigo-200 text-xs font-bold px-3 py-1.5 rounded-xl border border-indigo-400/30">
                    {{ $branch->name ?? 'Branş Tanımsız' }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold p-4 rounded-2xl flex items-center gap-2 shadow-xs">
                <span class="text-base">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 text-xs font-bold p-4 rounded-2xl flex items-center gap-2 shadow-xs">
                <span class="text-base">⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Atölye / Dükkan Bilgileri -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-4">
                <div class="pb-3 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-black text-slate-900">1. Atölye & Uzmanlık Bilgileri</h3>
                        <p class="text-xs text-slate-400">Panelde, fişlerde ve raporlarda görünecek iş yeri kimliği</p>
                    </div>
                    <span class="text-xl">🏪</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Servis / Dükkan Adı *</label>
                        <input type="text" name="name" value="{{ old('name', $branch?->name) }}" required
                               class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-bold">
                        @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Branş / Uzmanlık Başlığı</label>
                        <input type="text" name="title" value="{{ old('title', $branch?->title) }}"
                               placeholder="Örn: Motor Revizyon & Ağır Bakım"
                               class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Uzmanlık Açıklaması / Hizmet Kapsamı</label>
                        <input type="text" name="description" value="{{ old('description', $branch?->description) }}"
                               placeholder="Örn: Benzinli/Dizel motor rektifiye, triger, sibop ve kompresyon testleri"
                               class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Dükkan Telefon Numarası</label>
                        <input type="text" name="phone" value="{{ old('phone', $branch?->phone) }}"
                               placeholder="0532 XXX XX XX"
                               class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Para Birimi Sembolü</label>
                        <input type="text" name="currency" value="{{ old('currency', $branch?->currency ?? '₺') }}"
                               placeholder="₺"
                               class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Dükkan Adresi</label>
                        <textarea name="address" rows="2"
                                  placeholder="Oto Sanayi Sitesi Blok / No..."
                                  class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('address', $branch?->address) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Fatura & Vergi Bilgileri -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-4">
                <div class="pb-3 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-black text-slate-900">2. Fatura & Resmi Bilgiler</h3>
                        <p class="text-xs text-slate-400">Servis teslim fişinin başlığında veya faturada yer alacak vergi bilgileri</p>
                    </div>
                    <span class="text-xl">🧾</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Vergi Dairesi</label>
                        <input type="text" name="tax_office" value="{{ old('tax_office', $branch?->tax_office) }}"
                               placeholder="Örn: Maslak V.D."
                               class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Vergi / TC No</label>
                        <input type="text" name="tax_no" value="{{ old('tax_no', $branch?->tax_no) }}"
                               placeholder="Örn: 1234567890"
                               class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono">
                    </div>
                </div>
            </div>

            <!-- Fiş & Bildirim Şablonları -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-4">
                <div class="pb-3 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-black text-slate-900">3. Fiş Alt Metni & WhatsApp Şablonu</h3>
                        <p class="text-xs text-slate-400">Yazıcı çıktıları ve müşteriye giden WhatsApp mesajları için özel metinler</p>
                    </div>
                    <span class="text-xl">💬</span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Servis Teslim Fişi Alt Notu (Yazıcı Çıktısı İçin)</label>
                        <input type="text" name="receipt_footer" value="{{ old('receipt_footer', $branch?->receipt_footer ?? 'Bizi tercih ettiğiniz için teşekkür ederiz. İyi yolculuklar!') }}"
                               placeholder="Bizi tercih ettiğiniz için teşekkür ederiz. İyi yolculuklar!"
                               class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Varsayılan WhatsApp Mesaj Şablonu</label>
                        <textarea name="whatsapp_message" rows="3"
                                  placeholder="Sayın {musteri_adi}, {plaka} plakalı aracınızın servis işlemleri tamamlanmıştır. Toplam Tutar: {tutar} TL. SanayiPro Servisimizden teslim alabilirsiniz."
                                  class="w-full text-xs rounded-xl border-slate-300 p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('whatsapp_message', $branch?->whatsapp_message) }}</textarea>
                        <span class="text-[11px] text-slate-400 mt-1 block">
                            Dinamik etiketler: <code class="font-mono text-indigo-600 font-bold">{musteri_adi}</code>, <code class="font-mono text-indigo-600 font-bold">{plaka}</code>, <code class="font-mono text-indigo-600 font-bold">{tutar}</code>, <code class="font-mono text-indigo-600 font-bold">{servis_adi}</code>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Kaydet Butonu -->
            <div class="flex items-center justify-end gap-3">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-6 py-3 rounded-2xl shadow-lg transition-all flex items-center gap-2">
                    <span>💾 Ayarları Kaydet</span>
                </button>
            </div>
        </form>

        <!-- Sistem Rol & Yetki Bilgilendirmesi -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs">
            <h3 class="text-sm font-black text-slate-900 mb-1">Sistem Yetki Rol Yapısı</h3>
            <p class="text-xs text-slate-400 mb-4">Giriş yapan kullanıcının dükkan içi rolü ve yetki kapsamı</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                <div class="p-3.5 bg-purple-50 rounded-2xl border border-purple-200">
                    <strong class="text-purple-900 block font-bold">Admin</strong>
                    <span class="text-purple-700 text-[11px]">Tam Yetki & Tüm Şubeleri Yönetme</span>
                </div>
                <div class="p-3.5 bg-blue-50 rounded-2xl border border-blue-200">
                    <strong class="text-blue-900 block font-bold">Servis Müdürü</strong>
                    <span class="text-blue-700 text-[11px]">Dükkan Yönetimi, Müşteri & Finans Kontrolü</span>
                </div>
                <div class="p-3.5 bg-emerald-50 rounded-2xl border border-emerald-200">
                    <strong class="text-emerald-900 block font-bold">Usta</strong>
                    <span class="text-emerald-700 text-[11px]">İş Emri Açma, Parça Kullanımı & AI Teşhis</span>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
