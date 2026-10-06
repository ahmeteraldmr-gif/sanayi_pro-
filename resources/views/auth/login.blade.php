<x-guest-layout>

    <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-800/80 grid grid-cols-1 lg:grid-cols-12"
         x-data="{
             email: '{{ old('email', 'admin@sanayi.local') }}',
             password: 'password',
             showPassword: false,
             activeRole: 'admin',
             selectRole(role, mail) {
                 this.activeRole = role;
                 this.email = mail;
                 this.password = 'password';
             }
         }">

        <!-- SOL BÖLÜM: Marka & Sanayi Dalları Tanıtımı -->
        <div class="lg:col-span-5 bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-white p-5 sm:p-8 lg:p-10 flex flex-col justify-between relative overflow-hidden border-b lg:border-b-0 lg:border-r border-slate-800">
            <!-- Arka plan parlama efekti -->
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Üst Kısım: Logo & Geri Dön Linki -->
            <div class="relative z-10">
                <div class="flex items-center justify-between gap-4 mb-4 sm:mb-8">
                    <a href="{{ route('landing') }}" class="inline-flex items-center gap-1.5 sm:gap-2 text-xs font-semibold text-slate-400 hover:text-white transition-colors bg-slate-900/80 px-2.5 sm:px-3 py-1.5 rounded-lg border border-slate-800 hover:border-slate-700">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Ana Sayfa</span>
                    </a>
                    <span class="text-[10px] sm:text-[11px] font-bold tracking-wider uppercase text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-2.5 py-1 rounded-full">v2.0 Çoklu Şube</span>
                </div>

                <div class="flex items-center gap-3 mb-3 sm:mb-6">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-600/30 flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-lg sm:text-xl font-bold tracking-tight text-white">Sanayi<span class="text-indigo-400">Pro</span></div>
                        <div class="text-[11px] sm:text-xs text-slate-400">Oto Servis Yönetim Sistemi</div>
                    </div>
                </div>

                <h1 class="text-xl sm:text-3xl font-extrabold tracking-tight text-white mb-2 sm:mb-4 leading-snug">
                    Tüm sanayi kollarını <br class="hidden sm:inline">tek panelde toplayın.
                </h1>
                <p class="text-slate-400 text-xs sm:text-sm leading-relaxed mb-4 sm:mb-6 hidden sm:block">
                    Süper yönetici veya şube ustası olarak giriş yapın. Her atölye sadece kendi işlemlerini, müşterilerini ve parça stoklarını yönetir.
                </p>

                <!-- Sanayi Dalları Etiketleri (Masaüstü/Tablet) -->
                <div class="space-y-2 mb-6 hidden sm:block">
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Desteklenen Sanayi Dalları</div>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center gap-1.5 bg-slate-900/90 border border-slate-800 text-slate-300 text-xs px-2.5 py-1.5 rounded-lg">
                            <span>⚡</span> Oto Elektrik
                        </span>
                        <span class="inline-flex items-center gap-1.5 bg-slate-900/90 border border-slate-800 text-slate-300 text-xs px-2.5 py-1.5 rounded-lg">
                            <span>🔧</span> Periyodik Bakım
                        </span>
                        <span class="inline-flex items-center gap-1.5 bg-slate-900/90 border border-slate-800 text-slate-300 text-xs px-2.5 py-1.5 rounded-lg">
                            <span>🛠️</span> Motor & Mekanik
                        </span>
                        <span class="inline-flex items-center gap-1.5 bg-slate-900/90 border border-slate-800 text-slate-300 text-xs px-2.5 py-1.5 rounded-lg">
                            <span>🚗</span> Kaporta & Boya
                        </span>
                    </div>
                </div>

                <!-- Öne Çıkan Özellikler Listesi (Masaüstü/Tablet) -->
                <div class="space-y-2.5 pt-4 border-t border-slate-800/80 text-xs text-slate-300 hidden sm:block">
                    <div class="flex items-center gap-2.5">
                        <div class="w-4 h-4 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0">✓</div>
                        <span>Şubeler arası bağımsız stok ve cari hesap takibi</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-4 h-4 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0">✓</div>
                        <span>Plakayla tek saniyede servis geçmişi sorgulama</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-4 h-4 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0">✓</div>
                        <span>Usta, çırak ve yönetici yetki sınırlandırması</span>
                    </div>
                </div>
            </div>

            <!-- Alt Durum Göstergesi -->
            <div class="relative z-10 mt-4 sm:mt-8 pt-3 sm:pt-4 flex items-center justify-between text-[11px] sm:text-xs text-slate-500">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sistem Çevrimiçi
                </span>
                <span>Sanayi Bulut Ağı</span>
            </div>
        </div>

        <!-- SAĞ BÖLÜM: Giriş Formu & Tek Tıkla Hesap Seçimi -->
        <div class="lg:col-span-7 p-5 sm:p-10 lg:p-12 flex flex-col justify-center bg-white">
            
            <div class="mb-5 sm:mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Giriş Yap</h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">Lütfen sisteme kayıtlı e-posta adresiniz ve şifrenizle giriş yapın.</p>
            </div>

            <!-- TEK TIKLA TEST HESABI SEÇİCİSİ (Quick Demo Switcher) -->
            <div class="mb-5 sm:mb-6 bg-slate-50 border border-slate-200/80 rounded-2xl p-3 sm:p-3.5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Hızlı Test Girişi (Tek Tıkla Seç)
                    </span>
                    <span class="text-[11px] text-slate-400">Şifre: password</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <!-- Admin -->
                    <button type="button"
                            @click="selectRole('admin', 'admin@sanayi.local')"
                            :class="activeRole === 'admin' ? 'border-indigo-600 bg-indigo-50/70 text-indigo-900 shadow-sm' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300'"
                            class="p-2.5 rounded-xl border text-left transition-all">
                        <div class="text-xs font-bold flex items-center justify-between">
                            <span>👑 Admin</span>
                            <span x-show="activeRole === 'admin'" class="text-indigo-600 text-xs">✓</span>
                        </div>
                        <div class="text-[10px] text-slate-500 truncate mt-0.5">Tüm Yetkiler</div>
                    </button>

                    <!-- Oto Elektrik -->
                    <button type="button"
                            @click="selectRole('elektrik', 'ahmet@elektrik.local')"
                            :class="activeRole === 'elektrik' ? 'border-indigo-600 bg-indigo-50/70 text-indigo-900 shadow-sm' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300'"
                            class="p-2.5 rounded-xl border text-left transition-all">
                        <div class="text-xs font-bold flex items-center justify-between">
                            <span>⚡ Ahmet Usta</span>
                            <span x-show="activeRole === 'elektrik'" class="text-indigo-600 text-xs">✓</span>
                        </div>
                        <div class="text-[10px] text-slate-500 truncate mt-0.5">Oto Elektrik Şubesi</div>
                    </button>

                    <!-- Bakım -->
                    <button type="button"
                            @click="selectRole('bakim', 'mehmet@bakim.local')"
                            :class="activeRole === 'bakim' ? 'border-indigo-600 bg-indigo-50/70 text-indigo-900 shadow-sm' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300'"
                            class="p-2.5 rounded-xl border text-left transition-all">
                        <div class="text-xs font-bold flex items-center justify-between">
                            <span>🔧 Mehmet Usta</span>
                            <span x-show="activeRole === 'bakim'" class="text-indigo-600 text-xs">✓</span>
                        </div>
                        <div class="text-[10px] text-slate-500 truncate mt-0.5">Bakım Şubesi</div>
                    </button>
                </div>

                <!-- TÜM SANAYİ KOLLARI USTA SEÇİCİSİ -->
                <div class="mt-3 pt-2.5 border-t border-slate-200/80">
                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Veya Tüm Sanayi Kolları Arasından Usta Seçin:</label>
                    <select @change="if($event.target.value) { email = $event.target.value; password = 'password'; activeRole = ''; }"
                            class="w-full text-xs font-semibold text-slate-800 bg-white border border-slate-300 rounded-xl p-2.5 focus:ring-2 focus:ring-indigo-600 focus:outline-none transition-all shadow-2xs">
                        <option value="">-- Tüm Şube Ustaları Listesi (16 Dal) --</option>
                        <optgroup label="1. Motor ve Mekanik Grubu">
                            <option value="selim@motor.local">⚙️ Motorcu Selim Usta (Motor & Mekanik)</option>
                            <option value="burak@sanziman.local">🕹️ Şanzımancı Burak Usta (Şanzıman & Vites Kutusu)</option>
                            <option value="cemal@rotduzen.local">📐 Rotçu Cemal Usta (Ön Düzen & Rot-Balans)</option>
                            <option value="kemal@fren.local">🛑 Frenci Kemal Usta (Fren Sistemleri)</option>
                            <option value="serkan@turbo.local">🚀 Turbocu Serkan Usta (Enjektör & Turbo)</option>
                        </optgroup>
                        <optgroup label="2. Elektrik ve Elektronik Grubu">
                            <option value="ahmet@elektrik.local">⚡ Elektrikçi Ahmet Usta (Oto Elektrik)</option>
                            <option value="murat@elektronik.local">💻 Elektronikçi Murat Usta (Oto Beyin & ECU)</option>
                            <option value="erhan@klima.local">❄️ Klimacı Erhan Usta (Oto Klima)</option>
                            <option value="dursun@aku.local">🔋 Akücü Dursun Usta (Akümülatör & Şarj)</option>
                        </optgroup>
                        <optgroup label="3. Kaporta, Boya ve Dış Aksam">
                            <option value="ismail@kaporta.local">🔨 Kaportacı İsmail Usta (Şasi & Kaporta)</option>
                            <option value="yusuf@boya.local">🎨 Boyacı Yusuf Usta (Fırınlı Boya)</option>
                            <option value="sinan@pdr.local">🎯 Göçükçü Sinan Usta (Boyasız Göçük PDR)</option>
                            <option value="kazim@plastik.local">🧩 Plastikçi Kazım Usta (Tampon & Plastik Kaynak)</option>
                            <option value="riza@cam.local">🪟 Camcı Rıza Usta (Oto Cam & Kriko Tamiri)</option>
                        </optgroup>
                        <optgroup label="4. İç Donanım, Kilit ve Güvenlik">
                            <option value="cengiz@doseme.local">🪡 Döşemeci Cengiz Usta (Koltuk & Tavan Döşeme)</option>
                            <option value="metin@kilit.local">🔑 Kilitçi Metin Usta (Kontak & İmmobilizer)</option>
                            <option value="baris@multimedya.local">📻 Müzikçi Barış Usta (Ses & Multimedya)</option>
                        </optgroup>
                        <optgroup label="5. Yardımcı ve Tamamlayıcı Branşlar">
                            <option value="hakan@egzoz.local">💨 Egzozcu Hakan Usta (Egzoz & Katalizör)</option>
                            <option value="veli@radyator.local">🌡️ Radyatörcü Veli Usta (Petek Temizleme & Soğutma)</option>
                            <option value="necati@torna.local">🔩 Tornacı Necati Usta (Kırık Civata & Diş Açma)</option>
                            <option value="orhan@lastik.local">🛞 Lastikçi Orhan Usta (Rot-Balans & Lastik)</option>
                        </optgroup>
                        <optgroup label="6. Bakım ve Gaz Sistemleri">
                            <option value="mehmet@bakim.local">🔧 Bakımcı Mehmet Usta (Periyodik Hızlı Bakım)</option>
                            <option value="levent@lpg.local">⛽ Gazcı Levent Usta (Oto LPG & Gaz)</option>
                        </optgroup>
                    </select>
                </div>
            </div>

            <!-- Durum / Hata Mesajları -->
            @if (session('status'))
                <div class="mb-4 text-sm text-emerald-800 bg-emerald-50 border border-emerald-200 px-4 py-3 rounded-xl flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- E-posta -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">E-posta Adresi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" x-model="email"
                               required autofocus autocomplete="username"
                               class="w-full pl-10 pr-4 py-3 bg-white border border-slate-300 rounded-xl text-base sm:text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition-all shadow-sm"
                               placeholder="ornek@sanayi.local">
                    </div>
                </div>

                <!-- Şifre -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Şifre</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                                Şifremi Unuttum
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" x-model="password"
                               required autocomplete="current-password"
                               class="w-full pl-10 pr-11 py-3 bg-white border border-slate-300 rounded-xl text-base sm:text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition-all shadow-sm"
                               placeholder="••••••••">
                        
                        <!-- Şifreyi Göster/Gizle Butonu -->
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Beni Hatırla -->
                <div class="flex items-center">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" checked
                               class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-xs font-medium text-slate-600">Beni bu cihazda hatırla</span>
                    </label>
                </div>

                <!-- Giriş Yap Butonu -->
                <button type="submit"
                        class="w-full mt-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3.5 px-4 rounded-xl text-sm transition-all duration-150 flex items-center justify-center gap-2 shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/35">
                    <span>Panele Giriş Yap</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <!-- Güvenlik Bilgisi -->
            <div class="mt-8 pt-5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    256-bit Uçtan Uca Şifreli Bağlantı
                </span>
                <span>SanayiPro © {{ date('Y') }}</span>
            </div>

        </div>

    </div>

</x-guest-layout>
