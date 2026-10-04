<div class="space-y-8">
    <!-- Header Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Perhitungan TOPSIS</h2>
            <p class="text-xs text-slate-500 mt-0.5">Penentuan urutan prioritas penanganan ibu hamil berdasarkan kedekatan solusi ideal.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('ahp.index') }}" class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <span>Lihat Bobot AHP</span>
            </a>

            <a href="{{ route('kriteria.index') }}" class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition">
                <span>Lihat Skala Penilaian</span>
            </a>

            <button type="button" wire:click="runCalculation" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#085a3c] hover:bg-[#064830] text-white text-xs font-bold shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                <span>Hitung TOPSIS</span>
            </button>
        </div>
    </div>

    <!-- 3 Stat Cards matching PDF page 14 -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- PRIORITAS TINGGI -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex items-center justify-between border-l-4 border-l-red-500">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Prioritas Tinggi</span>
                <span class="text-3xl font-extrabold text-slate-800">{{ $countTinggi }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>

        <!-- PRIORITAS SEDANG -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex items-center justify-between border-l-4 border-l-amber-500">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Prioritas Sedang</span>
                <span class="text-3xl font-extrabold text-slate-800">{{ $countSedang }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- PRIORITAS RENDAH -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex items-center justify-between border-l-4 border-l-emerald-500">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Prioritas Rendah</span>
                <span class="text-3xl font-extrabold text-slate-800">{{ $countRendah }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Search -->
        <div class="relative w-full max-w-sm">
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Cari nama ibu hamil atau kode..."
                   class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 bg-slate-50/70 focus:bg-white text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>

        <!-- Priority Filter Pills -->
        <div class="flex items-center gap-2 text-xs">
            <span class="text-slate-400 font-medium mr-1">Prioritas:</span>
            <button type="button" wire:click="$set('filterPriority', '')" class="px-3 py-1.5 rounded-lg font-semibold transition {{ empty($filterPriority) ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Semua</button>
            <button type="button" wire:click="$set('filterPriority', 'Tinggi')" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $filterPriority === 'Tinggi' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-700 hover:bg-red-100 border border-red-200' }}">Tinggi</button>
            <button type="button" wire:click="$set('filterPriority', 'Sedang')" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $filterPriority === 'Sedang' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200' }}">Sedang</button>
            <button type="button" wire:click="$set('filterPriority', 'Rendah')" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $filterPriority === 'Rendah' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' }}">Rendah</button>
        </div>
    </div>

    <!-- Table Hasil Perangkingan matching PDF page 14 -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden space-y-4 p-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-800">Hasil Perangkingan Prioritas</h3>
            <span class="text-xs text-slate-400 font-medium">Berdasarkan Nilai Preferensi ($V_i$)</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-100">
                        <th class="py-3 px-3 text-center">Peringkat</th>
                        <th class="py-3 px-4">Nama Ibu Hamil</th>
                        <th class="py-3 px-3">Status Anemia</th>
                        <th class="py-3 px-3">IMT</th>
                        <th class="py-3 px-3">LILA</th>
                        <th class="py-3 px-3">Usia</th>
                        <th class="py-3 px-3">Skor Preferensi</th>
                        <th class="py-3 px-3">Prioritas</th>
                        <th class="py-3 px-4">Faktor Risiko Utama</th>
                        <th class="py-3 px-4">Rekomendasi</th>
                        <th class="py-3 px-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($results as $res)
                        @php
                            $ibu = $res->ibuHamil;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-3 text-center font-bold text-slate-800 font-mono text-sm">
                                {{ $res->rank }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-900">
                                <div>{{ $ibu->nama ?? 'Pasien' }}</div>
                                <div class="text-xs text-slate-400 font-normal">{{ $ibu->kode_ibu_hamil ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-3 font-medium">
                                {{ $ibu ? $ibu->status_anemia_label : '-' }}
                            </td>
                            <td class="py-3.5 px-3">
                                {{ $ibu ? number_format($ibu->imt, 2) : '-' }}
                            </td>
                            <td class="py-3.5 px-3 {{ ($ibu && $ibu->lila < 23) ? 'text-red-600 font-bold' : '' }}">
                                {{ $ibu ? $ibu->lila . ' cm' : '-' }}
                            </td>
                            <td class="py-3.5 px-3">
                                {{ $ibu ? $ibu->usia . ' thn' : '-' }}
                            </td>
                            <td class="py-3.5 px-3 font-mono font-bold text-slate-900">
                                {{ number_format($res->preference_score, 4) }}
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="px-2.5 py-1 rounded-lg border text-xs font-bold {{ $res->priority_badge_color }}">
                                    {{ $res->priority }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-800 text-xs">
                                {{ $res->main_risk_factors ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 text-xs">
                                {{ $res->recommendation ?? '-' }}
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                <a href="{{ route('ranking.show', $res->id) }}" class="inline-flex items-center gap-1 font-semibold text-emerald-700 hover:text-emerald-900 text-xs sm:text-sm">
                                    <span>Detail</span>
                                    <span>&rarr;</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-8 text-center text-slate-400">
                                Belum ada data hasil perhitungan TOPSIS yang sesuai. Silakan klik tombol "Hitung TOPSIS".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Bottom Detail Button matching PDF page 14 -->
        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <a href="{{ route('topsis.detail') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition">
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <span>Lihat Detail Perhitungan</span>
            </a>
        </div>
    </div>
</div>
