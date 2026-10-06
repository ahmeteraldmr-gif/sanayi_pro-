<!DOCTYPE html>
<html lang="tr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sanayi Yönetim Sistemi' }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.x/dist/chart.umd.min.js"></script>

    <!-- Tesseract.js OCR for License Plate Camera Reader -->
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CDN için özel type tanımı: Tailwindcss okuması için gerekli -->
    <style type="text/tailwindcss">
        [x-cloak] { display: none !important; }
        .sidebar-link { @apply flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all text-slate-400 hover:text-white hover:bg-slate-800/50; }
        .sidebar-link.active { @apply bg-indigo-600 text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-600; }
        .sidebar-heading { @apply px-3 mt-6 mb-2 text-[11px] font-bold text-slate-500 uppercase tracking-widest; }
        .safe-bottom { padding-bottom: calc(0.5rem + env(safe-area-inset-bottom, 0px)); }
    </style>
</head>
<body class="h-full bg-slate-50 font-sans text-slate-800 selection:bg-indigo-500 selection:text-white">
<div class="flex h-full" x-data="{ sidebarOpen: false, mobileSearchOpen: false }">

    <!-- Sidebar Overlay (mobile) -->
    <div x-show="sidebarOpen" x-cloak
         class="fixed inset-0 z-40 bg-slate-900/80 backdrop-blur-sm lg:hidden"
         @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-950 text-slate-300 flex flex-col shadow-2xl
                  transform transition-transform duration-200 ease-in-out border-r border-slate-800
                  lg:translate-x-0 lg:static lg:inset-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        <!-- Logo & Mobile Close -->
        <div class="flex items-center justify-between px-5 sm:px-6 py-4 sm:py-5 border-b border-slate-800/60">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-600/20 flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="font-bold text-white text-sm tracking-tight">Sanayi<span class="text-indigo-400">Pro</span></div>
                    <div class="text-[10px] text-slate-500 uppercase tracking-widest truncate max-w-[130px]">
                        {{ auth()->user()->isAdmin() ? 'Süper Yönetici' : (auth()->user()->branch->name ?? 'Şube Çalışanı') }}
                    </div>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden p-1.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto" @click="if (window.innerWidth < 1024 && $event.target.closest('a')) sidebarOpen = false">
            
            @if(auth()->user()->isAdmin())
            <div class="sidebar-heading text-indigo-400">YÖNETİM PANELİ</div>
            <a href="{{ route('admin.branches.index') }}" class="sidebar-link {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Sanayi Dalları
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Kullanıcılar & Roller
            </a>
            <div class="sidebar-heading mt-6">SANAYİPRO SERVİS</div>
            @endif

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>

            <!-- İş Emirleri -->
            <div x-data="{ open: {{ request()->routeIs('work-orders.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full sidebar-link justify-between {{ request()->routeIs('work-orders.*') ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span>İş Emirleri</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div x-show="open" class="pl-9 pr-2 py-1 space-y-1 text-xs">
                    <a href="{{ route('work-orders.index', ['status' => 'devam_ediyor']) }}" class="block px-3 py-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/60 {{ request('status') === 'devam_ediyor' ? 'text-indigo-400 font-bold' : '' }}">
                        🔵 Aktif İşler
                    </a>
                    <a href="{{ route('work-orders.index', ['status' => 'beklemede']) }}" class="block px-3 py-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/60 {{ request('status') === 'beklemede' ? 'text-amber-400 font-bold' : '' }}">
                        🟡 Bekleyenler
                    </a>
                    <a href="{{ route('work-orders.index', ['status' => 'tamamlandi']) }}" class="block px-3 py-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/60 {{ request('status') === 'tamamlandi' ? 'text-emerald-400 font-bold' : '' }}">
                        🟢 Tamamlananlar
                    </a>
                    <a href="{{ route('work-orders.create') }}" class="block px-3 py-1.5 rounded-lg text-indigo-300 font-bold hover:bg-indigo-900/40">
                        ➕ İş Emri Oluştur
                    </a>
                </div>
            </div>

            <!-- Takvim -->
            <a href="{{ route('appointments.index') }}" class="sidebar-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Takvim
            </a>

            <!-- 🤖 Arıza Bilgi Bankası -->
            <a href="{{ route('diagnostic.knowledge-base') }}" class="sidebar-link {{ request()->routeIs('diagnostic.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                <span>Arıza Bilgi Bankası</span>
                <span class="ml-auto bg-indigo-500/20 text-indigo-300 text-[10px] font-bold px-1.5 py-0.5 rounded">AI</span>
            </a>

            <!-- Müşteriler -->
            <a href="{{ route('customers.index') }}" class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Müşteriler
            </a>

            <!-- Araçlar & Karneler -->
            <a href="{{ route('vehicles.index') }}" class="sidebar-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 5H4m0 0l4 4m-4-4l4-4"/></svg>
                Araçlar & Karneler
            </a>

            <!-- Ustalar & Performans -->
            <a href="{{ route('masters.index') }}" class="sidebar-link {{ request()->routeIs('masters.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Ustalar & Performans
            </a>

            <!-- Parçalar & Stok -->
            <a href="{{ route('parts.index') }}" class="sidebar-link {{ request()->routeIs('parts.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Parçalar & Stok
                @php $lowStock = \App\Models\Part::whereRaw('stock <= min_stock')->count(); @endphp
                @if($lowStock > 0)
                    <span class="ml-auto bg-red-500/20 text-red-400 border border-red-500/20 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $lowStock }}</span>
                @endif
            </a>

            <!-- Tedarikçiler -->
            <a href="{{ route('suppliers.index') }}" class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Tedarikçiler
            </a>

            <!-- Finans -->
            <div x-data="{ open: {{ request()->routeIs('finance.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full sidebar-link justify-between {{ request()->routeIs('finance.*') ? 'active' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Finans</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div x-show="open" class="pl-9 pr-2 py-1 space-y-1 text-xs">
                    <a href="{{ route('finance.index', ['tab' => 'gelir']) }}" class="block px-3 py-1.5 rounded-lg text-slate-400 hover:text-white">
                        💰 Gelir
                    </a>
                    <a href="{{ route('finance.index', ['tab' => 'gider']) }}" class="block px-3 py-1.5 rounded-lg text-slate-400 hover:text-white">
                        📦 Gider
                    </a>
                    <a href="{{ route('finance.index', ['tab' => 'tahsilatlar']) }}" class="block px-3 py-1.5 rounded-lg text-slate-400 hover:text-white">
                        ✅ Tahsilatlar
                    </a>
                    <a href="{{ route('finance.index', ['tab' => 'borclar']) }}" class="block px-3 py-1.5 rounded-lg text-slate-400 hover:text-white">
                        🔴 Borçlar
                    </a>
                </div>
            </div>

            <!-- Raporlar -->
            <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Raporlar
            </a>

            <!-- Ayarlar -->
            <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                Ayarlar
            </a>
        </nav>

        <!-- User -->
        <div class="border-t border-slate-800/60 p-4">
            <div class="flex items-center gap-3 bg-slate-900/50 p-2 rounded-xl border border-slate-800">
                <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center text-sm font-bold text-white shadow-md">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Çıkış" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col min-h-0 overflow-hidden">
        <!-- Topbar -->
        <header class="bg-white border-b border-slate-200 px-3 sm:px-6 py-2.5 sm:py-3.5 flex items-center justify-between gap-2 sm:gap-4 sticky top-0 z-20 shadow-xs">
            <div class="flex items-center gap-2 sm:gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 active:scale-95 transition-transform" aria-label="Menüyü Aç">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 lg:hidden">
                    <div class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="font-extrabold text-slate-900 text-sm tracking-tight">Sanayi<span class="text-indigo-600">Pro</span></span>
                </a>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight hidden lg:block">{{ $title ?? 'Dashboard' }}</h1>
            </div>

            <!-- Hızlı Plaka / Müşteri Arama (Desktop Global Search) -->
            <div class="relative flex-1 max-w-md mx-2 sm:mx-4 hidden sm:block"
                 x-data="{
                     query: '',
                     results: [],
                     open: false,
                     loading: false,
                     search() {
                         if (this.query.length < 2) {
                             this.results = [];
                             this.open = false;
                             return;
                         }
                         this.loading = true;
                         fetch('{{ route('vehicles.quick-search') }}?q=' + encodeURIComponent(this.query))
                             .then(r => r.json())
                             .then(data => {
                                 this.results = data;
                                 this.open = true;
                                 this.loading = false;
                             })
                             .catch(() => { this.loading = false; });
                     }
                 }"
                 @click.outside="open = false">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                           x-model="query"
                           @input.debounce.250ms="search()"
                           @focus="if(query.length >= 2) open = true"
                           placeholder="Plaka / Müşteri Ara... (örn: 34)"
                           class="w-full pl-9 pr-9 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition-all">
                    <div x-show="loading" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg class="animate-spin h-3.5 w-3.5 text-indigo-600" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Arama Sonuçları Dropdown -->
                <div x-show="open && results.length > 0" x-cloak
                     class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50 divide-y divide-slate-100 max-h-96 overflow-y-auto">
                    <div class="px-3.5 py-2 bg-slate-900 text-white flex items-center justify-between text-[11px] font-bold">
                        <span class="flex items-center gap-1.5 text-emerald-400">
                            <span>✓</span> <span>Araç Bulundu</span>
                        </span>
                        <span class="text-slate-400" x-text="results.length + ' Eşleşme'"></span>
                    </div>

                    <template x-for="item in results" :key="item.id">
                        <div class="p-3.5 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-900 text-sm" x-text="(item.brand || '') + ' ' + (item.model || '')"></span>
                                        <span x-show="item.year" class="text-xs text-slate-400" x-text="'(' + item.year + ')'"></span>
                                    </div>
                                    <div class="inline-flex items-center bg-slate-900 text-white font-mono font-bold text-xs px-2 py-0.5 rounded shadow-2xs mt-1" x-text="item.plate"></div>
                                </div>

                                <a :href="item.create_work_order_url" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] px-2.5 py-1.5 rounded-lg transition-colors shadow-2xs shrink-0 flex items-center gap-1">
                                    <span>+ İş Emri Aç</span>
                                </a>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50 p-2.5 rounded-xl border border-slate-100 mt-2">
                                <div class="col-span-2 flex items-center gap-1 text-slate-700 font-medium">
                                    <span>👤</span> <strong class="text-slate-900" x-text="item.customer ? item.customer.name : 'Müşteri Yok'"></strong>
                                    <span x-show="item.customer && item.customer.phone" class="text-slate-400 font-mono text-[11px]" x-text="'(' + item.customer.phone + ')'"></span>
                                </div>
                                <div class="text-slate-600">
                                    📅 Son Servis: <strong class="text-slate-800" x-text="item.last_service_date"></strong>
                                </div>
                                <div class="text-slate-600">
                                    💰 Top. Servis: <strong class="text-emerald-700 font-mono" x-text="item.total_spent_formatted"></strong>
                                </div>
                                <div class="col-span-2 text-slate-500 text-[11px] flex items-center justify-between pt-1 border-t border-slate-200/50">
                                    <span class="font-bold text-slate-700" x-text="'🔧 ' + item.total_services_count"></span>
                                    <a :href="item.url" class="text-indigo-600 font-bold hover:underline">Servis Geçmişi →</a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="open && results.length === 0 && !loading && query.length >= 2" x-cloak
                     class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-xl border border-slate-200 p-4 text-center z-50">
                    <p class="text-xs text-slate-500 font-semibold">Bu plakaya ait araç bulunamadı.</p>
                    <a href="{{ route('vehicles.create') }}" class="text-xs text-indigo-600 font-bold hover:underline inline-block mt-1">
                        + Yeni Araç Olarak Ekle
                    </a>
                </div>
            </div>

            <!-- Sağ Aksiyonlar: Mobil Arama Aç/Kapa + Hızlı İş Emri -->
            <div class="flex items-center gap-1.5 sm:gap-3">
                <!-- Mobil Arama Butonu -->
                <button type="button" @click="mobileSearchOpen = !mobileSearchOpen"
                        class="sm:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors"
                        title="Plaka veya Müşteri Ara">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>

                <span class="hidden md:inline-block text-xs font-medium text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200">
                    {{ now()->format('d M Y, l') }}
                </span>
                
                <a href="{{ route('work-orders.create') }}" class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-2.5 sm:px-3 py-2 rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span class="hidden sm:inline">Hızlı İş Emri</span>
                    <span class="sm:hidden font-bold">Yeni Fiş</span>
                </a>
            </div>
        </header>

        <!-- MOBİL GENİŞLETİLEBİLİR ARAMA ÇUBUĞU (EXPANDABLE SEARCH BAR) -->
        <div x-show="mobileSearchOpen" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="sm:hidden bg-slate-900 border-b border-slate-800 p-3 shadow-xl z-20"
             x-data="{
                 mobQuery: '',
                 mobResults: [],
                 mobLoading: false,
                 mobSearch() {
                     if (this.mobQuery.length < 2) {
                         this.mobResults = [];
                         return;
                     }
                     this.mobLoading = true;
                     fetch('{{ route('vehicles.quick-search') }}?q=' + encodeURIComponent(this.mobQuery))
                         .then(r => r.json())
                         .then(data => {
                             this.mobResults = data;
                             this.mobLoading = false;
                         })
                         .catch(() => { this.mobLoading = false; });
                 }
             }">
            <div class="relative">
                <input type="text"
                       x-model="mobQuery"
                       @input.debounce.250ms="mobSearch()"
                       placeholder="Plaka veya Müşteri Ara... (örn: 34)"
                       class="w-full pl-9 pr-8 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-base text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <button type="button" @click="mobileSearchOpen = false; mobQuery = ''; mobResults = []" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Sonuçlar Listesi -->
            <div x-show="mobResults.length > 0" class="mt-2 bg-white rounded-xl shadow-xl overflow-hidden divide-y divide-slate-100 max-h-72 overflow-y-auto">
                <template x-for="item in mobResults" :key="item.id">
                    <div class="p-3">
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <div>
                                <span class="font-bold text-slate-900 text-xs" x-text="(item.brand || '') + ' ' + (item.model || '')"></span>
                                <div class="inline-block bg-slate-900 text-white font-mono font-bold text-[10px] px-1.5 py-0.5 rounded" x-text="item.plate"></div>
                            </div>
                            <a :href="item.create_work_order_url" class="bg-emerald-600 text-white font-bold text-[10px] px-2 py-1 rounded-lg">
                                + Fiş Aç
                            </a>
                        </div>
                        <div class="text-[11px] text-slate-600 flex items-center justify-between">
                            <span x-text="item.customer ? item.customer.name : 'Müşteri Yok'"></span>
                            <a :href="item.url" class="text-indigo-600 font-bold hover:underline">Geçmiş →</a>
                        </div>
                    </div>
                </template>
            </div>
            <div x-show="mobResults.length === 0 && !mobLoading && mobQuery.length >= 2" class="mt-2 p-3 bg-slate-800 rounded-xl text-center text-xs text-slate-300">
                <span>Araç bulunamadı.</span>
                <a href="{{ route('vehicles.create') }}" class="text-indigo-400 font-bold underline ml-1">+ Ekle</a>
            </div>
        </div>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-3 sm:p-6 lg:p-8 pb-28 lg:pb-8 bg-slate-50/50">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-cloak class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3.5 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700 p-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3.5 rounded-xl text-sm shadow-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3.5 rounded-xl shadow-sm">
                    <ul class="list-disc list-inside text-sm font-medium space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <!-- ============================================================
         MOBİL ALT MENÜ (MOBILE BOTTOM NAVIGATION BAR) - 5 BUTON
    ============================================================ -->
    <nav class="lg:hidden fixed bottom-0 inset-x-0 z-30 bg-slate-950/95 backdrop-blur-md border-t border-slate-800 px-3 py-1.5 flex items-center justify-around shadow-2xl safe-bottom">
        <!-- 1. 🏠 Ana Sayfa -->
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 py-1 px-2 text-[10px] font-medium transition-colors {{ request()->routeIs('dashboard') ? 'text-indigo-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Ana Sayfa</span>
        </a>

        <!-- 2. 🔧 İşler -->
        <a href="{{ route('work-orders.index') }}" class="flex flex-col items-center gap-1 py-1 px-2 text-[10px] font-medium transition-colors {{ request()->routeIs('work-orders.*') && !request()->routeIs('work-orders.create') ? 'text-indigo-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span>İşler</span>
        </a>

        <!-- 3. ➕ Yeni İş (Ortada Büyük Vurgulu Buton) -->
        <a href="{{ route('work-orders.create') }}" class="-mt-5 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg shadow-indigo-600/40 border-2 border-slate-900 active:scale-95 transition-transform" title="Yeni Fiş / İş Emri">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        </a>

        <!-- 4. 🤖 AI Teşhis -->
        <a href="{{ route('diagnostic.knowledge-base') }}" class="flex flex-col items-center gap-1 py-1 px-2 text-[10px] font-medium transition-colors {{ request()->routeIs('diagnostic.*') ? 'text-indigo-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            <span>AI Teşhis</span>
        </a>

        <!-- 5. ☰ Menü (Drawer) -->
        <button type="button" @click="sidebarOpen = true" class="flex flex-col items-center gap-1 py-1 px-2 text-[10px] font-medium text-slate-400 hover:text-slate-200 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <span>Menü</span>
        </button>
    </nav>
</div>
</body>
</html>
