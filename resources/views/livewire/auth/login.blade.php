<div class="w-full max-w-6xl relative z-10 my-auto py-6 sm:py-8">
    <!-- Ambient glowing backdrop blurs matching Image 1 -->
    <div class="absolute -top-16 -left-16 w-80 h-80 bg-emerald-200/50 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute -bottom-16 -right-16 w-80 h-80 bg-teal-200/40 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <!-- Main Unified Login Card (2 Columns) -->
    <div class="bg-white rounded-3xl sm:rounded-[2.25rem] shadow-2xl shadow-emerald-950/10 border border-emerald-900/10 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[580px]">
        
        <!-- LEFT COLUMN: Brand Identity & Integrated Mother Illustration matching Gambar 2 -->
        <div class="lg:col-span-7 bg-[#eff6f1] relative overflow-hidden flex flex-col justify-between p-7 sm:p-10 lg:p-12 border-b lg:border-b-0 lg:border-r border-emerald-100/70 select-none">
            <!-- Background Artwork Illustration (Mother, Leaves, Hills & Clinic) -->
            <div class="absolute inset-0 pointer-events-none select-none z-0 flex items-end justify-end">
                <img src="{{ asset('images/login-illustration-2x.png') }}" 
                     alt="Ilustrasi Dukung Kehamilan Sehat" 
                     class="h-full w-full object-cover object-right-bottom opacity-95">
            </div>

            <!-- Foreground Content Layer -->
            <div class="relative z-10 flex flex-col h-full">
                <!-- Top Left: Logo & Title (Foto 2 Logo) -->
                <div class="flex items-center gap-3.5">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Logo SPK Stunting" 
                         class="w-12 h-12 rounded-2xl drop-shadow-xs object-contain shrink-0 bg-white/90 p-1 border border-emerald-100/80 shadow-2xs">
                    <div>
                        <h1 class="text-sm sm:text-base font-bold text-slate-800 tracking-tight leading-snug">
                            Sistem Pendukung Keputusan
                        </h1>
                        <p class="text-xs text-slate-500 font-medium">
                            Puskesmas Banjarnegara 2
                        </p>
                    </div>
                </div>

                <!-- Headline placed UP TOP below the logo matching Gambar 2 -->
                <div class="mt-8 sm:mt-10 lg:mt-12 max-w-sm sm:max-w-md">
                    <h2 class="text-2xl sm:text-3xl lg:text-[2.15rem] font-extrabold text-slate-800 tracking-tight leading-[1.22]">
                        Dukung Kehamilan <br>
                        Sehat untuk <br>
                        <span class="text-[#085a3c] font-black">Generasi yang Lebih Baik</span>
                    </h2>
                    <!-- Subtle accent bar matching Gambar 2 -->
                    <div class="w-12 h-1 bg-emerald-600/35 rounded-full mt-3.5"></div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Login Form & Demo Accounts matching Image 1 -->
        <div class="lg:col-span-5 bg-white p-7 sm:p-10 lg:p-11 flex flex-col justify-center">
            
            <!-- Header: Badge Logo & Titles -->
            <div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center p-2 mb-4 shadow-2xs">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                </div>

                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Masuk ke Sistem
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1.5 leading-relaxed">
                    Silakan masuk untuk mengakses dashboard prioritas penanganan ibu hamil di Puskesmas Banjarnegara 2.
                </p>
            </div>

            <!-- Global Error Banner -->
            @error('login_failed')
                <div class="mt-4 p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium rounded-xl flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            <!-- Login Form -->
            <form wire:submit.prevent="login" class="space-y-4 mt-6">
                <!-- USERNAME -->
                <div>
                    <label for="username" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input type="text" 
                               id="username" 
                               wire:model="username" 
                               placeholder="Masukkan username atau email"
                               class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600/30 focus:border-emerald-600 transition placeholder:text-slate-400 @error('username') border-rose-300 bg-rose-50/30 @enderror">
                    </div>
                    @error('username') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- KATA SANDI -->
                <div x-data="{ showPassword: false }">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider">Kata Sandi</label>
                        <a href="#" class="text-xs text-emerald-700 hover:text-emerald-800 font-semibold hover:underline">Lupa Kata Sandi?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'" 
                               id="password" 
                               wire:model="password" 
                               placeholder="Masukkan kata sandi"
                               class="w-full pl-10 pr-10 py-2.5 sm:py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600/30 focus:border-emerald-600 transition placeholder:text-slate-400 @error('password') border-rose-300 bg-rose-50/30 @enderror">
                        <button type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                                title="Tampilkan / Sembunyikan Password">
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </button>
                    </div>
                    @error('password') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- INGAT SAYA -->
                <div class="flex items-center">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" wire:model="remember" class="w-4 h-4 rounded text-emerald-700 border-slate-300 focus:ring-emerald-600">
                        <span class="text-xs text-slate-600 font-medium">Ingat Saya</span>
                    </label>
                </div>

                <!-- SUBMIT BUTTON -->
                <button type="submit" 
                        wire:loading.attr="disabled"
                        wire:target="login"
                        class="w-full min-h-[44px] py-3 px-4 bg-[#085a3c] hover:bg-[#064830] active:scale-[0.99] text-white font-semibold rounded-xl text-xs sm:text-sm shadow-md shadow-emerald-900/10 transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-80 disabled:cursor-wait">
                    <span wire:loading.remove wire:target="login" class="inline-flex items-center justify-center gap-2">
                        <span>Masuk</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                    <span wire:loading.inline-flex wire:target="login" class="inline-flex items-center justify-center gap-2 whitespace-nowrap">
                        <svg class="animate-spin h-4 w-4 text-white shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Memproses...</span>
                    </span>
                </button>
            </form>

            <!-- DEMO ACCOUNT DIVIDER matching Image 1 -->
            <div class="relative my-6 text-center">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-white px-3 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                        Masuk sebagai Akun Demo
                    </span>
                </div>
            </div>

            <!-- 3 DEMO ACCOUNT BUTTONS matching Image 1 -->
            <div class="grid grid-cols-3 gap-2">
                <!-- Bidan -->
                <button type="button" 
                        wire:click="fillDemo('bidan')" 
                        class="p-2.5 sm:p-3 rounded-xl bg-emerald-50/80 hover:bg-emerald-100/90 text-emerald-800 border border-emerald-200/60 transition text-center flex flex-col items-center justify-center gap-1 group cursor-pointer shadow-2xs">
                    <div class="w-6 h-6 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 group-hover:scale-110 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <span class="font-bold text-xs text-slate-800">Bidan</span>
                    <span class="text-[10px] text-emerald-700 font-semibold">(bidan)</span>
                </button>

                <!-- Admin -->
                <button type="button" 
                        wire:click="fillDemo('admin')" 
                        class="p-2.5 sm:p-3 rounded-xl bg-blue-50/80 hover:bg-blue-100/90 text-blue-800 border border-blue-200/60 transition text-center flex flex-col items-center justify-center gap-1 group cursor-pointer shadow-2xs">
                    <div class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center text-blue-700 group-hover:scale-110 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="font-bold text-xs text-slate-800">Admin</span>
                    <span class="text-[10px] text-blue-700 font-semibold">(admin)</span>
                </button>

                <!-- Ahli Gizi -->
                <button type="button" 
                        wire:click="fillDemo('ahligizi')" 
                        class="p-2.5 sm:p-3 rounded-xl bg-amber-50/80 hover:bg-amber-100/90 text-amber-800 border border-amber-200/60 transition text-center flex flex-col items-center justify-center gap-1 group cursor-pointer shadow-2xs">
                    <div class="w-6 h-6 rounded-lg bg-amber-100 flex items-center justify-center text-amber-700 group-hover:scale-110 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="font-bold text-xs text-slate-800">Ahli Gizi</span>
                    <span class="text-[10px] text-amber-700 font-semibold">(ahligizi)</span>
                </button>
            </div>
        </div>
    </div>
</div>
