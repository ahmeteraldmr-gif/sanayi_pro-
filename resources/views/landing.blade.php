<!DOCTYPE html>
<html lang="tr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SanayiPro — Oto Servis ve Sanayi Dükkan Yönetim Yazılımı</title>
    <meta name="description" content="Oto tamirhaneler, oto elektrikçiler ve sanayi esnafı için plaka aramalı iş emri, WhatsApp servis fişi, stok ikazı ve kasa takibi. Tarayıcıdan anında kullanın.">
    <meta name="keywords" content="oto servis programı, oto sanayi programı, servis takip programı, iş emri takibi, araç servis geçmişi, stok takibi, WhatsApp servis fişi">
    <link rel="canonical" href="{{ url('/') }}">

    <!-- Open Graph -->
    <meta property="og:title" content="SanayiPro — Oto Servis Yönetim Yazılımı">
    <meta property="og:description" content="İş emirlerini, araç geçmişini, müşterileri, stokları ve tahsilatları tek ekrandan yönet. Plakayı yaz, geçmişi bul.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="SanayiPro — Oto Servis Yönetim Yazılımı">
    <meta name="twitter:description" content="İş emirlerini, araç geçmişini, müşterileri, stokları ve tahsilatları tek ekrandan yönet.")

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            DEFAULT: '#0B1224',
                            light: '#111B33',
                            dark: '#070B16'
                        },
                        brand: {
                            DEFAULT: '#2563EB',
                            dark: '#1D4ED8',
                            light: '#60A5FA'
                        },
                        surface: '#FFFFFF',
                        background: '#F8FAFC',
                        heading: '#0F172A',
                        subtext: '#64748B',
                        borderline: '#E2E8F0',
                        status: {
                            success: '#10B981',
                            warning: '#F59E0B',
                            danger: '#EF4444'
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563EB;
            --primary-dark: #1D4ED8;
            --primary-light: #60A5FA;
            --navy: #0B1224;
            --navy-light: #111B33;
            --background: #F8FAFC;
            --surface: #FFFFFF;
            --text: #0F172A;
            --text-secondary: #64748B;
            --border: #E2E8F0;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
        }

        [x-cloak] { display: none !important; }

        /* Türkiye Standart Plaka Görseli */
        .tr-plate {
            background: #ffffff;
            border: 2px solid #000000;
            border-radius: 6px;
            font-family: 'Inter', monospace;
            font-weight: 900;
            color: #000000;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.12);
        }
        .tr-plate-blue {
            background: #003399;
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 6px;
            border-top-left-radius: 3px;
            border-bottom-left-radius: 3px;
        }

        .sanayi-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: all 0.2s ease;
        }
        .sanayi-card:hover {
            border-color: #CBD5E1;
            box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        }
    </style>
