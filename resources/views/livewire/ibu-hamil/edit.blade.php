<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Edit Data Ibu Hamil</h2>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui data rekam medis ibu hamil {{ $ibuHamil->nama }} ({{ $ibuHamil->kode_ibu_hamil }}).</p>
        </div>
        <a href="{{ route('ibu-hamil.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
            Kembali
        </a>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- 1. INFORMASI PRIBADI -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3 text-slate-800 font-bold text-sm sm:text-base">
                <svg class="w-5 h-5 text-slate-800 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <span>Informasi Pribadi</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <div class="absolute left-3.5 flex items-center pointer-events-none text-slate-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm6 12H6v-1c0-2 4-3.1 6-3.1s6 1.1 6 3.1v1z"/>
                            </svg>
                        </div>
                        <input type="text" wire:model="nama" placeholder="Ibu Hamil 1" 
                               class="w-full text-xs sm:text-sm pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30 text-slate-800 placeholder-slate-400">
                    </div>
                    @error('nama') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Tanggal Lahir (Usia) -->
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                        Tanggal Lahir (Usia)
                        @if($usia > 0)
                            <span class="text-emerald-700 font-bold ml-1">({{ $usia }} tahun)</span>
                        @endif
                        <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <div class="absolute left-3.5 flex items-center pointer-events-none text-slate-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 6c1.11 0 2-.9 2-2 0-.38-.1-.73-.29-1.03L12 1.12l-1.71 1.85c-.19.3-.29.65-.29 1.03 0 1.1.9 2 2 2zm4.6 9.99l-1.07-1.07-1.08 1.07c-1.3 1.3-3.58 1.3-4.89 0l-1.07-1.07-1.09 1.07C6.75 16.64 5.88 17 4.96 17c-.73 0-1.4-.23-1.96-.61V21c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-4.61c-.56.38-1.23.61-1.96.61-.92 0-1.79-.36-2.44-1.01zM18 9h-5V7h-2v2H6c-1.66 0-3 1.34-3 3v1.54c0 1.08.88 1.96 1.96 1.96.52 0 1.02-.2 1.38-.57l2.14-2.13 2.13 2.13c.74.74 2.03.74 2.77 0l2.14-2.13 2.13 2.13c.37.37.86.57 1.39.57 1.08 0 1.96-.88 1.96-1.96V12c0-1.66-1.34-3-3-3z"/>
                            </svg>
                        </div>
                        <input type="date" wire:model.live="tanggal_lahir" 
                               class="w-full text-xs sm:text-sm pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30 text-slate-800">
                    </div>
                    @error('tanggal_lahir') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                        Nomor Telepon
                    </label>
                    <div class="relative flex items-center">
                        <div class="absolute left-3.5 flex items-center pointer-events-none text-slate-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1c0 1.24.2 2.45.57 3.57a1 1 0 01-.25 1.02l-2.2 2.2z"/>
                            </svg>
                        </div>
                        <input type="text" wire:model="nomor_telepon" placeholder="08123456789" 
                               class="w-full text-xs sm:text-sm pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30 text-slate-800 placeholder-slate-400">
                    </div>
                </div>

                <!-- Desa / Kelurahan -->
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                        Desa/Kelurahan
                    </label>
                    <div class="relative flex items-center">
                        <div class="absolute left-3.5 flex items-center pointer-events-none text-slate-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                            </svg>
                        </div>
                        <select wire:model="desa_kelurahan" 
                                class="w-full text-xs sm:text-sm pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30 text-slate-800">
                            <option value="">-- Pilih Desa / Kelurahan --</option>
                            @foreach($desaList as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. INFORMASI KEHAMILAN -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3 text-slate-800 font-bold text-sm sm:text-base">
                <svg class="w-5 h-5 text-slate-800 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm2 7h-4c-1.1 0-2 .9-2 2v6c0 .55.45 1 1 1h1v4c0 .55.45 1 1 1s1-.45 1-1v-4h2v4c0 .55.45 1 1 1s1-.45 1-1v-4h1c.55 0 1-.45 1-1v-6c0-1.1-.9-2-2-2z"/>
                </svg>
                <span>Informasi Kehamilan</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- HPHT -->
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                        Hari Pertama Haid Terakhir (HPHT) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <div class="absolute left-3.5 flex items-center pointer-events-none text-slate-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 002 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                            </svg>
                        </div>
                        <input type="date" wire:model.live="hpht" 
                               class="w-full text-xs sm:text-sm pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30 text-slate-800">
                    </div>
                    @error('hpht') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- HPL -->
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                        Hari Perkiraan Lahir (HPL) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <div class="absolute left-3.5 flex items-center pointer-events-none text-slate-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 002 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                            </svg>
                        </div>
                        <input type="date" wire:model="hpl" 
                               class="w-full text-xs sm:text-sm pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30 text-slate-800">
                    </div>
                    @error('hpl') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Usia Kehamilan -->
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                        Usia Kehamilan
                    </label>
                    <div class="relative flex items-center">
                        <div class="absolute left-3.5 flex items-center pointer-events-none text-slate-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <input type="number" wire:model="usia_kehamilan_minggu" min="0" max="45" placeholder="0" 
                               class="w-full text-xs sm:text-sm pl-10 pr-16 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30 text-slate-800 placeholder-slate-400">
                        <span class="absolute right-4 text-xs font-semibold text-slate-500 pointer-events-none">minggu</span>
                    </div>
                </div>

                <!-- Status Kehamilan -->
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                        Status Kehamilan
                    </label>
                    <div class="relative flex items-center">
                        <div class="absolute left-3.5 flex items-center pointer-events-none text-slate-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm2 7h-4c-1.1 0-2 .9-2 2v6c0 .55.45 1 1 1h1v4c0 .55.45 1 1 1s1-.45 1-1v-4h2v4c0 .55.45 1 1 1s1-.45 1-1v-4h1c.55 0 1-.45 1-1v-6c0-1.1-.9-2-2-2z"/>
                            </svg>
                        </div>
                        <select wire:model="status_kehamilan" 
                                class="w-full text-xs sm:text-sm pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30 text-slate-800">
                            <option value="Trimester I">Trimester I</option>
                            <option value="Trimester II">Trimester II</option>
                            <option value="Trimester III">Trimester III</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. INFORMASI KESEHATAN (Hanya Status Anemia, IMT, LILA, Berat Badan Sebelum Hamil) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3 text-slate-800 font-bold text-sm sm:text-base">
                <svg class="w-5 h-5 text-slate-800 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-1 6h2v3h3v2h-3v3h-2v-3H8v-2h3V7z"/>
                </svg>
                <span>Informasi Kesehatan</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Status Anemia (Dengan Rentang) -->
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                        Status Anemia <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <div class="absolute left-3.5 flex items-center pointer-events-none text-slate-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.69l5.66 5.66a8 8 0 11-11.31 0z"/>
                            </svg>
                        </div>
                        <select wire:model="status_anemia" 
                                class="w-full text-xs sm:text-sm pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30 text-slate-800">
                            <option value="Normal (≥ 11)">Normal (≥ 11)</option>
                            <option value="Ringan (10 - 10,9)">Ringan (10 - 10,9)</option>
                            <option value="Sedang (7 - 9,9)">Sedang (7 - 9,9)</option>
                            <option value="Berat (< 7)">Berat (< 7)</option>
                        </select>
                    </div>
                    @error('status_anemia') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Indeks Massa Tubuh (IMT) -->
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                        Indeks Massa Tubuh (IMT) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <div class="absolute left-3.5 flex items-center pointer-events-none text-slate-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.66 0 3 1.34 3 3 0 .5-.13.97-.35 1.38l1.77 1.77-1.41 1.41-1.78-1.78C12.82 11.9 12.43 12 12 12c-1.66 0-3-1.34-3-3s1.34-3 3-3z"/>
                            </svg>
                        </div>
                        <input type="number" step="0.01" min="0" wire:model="imt" placeholder="0" 
                               class="w-full text-xs sm:text-sm pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30 text-slate-800 placeholder-slate-400">
                    </div>
                    @error('imt') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Lingkar Lengan Atas (LILA) -->
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                        Lingkar Lengan Atas (LILA) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <div class="absolute left-3.5 flex items-center pointer-events-none text-slate-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 4H3c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H3V6h2v4h2V6h2v2h2V6h2v4h2V6h2v2h2V6h3v12z"/>
                            </svg>
                        </div>
                        <input type="number" step="0.1" min="0" wire:model="lila" placeholder="0" 
                               class="w-full text-xs sm:text-sm pl-10 pr-12 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30 text-slate-800 placeholder-slate-400">
                        <span class="absolute right-3.5 text-xs sm:text-sm font-medium text-slate-600 pointer-events-none">cm</span>
                    </div>
                    @error('lila') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Berat Badan Sebelum Hamil -->
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                        Berat Badan Sebelum Hamil
                    </label>
                    <div class="relative flex items-center">
                        <div class="absolute left-3.5 flex items-center pointer-events-none text-slate-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 7h-3V6a4 4 0 00-8 0v1H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2zm-9-1a2 2 0 014 0v1h-4V6zm9 13H5V9h14v10z"/>
                            </svg>
                        </div>
                        <input type="number" step="0.1" min="0" wire:model="berat_badan_sebelum_hamil" placeholder="0" 
                               class="w-full text-xs sm:text-sm pl-10 pr-12 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30 text-slate-800 placeholder-slate-400">
                        <span class="absolute right-3.5 text-xs sm:text-sm font-medium text-slate-600 pointer-events-none">kg</span>
                    </div>
                    @error('berat_badan_sebelum_hamil') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('ibu-hamil.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-semibold transition">
                Batal
            </a>
            <button type="submit" class="px-7 py-2.5 rounded-xl bg-[#085a3c] hover:bg-[#064830] text-white text-xs sm:text-sm font-semibold shadow-sm transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
