<div class="w-full max-w-md bg-white rounded-3xl p-8 sm:p-10 shadow-xl border border-slate-100">
    <!-- Brand Logo -->
    <div class="flex justify-center mb-6">
        <div class="w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-700 shadow-inner">
            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Title & Subtitle -->
    <div class="text-left mb-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">Login</h2>
        <p class="text-sm text-slate-500 mt-1.5 leading-relaxed">
            Silakan masuk untuk mengakses dashboard prioritas penanganan ibu hamil.
        </p>
    </div>

    <!-- Global Error -->
    @error('login_failed')
        <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium rounded-xl flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ $message }}</span>
        </div>
    @enderror

    <!-- Form -->
    <form wire:submit.prevent="login" class="space-y-5">
        <div>
            <label for="username" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Username</label>
            <input type="text" 
                   id="username" 
                   wire:model="username" 
                   placeholder="Masukkan username atau email"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600/30 focus:border-emerald-600 transition placeholder:text-slate-400 @error('username') border-rose-300 bg-rose-50/30 @enderror">
            @error('username') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                <span class="text-xs text-emerald-700 hover:text-emerald-800 font-medium cursor-pointer">Lupa Kata Sandi?</span>
            </div>
            <input type="password" 
                   id="password" 
                   wire:model="password" 
                   placeholder="••••••••"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600/30 focus:border-emerald-600 transition placeholder:text-slate-400 @error('password') border-rose-300 bg-rose-50/30 @enderror">
            @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" wire:model="remember" class="w-4 h-4 rounded text-emerald-700 border-slate-300 focus:ring-emerald-600">
                <span class="text-xs text-slate-600 font-medium">Ingat Saya</span>
            </label>
        </div>

        <button type="submit" 
                class="w-full py-3.5 px-4 bg-[#085a3c] hover:bg-[#064830] active:scale-[0.99] text-white font-semibold rounded-xl text-sm shadow-md shadow-emerald-900/10 transition-all flex items-center justify-center gap-2 cursor-pointer">
            <span wire:loading.remove wire:target="login">Masuk</span>
            <span wire:loading wire:target="login" class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Memproses...
            </span>
        </button>
    </form>

    <!-- Quick Demo Credential Pills -->
    <div class="mt-8 pt-6 border-t border-slate-100 text-center">
        <p class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold mb-2.5">Akun Demo (Password: password)</p>
        <div class="flex flex-wrap justify-center gap-2">
            <button type="button" wire:click="$set('username', 'bidan')" class="px-2.5 py-1 text-xs rounded-lg bg-emerald-50 text-emerald-800 hover:bg-emerald-100 font-medium transition border border-emerald-100">Bidan (bidan)</button>
            <button type="button" wire:click="$set('username', 'admin')" class="px-2.5 py-1 text-xs rounded-lg bg-blue-50 text-blue-800 hover:bg-blue-100 font-medium transition border border-blue-100">Admin (admin)</button>
            <button type="button" wire:click="$set('username', 'ahligizi')" class="px-2.5 py-1 text-xs rounded-lg bg-amber-50 text-amber-800 hover:bg-amber-100 font-medium transition border border-amber-100">Ahli Gizi (ahligizi)</button>
        </div>
    </div>
</div>
