<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Tambah Ibu Hamil</h2>
            <p class="text-xs text-slate-500 mt-0.5">Lengkapi data pribadi, kehamilan, dan kesehatan ibu hamil.</p>
        </div>
        <a href="{{ route('ibu-hamil.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
            Kembali
        </a>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- 1. INFORMASI PRIBADI -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3 text-slate-800 font-bold text-sm">
                <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span>Informasi Pribadi</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="nama" placeholder="Misal: Siti Aminah" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                    @error('nama') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Tanggal Lahir 
                        @if($usia > 0)
                            <span class="text-emerald-700 font-bold">({{ $usia }} tahun)</span>
                        @endif
                        <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" wire:model.live="tanggal_lahir" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                    @error('tanggal_lahir') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor Telepon</label>
                    <input type="text" wire:model="nomor_telepon" placeholder="08xxxxxxxxxx" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Desa / Kelurahan</label>
                    <select wire:model="desa_kelurahan" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                        <option value="">-- Pilih Desa / Kelurahan --</option>
                        @foreach($desaList as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- 2. INFORMASI KEHAMILAN -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3 text-slate-800 font-bold text-sm">
                <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Informasi Kehamilan</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Hari Pertama Haid Terakhir (HPHT) <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model.live="hpht" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                    @error('hpht') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Hari Perkiraan Lahir (HPL) <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="hpl" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                    @error('hpl') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Usia Kehamilan (Minggu)</label>
                    <input type="number" wire:model="usia_kehamilan_minggu" min="0" max="45" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Kehamilan</label>
                    <select wire:model="status_kehamilan" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                        <option value="Trimester I">Trimester I (0 - 12 Minggu)</option>
                        <option value="Trimester II">Trimester II (13 - 27 Minggu)</option>
                        <option value="Trimester III">Trimester III (28 - 40+ Minggu)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 3. INFORMASI KESEHATAN -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3 text-slate-800 font-bold text-sm">
                <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                <span>Informasi Kesehatan & Kriteria Risiko</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Anemia <span class="text-rose-500">*</span></label>
                    <select wire:model="status_anemia" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                        <option value="Tidak Anemia">Tidak Anemia / Normal (Hb ≥ 11 g/dL)</option>
                        <option value="Anemia Ringan">Anemia Ringan (Hb 10 - 10.9 g/dL)</option>
                        <option value="Anemia Sedang">Anemia Sedang (Hb 7 - 9.9 g/dL)</option>
                        <option value="Anemia Berat">Anemia Berat (Hb < 7 g/dL)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kadar Hemoglobin / Hb (g/dL)</label>
                    <input type="number" step="0.1" wire:model.live="kadar_hb" placeholder="Misal: 9.5" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Lingkar Lengan Atas / LILA (cm) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.1" wire:model="lila" placeholder="Misal: 23.0" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                    <p class="text-[10px] text-slate-400 mt-1">Standar normal: ≥ 23.0 cm (KEK jika < 23.0 cm)</p>
                    @error('lila') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Indeks Massa Tubuh / IMT <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" wire:model="imt" placeholder="Misal: 24.13" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                    <p class="text-[10px] text-slate-400 mt-1">Normal: 18.5 - 24.9. Terhitung otomatis jika BB & TB diisi.</p>
                    @error('imt') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Berat Badan Sebelum Hamil (kg)</label>
                    <input type="number" step="0.1" wire:model.live="berat_badan_sebelum_hamil" placeholder="Misal: 55.0" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tinggi Badan (cm)</label>
                    <input type="number" step="0.1" wire:model.live="tinggi_badan" placeholder="Misal: 155.0" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('ibu-hamil.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                Batal
            </a>
            <button type="submit" class="px-7 py-2.5 rounded-xl bg-[#085a3c] hover:bg-[#064830] text-white text-xs font-semibold shadow-sm transition">
                Simpan Data Ibu Hamil
            </button>
        </div>
    </form>
</div>
