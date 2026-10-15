<div class="max-w-3xl mx-auto space-y-6">
    <!-- Flash Message Banner matching Ibu Hamil CRUD -->
    @if(session()->has('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-xs">
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
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 flex items-center justify-between shadow-xs">
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

    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Pengaturan Sistem</h2>
        <p class="text-xs text-slate-500 mt-0.5">Konfigurasi parameter ambang batas prioritas dan data puskesmas.</p>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- 1. IDENTITAS INSTANSI -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">Identitas Layanan Kesehatan</h3>
                <p class="text-xs text-slate-400">Nama puskesmas atau instansi pelayanan ibu hamil.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Puskesmas / Faskes</label>
                <input type="text" wire:model="namaPuskesmas" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                @error('namaPuskesmas') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Daftar Desa / Kelurahan Binaan (Pisahkan dengan koma)</label>
                <textarea wire:model="desaListText" rows="3" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30"></textarea>
                <p class="text-xs text-slate-400 mt-1">Daftar ini akan otomatis muncul pada opsi pilihan data ibu hamil dan filter.</p>
            </div>
        </div>

        <!-- 2. AMBANG BATAS PRIORITAS TOPSIS -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">Ambang Batas Kategori Prioritas TOPSIS</h3>
                <p class="text-xs text-slate-400">Atur batas nilai preferensi (V) untuk mengelompokkan kategori intervensi.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl border border-red-100 bg-red-50/40 space-y-2">
                    <label class="block text-xs font-bold text-red-800">Batas Minimal Prioritas Tinggi (V &ge;)</label>
                    <input type="number" step="0.01" min="0" max="1" wire:model="thresholdHigh" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-red-600/30">
                    <p class="text-xs text-slate-500">Default: 0.80. Pasien dengan V &ge; 0.80 akan dikategorikan Prioritas Tinggi.</p>
                    @error('thresholdHigh') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="p-4 rounded-xl border border-amber-100 bg-amber-50/40 space-y-2">
                    <label class="block text-xs font-bold text-amber-800">Batas Minimal Prioritas Sedang (V &ge;)</label>
                    <input type="number" step="0.01" min="0" max="1" wire:model="thresholdMedium" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-600/30">
                    <p class="text-xs text-slate-500">Default: 0.60. Pasien dengan 0.60 &le; V &lt; 0.80 akan dikategorikan Prioritas Sedang.</p>
                    @error('thresholdMedium') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="p-3 bg-slate-50 rounded-xl text-xs text-slate-500">
                Nilai preferensi di bawah batas sedang (&lt; 0.60) secara otomatis dikategorikan sebagai <strong>Prioritas Rendah</strong>.
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="px-7 py-2.5 rounded-xl bg-[#085a3c] hover:bg-[#064830] text-white text-xs font-semibold shadow-sm transition">
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
