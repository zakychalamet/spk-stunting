<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Detail Ibu Hamil</h2>
            <p class="text-xs text-slate-500 mt-0.5">Informasi lengkap rekam data medis dan kehamilan pasien.</p>
        </div>
        <a href="{{ route('ibu-hamil.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
            Kembali
        </a>
    </div>

    <!-- MAIN DETAIL CARD -->
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-8">
        
        <!-- 1. INFORMASI PRIBADI -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Informasi Pribadi</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50">
                    <span class="text-[11px] text-slate-400 font-medium block mb-1">Nama Lengkap</span>
                    <span class="text-base font-bold text-slate-800">{{ $ibuHamil->nama }}</span>
                    @if($ibuHamil->kode_ibu_hamil)
                        <span class="text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md font-semibold ml-2">{{ $ibuHamil->kode_ibu_hamil }}</span>
                    @endif
                </div>

                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50">
                    <span class="text-[11px] text-slate-400 font-medium block mb-1">Tanggal Lahir (Usia)</span>
                    <div class="text-base font-bold text-slate-800">
                        {{ $ibuHamil->tanggal_lahir ? $ibuHamil->tanggal_lahir->format('d/m/Y') : '-' }}
                    </div>
                    <span class="text-xs text-slate-500 font-semibold">{{ $ibuHamil->usia_label }}</span>
                </div>

                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50">
                    <span class="text-[11px] text-slate-400 font-medium block mb-1">Nomor Telepon</span>
                    <span class="text-sm font-semibold text-slate-800">{{ $ibuHamil->nomor_telepon ?: '-' }}</span>
                </div>

                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50">
                    <span class="text-[11px] text-slate-400 font-medium block mb-1">Desa / Kelurahan</span>
                    <span class="text-sm font-semibold text-slate-800">{{ $ibuHamil->desa_kelurahan ?: '-' }}</span>
                </div>
            </div>
        </div>

        <!-- 2. INFORMASI KEHAMILAN -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Informasi Kehamilan</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50">
                    <span class="text-[11px] text-slate-400 font-medium block mb-1">Hari Pertama Haid Terakhir (HPHT)</span>
                    <span class="text-base font-bold text-slate-800">{{ $ibuHamil->hpht ? $ibuHamil->hpht->format('d/m/Y') : '-' }}</span>
                </div>

                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50">
                    <span class="text-[11px] text-slate-400 font-medium block mb-1">Hari Perkiraan Lahir (HPL)</span>
                    <span class="text-base font-bold text-slate-800">{{ $ibuHamil->hpl ? $ibuHamil->hpl->format('d/m/Y') : '-' }}</span>
                </div>

                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50">
                    <span class="text-[11px] text-slate-400 font-medium block mb-1">Usia Kehamilan</span>
                    <span class="text-base font-bold text-slate-800">{{ $ibuHamil->usia_kehamilan_label }}</span>
                </div>

                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50">
                    <span class="text-[11px] text-slate-400 font-medium block mb-1">Status Kehamilan</span>
                    <span class="text-base font-bold text-slate-800">{{ $ibuHamil->status_kehamilan ?: '-' }}</span>
                </div>
            </div>
        </div>

        <!-- 3. INFORMASI KESEHATAN -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Informasi Kesehatan & Parameter Stunting</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50">
                    <span class="text-[11px] text-slate-400 font-medium block mb-1">Status Anemia</span>
                    <div class="text-base font-bold text-slate-800">{{ $ibuHamil->status_anemia ?: 'Normal' }}</div>
                    @if($ibuHamil->kadar_hb)
                        <span class="text-xs text-slate-500 font-medium">{{ $ibuHamil->kadar_hb }} g/dL</span>
                    @endif
                </div>

                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50">
                    <span class="text-[11px] text-slate-400 font-medium block mb-1">Indeks Massa Tubuh (IMT)</span>
                    <span class="text-base font-bold text-slate-800">{{ number_format($ibuHamil->imt, 2) }}</span>
                </div>

                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50">
                    <span class="text-[11px] text-slate-400 font-medium block mb-1">Lingkar Lengan Atas (LILA)</span>
                    <span class="text-base font-bold text-slate-800 {{ $ibuHamil->lila < 23 ? 'text-red-600' : 'text-slate-800' }}">
                        {{ $ibuHamil->lila }} cm
                    </span>
                    @if($ibuHamil->lila < 23)
                        <span class="text-[10px] text-red-600 font-semibold block mt-0.5">Indikasi KEK (&lt; 23 cm)</span>
                    @endif
                </div>

                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50">
                    <span class="text-[11px] text-slate-400 font-medium block mb-1">Berat Badan Sebelum Hamil</span>
                    <span class="text-base font-bold text-slate-800">{{ $ibuHamil->berat_badan_sebelum_hamil ? $ibuHamil->berat_badan_sebelum_hamil . ' kg' : '-' }}</span>
                    @if($ibuHamil->tinggi_badan)
                        <span class="text-xs text-slate-400 block mt-0.5">Tinggi: {{ $ibuHamil->tinggi_badan }} cm</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Button -->
        <div class="flex justify-end pt-4 border-t border-slate-100">
            <a href="{{ route('ibu-hamil.index') }}" class="px-8 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-semibold shadow-xs transition">
                Tutup
            </a>
        </div>
    </div>
</div>
