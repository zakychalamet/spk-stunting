<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Hasil Perangkingan Prioritas</h2>
            <p class="text-xs text-slate-500 mt-0.5">Urutan rekomendasi intervensi penanganan ibu hamil berdasarkan integrasi AHP & TOPSIS.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('topsis.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                <span>Hitung Ulang TOPSIS</span>
            </a>
            <a href="{{ route('topsis.detail') }}" class="px-4 py-2.5 rounded-xl bg-[#085a3c] hover:bg-[#064830] text-white text-xs font-semibold shadow-xs transition">
                <span>Lihat Detail Perhitungan</span>
            </a>
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

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                        <th class="py-3.5 px-4 text-center">Peringkat</th>
                        <th class="py-3.5 px-4">Nama Ibu Hamil</th>
                        <th class="py-3.5 px-3">Anemia</th>
                        <th class="py-3.5 px-3">IMT</th>
                        <th class="py-3.5 px-3">LILA</th>
                        <th class="py-3.5 px-3">Usia</th>
                        <th class="py-3.5 px-3">Skor Preferensi</th>
                        <th class="py-3.5 px-3">Prioritas</th>
                        <th class="py-3.5 px-4">Faktor Risiko Utama</th>
                        <th class="py-3.5 px-4">Rekomendasi</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($results as $res)
                        @php
                            $ibu = $res->ibuHamil;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-4 text-center font-extrabold text-slate-800 font-mono text-sm">
                                @if($res->rank == 1)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-100 text-amber-800 font-bold text-xs shadow-2xs">🥇 1</span>
                                @elseif($res->rank == 2)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-700 font-bold text-xs">🥈 2</span>
                                @elseif($res->rank == 3)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 text-orange-800 font-bold text-xs">🥉 3</span>
                                @else
                                    {{ $res->rank }}
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-900">
                                <div>{{ $ibu->nama ?? 'Pasien' }}</div>
                                <div class="text-[10px] text-slate-400 font-normal">{{ $ibu->kode_ibu_hamil ?? '-' }} • {{ $ibu->desa_kelurahan ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-3">
                                {{ $ibu->kadar_hb ? $ibu->kadar_hb . ' g/dL' : ($ibu->status_anemia ?? '-') }}
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
                                <span class="px-2.5 py-1 rounded-lg border text-[11px] font-bold {{ $res->priority_badge_color }}">
                                    {{ $res->priority }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-800 text-[11px]">
                                {{ $res->main_risk_factors ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 text-[11px]">
                                {{ $res->recommendation ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('ranking.show', $res->id) }}" class="inline-flex items-center gap-1 font-semibold text-emerald-700 hover:text-emerald-900 text-xs">
                                    <span>Detail</span>
                                    <span>&rarr;</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-10 text-center text-slate-400">
                                Tidak ada data hasil perangkingan yang sesuai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
