<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SPK Stunting' }} - Penentuan Prioritas Penanganan Ibu Hamil</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#085a3c] text-white flex flex-col justify-between shrink-0 min-h-screen shadow-xl select-none z-30">
        <div>
            <!-- Header / Logo -->
            <div class="px-6 py-6 border-b border-emerald-800/60 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-600/30 border border-emerald-400/40 flex items-center justify-center text-emerald-300 shadow-inner">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-tight tracking-wide text-white">SPK Stunting</h1>
                    <p class="text-xs text-emerald-200/70 font-medium">Prioritas Ibu Hamil</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="px-3 py-5 space-y-1.5 font-medium text-sm">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-white text-[#085a3c] font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Daftar Ibu Hamil -->
                <a href="{{ route('ibu-hamil.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-150 {{ request()->routeIs('ibu-hamil.*') ? 'bg-white text-[#085a3c] font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Daftar Ibu Hamil</span>
                </a>

                <!-- Kriteria & Skala -->
                <a href="{{ route('kriteria.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-150 {{ request()->routeIs('kriteria.*') ? 'bg-white text-[#085a3c] font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span>Kriteria & Skala</span>
                </a>

                <!-- Perhitungan Bobot (AHP) -->
                <a href="{{ route('ahp.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-150 {{ request()->routeIs('ahp.*') ? 'bg-white text-[#085a3c] font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span>Perhitungan Bobot</span>
                </a>

                <!-- Perhitungan TOPSIS -->
                <a href="{{ route('topsis.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-150 {{ request()->routeIs('topsis.*') || request()->routeIs('ranking.show') ? 'bg-white text-[#085a3c] font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                    <span>Perhitungan TOPSIS</span>
                </a>

                <!-- Pengguna -->
                @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('user.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-150 {{ request()->routeIs('user.*') ? 'bg-white text-[#085a3c] font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>Pengguna</span>
                </a>
                @endif

                <!-- Pengaturan -->
                <a href="{{ route('setting.index') }}" 
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl transition-all duration-150 {{ request()->routeIs('setting.*') ? 'bg-white text-[#085a3c] font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Pengaturan</span>
                </a>
            </nav>
        </div>

        <!-- Footer / Logout & Panduan -->
        <div class="px-4 py-5 border-t border-emerald-800/60 space-y-2 text-sm font-medium">
            <a href="javascript:void(0)" onclick="document.getElementById('panduanModal').classList.remove('hidden')" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-emerald-200 hover:bg-emerald-800/50 hover:text-white transition">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span>Panduan</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-emerald-200 hover:bg-emerald-800/50 hover:text-rose-200 transition text-left">
                    <svg class="w-5 h-5 shrink-0 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- TOPBAR -->
        <header class="bg-white border-b border-slate-200/80 px-8 py-4 flex items-center justify-between shadow-xs shrink-0">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-sm font-medium text-slate-500">
                <span class="text-slate-400">SPK Stunting</span>
                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-slate-700 font-semibold">{{ $breadcrumb ?? $title ?? 'Halaman' }}</span>
            </nav>

            <!-- User profile header pill -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2.5 pl-3 pr-4 py-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-full transition cursor-pointer">
                    <div class="w-9 h-9 rounded-full bg-emerald-700 text-white flex items-center justify-center font-bold text-sm uppercase shadow-sm">
                        {{ substr(auth()->user()->name ?? 'B', 0, 1) }}
                    </div>
                    <div class="text-left">
                        <div class="text-sm font-bold text-slate-800 leading-tight">
                            {{ auth()->user()->name ?? 'Bidan Kesehatan' }}
                        </div>
                        <div class="text-xs text-emerald-700 font-bold uppercase tracking-wider">
                            {{ auth()->user()->role_label ?? 'Bidan' }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="flex-1 overflow-y-auto p-8 bg-slate-50/70">
            <!-- Flash Message Banner -->
            @if(session()->has('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            @if(session()->has('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <!-- PANDUAN MODAL -->
    <div id="panduanModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="font-bold text-lg text-slate-800">Panduan Alur Sistem SPK Stunting</h3>
                <button type="button" onclick="document.getElementById('panduanModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>
            <div class="space-y-3 text-sm text-slate-600 leading-relaxed max-h-[60vh] overflow-y-auto pr-2">
                <p><strong>1. Data Ibu Hamil:</strong> Masukkan data kesehatan ibu hamil secara lengkap (Kadar Hb, IMT, LILA, Tanggal Lahir/Usia, dan HPHT).</p>
                <p><strong>2. Kriteria & Skala:</strong> Terdapat 4 kriteria utama (Anemia, IMT, LILA, Usia). Parameter skala medis menentukan skor risiko (1-4).</p>
                <p><strong>3. Perhitungan Bobot (AHP):</strong> Lakukan perbandingan berpasangan tingkat kepentingan kriteria berdasarkan Skala Saaty (1-9). Sistem akan menguji rasio konsistensi (CR &le; 0.10).</p>
                <p><strong>4. Perhitungan TOPSIS:</strong> Menghitung matriks keputusan terbobot, solusi ideal positif/negatif, dan jarak Euclidean untuk setiap pasien.</p>
                <p><strong>5. Hasil Perangkingan:</strong> Menghasilkan skor preferensi ($V_i$) dari 0 hingga 1. Semakin tinggi skor, semakin tinggi prioritas penanganan ibu hamil berisiko stunting.</p>
            </div>
            <div class="text-right pt-2 border-t">
                <button type="button" onclick="document.getElementById('panduanModal').classList.add('hidden')" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-sm font-semibold transition">Mengerti</button>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