</head>
<body class="font-sans text-[#0F172A] antialiased bg-[#F8FAFC] selection:bg-[#2563EB] selection:text-white relative">

    <!-- ============================================================
         1. ÜST SERVİS BANNER
    ============================================================ -->
    <div class="bg-[#0B1224] text-white text-xs py-2 px-4 text-center font-semibold border-b border-[#111B33] flex flex-wrap items-center justify-center gap-2">
        <span class="bg-[#2563EB] text-white text-[10px] font-bold px-2 py-0.5 rounded">SANAYİPRO v2.5</span>
        <span>Oto Tamirhaneleri İçin Plaktan Aramalı Servis Fişi, WhatsApp Bilgilendirme ve Stok Alarmı Yayınlandı!</span>
        <a href="{{ route('login') }}" class="underline hover:text-[#60A5FA] font-bold ml-1">Canlı Panele Giriş Yap →</a>
    </div>

    <!-- ============================================================
         2. NAVİGASYON VE LOGO (NAVBAR)
    ============================================================ -->
    <nav class="sticky top-0 inset-x-0 z-50 bg-white border-b border-[#E2E8F0] shadow-2xs" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Logo & Amblem -->
                <a href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#2563EB] text-white rounded-xl flex items-center justify-center font-black text-xl shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-black text-[#0F172A] text-xl tracking-tight">Sanayi<span class="text-[#2563EB]">Pro</span></span>
                            <span class="bg-[#2563EB]/10 text-[#2563EB] text-[10px] font-bold px-2 py-0.5 rounded border border-[#2563EB]/20">Servis Yazılımı</span>
                        </div>
                        <span class="text-[10px] text-[#64748B] font-medium block">Oto Sanayi Esnafı Yönetim Sistemi</span>
                    </div>
                </a>

                <!-- Masaüstü Linkler -->
                <div class="hidden lg:flex items-center gap-5 text-xs font-bold text-[#0F172A]">
                    <a href="#ai-teshis" class="hover:text-[#2563EB] transition-colors text-[#2563EB] font-extrabold">🤖 AI Asistan</a>
                    <a href="#nasil-calisir" class="hover:text-[#2563EB] transition-colors">💡 Nasıl Çalışır?</a>
                    <a href="#ekran-goruntuleri" class="hover:text-[#2563EB] transition-colors">Ekranlar</a>
                    <a href="#branslar" class="hover:text-[#2563EB] transition-colors">Branşlar</a>
                    <a href="#whatsapp-fisi" class="hover:text-[#2563EB] transition-colors">WhatsApp</a>
                    <a href="#moduller" class="hover:text-[#2563EB] transition-colors">Modüller</a>
                    <a href="#sss" class="hover:text-[#2563EB] transition-colors">S.S.S.</a>
                </div>

                <!-- Aksiyon Butonları -->
                <div class="hidden sm:flex items-center gap-3">
                    <a href="{{ route('login') }}"
                       class="text-xs font-bold text-[#64748B] hover:text-[#0F172A] px-3.5 py-2 rounded-lg hover:bg-slate-100 transition-colors">
                        Usta Girişi
                    </a>
                    <a href="{{ route('login') }}"
                       class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white px-4.5 py-2 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                        <span>Hemen Dene →</span>
                    </a>
                </div>

                <!-- Mobil Menü Düğmesi -->
                <button @click="open = !open" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Mobil Çekmece Menü -->
            <div x-show="open" x-cloak class="lg:hidden py-3 border-t border-[#E2E8F0] space-y-2 text-xs font-semibold text-[#0F172A]">
                <a href="#ai-teshis" @click="open=false" class="block px-3 py-2 text-[#2563EB] font-bold hover:bg-slate-100 rounded">🤖 AI Arıza Asistanı</a>
                <a href="#nasil-calisir" @click="open=false" class="block px-3 py-2 hover:bg-slate-100 rounded">💡 Nasıl Çalışır?</a>
                <a href="#ekran-goruntuleri" @click="open=false" class="block px-3 py-2 hover:bg-slate-100 rounded">Uygulama Ekranları</a>
                <a href="#branslar" @click="open=false" class="block px-3 py-2 hover:bg-slate-100 rounded">Sanayi Branşları</a>
                <a href="#whatsapp-fisi" @click="open=false" class="block px-3 py-2 hover:bg-slate-100 rounded">WhatsApp Servis Fişi</a>
                <a href="#moduller" @click="open=false" class="block px-3 py-2 hover:bg-slate-100 rounded">Dükkan Modülleri</a>
                <a href="#sss" @click="open=false" class="block px-3 py-2 hover:bg-slate-100 rounded">S.S.S.</a>
                <div class="pt-2">
                    <a href="{{ route('login') }}" class="w-full bg-[#2563EB] text-white text-center py-2.5 rounded-xl text-xs font-bold block">Panele Giriş Yap →</a>
                </div>
            </div>
        </div>
    </nav>


    <!-- ============================================================
         3. ANA MANŞET (HERO BÖLÜMÜ) & GERÇEK UYGULAMA MOCKUP'I
    ============================================================ -->
    <section class="bg-[#0B1224] text-white py-14 sm:py-20 border-b border-[#111B33] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Sol Taraf: Manşet Metni ve Rozetler -->
                <div class="lg:col-span-6 text-left">
                    <div class="inline-flex items-center gap-2 bg-[#111B33] text-slate-300 text-xs font-bold px-3.5 py-1.5 rounded-full border border-slate-700/60 mb-6 shadow-sm">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#10B981] animate-pulse"></span>
                        <span>Yerli Oto Servis & Sanayi Yönetim Yazılımı</span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight text-white mb-5">
                        Arabanın arızası çözülür, <br>
                        <span class="text-[#60A5FA]">dükkanın hesabı karışmasın.</span>
                    </h1>

                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed mb-8 max-w-xl font-normal">
                        İş emirlerini, araç geçmişini, müşterileri, stokları, tahsilatları ve servis fişlerini tek ekrandan yönet. Defter arama, eski WhatsApp mesajlarını karıştırma.
                    </p>

                    <div class="flex flex-wrap items-center gap-3.5">
                        <a href="{{ route('login') }}"
                           class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white px-6 py-3.5 rounded-xl font-black text-sm transition-all shadow-lg shadow-blue-600/30 flex items-center gap-2">
                            <span>Ücretsiz Deneyin →</span>
                        </a>
                        <a href="#nasil-calisir"
                           class="bg-[#111B33] hover:bg-slate-800 text-slate-200 border border-slate-700 px-5 py-3.5 rounded-xl font-bold text-sm transition-all flex items-center gap-1.5">
                            <span>▶ 90 Saniyede Nasıl Çalıştığını Gör</span>
                        </a>
                    </div>

                    <p class="text-xs text-slate-500 mt-3 font-medium">✓ Kurulum gerektirmez &nbsp;·&nbsp; ✓ Tarayıcıdan çalışır &nbsp;·&nbsp; ✓ Telefon, tablet ve bilgisayardan kullanılabilir</p>

                    <!-- Güven & Kontrol Şeridi (Şeffaf ve Net İfadeler) -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-10 pt-8 border-t border-[#111B33] text-xs">
                        <div class="bg-[#111B33] p-3.5 rounded-2xl border border-slate-800">
                            <div class="text-sm font-black text-white">Tek Panel</div>
                            <div class="text-slate-400 mt-0.5 font-medium">Müşteri, araç ve iş emirleri</div>
                        </div>
                        <div class="bg-[#111B33] p-3.5 rounded-2xl border border-slate-800">
                            <div class="text-sm font-black text-[#60A5FA]">Plaka ile Arama</div>
                            <div class="text-slate-400 mt-0.5 font-medium">Araç geçmişine hızlı erişim</div>
                        </div>
                        <div class="bg-[#111B33] p-3.5 rounded-2xl border border-slate-800">
                            <div class="text-sm font-black text-[#10B981]">Dijital Servis Fişi</div>
                            <div class="text-slate-400 mt-0.5 font-medium">Yazdır veya WhatsApp'tan gönder</div>
                        </div>
                        <div class="bg-[#111B33] p-3.5 rounded-2xl border border-slate-800">
                            <div class="text-sm font-black text-white">Sıfır Kurulum</div>
                            <div class="text-slate-400 mt-0.5 font-medium">Tarayıcıdan anında başlayın</div>
                        </div>
                    </div>
                </div>

                <!-- Sağ Taraf: Gerçek SanayiPro Program Mockup Görseli -->
                <div class="lg:col-span-6">
                    <div class="bg-slate-950 rounded-2xl shadow-2xl border border-slate-800 overflow-hidden font-sans text-xs">
                        <!-- Pencere Üst Barı -->
                        <div class="bg-slate-900 px-4 py-2.5 border-b border-slate-800 flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>
                                <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                                <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                                <span class="text-[11px] text-slate-400 font-mono ml-2 font-bold">SanayiPro — Canlı Servis Yönetimi</span>
                            </div>
                            <div class="flex items-center gap-3 text-slate-400 text-[11px]">
                                <span>🔔</span>
                                <span class="font-bold text-white">👨‍🔧 Ahmet Usta</span>
                            </div>
                        </div>

                        <!-- Uygulama İçeriği Split Layout (Sidebar + Main) -->
                        <div class="flex min-h-[340px]">
                            <!-- Sol Menü (Sidebar) -->
                            <div class="w-36 bg-slate-950 p-3 border-r border-slate-800/80 space-y-1 shrink-0 hidden sm:block">
                                <div class="font-black text-white text-xs mb-3 px-2 flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                    <span>Sanayi<span class="text-indigo-400">Pro</span></span>
                                </div>
                                <div class="bg-indigo-600 text-white font-bold px-2.5 py-1.5 rounded-lg text-[11px] flex items-center gap-1.5">
                                    <span>📊</span> <span>Dashboard</span>
                                </div>
                                <div class="text-slate-400 hover:text-white px-2.5 py-1.5 rounded-lg text-[11px] flex items-center gap-1.5">
                                    <span>📄</span> <span>İş Emirleri</span>
                                </div>
                                <div class="text-slate-400 hover:text-white px-2.5 py-1.5 rounded-lg text-[11px] flex items-center gap-1.5">
                                    <span>👤</span> <span>Müşteriler</span>
                                </div>
                                <div class="text-slate-400 hover:text-white px-2.5 py-1.5 rounded-lg text-[11px] flex items-center gap-1.5">
                                    <span>🚗</span> <span>Araçlar</span>
                                </div>
                                <div class="text-slate-400 hover:text-white px-2.5 py-1.5 rounded-lg text-[11px] flex items-center gap-1.5">
                                    <span>📦</span> <span>Stok</span>
                                </div>
                                <div class="text-slate-400 hover:text-white px-2.5 py-1.5 rounded-lg text-[11px] flex items-center gap-1.5">
                                    <span>💰</span> <span>Finans</span>
                                </div>
                            </div>

                            <!-- Sağ Ana Panel -->
                            <div class="flex-1 bg-slate-900 p-4 space-y-3">
                                <div class="flex items-center justify-between text-slate-300 pb-2 border-b border-slate-800">
                                    <span class="font-bold text-white text-xs">Bugünkü Durum</span>
                                    <span class="text-[10px] bg-emerald-500/20 text-emerald-300 px-2 py-0.5 rounded font-mono font-bold">Canlı</span>
                                </div>

                                <!-- 2 KPI Kartı -->
                                <div class="grid grid-cols-2 gap-2.5">
                                    <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                                        <div class="text-[10px] text-slate-400 uppercase font-bold">Günlük Tahsilat</div>
                                        <div class="text-lg font-mono font-black text-emerald-400 mt-0.5">₺12.450</div>
                                        <div class="text-[9px] text-slate-500 mt-0.5">Kasa Güvenli</div>
                                    </div>
                                    <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                                        <div class="text-[10px] text-slate-400 uppercase font-bold">Atölyede İş</div>
                                        <div class="text-lg font-mono font-black text-indigo-400 mt-0.5">8 İş Emri</div>
                                        <div class="text-[9px] text-indigo-300/80 mt-0.5">Lifte Alındı</div>
                                    </div>
                                </div>

                                <!-- Araç Sağlık Karnesi Mini Kartı -->
                                <div class="bg-slate-950 p-3 rounded-xl border border-slate-800/80">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-mono font-black text-white bg-slate-900 px-2 py-0.5 rounded border border-slate-700 text-[11px]">34 ABC 123</span>
                                            <span class="font-bold text-slate-300 text-[11px]">BMW 320d</span>
                                        </div>
                                        <span class="text-[9px] bg-emerald-500/20 text-emerald-300 font-bold px-2 py-0.5 rounded">Sağlık Karnesi ✓</span>
                                    </div>

                                    <div class="grid grid-cols-3 gap-1 text-[10px] pt-1 border-t border-slate-900">
                                        <span class="text-slate-400">🟢 Motor: <strong class="text-white">İyi</strong></span>
                                        <span class="text-slate-400">🟡 Fren: <strong class="text-amber-400">Bakım</strong></span>
                                        <span class="text-slate-400">🟢 Akü: <strong class="text-white">İyi</strong></span>
                                    </div>
                                </div>

                                <!-- Mini Son İş Emri Satırı -->
                                <div class="bg-slate-950 p-2.5 rounded-xl border border-slate-800 flex items-center justify-between text-[11px]">
                                    <div class="flex items-center gap-2">
                                        <span class="text-slate-400 font-mono font-bold">#1024</span>
                                        <span class="font-bold text-white">Ford Focus (34 HK 123)</span>
                                    </div>
                                    <span class="font-mono font-bold text-emerald-400">₺3.800</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    </section>

    <!-- ============================================================
         3.5. SANAYİDE NE DEĞİŞİYOR? (ÖNCE - SONRA KARŞILAŞTIRMA)
    ============================================================ -->
    <section class="py-16 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-bold text-indigo-700 uppercase bg-indigo-50 border border-indigo-200 px-3.5 py-1.5 rounded-full shadow-2xs">
                    ⚡ DİJİTAL DÖNÜŞÜM
                </span>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 mt-3 tracking-tight">
                    Sanayide Ne Değişiyor?
                </h2>
                <p class="text-slate-600 text-sm mt-2">
                    Klasik defter karmaşasını geride bırakın, dükkanınızı tam kontrole alın.
                </p>
            </div>

            <!-- İki Kolonlu Önce / Sonra Karşılaştırma Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                
                <!-- Sol Kolon: Eskiden (Klasik Defter) -->
                <div class="bg-rose-50/70 border-2 border-rose-200 rounded-3xl p-6 sm:p-8 shadow-xs relative">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-rose-200">
                        <div class="w-10 h-10 rounded-2xl bg-rose-200/80 text-rose-800 font-black text-lg flex items-center justify-center shrink-0 shadow-2xs">
                            ❌
                        </div>
                        <div>
                            <h3 class="font-black text-rose-900 text-lg">ESKİDEN (Defter & Kağıt Düzeni)</h3>
                            <p class="text-xs text-rose-700 font-medium">Geleneksel oto tamirhanelerinde yaşanan sıkıntılar</p>
                        </div>
                    </div>

                    <ul class="space-y-4 text-xs sm:text-sm">
                        <li class="flex items-start gap-3 text-slate-800">
                            <span class="text-rose-600 font-black shrink-0 text-base">❌</span>
                            <div>
                                <strong class="text-rose-950 font-bold block">Kağıt servis fişleri</strong>
                                <span class="text-slate-600 text-xs">Yırtılan, kaybolan, yağlanan ve okunamayan koçanlar.</span>
                            </div>
                        </li>

                        <li class="flex items-start gap-3 text-slate-800">
                            <span class="text-rose-600 font-black shrink-0 text-base">❌</span>
                            <div>
                                <strong class="text-rose-950 font-bold block">WhatsApp'ta kaybolan müşteri bilgileri</strong>
                                <span class="text-slate-600 text-xs">Aratınca bulunamayan mesajlar, silinen fotoğraflar.</span>
                            </div>
                        </li>

                        <li class="flex items-start gap-3 text-slate-800">
                            <span class="text-rose-600 font-black shrink-0 text-base">❌</span>
                            <div>
                                <strong class="text-rose-950 font-bold block">Hangi araç ne zaman geldi belli değil</strong>
                                <span class="text-slate-600 text-xs">Eski parçaların ne zaman takıldığının unutulması.</span>
                            </div>
                        </li>

                        <li class="flex items-start gap-3 text-slate-800">
                            <span class="text-rose-600 font-black shrink-0 text-base">❌</span>
                            <div>
                                <strong class="text-rose-950 font-bold block">Parça stoğu takip edilemiyor</strong>
                                <span class="text-slate-600 text-xs">Rafta malzeme bitince aracın lifte asılı kalması.</span>
                            </div>
                        </li>

                        <li class="flex items-start gap-3 text-slate-800">
                            <span class="text-rose-600 font-black shrink-0 text-base">❌</span>
                            <div>
                                <strong class="text-rose-950 font-bold block">Tahsilatlar unutuluyor</strong>
                                <span class="text-slate-600 text-xs">Alacakların ve veresiyelerin akılda tutulmaya çalışılması.</span>
                            </div>
                        </li>

                        <li class="flex items-start gap-3 text-slate-800">
                            <span class="text-rose-600 font-black shrink-0 text-base">❌</span>
                            <div>
                                <strong class="text-rose-950 font-bold block">Gün sonunda hesap karışıyor</strong>
                                <span class="text-slate-600 text-xs">Kasaya giren nakit ve kart tutarlarının tutmaması.</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Sağ Kolon: SanayiPro İle (Dijital Servis) -->
                <div class="bg-emerald-50/70 border-2 border-emerald-200 rounded-3xl p-6 sm:p-8 shadow-xs relative">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-emerald-200">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-200/80 text-emerald-800 font-black text-lg flex items-center justify-center shrink-0 shadow-2xs">
                            ✅
                        </div>
                        <div>
                            <h3 class="font-black text-emerald-900 text-lg">SANAYİPRO İLE (Dijital Dönüşüm)</h3>
                            <p class="text-xs text-emerald-700 font-medium">Modern oto servislerinin yeni standardı</p>
                        </div>
                    </div>

                    <ul class="space-y-4 text-xs sm:text-sm">
                        <li class="flex items-start gap-3 text-slate-800">
                            <span class="text-emerald-600 font-black shrink-0 text-base">✅</span>
                            <div>
                                <strong class="text-emerald-950 font-bold block">Dijital servis fişi</strong>
                                <span class="text-slate-600 text-xs">A4 veya küçük termal yazıcıdan tek tıkla resmi teslimat dökümü.</span>
                            </div>
                        </li>

                        <li class="flex items-start gap-3 text-slate-800">
                            <span class="text-emerald-600 font-black shrink-0 text-base">✅</span>
                            <div>
                                <strong class="text-emerald-950 font-bold block">Plakadan müşteri/araç bulma</strong>
                                <span class="text-slate-600 text-xs">Plakayı yazın; 2 saniyede müşteri ve tüm geçmiş önünüze gelsin.</span>
                            </div>
                        </li>

                        <li class="flex items-start gap-3 text-slate-800">
                            <span class="text-emerald-600 font-black shrink-0 text-base">✅</span>
                            <div>
                                <strong class="text-emerald-950 font-bold block">İş emri takibi</strong>
                                <span class="text-slate-600 text-xs">Bekleyen, işlemde olan ve tamamlanan araçların canlı durum paneli.</span>
                            </div>
                        </li>

                        <li class="flex items-start gap-3 text-slate-800">
                            <span class="text-emerald-600 font-black shrink-0 text-base">✅</span>
                            <div>
                                <strong class="text-emerald-950 font-bold block">Parça ve stok yönetimi</strong>
                                <span class="text-slate-600 text-xs">Stoklar minimum seviyeye inince sistem otomatik ikaz verir.</span>
                            </div>
                        </li>

                        <li class="flex items-start gap-3 text-slate-800">
                            <span class="text-emerald-600 font-black shrink-0 text-base">✅</span>
                            <div>
                                <strong class="text-emerald-950 font-bold block">Tahsilat takibi</strong>
                                <span class="text-slate-600 text-xs">Ödendi ve açık alacakların kuruşu kuruşuna kasa takibi.</span>
                            </div>
                        </li>

                        <li class="flex items-start gap-3 text-slate-800">
                            <span class="text-emerald-600 font-black shrink-0 text-base">✅</span>
                            <div>
                                <strong class="text-emerald-950 font-bold block">WhatsApp ile fiş gönderme</strong>
                                <span class="text-slate-600 text-xs">Hesabınızı müşterinin cebine anında resmi mesaj dökümü olarak iletin.</span>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </section>





    <!-- ============================================================
         3.9. AI AKILLI ARIZA ASİSTANI PAZARLAMA BÖLÜMÜ
    ============================================================ -->
    <section id="ai-teshis" class="py-16 sm:py-24 bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-950 text-white border-b border-indigo-900/40 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                <!-- Sol: Başlık ve Açıklama -->
                <div>
                    <span class="inline-flex items-center gap-2 text-xs font-black tracking-widest text-indigo-400 uppercase bg-indigo-500/10 border border-indigo-500/20 px-4 py-1.5 rounded-full mb-6">
                        🤖 AKILLI ARIZA ASİSTANI
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-black text-white leading-tight tracking-tight mb-5">
                        Arızayı bulamadın mı?<br>
                        <span class="text-[#60A5FA]">SanayiPro ikinci gözün olsun.</span>
                    </h2>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed mb-6 max-w-lg">
                        Belirtileri, OBD kodlarını ve yaptığın kontrolleri gir. Akıllı Arıza Asistanı olası nedenleri ve kontrol edilebilecek noktaları sıralasın.
                    </p>

                    <ul class="space-y-3 text-sm mb-8">
                        <li class="flex items-center gap-3 text-slate-200">
                            <span class="w-6 h-6 rounded-full bg-indigo-500/20 text-indigo-400 border border-indigo-500/40 flex items-center justify-center font-bold text-xs shrink-0">✓</span>
                            <span>Araç bilgileri ve şikayeti gir, AI olası nedenleri sıralasın</span>
                        </li>
                        <li class="flex items-center gap-3 text-slate-200">
                            <span class="w-6 h-6 rounded-full bg-indigo-500/20 text-indigo-400 border border-indigo-500/40 flex items-center justify-center font-bold text-xs shrink-0">✓</span>
                            <span>OBD hata kodlarını ekle, kontrol önerileri al</span>
                        </li>
                        <li class="flex items-center gap-3 text-slate-200">
                            <span class="w-6 h-6 rounded-full bg-indigo-500/20 text-indigo-400 border border-indigo-500/40 flex items-center justify-center font-bold text-xs shrink-0">✓</span>
                            <span>Teşhis sonuçları Arıza Bilgi Bankasına kaydedilir</span>
                        </li>
                        <li class="flex items-center gap-3 text-slate-200">
                            <span class="w-6 h-6 rounded-full bg-indigo-500/20 text-indigo-400 border border-indigo-500/40 flex items-center justify-center font-bold text-xs shrink-0">✓</span>
                            <span>İş emrinden doğrudan başlatılabilir</span>
                        </li>
                    </ul>

                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-black px-6 py-3.5 rounded-xl transition-all shadow-lg shadow-indigo-600/30 text-sm">
                        <span>🤖 Arıza Asistanını Dene →</span>
                    </a>

                    <!-- Güvenlik Uyarısı -->
                    <div class="mt-6 bg-slate-800/60 border border-slate-700/60 rounded-xl p-4">
                        <p class="text-slate-400 text-xs leading-relaxed">
                            ⚠️ <strong class="text-slate-300">Önemli Not:</strong> Yapay zekâ çıktıları teşhise yardımcı değerlendirmelerdir; kesin teşhis yerine geçmez. Fiziksel kontrol ve usta değerlendirmesi her zaman gereklidir.
                        </p>
                    </div>
                </div>

                <!-- Sağ: AI Teşhis Mockup -->
                <div class="w-full max-w-sm mx-auto lg:mx-0 lg:ml-auto">
                    <div class="bg-slate-900 rounded-2xl border border-slate-700 shadow-2xl overflow-hidden">

                        <!-- Mockup Başlık -->
                        <div class="bg-gradient-to-r from-indigo-700 to-blue-700 px-4 py-3 flex items-center gap-2">
                            <span class="w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center text-xs">🤖</span>
                            <div>
                                <div class="font-black text-white text-xs">SanayiPro</div>
                                <div class="text-[10px] text-indigo-200 font-bold">Akıllı Arıza Asistanı</div>
                            </div>
                        </div>

                        <div class="p-4 space-y-3 text-xs">
                            <!-- Araç -->
                            <div class="bg-slate-800 rounded-xl p-3 border border-slate-700">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-black text-white">BMW 320d</div>
                                        <div class="text-slate-400">184.250 KM · 2018</div>
                                    </div>
                                    <span class="font-mono font-black text-[#60A5FA] bg-slate-900 px-2 py-0.5 rounded border border-slate-700 text-[10px]">34 ABC 123</span>
                                </div>
                            </div>

                            <!-- Belirtiler -->
                            <div class="bg-slate-800 rounded-xl p-3 border border-slate-700">
                                <div class="text-[10px] text-indigo-400 font-black uppercase mb-2">Belirtiler</div>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 text-slate-200"><span class="text-indigo-400">☑</span> Tekleme</div>
                                    <div class="flex items-center gap-2 text-slate-200"><span class="text-indigo-400">☑</span> Çekiş düşüklüğü</div>
                                    <div class="flex items-center gap-2 text-slate-200"><span class="text-indigo-400">☑</span> Motor arıza lambası</div>
                                </div>
                            </div>

                            <!-- OBD -->
                            <div class="bg-slate-800 rounded-xl p-3 border border-slate-700 flex items-center justify-between">
                                <span class="text-slate-400 font-bold">OBD Kodu</span>
                                <span class="bg-rose-900/60 text-rose-300 border border-rose-700/50 font-mono font-black px-2.5 py-1 rounded-lg">P0300</span>
                            </div>

                            <!-- AI Sonuçları -->
                            <div class="border border-indigo-700/60 rounded-xl overflow-hidden">
                                <div class="bg-indigo-900/40 px-3 py-2 text-[10px] text-indigo-400 font-black uppercase">🔍 Olası Nedenler</div>
                                <div class="divide-y divide-slate-800 bg-slate-800/40">
                                    <div class="flex items-center justify-between px-3 py-2">
                                        <span class="text-slate-200 font-bold">Ateşleme sistemi</span>
                                        <span class="bg-rose-500/20 text-rose-300 border border-rose-500/30 text-[10px] font-bold px-2 py-0.5 rounded-full">🔴 Yüksek</span>
                                    </div>
                                    <div class="flex items-center justify-between px-3 py-2">
                                        <span class="text-slate-200 font-bold">Yakıt sistemi</span>
                                        <span class="bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px] font-bold px-2 py-0.5 rounded-full">🟠 Orta</span>
                                    </div>
                                    <div class="flex items-center justify-between px-3 py-2">
                                        <span class="text-slate-200 font-bold">Hava sistemi</span>
                                        <span class="bg-yellow-500/20 text-yellow-300 border border-yellow-500/30 text-[10px] font-bold px-2 py-0.5 rounded-full">🟡 Düşük</span>
                                    </div>
                                </div>
                            </div>

                            <!-- CTA -->
                            <div class="text-center text-[10px] text-slate-500 pt-1">Demo görünümü · Gerçek analiz için giriş yapın</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ============================================================
         4. BİR OTO SERVİSİ NASIL KULLANIR? (SİSTEM BİREBİR NASIL ÇALIŞIR?)
    ============================================================ -->
    <section id="nasil-calisir" class="py-16 sm:py-24 bg-slate-900 text-white border-b border-slate-800 relative overflow-hidden">
        <!-- Arka Plan Desen Akşentleri -->
        <div class="absolute inset-0 bg-[radial-gradient(#334155_1px,transparent_1px)] [background-size:16px_16px] opacity-30"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-black tracking-widest text-indigo-400 uppercase bg-indigo-500/10 border border-indigo-500/20 px-4 py-1.5 rounded-full">
                    💡 İŞLEYİŞ SÜRECİ
                </span>
                <h2 class="text-3xl sm:text-5xl font-black text-white mt-4 tracking-tight">
                    Bir Oto Servisi Nasıl Kullanır?
                </h2>
                <p class="text-slate-400 text-sm sm:text-base mt-3">
                    Sistem Birebir Nasıl Çalışır? Dükkanınızda araç kabulden müşteri teslimatına kadar süreç 4 pratik adımda tamamlanır.
                </p>
            </div>

            <!-- 4 Büyük Adımlı Görsel Akış Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
                
                <!-- Adım 01 -->
                <div class="relative bg-slate-800/80 border border-slate-700/80 hover:border-indigo-500/50 rounded-3xl p-6 transition-all group flex flex-col justify-between shadow-xl">
                    <div class="absolute -top-4 left-6 bg-indigo-600 text-white text-xs font-black px-3 py-1 rounded-lg shadow-md border border-indigo-400">
                        1. ADIM
                    </div>
                    <div>
                        <div class="flex items-center justify-between mt-2 mb-4">
                            <span class="text-5xl font-black text-indigo-400 font-mono tracking-tighter opacity-90 group-hover:scale-110 transition-transform">01</span>
                            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-2xl">
                                🚗
                            </div>
                        </div>
                        <h3 class="text-lg font-black text-white mb-2 group-hover:text-indigo-300 transition-colors">
                            Müşteriyi kaydet
                        </h3>
                        <p class="text-slate-300 text-xs sm:text-sm leading-relaxed mb-4">
                            Plaka + telefon ile müşteri ve araç bilgilerini oluştur.
                        </p>
                    </div>
                    <div class="bg-slate-900/90 rounded-2xl p-3 border border-slate-700 text-[11px] space-y-1 text-slate-300">
                        <div class="flex items-center gap-1.5 font-bold text-white">
                            <span class="text-emerald-400">✓</span> Plaka ile anında arama
                        </div>
                        <div class="text-slate-400 text-[10px]">Tüm geçmiş servis kayıtları 2 saniyede ekranda.</div>
                    </div>
                    
                    <!-- Masaüstü Ok (Sağ) -->
                    <div class="hidden md:flex absolute -right-4 top-1/2 -translate-y-1/2 z-20 w-8 h-8 rounded-full bg-indigo-600 text-white items-center justify-center font-black text-sm shadow-lg border border-indigo-400">
                        →
                    </div>
                    <!-- Mobil Ok (Alt) -->
                    <div class="md:hidden flex justify-center mt-4 text-indigo-400 font-black text-xl">
                        ↓
                    </div>
                </div>

                <!-- Adım 02 -->
                <div class="relative bg-slate-800/80 border border-slate-700/80 hover:border-blue-500/50 rounded-3xl p-6 transition-all group flex flex-col justify-between shadow-xl">
                    <div class="absolute -top-4 left-6 bg-blue-600 text-white text-xs font-black px-3 py-1 rounded-lg shadow-md border border-blue-400">
                        2. ADIM
                    </div>
                    <div>
                        <div class="flex items-center justify-between mt-2 mb-4">
                            <span class="text-5xl font-black text-blue-400 font-mono tracking-tighter opacity-90 group-hover:scale-110 transition-transform">02</span>
                            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-2xl">
                                🛠️
                            </div>
                        </div>
                        <h3 class="text-lg font-black text-white mb-2 group-hover:text-blue-300 transition-colors">
                            İş emrini oluştur
                        </h3>
                        <p class="text-slate-300 text-xs sm:text-sm leading-relaxed mb-4">
                            Arıza, yapılacak işlemler ve kullanılacak parçaları ekle.
                        </p>
                    </div>
                    <div class="bg-slate-900/90 rounded-2xl p-3 border border-slate-700 text-[11px] space-y-1 text-slate-300">
                        <div class="flex items-center gap-1.5 font-bold text-white">
                            <span class="text-emerald-400">✓</span> Parça & İşçilik Girimi
                        </div>
                        <div class="text-slate-400 text-[10px]">Stoktan düşüş veya serbest tutar yazma.</div>
                    </div>

                    <!-- Masaüstü Ok (Sağ) -->
                    <div class="hidden md:flex absolute -right-4 top-1/2 -translate-y-1/2 z-20 w-8 h-8 rounded-full bg-blue-600 text-white items-center justify-center font-black text-sm shadow-lg border border-blue-400">
                        →
                    </div>
                    <!-- Mobil Ok (Alt) -->
                    <div class="md:hidden flex justify-center mt-4 text-blue-400 font-black text-xl">
                        ↓
                    </div>
                </div>

                <!-- Adım 03 -->
                <div class="relative bg-slate-800/80 border border-slate-700/80 hover:border-amber-500/50 rounded-3xl p-6 transition-all group flex flex-col justify-between shadow-xl">
                    <div class="absolute -top-4 left-6 bg-amber-600 text-white text-xs font-black px-3 py-1 rounded-lg shadow-md border border-amber-400">
                        3. ADIM
                    </div>
                    <div>
                        <div class="flex items-center justify-between mt-2 mb-4">
                            <span class="text-5xl font-black text-amber-400 font-mono tracking-tighter opacity-90 group-hover:scale-110 transition-transform">03</span>
                            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-2xl">
                                📊
                            </div>
                        </div>
                        <h3 class="text-lg font-black text-white mb-2 group-hover:text-amber-300 transition-colors">
                            İşlemi takip et
                        </h3>
                        <p class="text-slate-300 text-xs sm:text-sm leading-relaxed mb-4">
                            Araç hangi aşamada? Parçalar kullanıldı mı? Ödeme alındı mı?
                        </p>
                    </div>
                    <div class="bg-slate-900/90 rounded-2xl p-3 border border-slate-700 text-[11px] space-y-1 text-slate-300">
                        <div class="flex items-center gap-1.5 font-bold text-white">
                            <span class="text-emerald-400">✓</span> Canlı Lifte Süreç Takibi
                        </div>
                        <div class="text-slate-400 text-[10px]">Aşama timeline'ı ve tahsilat takibi.</div>
                    </div>

                    <!-- Masaüstü Ok (Sağ) -->
                    <div class="hidden md:flex absolute -right-4 top-1/2 -translate-y-1/2 z-20 w-8 h-8 rounded-full bg-amber-600 text-white items-center justify-center font-black text-sm shadow-lg border border-amber-400">
                        →
                    </div>
                    <!-- Mobil Ok (Alt) -->
                    <div class="md:hidden flex justify-center mt-4 text-amber-400 font-black text-xl">
                        ↓
                    </div>
                </div>

                <!-- Adım 04 -->
                <div class="relative bg-slate-800/80 border border-slate-700/80 hover:border-emerald-500/50 rounded-3xl p-6 transition-all group flex flex-col justify-between shadow-xl">
                    <div class="absolute -top-4 left-6 bg-emerald-600 text-white text-xs font-black px-3 py-1 rounded-lg shadow-md border border-emerald-400">
                        4. ADIM
                    </div>
                    <div>
                        <div class="flex items-center justify-between mt-2 mb-4">
                            <span class="text-5xl font-black text-emerald-400 font-mono tracking-tighter opacity-90 group-hover:scale-110 transition-transform">04</span>
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl">
                                💬
                            </div>
                        </div>
                        <h3 class="text-lg font-black text-white mb-2 group-hover:text-emerald-300 transition-colors">
                            Fişi müşteriye gönder
                        </h3>
                        <p class="text-slate-300 text-xs sm:text-sm leading-relaxed mb-4">
                            Servis fişini yazdır veya WhatsApp üzerinden müşteriye gönder.
                        </p>
                    </div>
                    <div class="bg-slate-900/90 rounded-2xl p-3 border border-slate-700 text-[11px] space-y-1 text-slate-300">
                        <div class="flex items-center gap-1.5 font-bold text-white">
                            <span class="text-emerald-400">✓</span> WhatsApp & Termal Döküm
                        </div>
                        <div class="text-slate-400 text-[10px]">Tek tıkla müşterinin cebine döküm iletimi.</div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ============================================================
         5. SANAYİPRO'YU YAKINDAN İNCELEYİN (GERÇEK EKRAN GÖRÜNTÜLERİ)
    ============================================================ -->
    <section id="ekran-goruntuleri" class="py-16 sm:py-24 bg-slate-100 border-b border-slate-200" x-data="{ activeTab: 'dashboard' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-black tracking-widest text-blue-700 uppercase bg-blue-100 border border-blue-200 px-4 py-1.5 rounded-full shadow-2xs">
                    📱 GERÇEK UYGULAMA EKRANLARI
                </span>
                <h2 class="text-3xl sm:text-5xl font-black text-slate-900 mt-4 tracking-tight">
                    SanayiPro'yu Yakından İnceleyin
                </h2>
                <p class="text-slate-600 text-sm sm:text-base mt-3">
                    Oto servisinizin ihtiyacı olan tüm modüller; göz yormayan, usta dostu ve modern arayüzlerle tasarlandı.
                </p>
            </div>

            <!-- Ekran Seçici Tab Menüsü -->
            <div class="flex flex-wrap items-center justify-center gap-2.5 mb-10">
                <button type="button" @click="activeTab = 'dashboard'"
                        :class="activeTab === 'dashboard' ? 'bg-slate-900 text-white font-black shadow-md scale-105 border-slate-900' : 'bg-white text-slate-700 font-bold border-slate-300 hover:bg-slate-50'"
                        class="px-5 py-3 rounded-xl border text-xs transition-all cursor-pointer flex items-center gap-2">
                    <span>📊</span> <span>Dashboard</span>
                </button>

                <button type="button" @click="activeTab = 'is-emirleri'"
                        :class="activeTab === 'is-emirleri' ? 'bg-slate-900 text-white font-black shadow-md scale-105 border-slate-900' : 'bg-white text-slate-700 font-bold border-slate-300 hover:bg-slate-50'"
                        class="px-5 py-3 rounded-xl border text-xs transition-all cursor-pointer flex items-center gap-2">
                    <span>🛠️</span> <span>İş Emirleri</span>
                </button>

                <button type="button" @click="activeTab = 'musteri-arac'"
                        :class="activeTab === 'musteri-arac' ? 'bg-slate-900 text-white font-black shadow-md scale-105 border-slate-900' : 'bg-white text-slate-700 font-bold border-slate-300 hover:bg-slate-50'"
                        class="px-5 py-3 rounded-xl border text-xs transition-all cursor-pointer flex items-center gap-2">
                    <span>🚗</span> <span>Müşteri & Araçlar</span>
                </button>

                <button type="button" @click="activeTab = 'stok'"
                        :class="activeTab === 'stok' ? 'bg-slate-900 text-white font-black shadow-md scale-105 border-slate-900' : 'bg-white text-slate-700 font-bold border-slate-300 hover:bg-slate-50'"
                        class="px-5 py-3 rounded-xl border text-xs transition-all cursor-pointer flex items-center gap-2">
                    <span>📦</span> <span>Parça & Stok</span>
                </button>

                <button type="button" @click="activeTab = 'finans'"
                        :class="activeTab === 'finans' ? 'bg-slate-900 text-white font-black shadow-md scale-105 border-slate-900' : 'bg-white text-slate-700 font-bold border-slate-300 hover:bg-slate-50'"
                        class="px-5 py-3 rounded-xl border text-xs transition-all cursor-pointer flex items-center gap-2">
                    <span>💰</span> <span>Finans</span>
                </button>
            </div>

            <!-- İnteraktif Ekran Görüntü Mockup Çerçevesi -->
            <div class="max-w-5xl mx-auto bg-slate-950 rounded-3xl shadow-2xl border-2 border-slate-800 overflow-hidden font-sans text-xs">
                
                <!-- Pencere Üst Barı -->
                <div class="bg-slate-900 px-5 py-3 border-b border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-3.5 h-3.5 rounded-full bg-rose-500 inline-block"></span>
                        <span class="w-3.5 h-3.5 rounded-full bg-amber-500 inline-block"></span>
                        <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 inline-block"></span>
                        <span class="text-xs text-slate-400 font-mono ml-2 font-bold" x-text="'SanayiPro App — ' + activeTab.toUpperCase()">SanayiPro App</span>
                    </div>
                    <div class="flex items-center gap-4 text-slate-300 text-xs font-bold">
                        <span class="bg-indigo-600/30 text-indigo-300 border border-indigo-500/30 px-2.5 py-0.5 rounded text-[10px]">Canlı Sürüm v2.5</span>
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Ahmet Usta</span>
                    </div>
                </div>

                <!-- App İçerik Alanı -->
                <div class="p-6 bg-slate-900 min-h-[420px]">
                    
                    <!-- 1. DASHBOARD EKRANI MOCKUP'I -->
                    <div x-show="activeTab === 'dashboard'" x-cloak class="space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                            <div>
                                <h3 class="text-lg font-black text-white">Dükkan Genel Durum Paneli</h3>
                                <p class="text-xs text-slate-400">Bugünkü ciro, lifteki araçlar ve kritik uyarılara tek bakışta ulaşın.</p>
                            </div>
                            <span class="bg-emerald-500/20 text-emerald-400 text-xs font-mono font-bold px-3 py-1 rounded-lg border border-emerald-500/30">BUGÜN: 01 EKİM 2026</span>
                        </div>

                        <!-- 5 Ana KPI Kartı -->
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                            <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
                                <div class="text-[10px] text-slate-400 font-bold uppercase">💰 Ciro</div>
                                <div class="text-xl font-mono font-black text-emerald-400 mt-1">₺18.450</div>
                                <div class="text-[9px] text-slate-500 mt-1">Günlük Net Kasa</div>
                            </div>
                            <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
                                <div class="text-[10px] text-slate-400 font-bold uppercase">🔧 Tamamlanan</div>
                                <div class="text-xl font-mono font-black text-indigo-400 mt-1">12 Araç</div>
                                <div class="text-[9px] text-slate-500 mt-1">Servisten Çıktı</div>
                            </div>
                            <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
                                <div class="text-[10px] text-slate-400 font-bold uppercase">🚗 Servisteki</div>
                                <div class="text-xl font-mono font-black text-blue-400 mt-1">7 Araç</div>
                                <div class="text-[9px] text-slate-500 mt-1">Lifte Alındı</div>
                            </div>
                            <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
                                <div class="text-[10px] text-slate-400 font-bold uppercase">📦 Kritik Stok</div>
                                <div class="text-xl font-mono font-black text-rose-400 mt-1">4 Parça</div>
                                <div class="text-[9px] text-rose-300 mt-1">Sipariş Verilmeli</div>
                            </div>
                            <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800 col-span-2 sm:col-span-1">
                                <div class="text-[10px] text-slate-400 font-bold uppercase">💳 Bekleyen</div>
                                <div class="text-xl font-mono font-black text-amber-400 mt-1">₺6.250</div>
                                <div class="text-[9px] text-amber-300 mt-1">Veresiye Alacak</div>
                            </div>
                        </div>

                        <!-- Canlı Lifteki Araçlar Tablosu & Araç Sağlık Karnesi Kartı -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 pt-2">
                            <div class="md:col-span-8 bg-slate-950 p-4 rounded-2xl border border-slate-800">
                                <div class="font-bold text-white mb-3 flex items-center justify-between">
                                    <span>🚗 Atölyede İşlem Gören Araçlar</span>
                                    <span class="text-[10px] bg-slate-800 text-slate-300 px-2 py-0.5 rounded">Canlı Takip</span>
                                </div>
                                <div class="space-y-2 text-xs">
                                    <div class="bg-slate-900 p-2.5 rounded-xl border border-slate-800 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="font-mono font-black text-white bg-slate-800 px-2.5 py-1 rounded border border-slate-700">34 ABC 123</span>
                                            <div>
                                                <div class="font-bold text-white">BMW 320d — Mehmet Usta</div>
                                                <div class="text-[10px] text-slate-400">Yağ Bakımı & Fren Kontrolü</div>
                                            </div>
                                        </div>
                                        <span class="bg-purple-500/20 text-purple-300 border border-purple-500/30 px-2.5 py-1 rounded-lg text-[10px] font-bold">🟣 İşlem Başladı</span>
                                    </div>

                                    <div class="bg-slate-900 p-2.5 rounded-xl border border-slate-800 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="font-mono font-black text-white bg-slate-800 px-2.5 py-1 rounded border border-slate-700">34 HK 123</span>
                                            <div>
                                                <div class="font-bold text-white">Ford Focus — Ahmet Usta</div>
                                                <div class="text-[10px] text-slate-400">Akü Değişimi & OBD Kodlama</div>
                                            </div>
                                        </div>
                                        <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-2.5 py-1 rounded-lg text-[10px] font-bold">🟢 Teslim Edildi</span>
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-4 bg-slate-950 p-4 rounded-2xl border border-slate-800 space-y-3">
                                <div class="font-bold text-white flex items-center justify-between">
                                    <span>🩺 Araç Sağlık Karnesi</span>
                                    <span class="text-[10px] text-emerald-400 font-mono">Otomatik</span>
                                </div>
                                <div class="bg-slate-900 p-3 rounded-xl border border-slate-800 space-y-2">
                                    <div class="font-mono font-black text-white text-xs">BMW 320d (34 ABC 123)</div>
                                    <div class="space-y-1 text-[11px]">
                                        <div class="flex justify-between"><span>🟢 Motor:</span><span class="font-bold text-emerald-400">İyi</span></div>
                                        <div class="flex justify-between"><span>🟡 Fren:</span><span class="font-bold text-amber-400">Yakında Bakım</span></div>
                                        <div class="flex justify-between"><span>🟢 Akü:</span><span class="font-bold text-emerald-400">İyi</span></div>
                                        <div class="flex justify-between"><span>🔴 Lastikler:</span><span class="font-bold text-rose-400">Değişim Öneriliyor</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. İŞ EMİRLERİ EKRANI MOCKUP'I -->
                    <div x-show="activeTab === 'is-emirleri'" x-cloak class="space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                            <div>
                                <h3 class="text-lg font-black text-white">İş Emirleri & Aşama Timeline'ı</h3>
                                <p class="text-xs text-slate-400">Tüm servis araçlarını görsel timeline ile anında takip edin.</p>
                            </div>
                            <button class="bg-indigo-600 text-white font-bold text-xs px-3.5 py-1.5 rounded-xl">+ Yeni İş Emri Aç</button>
                        </div>

                        <!-- Görsel Timeline Şeridi -->
                        <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
                            <div class="text-xs font-bold text-slate-400 mb-3 uppercase tracking-wider">İş Emri Aşama Görselleştirme</div>
                            <div class="flex flex-wrap items-center justify-between gap-2 text-xs font-bold">
                                <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-3 py-1.5 rounded-xl flex items-center gap-1.5">🟢 Araç Kabul</span>
                                <span class="text-slate-600">➔</span>
                                <span class="bg-blue-500/20 text-blue-300 border border-blue-500/30 px-3 py-1.5 rounded-xl flex items-center gap-1.5">🔵 Arıza Tespiti</span>
                                <span class="text-slate-600">➔</span>
                                <span class="bg-purple-500/20 text-purple-300 border border-purple-500/30 px-3 py-1.5 rounded-xl flex items-center gap-1.5">🟣 İşlem Başladı</span>
                                <span class="text-slate-600">➔</span>
                                <span class="bg-amber-500/20 text-amber-300 border border-amber-500/30 px-3 py-1.5 rounded-xl flex items-center gap-1.5">🟡 Parça Bekleniyor</span>
                                <span class="text-slate-600">➔</span>
                                <span class="bg-emerald-600 text-white px-3 py-1.5 rounded-xl flex items-center gap-1.5">✅ Teslim Edildi</span>
                            </div>
                        </div>

                        <!-- Örnek İş Emirleri Tablosu -->
                        <div class="bg-slate-950 rounded-2xl border border-slate-800 overflow-hidden">
                            <table class="w-full text-left text-xs text-slate-300">
                                <thead class="bg-slate-900 text-slate-400 border-b border-slate-800 font-bold uppercase text-[10px]">
                                    <tr>
                                        <th class="p-3">Fiş #</th>
                                        <th class="p-3">Plaka / Araç</th>
                                        <th class="p-3">Sorumlu Usta</th>
                                        <th class="p-3">Yapılan İşlem</th>
                                        <th class="p-3">Tutar</th>
                                        <th class="p-3">Durum</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-900">
                                    <tr class="hover:bg-slate-900/50">
                                        <td class="p-3 font-mono font-bold text-white">#1024</td>
                                        <td class="p-3 font-bold text-white">34 ABC 123 (BMW 320d)</td>
                                        <td class="p-3 text-slate-300">Mehmet Usta</td>
                                        <td class="p-3 text-slate-400">Yağ Bakımı + Filtreler</td>
                                        <td class="p-3 font-mono font-bold text-emerald-400">₺4.500</td>
                                        <td class="p-3"><span class="bg-purple-500/20 text-purple-300 px-2 py-0.5 rounded text-[10px] font-bold">🟣 İşlem Başladı</span></td>
                                    </tr>
                                    <tr class="hover:bg-slate-900/50">
                                        <td class="p-3 font-mono font-bold text-white">#1025</td>
                                        <td class="p-3 font-bold text-white">34 HK 123 (Ford Focus)</td>
                                        <td class="p-3 text-slate-300">Ahmet Usta</td>
                                        <td class="p-3 text-slate-400">72Ah Varta Akü Değişimi</td>
                                        <td class="p-3 font-mono font-bold text-emerald-400">₺3.800</td>
                                        <td class="p-3"><span class="bg-emerald-500/20 text-emerald-300 px-2 py-0.5 rounded text-[10px] font-bold">🟢 Teslim Edildi</span></td>
                                    </tr>
                                    <tr class="hover:bg-slate-900/50">
                                        <td class="p-3 font-mono font-bold text-white">#1026</td>
                                        <td class="p-3 font-bold text-white">35 DEF 456 (Fiat Egea)</td>
                                        <td class="p-3 text-slate-300">Ali Usta</td>
                                        <td class="p-3 text-slate-400">Ön Fren Balata & Disk</td>
                                        <td class="p-3 font-mono font-bold text-amber-400">₺2.100</td>
                                        <td class="p-3"><span class="bg-amber-500/20 text-amber-300 px-2 py-0.5 rounded text-[10px] font-bold">🟡 Parça Bekleniyor</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 3. MÜŞTERİ VE ARAÇ SAĞLIK KARNESİ MOCKUP'I -->
                    <div x-show="activeTab === 'musteri-arac'" x-cloak class="space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                            <div>
                                <h3 class="text-lg font-black text-white">Müşteriler & Araç Sağlık Karnesi</h3>
                                <p class="text-xs text-slate-400">Her aracın geçmiş servis kayıtları ve sağlık karnesi otomatik oluşur.</p>
                            </div>
                            <div class="bg-slate-950 px-3 py-1.5 rounded-xl border border-slate-800 font-mono text-white text-xs">
                                🔍 Plaka Arama: <strong class="text-indigo-400">34 ABC 123</strong>
                            </div>
                        </div>

                        <!-- Müşteri Kartı ve Sağlık Karnesi Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                            <div class="md:col-span-5 bg-slate-950 p-5 rounded-2xl border border-slate-800 space-y-3">
                                <div class="text-xs font-bold text-indigo-400 uppercase">Müşteri Profil Bilgisi</div>
                                <div class="text-base font-black text-white">Mehmet Yılmaz</div>
                                <div class="text-slate-400 text-xs space-y-1">
                                    <div>📞 0532 123 45 67</div>
                                    <div>📍 İkitelli OSB Bağcılar Güngören San. Sit.</div>
                                    <div class="text-emerald-400 font-bold pt-1">✓ Toplam Servis Gelişi: 4 Kez</div>
                                </div>
                            </div>

                            <div class="md:col-span-7 bg-slate-950 p-5 rounded-2xl border border-slate-800 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="font-mono font-black text-white bg-slate-900 px-3 py-1 rounded-xl border border-slate-700 text-sm">34 ABC 123 — BMW 320d</span>
                                    <span class="text-xs bg-emerald-500/20 text-emerald-300 font-bold px-2.5 py-1 rounded-lg">Sağlık Karnesi ✓</span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 text-xs pt-2">
                                    <div class="bg-slate-900 p-2.5 rounded-xl border border-slate-800 flex justify-between items-center">
                                        <span class="text-slate-300">Motor Durumu</span>
                                        <span class="font-bold text-emerald-400">🟢 İyi</span>
                                    </div>
                                    <div class="bg-slate-900 p-2.5 rounded-xl border border-slate-800 flex justify-between items-center">
                                        <span class="text-slate-300">Fren Sistemi</span>
                                        <span class="font-bold text-amber-400">🟡 Yakında Bakım</span>
                                    </div>
                                    <div class="bg-slate-900 p-2.5 rounded-xl border border-slate-800 flex justify-between items-center">
                                        <span class="text-slate-300">Akü Volt / Sağlık</span>
                                        <span class="font-bold text-emerald-400">🟢 İyi (12.8V)</span>
                                    </div>
                                    <div class="bg-slate-900 p-2.5 rounded-xl border border-slate-800 flex justify-between items-center">
                                        <span class="text-slate-300">Lastik Diş Derinliği</span>
                                        <span class="font-bold text-rose-400">🔴 Değişim Öneriliyor</span>
                                    </div>
                                </div>

                                <div class="flex justify-between text-[11px] text-slate-400 pt-2 border-t border-slate-900 font-mono">
                                    <span>Son Bakım: 01.10.2026</span>
                                    <span>Sonraki Bakım: 01.04.2027</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. PARÇA VE STOK MOCKUP'I -->
                    <div x-show="activeTab === 'stok'" x-cloak class="space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                            <div>
                                <h3 class="text-lg font-black text-white">Parça & Stok Yönetimi</h3>
                                <p class="text-xs text-slate-400">Minimum stoğun altına düşen yedek parçalar için anında ikaz alın.</p>
                            </div>
                            <span class="bg-rose-500/20 text-rose-300 border border-rose-500/30 px-3 py-1 rounded-lg text-xs font-bold">⚠️ 4 Kritik Stok İkazı</span>
                        </div>

                        <!-- Stok Tablosu Görünümü -->
                        <div class="bg-slate-950 rounded-2xl border border-slate-800 overflow-hidden">
                            <table class="w-full text-left text-xs text-slate-300">
                                <thead class="bg-slate-900 text-slate-400 border-b border-slate-800 font-bold uppercase text-[10px]">
                                    <tr>
                                        <th class="p-3">Kodu</th>
                                        <th class="p-3">Parça Adı</th>
                                        <th class="p-3">Mevcut Stok</th>
                                        <th class="p-3">Alış Fiyatı</th>
                                        <th class="p-3">Satış Fiyatı</th>
                                        <th class="p-3">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-900">
                                    <tr class="hover:bg-slate-900/50">
                                        <td class="p-3 font-mono text-slate-400">PAR-101</td>
                                        <td class="p-3 font-bold text-white">72Ah Varta AGM Akü</td>
                                        <td class="p-3"><span class="bg-rose-500/20 text-rose-300 border border-rose-500/30 px-2 py-0.5 rounded text-[10px] font-bold">2 Adet (Kritik Stok)</span></td>
                                        <td class="p-3 font-mono text-slate-400">₺2.800</td>
                                        <td class="p-3 font-mono font-bold text-emerald-400">₺3.600</td>
                                        <td class="p-3"><button class="bg-slate-800 hover:bg-slate-700 text-white px-2.5 py-1 rounded font-bold text-[10px]">+ Stok Ekle</button></td>
                                    </tr>
                                    <tr class="hover:bg-slate-900/50">
                                        <td class="p-3 font-mono text-slate-400">PAR-102</td>
                                        <td class="p-3 font-bold text-white">Castrol Edge 5W-30 (4L)</td>
                                        <td class="p-3"><span class="bg-emerald-500/20 text-emerald-300 px-2 py-0.5 rounded text-[10px] font-bold">14 Adet</span></td>
                                        <td class="p-3 font-mono text-slate-400">₺850</td>
                                        <td class="p-3 font-mono font-bold text-emerald-400">₺1.200</td>
                                        <td class="p-3"><button class="bg-slate-800 hover:bg-slate-700 text-white px-2.5 py-1 rounded font-bold text-[10px]">+ Stok Ekle</button></td>
                                    </tr>
                                    <tr class="hover:bg-slate-900/50">
                                        <td class="p-3 font-mono text-slate-400">PAR-103</td>
                                        <td class="p-3 font-bold text-white">Brembo Ön Fren Balatası</td>
                                        <td class="p-3"><span class="bg-rose-500/20 text-rose-300 border border-rose-500/30 px-2 py-0.5 rounded text-[10px] font-bold">1 Adet (Kritik Stok)</span></td>
                                        <td class="p-3 font-mono text-slate-400">₺900</td>
                                        <td class="p-3 font-mono font-bold text-emerald-400">₺1.350</td>
                                        <td class="p-3"><button class="bg-slate-800 hover:bg-slate-700 text-white px-2.5 py-1 rounded font-bold text-[10px]">+ Stok Ekle</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 5. FİNANS VE KASA MOCKUP'I -->
                    <div x-show="activeTab === 'finans'" x-cloak class="space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                            <div>
                                <h3 class="text-lg font-black text-white">Finans, Gelir & Tahsilat Takibi</h3>
                                <p class="text-xs text-slate-400">Nakit, Kredi Kartı ve Veresiyeleri kuruşu kuruşuna kontrol edin.</p>
                            </div>
                            <span class="bg-emerald-500/20 text-emerald-300 font-mono font-bold text-xs px-3 py-1 rounded-lg">Net Kâr Marjı: %38</span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
                                <div class="text-[10px] text-slate-400 font-bold uppercase">Nakit Kasa</div>
                                <div class="text-lg font-mono font-black text-emerald-400 mt-1">₺12.450</div>
                            </div>
                            <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
                                <div class="text-[10px] text-slate-400 font-bold uppercase">POS / Kredi Kartı</div>
                                <div class="text-lg font-mono font-black text-indigo-400 mt-1">₺6.000</div>
                            </div>
                            <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
                                <div class="text-[10px] text-slate-400 font-bold uppercase">Veresiyeler</div>
                                <div class="text-lg font-mono font-black text-amber-400 mt-1">₺6.250</div>
                            </div>
                            <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
                                <div class="text-[10px] text-slate-400 font-bold uppercase">Net Kâr (Ekim)</div>
                                <div class="text-lg font-mono font-black text-white mt-1">₺48.200</div>
                            </div>
                        </div>

                        <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
                            <div class="font-bold text-white mb-2 flex items-center justify-between text-xs">
                                <span>💳 Bekleyen Veresiyeler & Tahsilat Listesi</span>
                                <span class="text-amber-400 text-[10px]">Hatırlatma Uyarısı Gönderilebilir</span>
                            </div>
                            <div class="bg-slate-900 p-3 rounded-xl border border-slate-800 flex items-center justify-between text-xs">
                                <div>
                                    <div class="font-bold text-white">Kaya Lojistik (34 HK 88)</div>
                                    <div class="text-[10px] text-slate-400">Vade Tarihi: 15 Ekim 2026</div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-mono font-bold text-amber-400">₺6.250 Açık Alacak</span>
                                    <button class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[10px] px-2.5 py-1 rounded-lg">💬 WhatsApp Hatırlat</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Modüller Özet Izgarası -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mt-12">
                
                <div @click="activeTab = 'dashboard'" class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-blue-500 transition-all cursor-pointer shadow-2xs group">
                    <div class="text-2xl mb-2 group-hover:scale-110 transition-transform">📊</div>
                    <h4 class="font-black text-slate-900 text-sm mb-1">Dashboard</h4>
                    <p class="text-slate-600 text-xs leading-relaxed">Ciro, servisteki araçlar ve kritik stok uyarısı tek bakışta.</p>
                </div>

                <div @click="activeTab = 'is-emirleri'" class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-blue-500 transition-all cursor-pointer shadow-2xs group">
                    <div class="text-2xl mb-2 group-hover:scale-110 transition-transform">🛠️</div>
                    <h4 class="font-black text-slate-900 text-sm mb-1">İş Emirleri</h4>
                    <p class="text-slate-600 text-xs leading-relaxed">Lifteki araçların durumunu timeline ile anında yönetin.</p>
                </div>

                <div @click="activeTab = 'musteri-arac'" class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-blue-500 transition-all cursor-pointer shadow-2xs group">
                    <div class="text-2xl mb-2 group-hover:scale-110 transition-transform">🚗</div>
                    <h4 class="font-black text-slate-900 text-sm mb-1">Müşteri & Araçlar</h4>
                    <p class="text-slate-600 text-xs leading-relaxed">Plakadan geçmiş servis dökümü ve otomatik Sağlık Karnesi.</p>
                </div>

                <div @click="activeTab = 'stok'" class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-blue-500 transition-all cursor-pointer shadow-2xs group">
                    <div class="text-2xl mb-2 group-hover:scale-110 transition-transform">📦</div>
                    <h4 class="font-black text-slate-900 text-sm mb-1">Parça & Stok</h4>
                    <p class="text-slate-600 text-xs leading-relaxed">Azalan malzemeleri görün, raf stoğunu 0 hatayla takip edin.</p>
                </div>

                <div @click="activeTab = 'finans'" class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-blue-500 transition-all cursor-pointer shadow-2xs group sm:col-span-2 lg:col-span-1">
                    <div class="text-2xl mb-2 group-hover:scale-110 transition-transform">💰</div>
                    <h4 class="font-black text-slate-900 text-sm mb-1">Finans</h4>
                    <p class="text-slate-600 text-xs leading-relaxed">Kasa, veresiyeler ve cebinizde kalan net kâr hesabı.</p>
                </div>

            </div>

        </div>
    </section>


    <!-- ============================================================
         6. SANAYİ UZMANLIK BRANŞLARI ("Sanayide Nesiniz?")
    ============================================================ -->
    <section id="branslar" class="py-16 bg-slate-50 border-b border-slate-200" x-data="{
        activeBranch: 'motor',
        branches: {
            motor: { title: 'Motor & Mekanik', icon: '⚙️', problem: 'Piston, triger ve rektifiye masraflarının kağıtlarda unutulup kafa hesabında kaybolması.', solution: 'Tüm parçaları ve rektifiye giderlerini iş emrine ekleyin, net kârınızı anında hesaplayın.' },
            elektrik: { title: 'Oto Elektrik & Akü', icon: '⚡', problem: 'Akü garantilerinde 6 ay sonra fiş arama ve fatura bulma tartışmaları yaşanması.', solution: 'Plakayı yazın; akü markası, amper ve garanti bitiş tarihi 2 saniyede önünüze gelsin.' },
            fren: { title: 'Fren & Ön Düzen', icon: '🛑', problem: 'Hangi taksiye ne zaman fren balatası takıldığı bilinemediğinden takibinin aksaması.', solution: 'Balata ve disk stokları 2 adedin altına inince sistem otomatik sipariş uyarısı versin.' },
            kaporta: { title: 'Kaporta & Boya & PDR', icon: '🚗', problem: 'Kaza hasarında şase, doğrultma ve boya işçiliklerinin kağıtlara dağılması.', solution: 'Tüm sac, fırın boya ve parça işçiliğini tek bir resmi dökümde müşteriye sunun.' },
            kilit: { title: 'Oto Kilit & Anahtar', icon: '🛡️', problem: 'Kodlanan akıllı anahtar ve çip kaparolarının kayıt tutulmadığı için unutulması.', solution: 'Kart ve anahtar seri numaraları plaka kaydıyla eşleşsin, avanslar hatasız düşsün.' }
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <span class="text-xs font-bold text-blue-700 uppercase bg-blue-100 px-3 py-1 rounded">Uzmanlık Alanınız</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 mt-2">Sanayide Ne İş Yapıyorsunuz?</h2>
                <p class="text-slate-600 text-sm mt-1">Kendi branşınızı seçin, SanayiPro'nun dükkanınıza getireceği kolaylığı görün.</p>
            </div>

            <!-- Sekme Düğmeleri -->
            <div class="flex flex-wrap justify-center gap-2 mb-8">
                <template x-for="(b, key) in branches" :key="key">
                    <button type="button"
                            @click="activeBranch = key"
                            :class="activeBranch === key ? 'bg-blue-700 text-white font-bold shadow-sm' : 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-100'"
                            class="px-4 py-2.5 rounded-lg text-xs font-semibold flex items-center gap-2 cursor-pointer transition-all">
                        <span x-text="b.icon"></span>
                        <span x-text="b.title"></span>
                    </button>
                </template>
            </div>

            <!-- İki Kolon Karşılaştırma -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                <div class="bg-rose-50 border border-rose-200 p-5 rounded-xl text-xs">
                    <h4 class="font-bold text-rose-800 mb-2 flex items-center gap-1.5">
                        <span>❌</span> Klasik Defterde Yaşanan Sorun:
                    </h4>
                    <p class="text-slate-700 leading-relaxed" x-text="branches[activeBranch].problem"></p>
                </div>

                <div class="bg-emerald-50 border border-emerald-200 p-5 rounded-xl text-xs">
                    <h4 class="font-bold text-emerald-800 mb-2 flex items-center gap-1.5">
                        <span>✅</span> SanayiPro İle Çözüm:
                    </h4>
                    <p class="text-slate-800 font-medium leading-relaxed" x-text="branches[activeBranch].solution"></p>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================================================
         6. WHATSAPP SERVİS FİŞİ BÖLÜMÜ
    ============================================================ -->
    <section id="whatsapp-fisi" class="py-16 sm:py-24 bg-emerald-950 text-white border-b border-emerald-900 relative overflow-hidden">
        <!-- Arka Plan Desen Parlaması -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-emerald-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Sol Kolon: Başlık ve Açıklamalar -->
                <div class="lg:col-span-6 space-y-6 text-left">
                    <span class="inline-flex items-center gap-2 text-xs font-black tracking-widest text-emerald-400 uppercase bg-emerald-900/60 border border-emerald-700/50 px-4 py-1.5 rounded-full shadow-2xs">
                        <span>💬 WHATSAPP SERVİS FİŞİ</span>
                    </span>

                    <h2 class="text-3xl sm:text-5xl font-black text-white leading-tight tracking-tight">
                        Fişi yazdırmak <br>
                        <span class="text-emerald-400">zorunda değilsiniz.</span>
                    </h2>

                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-xl">
                        Servis tamamlandığında müşterinin fişini WhatsApp üzerinden saniyeler içinde gönderin.
                    </p>

                    <div class="space-y-3 pt-2 text-xs sm:text-sm">
                        <div class="flex items-center gap-3 text-slate-200 font-semibold">
                            <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center font-bold text-xs shrink-0">✓</div>
                            <span>Kağıt fiş basma, yazıcı arızası ve mürekkep masrafına son verin.</span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-200 font-semibold">
                            <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center font-bold text-xs shrink-0">✓</div>
                            <span>Müşteriniz neye ne ödediğini cebindeki resmi WhatsApp mesajında net görsün.</span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-200 font-semibold">
                            <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center font-bold text-xs shrink-0">✓</div>
                            <span>Araç sahibine güven verir, usta ile müşteri arasındaki yanlış anlamaları çözer.</span>
                        </div>
                    </div>

                    <div class="pt-4">
                        <a href="{{ route('login') }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-sm px-6 py-3.5 rounded-xl transition-all shadow-lg shadow-emerald-500/20 inline-flex items-center gap-2">
                            <span>WhatsApp'tan Gönder →</span>
                        </a>
                    </div>
                </div>

                <!-- Sağ Kolon: WhatsApp Mesaj Kartı Simülasyonu -->
                <div class="lg:col-span-6 flex justify-center">
                    <div class="w-full max-w-md bg-slate-900 rounded-3xl p-5 border-2 border-emerald-700/60 shadow-2xl space-y-4">
                        
                        <!-- Sohbet Üst Barı -->
                        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-600 text-white font-black flex items-center justify-center text-sm shadow-md">
                                    SP
                                </div>
                                <div>
                                    <div class="font-bold text-white text-xs">SanayiPro</div>
                                    <div class="text-[10px] text-emerald-400 font-medium flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Servis Fişi Müşterinin WhatsApp'ında
                                    </div>
                                </div>
                            </div>
                            <span class="text-[10px] bg-emerald-500/20 text-emerald-300 font-mono px-2.5 py-1 rounded-full font-bold border border-emerald-500/30">Canlı Mesaj</span>
                        </div>

                        <!-- WhatsApp Mesaj Balonu -->
                        <div class="bg-emerald-900/40 border border-emerald-700/50 rounded-2xl p-4 text-xs space-y-3">
                            <div class="flex justify-between items-center pb-2 border-b border-emerald-800/80">
                                <span class="font-mono font-black text-white text-sm">🚗 Ford Focus 1.6 TDCI</span>
                                <span class="font-mono font-bold text-emerald-300 bg-slate-900 px-2.5 py-0.5 rounded border border-emerald-700">34 HK 123</span>
                            </div>

                            <div>
                                <div class="text-[11px] font-bold text-emerald-300 uppercase tracking-wider mb-1">Yapılan İşlemler</div>
                                <div class="space-y-1 text-slate-200">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-emerald-400 font-bold">✓</span> Akü değişimi
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-emerald-400 font-bold">✓</span> OBD kodlama
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-emerald-800/80 flex items-center justify-between">
                                <span class="text-slate-300 font-bold text-xs uppercase">Toplam</span>
                                <span class="text-xl font-mono font-black text-emerald-300">3.800 ₺</span>
                            </div>

                            <div class="pt-1 flex items-center justify-between text-[10px] text-emerald-400/80 font-mono">
                                <span>14:24 • Mesaj İletildi</span>
                                <span class="font-bold text-emerald-300">✓✓ Okundu</span>
                            </div>
                        </div>

                        <!-- Mesaj Gönder Aksiyon Butonu Simülasyonu -->
                        <div class="pt-2">
                            <a href="{{ route('login') }}" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-black py-3 rounded-xl text-xs transition-all shadow-md flex items-center justify-center gap-2">
                                <span>WhatsApp'tan Gönder →</span>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ============================================================
         6.5. GÜVEN VE NETLİK BÖLÜMÜ (ŞEFFAF DÜKKAN YÖNETİMİ)
    ============================================================ -->
    <section class="py-16 sm:py-24 bg-slate-900 text-white border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <div class="max-w-3xl mx-auto mb-12">
                <span class="text-xs font-black tracking-widest text-indigo-400 uppercase bg-indigo-500/10 border border-indigo-500/20 px-4 py-1.5 rounded-full">
                    🛡️ GÜVENLİ VE ŞEFFAF YAZILIM
                </span>
                <h2 class="text-3xl sm:text-5xl font-black text-white mt-4 tracking-tight">
                    SanayiPro ile işletmenizin kontrolü sizde.
                </h2>
                <p class="text-slate-400 text-sm sm:text-base mt-3">
                    Pazarlama abartıları veya yapay rakamlar yok. Dükkanınızın günlük işleyişini %100 kontrol altında tutan net çözümler var.
                </p>
            </div>

            <!-- 4 Ana Güven Sütunu -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto text-left">
                
                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 hover:border-indigo-500/50 transition-all shadow-lg flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-2xl mb-4">
                            🖥️
                        </div>
                        <h3 class="text-lg font-black text-white mb-2">Tek Panel</h3>
                        <p class="text-slate-300 text-xs leading-relaxed">
                            Müşteri, araç ve iş emirleri tek bir ekranda toplanır.
                        </p>
                    </div>
                    <div class="pt-4 mt-4 border-t border-slate-700/80 text-[11px] text-slate-400 font-medium">
                        Ekran değiştirmeden anında müdahale imkanı.
                    </div>
                </div>

                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 hover:border-blue-500/50 transition-all shadow-lg flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-2xl mb-4">
                            🔍
                        </div>
                        <h3 class="text-lg font-black text-white mb-2">Plaka ile Arama</h3>
                        <p class="text-slate-300 text-xs leading-relaxed">
                            Araç geçmişine ve müşteri detaylarına hızlı erişim.
                        </p>
                    </div>
                    <div class="pt-4 mt-4 border-t border-slate-700/80 text-[11px] text-slate-400 font-medium">
                        Eski takılan parçalar 2 saniyede önünüzde.
                    </div>
                </div>

                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 hover:border-emerald-500/50 transition-all shadow-lg flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl mb-4">
                            💬
                        </div>
                        <h3 class="text-lg font-black text-white mb-2">Dijital Servis Fişi</h3>
                        <p class="text-slate-300 text-xs leading-relaxed">
                            Servis dökümünü yazdırın veya WhatsApp'tan gönderin.
                        </p>
                    </div>
                    <div class="pt-4 mt-4 border-t border-slate-700/80 text-[11px] text-slate-400 font-medium">
                        Resmi mesaj ile müşteri memnuniyeti ve güveni.
                    </div>
                </div>

                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-6 hover:border-amber-500/50 transition-all shadow-lg flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-2xl mb-4">
                            ⚡
                        </div>
                        <h3 class="text-lg font-black text-white mb-2">Sıfır Kurulum</h3>
                        <p class="text-slate-300 text-xs leading-relaxed">
                            Karmaşık program kurulumu yok. İnterneti olan her cihazdan açın.
                        </p>
                    </div>
                    <div class="pt-4 mt-4 border-t border-slate-700/80 text-[11px] text-slate-400 font-medium">
                        Cep telefonu, tablet veya bilgisayardan anında giriş.
                    </div>
                </div>

            </div>

        </div>
    </section>


    <!-- ============================================================
         7. DÜKKAN MODÜLLERİ (ÖZELLİKLER VE GÖRSEL KARTLAR)
    ============================================================ -->
    <section id="moduller" class="py-16 bg-slate-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-xs font-bold text-blue-700 uppercase bg-blue-100 px-3 py-1 rounded">Dükkan Modülleri</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 mt-2">Atölyenizi Kolaylaştıracak Tüm Araçlar</h2>
                <p class="text-slate-600 text-sm mt-1">Karmaşık bilgisayar programları yok. Cep telefonunuzdan her ustanın kullanacağı kadar pratik.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Modül 1: Stok İkazı -->
                <div class="sanayi-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 font-bold flex items-center justify-center text-xl mb-4">
                            📦
                        </div>
                        <h3 class="font-bold text-slate-900 text-base mb-2">Otomatik Stok & Parça İkazı</h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4">Balata, akü veya motor yağı stoğu belirlediğiniz limitlerin altına inince sistem kırmızı ikaz verir. Lifte araç asılı kalmaz.</p>
                    </div>
                    <div class="bg-rose-50 border border-rose-200 p-2.5 rounded text-[11px]">
                        <span class="font-bold text-rose-800 block">⚠️ KRİTİK STOK UYARISI</span>
                        <span class="text-slate-700">72Ah Varta Akü: Son 1 Adet Kaldı!</span>
                    </div>
                </div>

                <!-- Modül 2: Çoklu Şube -->
                <div class="sanayi-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold flex items-center justify-center text-xl mb-4">
                            🏪
                        </div>
                        <h3 class="font-bold text-slate-900 text-base mb-2">Çoklu Şube & Bağımsız Kasa</h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4">Motor, kaporta veya elektrik şubelerinizin kasalarını ve ustalarını ayrı ayrı yetkilendirin. Hesabınız karışmasın.</p>
                    </div>
                    <div class="bg-emerald-50 border border-emerald-200 p-2.5 rounded text-[11px] flex justify-between items-center">
                        <span class="font-bold text-emerald-900">Motor Şubesi Kasası:</span>
                        <span class="font-mono font-bold text-emerald-800">42.500 ₺ Net</span>
                    </div>
                </div>

                <!-- Modül 3: Net Kâr -->
                <div class="sanayi-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 font-bold flex items-center justify-center text-xl mb-4">
                            📊
                        </div>
                        <h3 class="font-bold text-slate-900 text-base mb-2">Günlük Net Kâr Analizi</h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4">Cironuzu değil, yedek parça maliyetleri düşüldükten sonra dükkana kalan net cebinizdeki parayı görün.</p>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 p-2.5 rounded text-[11px] flex justify-between items-center">
                        <span class="font-bold text-blue-900">Bu Ayki Net Marj:</span>
                        <span class="font-mono font-bold text-blue-800">%38 Net Kâr</span>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ============================================================
         8. SANAYİPRO DÜKKÂNA NE KAZANDIRIR? (İŞ SONUÇLARI)
    ============================================================ -->
    <section id="kazanimlar" class="py-16 sm:py-24 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-bold text-blue-700 uppercase bg-blue-100 border border-blue-200 px-3.5 py-1.5 rounded-full shadow-xs">
                    💡 İŞ SONUÇLARI
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 mt-3 tracking-tight">
                    SanayiPro dükkâna ne kazandırır?
                </h2>
                <p class="text-slate-600 text-sm mt-2">Teknik özellik listesi değil — dükkanında gerçekten ne değişeceği.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="sanayi-card p-6 hover:shadow-md transition-all hover:border-blue-300 group">
                    <div class="text-3xl mb-4">💰</div>
                    <h3 class="font-black text-slate-900 text-base mb-2 group-hover:text-blue-700 transition-colors">Unutulan tahsilatı azalt</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Kimin ne kadar borcu kaldığını tek ekranda takip et. Veresiyeler gözden kaybolmasın.</p>
                </div>

                <div class="sanayi-card p-6 hover:shadow-md transition-all hover:border-blue-300 group">
                    <div class="text-3xl mb-4">📦</div>
                    <h3 class="font-black text-slate-900 text-base mb-2 group-hover:text-blue-700 transition-colors">Parçayı kaybetme</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Hangi parçadan kaç tane kaldığını gör, kritik stokları takip et. Parça bitince sistem ikaz versin.</p>
                </div>

                <div class="sanayi-card p-6 hover:shadow-md transition-all hover:border-blue-300 group">
                    <div class="text-3xl mb-4">⏱️</div>
                    <h3 class="font-black text-slate-900 text-base mb-2 group-hover:text-blue-700 transition-colors">Defter aramakla uğraşma</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Plakayı yaz, aracın geçmiş servis kayıtlarına ulaş. Eski fişleri karıştırmaya gerek yok.</p>
                </div>

                <div class="sanayi-card p-6 hover:shadow-md transition-all hover:border-blue-300 group">
                    <div class="text-3xl mb-4">🚗</div>
                    <h3 class="font-black text-slate-900 text-base mb-2 group-hover:text-blue-700 transition-colors">Atölyedeki araçları takip et</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Bekleyen, işlemde olan ve tamamlanan işleri tek panelden gör. Hangi araç hangi aşamada?</p>
                </div>

                <div class="sanayi-card p-6 hover:shadow-md transition-all hover:border-blue-300 group">
                    <div class="text-3xl mb-4">🧾</div>
                    <h3 class="font-black text-slate-900 text-base mb-2 group-hover:text-blue-700 transition-colors">Servis fişini düzenli tut</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Yapılan işlemleri ve kullanılan parçaları kayıt altında tut. Müşteri "bu işi yapmadın" diyemez.</p>
                </div>

                <div class="sanayi-card p-6 hover:shadow-md transition-all hover:border-blue-300 group">
                    <div class="text-3xl mb-4">📊</div>
                    <h3 class="font-black text-slate-900 text-base mb-2 group-hover:text-blue-700 transition-colors">Dükkânın durumunu gör</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Gelir, gider, tahsilat ve operasyon durumunu tek yerden takip et. Gün sonunda hesabın net olsun.</p>
                </div>

            </div>
        </div>
    </section>


    <!-- ============================================================
         9. TELEFONDAN KULLANIM BÖLÜMÜ
    ============================================================ -->
    <section id="mobil" class="py-16 sm:py-24 bg-slate-900 text-white border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                <!-- Sol: Başlık -->
                <div>
                    <span class="inline-flex items-center gap-2 text-xs font-black tracking-widest text-emerald-400 uppercase bg-emerald-900/30 border border-emerald-700/40 px-4 py-1.5 rounded-full mb-6">
                        📱 MOBİL KULLANIM
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-black text-white leading-tight tracking-tight mb-5">
                        Dükkân cebinde.
                    </h2>
                    <p class="text-slate-300 text-lg font-bold mb-3">Bilgisayarın başında olmak zorunda değilsin.</p>
                    <p class="text-slate-400 text-sm leading-relaxed mb-8 max-w-lg">
                        İş emrini aç, müşteriyi bul, aracı kontrol et ve servis durumunu telefonundan takip et. SanayiPro, telefona ve tablete tam uyumlu web uygulamasıdır.
                    </p>

                    <div class="space-y-4 mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-slate-800 border border-slate-700 rounded-2xl flex items-center justify-center text-2xl shrink-0">📱</div>
                            <div>
                                <div class="font-bold text-white text-sm">Telefon</div>
                                <div class="text-slate-400 text-xs">320px–430px arası tüm Android ve iPhone ekranları</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-slate-800 border border-slate-700 rounded-2xl flex items-center justify-center text-2xl shrink-0">📟</div>
                            <div>
                                <div class="font-bold text-white text-sm">Tablet</div>
                                <div class="text-slate-400 text-xs">iPad ve Android tabletlerde rahat kullanım</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-slate-800 border border-slate-700 rounded-2xl flex items-center justify-center text-2xl shrink-0">🖥️</div>
                            <div>
                                <div class="font-bold text-white text-sm">Bilgisayar</div>
                                <div class="text-slate-400 text-xs">Masaüstü ve dizüstü bilgisayarda tam panel görünümü</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-800/60 border border-slate-700 rounded-2xl p-4 text-sm text-slate-300">
                        <span class="text-amber-400 font-bold">⚠️ Not:</span> SanayiPro bir web uygulamasıdır. App Store veya Play Store'dan indirilen native bir uygulama değildir. İnternet tarayıcınızdan açılır.
                    </div>
                </div>

                <!-- Sağ: Telefon Mockup -->
                <div class="flex justify-center">
                    <div class="relative">
                        <!-- Telefon Çerçevesi -->
                        <div class="w-64 bg-slate-950 rounded-[2.5rem] border-4 border-slate-700 shadow-2xl overflow-hidden" style="min-height:500px;">
                            <!-- Kamera -->
                            <div class="w-16 h-5 bg-slate-900 rounded-b-2xl mx-auto mb-0"></div>

                            <!-- Ekran İçeriği -->
                            <div class="bg-[#0B1224] text-white text-[10px] px-3 py-2 space-y-2">

                                <!-- Üst Bar -->
                                <div class="flex items-center justify-between py-1">
                                    <span class="font-black text-white text-xs">Sanayi<span class="text-indigo-400">Pro</span></span>
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                </div>

                                <!-- Alt Nav Çubuğu Simulasyonu -->
                                <div class="bg-slate-900/80 rounded-xl px-2 py-1.5 flex items-center gap-1 border border-slate-800">
                                    <div class="flex-1 flex items-center gap-1">
                                        <span class="text-[9px]">🔍</span>
                                        <span class="text-slate-400 text-[9px]">Plaka ara...</span>
                                    </div>
                                </div>

                                <!-- KPI Kartları -->
                                <div class="grid grid-cols-2 gap-1.5">
                                    <div class="bg-slate-900 rounded-xl p-2 border border-slate-800">
                                        <div class="text-slate-400 text-[8px] uppercase font-bold">Bugün</div>
                                        <div class="font-mono font-black text-emerald-400 text-sm">₺18.450</div>
                                    </div>
                                    <div class="bg-slate-900 rounded-xl p-2 border border-slate-800">
                                        <div class="text-slate-400 text-[8px] uppercase font-bold">Açık İş</div>
                                        <div class="font-mono font-black text-indigo-400 text-sm">7 Araç</div>
                                    </div>
                                </div>

                                <!-- İş emirleri listesi -->
                                <div class="space-y-1.5">
                                    <div class="bg-slate-900 rounded-xl p-2 border border-slate-800 flex items-center justify-between">
                                        <div>
                                            <div class="font-mono font-black text-white text-[10px]">34 ABC 123</div>
                                            <div class="text-slate-400 text-[8px]">BMW 320d · Mehmet Usta</div>
                                        </div>
                                        <span class="bg-purple-500/20 text-purple-300 text-[8px] font-bold px-1.5 py-0.5 rounded">İşlemde</span>
                                    </div>
                                    <div class="bg-slate-900 rounded-xl p-2 border border-slate-800 flex items-center justify-between">
                                        <div>
                                            <div class="font-mono font-black text-white text-[10px]">34 HK 123</div>
                                            <div class="text-slate-400 text-[8px]">Ford Focus · Ahmet Usta</div>
                                        </div>
                                        <span class="bg-emerald-500/20 text-emerald-300 text-[8px] font-bold px-1.5 py-0.5 rounded">Teslim</span>
                                    </div>
                                </div>

                                <!-- Mobil Alt Nav -->
                                <div class="bg-slate-950 border-t border-slate-800 flex items-center justify-around py-2 rounded-xl mt-2">
                                    <div class="flex flex-col items-center gap-0.5">
                                        <span class="text-sm">🏠</span>
                                        <span class="text-[8px] text-blue-400 font-bold">Ana</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-0.5">
                                        <span class="text-sm">🔧</span>
                                        <span class="text-[8px] text-slate-400">İşler</span>
                                    </div>
                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center -mt-3 shadow-lg">
                                        <span class="text-white font-black text-sm">+</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-0.5">
                                        <span class="text-sm">🤖</span>
                                        <span class="text-[8px] text-slate-400">AI</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-0.5">
                                        <span class="text-sm">☰</span>
                                        <span class="text-[8px] text-slate-400">Menü</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Ana Ekran Çubuğu -->
                            <div class="flex justify-center py-2 bg-slate-950">
                                <div class="w-20 h-1 bg-slate-600 rounded-full"></div>
                            </div>
                        </div>

                        <!-- Dekoratif Arka Plan -->
                        <div class="absolute -z-10 -inset-4 bg-indigo-600/10 rounded-3xl blur-2xl"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ============================================================
         10. DİJİTAL SERVİS FİŞİ MOCKUP BÖLÜMÜ
    ============================================================ -->
    <section id="servis-fisi" class="py-16 sm:py-24 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                <!-- Sol: Açıklama -->
                <div>
                    <span class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 uppercase bg-slate-100 border border-slate-300 px-3.5 py-1.5 rounded-full mb-6">
                        🧾 DİJİTAL SERVİS FİŞİ
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 leading-tight tracking-tight mb-5">
                        Kağıt fişin yerini<br>
                        <span class="text-[#2563EB]">dijital kayıt alsın.</span>
                    </h2>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6 max-w-lg">
                        Her iş emrinde yapılan işlemler ve kullanılan parçalar otomatik olarak kayıt altına alınır. Müşteriye servis fişini WhatsApp'tan gönder veya yazdır.
                    </p>

                    <div class="space-y-3 mb-8">
                        <div class="flex items-center gap-3 text-sm text-slate-700">
                            <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 border border-blue-200 flex items-center justify-center font-bold text-xs shrink-0">✓</span>
                            <span>Yapılan işlemler ve parça kalemleri detaylı görünür</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-700">
                            <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 border border-blue-200 flex items-center justify-center font-bold text-xs shrink-0">✓</span>
                            <span>A4 veya küçük termal yazıcıdan yazdırılabilir</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-700">
                            <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 border border-blue-200 flex items-center justify-center font-bold text-xs shrink-0">✓</span>
                            <span>WhatsApp'tan tek tıkla müşteriye iletilebilir</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-700">
                            <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 border border-blue-200 flex items-center justify-center font-bold text-xs shrink-0">✓</span>
                            <span>Geçmiş servis fişleri her zaman sistemde saklanır</span>
                        </div>
                    </div>

                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-black px-6 py-3.5 rounded-xl transition-all shadow-lg shadow-blue-600/20 text-sm">
                        <span>Servis Fişi Oluştur →</span>
                    </a>
                </div>

                <!-- Sağ: Fiş Mockup -->
                <div class="flex justify-center">
                    <div class="w-full max-w-xs bg-white border-2 border-slate-200 rounded-2xl shadow-2xl overflow-hidden font-mono">

                        <!-- Fiş Başlığı -->
                        <div class="bg-[#0B1224] text-white text-center py-5 px-4">
                            <div class="font-black text-lg tracking-wide">SanayiPro</div>
                            <div class="text-slate-400 text-[10px] font-sans font-bold uppercase tracking-widest mt-0.5">Dijital Servis Fişi</div>
                            <div class="text-[11px] text-slate-400 mt-2 font-sans">04 Ekim 2026 · #1024</div>
                        </div>

                        <div class="p-4 space-y-3">
                            <!-- Araç Bilgisi -->
                            <div class="text-[11px] space-y-1 pb-3 border-b border-dashed border-slate-200">
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Araç:</span>
                                    <span class="font-black text-slate-900">BMW 320d</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Plaka:</span>
                                    <span class="font-black text-slate-900">34 ABC 123</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">KM:</span>
                                    <span class="font-black text-slate-900">184.250</span>
                                </div>
                            </div>

                            <!-- İşlem Kalemleri -->
                            <div class="text-[11px] space-y-1.5 pb-3 border-b border-dashed border-slate-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-700">Motor Yağı (5W-30)</span>
                                    <span class="font-black text-slate-900">₺1.500</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-700">Yağ Filtresi</span>
                                    <span class="font-black text-slate-900">₺450</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-700">Fren Balatası (Ön)</span>
                                    <span class="font-black text-slate-900">₺2.100</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-700">İşçilik</span>
                                    <span class="font-black text-slate-900">₺1.200</span>
                                </div>
                            </div>

                            <!-- Toplam -->
                            <div class="flex justify-between items-center py-2">
                                <span class="font-black text-slate-900 text-sm">TOPLAM</span>
                                <span class="font-black text-[#2563EB] text-xl">₺5.250</span>
                            </div>

                            <!-- Usta -->
                            <div class="text-[10px] text-slate-400 text-center pb-1">Servis Yetkilisi: Ahmet Usta</div>

                            <!-- Butonlar -->
                            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100">
                                <div class="bg-emerald-600 text-white text-center py-2 rounded-xl text-[10px] font-black">
                                    💬 WhatsApp
                                </div>
                                <div class="bg-slate-900 text-white text-center py-2 rounded-xl text-[10px] font-black">
                                    🖨️ Yazdır
                                </div>
                            </div>

                            <!-- Demo Notu -->
                            <div class="text-center text-[9px] text-slate-400 pt-1">⚠️ Demo görünümü · Gerçek fiş değildir</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ============================================================
         6. SANAYİ UZMANLIK BRANŞLARI ("Sanayide Nesiniz?")
    ============================================================ -->
    <section id="branslar" class="py-16 bg-slate-50 border-b border-slate-200" x-data="{
        activeBranch: 'motor',
        branches: {
            motor: { title: 'Motor & Mekanik', icon: '⚙️', problem: 'Piston, triger ve rektifiye masraflarının kağıtlarda unutulup kafa hesabında kaybolması.', solution: 'Tüm parçaları ve rektifiye giderlerini iş emrine ekleyin, net kârınızı anında hesaplayın.' },
            elektrik: { title: 'Oto Elektrik & Akü', icon: '⚡', problem: 'Akü garantilerinde 6 ay sonra fiş arama ve fatura bulma tartışmaları yaşanması.', solution: 'Plakayı yazın; akü markası, amper ve garanti bitiş tarihi 2 saniyede önünüze gelsin.' },
            fren: { title: 'Fren & Ön Düzen', icon: '🛑', problem: 'Hangi taksiye ne zaman fren balatası takıldığı bilinemediğinden takibinin aksaması.', solution: 'Balata ve disk stokları 2 adedin altına inince sistem otomatik sipariş uyarısı versin.' },
            kaporta: { title: 'Kaporta & Boya & PDR', icon: '🚗', problem: 'Kaza hasarında şase, doğrultma ve boya işçiliklerinin kağıtlara dağılması.', solution: 'Tüm sac, fırın boya ve parça işçiliğini tek bir resmi dökümde müşteriye sunun.' },
            kilit: { title: 'Oto Kilit & Anahtar', icon: '🛡️', problem: 'Kodlanan akıllı anahtar ve çip kaparolarının kayıt tutulmadığı için unutulması.', solution: 'Kart ve anahtar seri numaraları plaka kaydıyla eşleşsin, avanslar hatasız düşsün.' },
            lastik: { title: 'Lastik & Rot-Balans', icon: '🛞', problem: 'Hangi araçta ne ebat lastik takıldığının takip edilememesi, sezon değişiminde sorunlar.', solution: 'Lastik ebadı, markası ve takılma tarihi araç kaydında saklanır. Sezon gelince plakayla bul.' },
            klima: { title: 'Oto Klima Servisi', icon: '❄️', problem: 'Klima gazı dolum tarihleri ve miktarları kağıtlarda kaybolması.', solution: 'Klima bakım geçmişi araç karnesiyle birlikte plaka aramasında anında görünür.' }
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <span class="text-xs font-bold text-blue-700 uppercase bg-blue-100 px-3 py-1 rounded">Uzmanlık Alanınız</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 mt-2">SanayiPro hangi işletmeler için?</h2>
                <p class="text-slate-600 text-sm mt-1">Kendi branşınızı seçin, SanayiPro'nun dükkanınıza getireceği kolaylığı görün.</p>
            </div>

            <!-- Sekme Düğmeleri -->
            <div class="flex flex-wrap justify-center gap-2 mb-8">
                <template x-for="(b, key) in branches" :key="key">
                    <button type="button"
                            @click="activeBranch = key"
                            :class="activeBranch === key ? 'bg-blue-700 text-white font-bold shadow-sm' : 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-100'"
                            class="px-4 py-2.5 rounded-lg text-xs font-semibold flex items-center gap-2 cursor-pointer transition-all">
                        <span x-text="b.icon"></span>
                        <span x-text="b.title"></span>
                    </button>
                </template>
            </div>

            <!-- İki Kolon Karşılaştırma -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                <div class="bg-rose-50 border border-rose-200 p-5 rounded-xl text-xs">
                    <h4 class="font-bold text-rose-800 mb-2 flex items-center gap-1.5">
                        <span>❌</span> Klasik Defterde Yaşanan Sorun:
                    </h4>
                    <p class="text-slate-700 leading-relaxed" x-text="branches[activeBranch].problem"></p>
                </div>

                <div class="bg-emerald-50 border border-emerald-200 p-5 rounded-xl text-xs">
                    <h4 class="font-bold text-emerald-800 mb-2 flex items-center gap-1.5">
                        <span>✅</span> SanayiPro İle Çözüm:
                    </h4>
                    <p class="text-slate-800 font-medium leading-relaxed" x-text="branches[activeBranch].solution"></p>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================================================
         6.5. WHATSAPP SERVİS FİŞİ BÖLÜMÜ (GELİŞTİRİLMİŞ)
    ============================================================ -->
    <section id="whatsapp-fisi" class="py-16 sm:py-24 bg-emerald-950 text-white border-b border-emerald-900 relative overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-emerald-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <!-- Sol Kolon: Başlık ve Açıklamalar -->
                <div class="lg:col-span-6 space-y-6 text-left">
                    <span class="inline-flex items-center gap-2 text-xs font-black tracking-widest text-emerald-400 uppercase bg-emerald-900/60 border border-emerald-700/50 px-4 py-1.5 rounded-full shadow-xs">
                        <span>💬 WHATSAPP SERVİS FİŞİ</span>
                    </span>

                    <h2 class="text-3xl sm:text-5xl font-black text-white leading-tight tracking-tight">
                        "Abi araba hazır mı?"<br>
                        <span class="text-emerald-400">mesajlarını azalt.</span>
                    </h2>

                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-xl">
                        Müşteriye servis bilgilerini ve fişini WhatsApp üzerinden gönder. Müşteri durumu zaten bilsin, seni aramak zorunda kalmasın.
                    </p>

                    <div class="space-y-3 pt-2 text-xs sm:text-sm">
                        <div class="flex items-center gap-3 text-slate-200 font-semibold">
                            <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center font-bold text-xs shrink-0">✓</div>
                            <span>Kağıt fiş basma, yazıcı arızası ve mürekkep masrafına son verin.</span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-200 font-semibold">
                            <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center font-bold text-xs shrink-0">✓</div>
                            <span>Müşteriniz neye ne ödediğini cebindeki resmi WhatsApp mesajında net görsün.</span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-200 font-semibold">
                            <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center font-bold text-xs shrink-0">✓</div>
                            <span>Araç sahibine güven verir, usta ile müşteri arasındaki yanlış anlamaları çözer.</span>
                        </div>
                    </div>

                    <div class="pt-4">
                        <a href="{{ route('login') }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-sm px-6 py-3.5 rounded-xl transition-all shadow-lg shadow-emerald-500/20 inline-flex items-center gap-2">
                            <span>WhatsApp'tan Gönder →</span>
                        </a>
                    </div>
                </div>

                <!-- Sağ Kolon: WhatsApp Mesaj Kartı Simülasyonu -->
                <div class="lg:col-span-6 flex justify-center">
                    <div class="w-full max-w-md bg-slate-900 rounded-3xl p-5 border-2 border-emerald-700/60 shadow-2xl space-y-4">

                        <!-- Sohbet Üst Barı -->
                        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-600 text-white font-black flex items-center justify-center text-sm shadow-md">
                                    SP
                                </div>
                                <div>
                                    <div class="font-bold text-white text-xs">SanayiPro Servis</div>
                                    <div class="text-[10px] text-emerald-400 font-medium flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Servis Fişi Müşterinin WhatsApp'ında
                                    </div>
                                </div>
                            </div>
                            <span class="text-[10px] bg-emerald-500/20 text-emerald-300 font-mono px-2.5 py-1 rounded-full font-bold border border-emerald-500/30">Demo</span>
                        </div>

                        <!-- WhatsApp Mesaj Balonu -->
                        <div class="bg-emerald-900/40 border border-emerald-700/50 rounded-2xl p-4 text-xs space-y-3">
                            <div class="text-slate-300 leading-relaxed">
                                Sayın <strong class="text-white">Mehmet Bey</strong>,<br>
                                <span class="font-mono font-bold text-emerald-300">34 ABC 123</span> plakalı BMW 320d aracınızın servis işlemleri tamamlandı. ✅
                            </div>

                            <div class="pb-2 border-b border-emerald-800/80">
                                <div class="text-[11px] font-bold text-emerald-300 uppercase tracking-wider mb-2">Yapılan İşlemler</div>
                                <div class="space-y-1 text-slate-200">
                                    <div class="flex items-center gap-1.5"><span class="text-emerald-400 font-bold">•</span> Motor yağı değişimi</div>
                                    <div class="flex items-center gap-1.5"><span class="text-emerald-400 font-bold">•</span> Yağ filtresi değişimi</div>
                                    <div class="flex items-center gap-1.5"><span class="text-emerald-400 font-bold">•</span> Ön fren balatası</div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-slate-300 font-bold text-xs uppercase">Toplam</span>
                                <span class="text-xl font-mono font-black text-emerald-300">₺5.250</span>
                            </div>

                            <div class="pt-1 flex items-center justify-between text-[10px] text-emerald-400/80 font-mono">
                                <span>14:24 • Demo Mesajı</span>
                                <span class="font-bold text-emerald-300">✓✓ Okundu</span>
                            </div>
                        </div>

                        <!-- Mesaj Gönder Aksiyon Butonu Simülasyonu -->
                        <div class="pt-2">
                            <a href="{{ route('login') }}" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-black py-3 rounded-xl text-xs transition-all shadow-md flex items-center justify-center gap-2">
                                <span>WhatsApp'tan Gönder →</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ============================================================
         7. DÜKKAN MODÜLLERİ (ÖZELLİKLER VE GÖRSEL KARTLAR)
    ============================================================ -->
    <section id="moduller" class="py-16 bg-slate-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-xs font-bold text-blue-700 uppercase bg-blue-100 px-3 py-1 rounded">Dükkan Modülleri</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 mt-2">Atölyenizi Kolaylaştıracak Tüm Araçlar</h2>
                <p class="text-slate-600 text-sm mt-1">Karmaşık bilgisayar programları yok. Cep telefonunuzdan her ustanın kullanacağı kadar pratik.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="sanayi-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 font-bold flex items-center justify-center text-xl mb-4">
                            📦
                        </div>
                        <h3 class="font-bold text-slate-900 text-base mb-2">Otomatik Stok & Parça İkazı</h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4">Balata, akü veya motor yağı stoğu belirlediğiniz limitlerin altına inince sistem kırmızı ikaz verir. Lifte araç asılı kalmaz.</p>
                    </div>
                    <div class="bg-rose-50 border border-rose-200 p-2.5 rounded text-[11px]">
                        <span class="font-bold text-rose-800 block">⚠️ KRİTİK STOK UYARISI</span>
                        <span class="text-slate-700">72Ah Varta Akü: Son 1 Adet Kaldı!</span>
                    </div>
                </div>

                <div class="sanayi-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold flex items-center justify-center text-xl mb-4">
                            🏪
                        </div>
                        <h3 class="font-bold text-slate-900 text-base mb-2">Çoklu Şube & Bağımsız Kasa</h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4">Motor, kaporta veya elektrik şubelerinizin kasalarını ve ustalarını ayrı ayrı yetkilendirin. Hesabınız karışmasın.</p>
                    </div>
                    <div class="bg-emerald-50 border border-emerald-200 p-2.5 rounded text-[11px] flex justify-between items-center">
                        <span class="font-bold text-emerald-900">Motor Şubesi Kasası:</span>
                        <span class="font-mono font-bold text-emerald-800">42.500 ₺ Net</span>
                    </div>
                </div>

                <div class="sanayi-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 font-bold flex items-center justify-center text-xl mb-4">
                            📊
                        </div>
                        <h3 class="font-bold text-slate-900 text-base mb-2">Günlük Net Kâr Analizi</h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4">Cironuzu değil, yedek parça maliyetleri düşüldükten sonra dükkana kalan net cebinizdeki parayı görün.</p>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 p-2.5 rounded text-[11px] flex justify-between items-center">
                        <span class="font-bold text-blue-900">Bu Ayki Net Marj:</span>
                        <span class="font-mono font-bold text-blue-800">%38 Net Kâr</span>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ============================================================
         11. NEDEN GÜVENİLİR? (GÜVEN BÖLÜMÜ - GELİŞTİRİLMİŞ)
    ============================================================ -->
    <section id="guven" class="py-16 sm:py-24 bg-slate-900 text-white border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

            <div class="max-w-3xl mx-auto mb-12">
                <span class="text-xs font-black tracking-widest text-indigo-400 uppercase bg-indigo-500/10 border border-indigo-500/20 px-4 py-1.5 rounded-full">
                    🛡️ NEDEN GÜVENİLİR?
                </span>
                <h2 class="text-3xl sm:text-5xl font-black text-white mt-4 tracking-tight">
                    SanayiPro ile işletmenizin kontrolü sizde.
                </h2>
                <p class="text-slate-400 text-sm sm:text-base mt-3">
                    Sahte rakamlar veya abartılı vaatler yok. Sistemin gerçekten sunduğu özellikler:
                </p>
            </div>

            <!-- Güven Özellikleri Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 max-w-5xl mx-auto text-left mb-12">

                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 hover:border-indigo-500/50 transition-all">
                    <div class="text-2xl mb-3">🔐</div>
                    <h3 class="text-sm font-black text-white mb-2">Rol Bazlı Yetkilendirme</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Her ustanın yetkisini belirleyin. Kasayı sadece yönetici görsün, usta sadece kendi iş emirlerini açsın.</p>
                </div>

                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 hover:border-blue-500/50 transition-all">
                    <div class="text-2xl mb-3">🏢</div>
                    <h3 class="text-sm font-black text-white mb-2">Şube Bazlı Veri İzolasyonu</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Farklı şubelerinizin verileri birbirine karışmaz. Her şube bağımsız kasa ve usta yönetimi yapar.</p>
                </div>

                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 hover:border-emerald-500/50 transition-all">
                    <div class="text-2xl mb-3">📋</div>
                    <h3 class="text-sm font-black text-white mb-2">Düzenli Kayıt Sistemi</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Her iş emri, her işçilik kalemi ve her tahsilat zaman damgasıyla kayıt altına alınır. Geçmişe dönük erişim her zaman mümkün.</p>
                </div>

                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 hover:border-blue-500/50 transition-all">
                    <div class="text-2xl mb-3">📱</div>
                    <h3 class="text-sm font-black text-white mb-2">Telefon & Tablet Uyumu</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">320px'den 1920px'e kadar tüm ekran boyutlarında düzgün çalışır. Özel uygulama indirmenize gerek yok.</p>
                </div>

                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 hover:border-amber-500/50 transition-all">
                    <div class="text-2xl mb-3">🔒</div>
                    <h3 class="text-sm font-black text-white mb-2">Güvenli Oturum Sistemi</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Kullanıcı adı ve şifre ile korunan bireysel hesaplar. Her giriş oturumu güvenli şekilde yönetilir.</p>
                </div>

                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 hover:border-indigo-500/50 transition-all">
                    <div class="text-2xl mb-3">🤖</div>
                    <h3 class="text-sm font-black text-white mb-2">Akıllı Arıza Asistanı</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Belirtileri ve OBD kodlarını girin; AI destekli olası arıza nedenleri ve kontrol önerileri alın. Fiziksel teşhisin yerini almaz.</p>
                </div>

            </div>

        </div>
    </section>


    <!-- ============================================================
         9. SIKÇA SORULAN SORULAR (S.S.S. - GENİŞLETİLMİŞ)
    ============================================================ -->
    <section id="sss" class="py-16 bg-white border-b border-slate-200" x-data="{ openFaq: null }">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <span class="text-xs font-bold text-blue-700 uppercase bg-blue-100 border border-blue-200 px-3 py-1 rounded-full">S.S.S.</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-3">Sıkça Sorulan Sorular</h2>
                <p class="text-slate-600 text-xs mt-1">Sistem hakkında aklınıza takılan tüm yanıtlar.</p>
            </div>

            <div class="space-y-3 text-xs">

                <div class="sanayi-card overflow-hidden">
                    <button type="button" @click="openFaq = (openFaq === 1 ? null : 1)" class="w-full p-4 text-left font-bold text-slate-900 flex justify-between items-center cursor-pointer min-h-[44px]">
                        <span>Bilgisayar bilmem gerekiyor mu?</span>
                        <span x-text="openFaq === 1 ? '−' : '+'" class="text-base font-mono text-slate-400 shrink-0 ml-2"></span>
                    </button>
                    <div x-show="openFaq === 1" x-cloak class="px-4 pb-4 text-slate-600 border-t border-slate-100 pt-3 leading-relaxed">
                        Hayır. SanayiPro, oto sanayi esnafının kolaylıkla kullanabileceği şekilde tasarlanmıştır. Plakayı yaz, müşteriyi bul, iş emrini oluştur — hepsi bu kadar. Yazılım bilgisine ihtiyaç yoktur.
                    </div>
                </div>

                <div class="sanayi-card overflow-hidden">
                    <button type="button" @click="openFaq = (openFaq === 2 ? null : 2)" class="w-full p-4 text-left font-bold text-slate-900 flex justify-between items-center cursor-pointer min-h-[44px]">
                        <span>Telefondan kullanabilir miyim?</span>
                        <span x-text="openFaq === 2 ? '−' : '+'" class="text-base font-mono text-slate-400 shrink-0 ml-2"></span>
                    </button>
                    <div x-show="openFaq === 2" x-cloak class="px-4 pb-4 text-slate-600 border-t border-slate-100 pt-3 leading-relaxed">
                        Evet. SanayiPro, telefon ve tablet ekranlarına tam uyumlu bir web uygulamasıdır. Tarayıcınızdan (Chrome, Safari vb.) giriş yaparak tüm özellikleri kullanabilirsiniz. Uygulama indirmenize gerek yoktur.
                    </div>
                </div>

                <div class="sanayi-card overflow-hidden">
                    <button type="button" @click="openFaq = (openFaq === 3 ? null : 3)" class="w-full p-4 text-left font-bold text-slate-900 flex justify-between items-center cursor-pointer min-h-[44px]">
                        <span>Birden fazla usta kullanabilir mi?</span>
                        <span x-text="openFaq === 3 ? '−' : '+'" class="text-base font-mono text-slate-400 shrink-0 ml-2"></span>
                    </button>
                    <div x-show="openFaq === 3" x-cloak class="px-4 pb-4 text-slate-600 border-t border-slate-100 pt-3 leading-relaxed">
                        Evet. Admin paneliniz üzerinden dilediğiniz kadar usta veya şube hesabı açabilirsiniz. Her ustanın yetkisini (Örn: Sadece iş emri açabilir, kasayı göremez) ayrı ayrı belirleyebilirsiniz.
                    </div>
                </div>

                <div class="sanayi-card overflow-hidden">
                    <button type="button" @click="openFaq = (openFaq === 4 ? null : 4)" class="w-full p-4 text-left font-bold text-slate-900 flex justify-between items-center cursor-pointer min-h-[44px]">
                        <span>Müşteriye WhatsApp'tan servis fişi gönderebilir miyim?</span>
                        <span x-text="openFaq === 4 ? '−' : '+'" class="text-base font-mono text-slate-400 shrink-0 ml-2"></span>
                    </button>
                    <div x-show="openFaq === 4" x-cloak class="px-4 pb-4 text-slate-600 border-t border-slate-100 pt-3 leading-relaxed">
                        Evet. İş emri tamamlandığında "WhatsApp'tan Gönder" butonuna basın; sistem yapılan işlemler ve toplam tutarı içeren hazır bir mesaj oluşturur. Siz sadece gönderin.
                    </div>
                </div>

                <div class="sanayi-card overflow-hidden">
                    <button type="button" @click="openFaq = (openFaq === 5 ? null : 5)" class="w-full p-4 text-left font-bold text-slate-900 flex justify-between items-center cursor-pointer min-h-[44px]">
                        <span>Yazıcı almak zorunda mıyım?</span>
                        <span x-text="openFaq === 5 ? '−' : '+'" class="text-base font-mono text-slate-400 shrink-0 ml-2"></span>
                    </button>
                    <div x-show="openFaq === 5" x-cloak class="px-4 pb-4 text-slate-600 border-t border-slate-100 pt-3 leading-relaxed">
                        Hayır. Servis fişini WhatsApp üzerinden göndermek için yazıcıya ihtiyacınız yoktur. Ancak isterseniz normal A4 veya termal yazıcıdan yazdırma da desteklenmektedir.
                    </div>
                </div>

                <div class="sanayi-card overflow-hidden">
                    <button type="button" @click="openFaq = (openFaq === 6 ? null : 6)" class="w-full p-4 text-left font-bold text-slate-900 flex justify-between items-center cursor-pointer min-h-[44px]">
                        <span>Kurulum için bilgisayar veya özel donanım gerekiyor mu?</span>
                        <span x-text="openFaq === 6 ? '−' : '+'" class="text-base font-mono text-slate-400 shrink-0 ml-2"></span>
                    </button>
                    <div x-show="openFaq === 6" x-cloak class="px-4 pb-4 text-slate-600 border-t border-slate-100 pt-3 leading-relaxed">
                        Hayır. SanayiPro %100 web tabanlıdır. Cep telefonunuzdan, tabletinizden veya bilgisayarınızdan kullanıcı adı ve şifrenizle anında giriş yapabilirsiniz. Özel cihaz masrafı gerektirmez.
                    </div>
                </div>

                <div class="sanayi-card overflow-hidden">
                    <button type="button" @click="openFaq = (openFaq === 7 ? null : 7)" class="w-full p-4 text-left font-bold text-slate-900 flex justify-between items-center cursor-pointer min-h-[44px]">
                        <span>Akıllı Arıza Asistanı nasıl çalışıyor?</span>
                        <span x-text="openFaq === 7 ? '−' : '+'" class="text-base font-mono text-slate-400 shrink-0 ml-2"></span>
                    </button>
                    <div x-show="openFaq === 7" x-cloak class="px-4 pb-4 text-slate-600 border-t border-slate-100 pt-3 leading-relaxed">
                        Araç bilgilerini, belirtileri ve OBD hata kodlarını girersiniz. Sistem bu bilgileri yapay zekâ modeline gönderir ve olası arıza nedenleri ile kontrol edilmesi önerilen noktaları listeler. Sonuçlar yardımcı değerlendirmedir; kesin teşhis yerine geçmez, fiziksel kontrol her zaman gereklidir.
                    </div>
                </div>

                <div class="sanayi-card overflow-hidden">
                    <button type="button" @click="openFaq = (openFaq === 8 ? null : 8)" class="w-full p-4 text-left font-bold text-slate-900 flex justify-between items-center cursor-pointer min-h-[44px]">
                        <span>İnternet bağlantısı kesildiğinde ne olur?</span>
                        <span x-text="openFaq === 8 ? '−' : '+'" class="text-base font-mono text-slate-400 shrink-0 ml-2"></span>
                    </button>
                    <div x-show="openFaq === 8" x-cloak class="px-4 pb-4 text-slate-600 border-t border-slate-100 pt-3 leading-relaxed">
                        SanayiPro internet bağlantısı gerektiren bir web uygulamasıdır. Bağlantı kesildiğinde sisteme erişilemez. Ancak tüm verileriniz sunucuda güvenli şekilde saklandığından, bağlantı geri geldiğinde kaldığınız yerden devam edebilirsiniz.
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ============================================================
         15. GÜÇLÜ SON CTA (FOOTER'DAN ÖNCE)
    ============================================================ -->
    <section class="py-20 sm:py-28 bg-gradient-to-br from-[#0B1224] via-indigo-950 to-[#0B1224] text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#1e40af_1px,transparent_1px)] [background-size:24px_24px] opacity-10 pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <span class="inline-flex items-center gap-2 text-xs font-black tracking-widest text-indigo-400 uppercase bg-indigo-500/10 border border-indigo-500/20 px-4 py-1.5 rounded-full mb-8">
                🚀 BAŞLAMAYA HAZIR MISINIZ?
            </span>

            <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white leading-tight tracking-tight mb-6">
                Defteri bırak.<br>
                <span class="text-[#60A5FA]">Dükkânını sisteme geçir.</span>
            </h2>

            <p class="text-slate-300 text-sm sm:text-lg leading-relaxed mb-10 max-w-2xl mx-auto">
                SanayiPro ile müşterilerini, araçlarını, iş emirlerini, stoklarını ve tahsilatlarını tek yerden yönet.
            </p>

            <div class="flex flex-wrap justify-center gap-4 mb-10">
                <a href="{{ route('login') }}"
                   class="bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-black text-base sm:text-lg px-8 py-4 rounded-2xl transition-all shadow-2xl shadow-blue-600/30 flex items-center gap-2">
                    <span>Ücretsiz Hesap Oluştur →</span>
                </a>
                <a href="#nasil-calisir"
                   class="bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm px-8 py-4 rounded-2xl border border-slate-700 transition-all flex items-center gap-2">
                    <span>Nasıl Çalıştığını Gör</span>
                </a>
            </div>

            <!-- Güven Şeridi -->
            <div class="flex flex-wrap justify-center items-center gap-6 text-xs text-slate-400 font-medium">
                <div class="flex items-center gap-2">
                    <span class="text-emerald-400 font-bold">✓</span>
                    <span>Kurulum gerektirmez</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-emerald-400 font-bold">✓</span>
                    <span>Telefondan kullanılabilir</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-emerald-400 font-bold">✓</span>
                    <span>Tarayıcıdan çalışır</span>
                </div>
            </div>
        </div>
    </section>


    <!-- ============================================================
         10. ALT BİLGİ (FOOTER)
    ============================================================ -->
    <footer class="bg-[#0B1224] text-[#64748B] py-10 text-xs border-t border-[#111B33]">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="font-black text-white text-sm">Sanayi<span class="text-[#2563EB]">Pro</span> © 2026</span>
                <span class="text-slate-700">|</span>
                <span>Oto Servis Yönetim Sistemi</span>
            </div>
            <div class="flex flex-wrap gap-4 font-medium justify-center">
                <a href="#ai-teshis" class="hover:text-white transition-colors">AI Asistan</a>
                <a href="#nasil-calisir" class="hover:text-white transition-colors">Nasıl Çalışır?</a>
                <a href="#ekran-goruntuleri" class="hover:text-white transition-colors">Ekranlar</a>
                <a href="#branslar" class="hover:text-white transition-colors">Branşlar</a>
                <a href="#moduller" class="hover:text-white transition-colors">Modüller</a>
                <a href="#sss" class="hover:text-white transition-colors">S.S.S.</a>
                <a href="{{ route('login') }}" class="text-[#60A5FA] font-bold hover:underline">Giriş Yap →</a>
            </div>
        </div>
    </footer>


</body>
</html>

