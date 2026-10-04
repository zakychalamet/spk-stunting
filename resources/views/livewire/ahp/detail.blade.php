<div class="max-w-5xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Detail Perhitungan AHP</h2>
            <p class="text-xs text-slate-500 mt-0.5">Transparansi langkah matematis pembobotan kriteria dan uji rasio konsistensi.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('ahp.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                Kembali ke Matriks
            </a>
            <a href="{{ route('topsis.index') }}" class="px-5 py-2 rounded-xl bg-[#085a3c] hover:bg-[#064830] text-white text-xs font-semibold shadow-xs transition">
                Lanjut ke TOPSIS &rarr;
            </a>
        </div>
    </div>

    @if(!$calculation)
        <div class="p-8 bg-white rounded-2xl border border-slate-200 text-center text-slate-500 space-y-3">
            <p class="text-sm">Belum ada data perhitungan AHP yang aktif.</p>
            <a href="{{ route('ahp.index') }}" class="inline-block px-4 py-2 bg-emerald-700 text-white text-xs font-semibold rounded-xl">Lakukan Perhitungan Sekarang</a>
        </div>
    @else

        <!-- 1. MATRIKS PERBANDINGAN BERPASANGAN -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">1. Matriks Perbandingan Berpasangan</h3>
                <span class="text-xs text-slate-400 font-medium">Skala Saaty $A = [a_{ij}]$</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center text-xs sm:text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                            <th class="py-3 px-4 text-left">KRITERIA</th>
                            @foreach($criteria as $c)
                                <th class="py-3 px-3">{{ $c->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($criteria as $row)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3 px-4 font-bold text-slate-800 text-left">
                                    {{ $row->name }}
                                </td>
                                @foreach($criteria as $col)
                                    @php
                                        $val = $detailData['matrix'][$row->id][$col->id] ?? 1;
                                    @endphp
                                    <td class="py-3 px-3 font-medium">
                                        @if($row->id == $col->id)
                                            <span class="font-bold text-emerald-800">1</span>
                                        @elseif($val < 1)
                                            <span class="text-slate-500">{{ round($val, 4) }} <small class="text-slate-400">({{ '1/' . round(1/$val) }})</small></span>
                                        @else
                                            <span class="font-bold text-slate-800">{{ round($val) }}</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        <!-- Total Row -->
                        <tr class="bg-slate-50/80 font-bold text-slate-900 border-t-2 border-slate-200">
                            <td class="py-3 px-4 text-left">Total Kolom</td>
                            @foreach($criteria as $col)
                                <td class="py-3 px-3 font-mono text-emerald-800">
                                    {{ number_format($detailData['colSums'][$col->id] ?? 0, 4) }}
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Accordion formula Step 1 -->
            <div class="pt-2 border-t border-slate-100">
                <button type="button" wire:click="toggleStep(1)" class="w-full flex items-center justify-between text-xs font-semibold text-slate-500 hover:text-emerald-700 transition py-1">
                    <span>Lihat Detail Perhitungan Jumlah Kolom</span>
                    <span>{{ $showStep1 ? '▲ Sembunyikan' : '▼ Tampilkan' }}</span>
                </button>
                @if($showStep1)
                    <div class="mt-3 p-4 bg-slate-50 rounded-xl text-xs text-slate-600 font-mono space-y-1.5 leading-relaxed">
                        @foreach($criteria as $col)
                            @php
                                $colParts = [];
                                foreach($criteria as $row) {
                                    $v = $detailData['matrix'][$row->id][$col->id] ?? 1;
                                    $colParts[] = ($v < 1) ? ('1/' . round(1/$v)) : round($v);
                                }
                            @endphp
                            <div>
                                <strong>{{ $col->name }}</strong> = {{ implode(' + ', $colParts) }} = <strong>{{ number_format($detailData['colSums'][$col->id] ?? 0, 4) }}</strong>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- 2. NORMALISASI MATRIKS BERPASANGAN -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">2. Normalisasi Matriks Berpasangan</h3>
                <span class="text-xs text-slate-400 font-medium">Formula: $r_{ij} = \frac{a_{ij}}{\sum a_{kj}}$</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center text-xs sm:text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                            <th class="py-3 px-4 text-left">KRITERIA</th>
                            @foreach($criteria as $c)
                                <th class="py-3 px-3">{{ $c->name }}</th>
                            @endforeach
                            <th class="py-3 px-3 font-extrabold text-slate-800 bg-slate-100/50">Jumlah Baris</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-mono">
                        @foreach($criteria as $row)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3 px-4 font-bold text-slate-800 text-left font-sans">
                                    {{ $row->name }}
                                </td>
                                @foreach($criteria as $col)
                                    @php
                                        $norm = $detailData['normalizedMatrix'][$row->id][$col->id] ?? 0;
                                    @endphp
                                    <td class="py-3 px-3">
                                        {{ number_format($norm, 4) }}
                                    </td>
                                @endforeach
                                <td class="py-3 px-3 font-bold text-slate-900 bg-slate-50/50">
                                    {{ number_format($detailData['rowSums'][$row->id] ?? 0, 4) }}
                                </td>
                            </tr>
                        @endforeach
                        <!-- Total of Columns in Normalized Matrix (Should sum to 1) -->
                        <tr class="bg-slate-50/80 font-bold text-slate-900 border-t-2 border-slate-200 font-mono">
                            <td class="py-3 px-4 text-left font-sans">Total</td>
                            @foreach($criteria as $col)
                                <td class="py-3 px-3 text-emerald-800">
                                    1.0000
                                </td>
                            @endforeach
                            <td class="py-3 px-3 bg-slate-100/60 text-slate-900 font-bold">
                                {{ count($criteria) }}.0000
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Accordion formula Step 2 -->
            <div class="pt-2 border-t border-slate-100">
                <button type="button" wire:click="toggleStep(2)" class="w-full flex items-center justify-between text-xs font-semibold text-slate-500 hover:text-emerald-700 transition py-1">
                    <span>Lihat Contoh Pembagian Normalisasi</span>
                    <span>{{ $showStep2 ? '▲ Sembunyikan' : '▼ Tampilkan' }}</span>
                </button>
                @if($showStep2)
                    <div class="mt-3 p-4 bg-slate-50 rounded-xl text-xs text-slate-600 font-mono space-y-2 leading-relaxed">
                        @php
                            $first = $criteria->first();
                        @endphp
                        @if($first)
                            <div>Contoh Baris 1, Kolom 1 ({{ $first->name }}, {{ $first->name }}):</div>
                            <div class="pl-4">
                                $r_{11} = \frac{1}{{{ number_format($detailData['colSums'][$first->id] ?? 2, 4) }}} = <strong>{{ number_format($detailData['normalizedMatrix'][$first->id][$first->id] ?? 0.49, 4) }}</strong>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- 3. BOBOT PRIORITAS (EIGEN VECTOR) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">3. Bobot Prioritas (Eigen Vector)</h3>
                <span class="text-xs text-slate-400 font-medium">Formula: $w_i = \frac{1}{n} \sum r_{ij}$</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                            <th class="py-3 px-4">Kriteria</th>
                            <th class="py-3 px-4">Eigen Vector (Bobot)</th>
                            <th class="py-3 px-4">Persentase</th>
                            <th class="py-3 px-4">Visualisasi Bobot</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($criteria as $c)
                            @php
                                $w = $detailData['weights'][$c->id] ?? 0;
                                $pct = $detailData['weightPercentages'][$c->id] ?? 0;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3 px-4 font-bold text-slate-800">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-xs font-mono mr-1.5">{{ $c->code }}</span>
                                    {{ $c->name }}
                                </td>
                                <td class="py-3 px-4 font-mono font-bold text-slate-900">
                                    {{ number_format($w, 4) }}
                                </td>
                                <td class="py-3 px-4 font-bold text-emerald-800">
                                    {{ $pct }}%
                                </td>
                                <td class="py-3 px-4">
                                    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden max-w-sm">
                                        <div class="bg-[#085a3c] h-full rounded-full" style="width: {{ min(100, $pct) }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. UJI KONSISTENSI & KARTU STATUS -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">4. Uji Konsistensi Logika (Consistency Ratio)</h3>
                <span class="text-xs text-slate-400 font-medium">Toleransi CR &le; 0.10</span>
            </div>

            <!-- 3 Stat Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/60 space-y-1 text-center">
                    <span class="text-xs text-slate-400 font-semibold block uppercase tracking-wider">Lambda Max (&lambda; max)</span>
                    <span class="text-3xl font-extrabold text-slate-800 font-mono">{{ number_format($detailData['lambdaMax'] ?? 0, 4) }}</span>
                </div>

                <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/60 space-y-1 text-center">
                    <span class="text-xs text-slate-400 font-semibold block uppercase tracking-wider">Consistency Index (CI)</span>
                    <span class="text-3xl font-extrabold text-slate-800 font-mono">{{ number_format($detailData['ci'] ?? 0, 4) }}</span>
                    <span class="text-xs text-slate-400 block mt-0.5">(\lambda max - n) / (n - 1)</span>
                </div>

                <div class="p-5 rounded-2xl border {{ ($detailData['isConsistent'] ?? true) ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-red-50 border-red-200 text-red-800' }} space-y-1 text-center">
                    <span class="text-xs font-semibold block uppercase tracking-wider">Consistency Ratio (CR)</span>
                    <span class="text-3xl font-extrabold font-mono">{{ number_format($detailData['cr'] ?? 0, 4) }}</span>
                    <span class="text-xs block mt-0.5">CR = CI / RI (RI = {{ $detailData['ri'] ?? 0.90 }})</span>
                </div>
            </div>

            <!-- Status Banner matching PDF Page 7 & 9 -->
            @if($detailData['isConsistent'] ?? true)
                <div class="p-5 rounded-2xl bg-emerald-800 text-white space-y-1.5 shadow-sm">
                    <div class="flex items-center gap-2 font-bold text-sm">
                        <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Konsistensi Diterima</span>
                    </div>
                    <p class="text-xs text-emerald-100 leading-relaxed pl-7">
                        Nilai Consistency Ratio (CR) sebesar <strong>{{ number_format($detailData['cr'] ?? 0, 4) }}</strong> berada di bawah syarat &le; 0,10. Hasil perbandingan berpasangan dinyatakan konsisten dan bobot kriteria dapat digunakan pada proses penilaian risiko stunting.
                    </p>
                </div>
            @else
                <div class="p-5 rounded-2xl bg-red-800 text-white space-y-1.5 shadow-sm">
                    <div class="flex items-center gap-2 font-bold text-sm">
                        <svg class="w-5 h-5 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span>Konsistensi Tidak Diterima</span>
                    </div>
                    <p class="text-xs text-red-100 leading-relaxed pl-7">
                        Nilai Consistency Ratio (CR) sebesar <strong>{{ number_format($detailData['cr'] ?? 0, 4) }} &gt; 0,10</strong>. Hasil perbandingan berpasangan dinyatakan tidak konsisten dan perlu diperiksa kembali sebelum digunakan dalam proses penilaian risiko.
                    </p>
                </div>
            @endif
        </div>
    @endif
</div>
