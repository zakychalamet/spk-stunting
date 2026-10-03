<div class="space-y-8">
    <!-- GREETING BANNER matching PDF page 2 -->
    <div class="bg-[#085a3c] text-white rounded-3xl p-8 sm:p-10 shadow-sm relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
        <!-- Background decorative pattern -->
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-700/30 rounded-full blur-2xl pointer-events-none"></div>

        <div class="space-y-3 z-10 max-w-xl">
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                {{ $greeting }}, {{ auth()->user()->name ?? 'Bidan Kesehatan' }}
            </h2>
            <p class="text-emerald-100 text-xs sm:text-sm leading-relaxed">
                Kelola data ibu hamil, pantau faktor risiko secara akurat, dan tentukan prioritas penanganan intervensi stunting dengan metode AHP dan TOPSIS.
            </p>
            <div class="pt-2">
                <a href="{{ route('ibu-hamil.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-[#085a3c] hover:bg-emerald-50 text-xs font-bold shadow-xs transition">
                    <span>Lihat Data Ibu Hamil</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>

        <!-- Medical Hero SVG Illustration -->
        <div class="z-10 shrink-0 hidden md:flex items-center justify-center w-40 h-40 rounded-full bg-emerald-700/40 border border-emerald-500/30 p-6">
            <svg class="w-24 h-24 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 0l2 2m-2-2l-2 2"></path>
            </svg>
        </div>
    </div>

    <!-- 4 STAT METRICS CARDS matching PDF page 2 -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- TOTAL IBU HAMIL -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Ibu Hamil</span>
                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-800 tracking-tight">
                {{ $totalIbuHamil }}
            </div>
        </div>

        <!-- PRIORITAS TINGGI -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between space-y-3 border-b-2 border-b-red-500">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Prioritas Tinggi</span>
                <div class="w-7 h-7 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-red-600 tracking-tight">
                {{ $countTinggi }}
            </div>
        </div>

        <!-- PRIORITAS SEDANG -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between space-y-3 border-b-2 border-b-amber-500">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Prioritas Sedang</span>
                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-amber-600 tracking-tight">
                {{ $countSedang }}
            </div>
        </div>

        <!-- PRIORITAS RENDAH -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between space-y-3 border-b-2 border-b-emerald-500">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Prioritas Rendah</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-emerald-600 tracking-tight">
                {{ $countRendah }}
            </div>
        </div>
    </div>

    <!-- 2 CHARTS SECTION matching PDF page 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Bar Chart: Ibu Hamil Berdasarkan Kriteria (Anemia / LILA / IMT / Usia) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">{{ $chartInfo['title'] }}</h3>
                    <p class="text-xs text-slate-400 font-medium">{{ $chartInfo['subtitle'] }}</p>
                </div>

                <!-- Dots Pagination & Navigation matching design -->
                <div class="flex items-center gap-2">
                    <button type="button" 
                            wire:click="prevChart" 
                            title="Kriteria Sebelumnya" 
                            class="text-slate-400 hover:text-slate-700 transition p-0.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <div class="flex items-center gap-1.5">
                        @foreach(['anemia', 'lila', 'imt', 'usia'] as $key)
                            <button type="button" 
                                    wire:click="setChart('{{ $key }}')" 
                                    title="Kriteria: {{ strtoupper($key) }}"
                                    class="transition-all duration-200 rounded-full {{ $activeChart === $key ? 'w-2.5 h-2.5 bg-[#085a3c]' : 'w-2 h-2 bg-slate-300 hover:bg-slate-400 cursor-pointer' }}">
                            </button>
                        @endforeach
                    </div>
                    <button type="button" 
                            wire:click="nextChart" 
                            title="Kriteria Selanjutnya" 
                            class="text-slate-400 hover:text-slate-700 transition p-0.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>

            <!-- CSS Native Bar Chart -->
            <div class="pt-4 flex items-end justify-around gap-6 h-48 px-4 border-b border-slate-100">
                @foreach($currentChartData as $item)
                    @php
                        $heightPct = $maxChartCount > 0 ? round(($item['count'] / $maxChartCount) * 100) : 0;
                    @endphp
                    <div class="flex flex-col items-center gap-2 flex-1 h-full justify-end group">
                        <span class="text-xs font-extrabold text-slate-700">{{ $item['count'] }}</span>
                        <div class="w-full max-w-[48px] {{ $item['color'] }} rounded-t-xl transition-all duration-300 shadow-2xs" 
                             style="background-color: {{ $item['hex'] }}; height: {{ max(10, $heightPct) }}%"></div>
                        <span class="text-[10px] text-slate-500 font-semibold text-center line-clamp-1 group-hover:text-slate-900" title="{{ $item['label'] }}">{{ $item['label'] }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Legend Pills -->
            <div class="flex flex-wrap items-center justify-center gap-4 text-[11px] text-slate-500 pt-1">
                @foreach($currentChartData as $item)
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full {{ $item['dot_color'] }}" style="background-color: {{ $item['hex'] }}"></span> 
                        <span>{{ $item['legend'] }}</span>
                    </span>
                @endforeach
            </div>
        </div>

        <!-- Donut Chart: Distribusi Prioritas (1 Col) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">Distribusi Prioritas</h3>
                <span class="text-xs text-slate-400 font-medium">Persentase Kategori Penanganan</span>
            </div>

            <!-- Donut Visual Ring -->
            <div class="relative w-40 h-40 mx-auto flex items-center justify-center">
                @php
                    $totalTopsis = ($countTinggi + $countSedang + $countRendah) ?: 1;
                    $degTinggi = ($countTinggi / $totalTopsis) * 360;
                    $degSedang = $degTinggi + (($countSedang / $totalTopsis) * 360);
                @endphp
                <div class="w-36 h-36 rounded-full flex items-center justify-center shadow-inner"
                     style="background: conic-gradient(
                         #dc2626 0deg {{ $degTinggi }}deg,
                         #d97706 {{ $degTinggi }}deg {{ $degSedang }}deg,
                         #059669 {{ $degSedang }}deg 360deg
                     )">
                    <div class="w-24 h-24 bg-white rounded-full flex flex-col items-center justify-center shadow-sm">
                        <span class="text-[10px] text-slate-400 uppercase font-semibold">Total</span>
                        <span class="text-xl font-black text-slate-800">{{ $totalTopsis == 1 && ($countTinggi + $countSedang + $countRendah == 0) ? 0 : ($countTinggi + $countSedang + $countRendah) }}</span>
                    </div>
                </div>
            </div>

            <!-- Legend Pills -->
            <div class="flex items-center justify-center gap-4 text-xs font-semibold pt-2">
                <span class="flex items-center gap-1.5 text-slate-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-600"></span>
                    <span>Tinggi: {{ $countTinggi }}</span>
                </span>
                <span class="flex items-center gap-1.5 text-slate-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span>
                    <span>Sedang: {{ $countSedang }}</span>
                </span>
                <span class="flex items-center gap-1.5 text-slate-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                    <span>Rendah: {{ $countRendah }}</span>
                </span>
            </div>
        </div>

    </div>

    <!-- TABEL PRIORITAS PENANGANAN IBU HAMIL matching PDF page 2 -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Prioritas Penanganan Ibu Hamil</h3>
                <p class="text-xs text-slate-500 mt-0.5">Ibu hamil dengan nilai prioritas tertinggi berdasarkan hasil perhitungan sistem.</p>
            </div>
            <a href="{{ route('ranking.index') }}" class="text-xs text-emerald-700 hover:underline font-semibold flex items-center gap-1">
                <span>Lihat Semua Perangkingan</span>
                <span>&rarr;</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-100">
                        <th class="py-3 px-3 text-center">Peringkat</th>
                        <th class="py-3 px-4">Nama Ibu Hamil</th>
                        <th class="py-3 px-3">Skor Prioritas</th>
                        <th class="py-3 px-3">Prioritas</th>
                        <th class="py-3 px-4">Faktor Risiko Utama</th>
                        <th class="py-3 px-4">Rekomendasi</th>
                        <th class="py-3 px-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($topPriorities as $row)
                        @php $ibu = $row->ibuHamil; @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-3 text-center font-bold font-mono text-slate-800">
                                {{ $row->rank }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-900">
                                <div>{{ $ibu->nama ?? 'Pasien' }}</div>
                                <div class="text-[10px] text-slate-400 font-normal">{{ $ibu->kode_ibu_hamil ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-3 font-mono font-bold text-slate-900">
                                {{ number_format($row->preference_score, 3) }}
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="px-2.5 py-1 rounded-lg border text-[11px] font-bold {{ $row->priority_badge_color }}">
                                    {{ $row->priority }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-800 text-[11px] font-medium">
                                {{ $row->main_risk_factors ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 text-[11px]">
                                {{ $row->recommendation ?: '-' }}
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                <a href="{{ route('ranking.show', $row->id) }}" class="inline-flex items-center gap-1 font-semibold text-emerald-700 hover:text-emerald-900 text-xs">
                                    <span>Detail</span>
                                    <span>&rarr;</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Belum ada hasil perhitungan prioritas. Silakan jalankan perhitungan TOPSIS.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
