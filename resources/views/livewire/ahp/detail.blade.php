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
            @if($detailData['isConsistent'] ?? true)
                <a href="{{ route('topsis.index') }}" class="px-5 py-2 rounded-xl bg-[#085a3c] hover:bg-[#064830] text-white text-xs font-semibold shadow-xs transition">
                    Lanjut ke TOPSIS &rarr;
                </a>
            @else
                <button type="button" 
                        disabled 
                        title="Perbandingan kriteria perlu diperiksa kembali (CR > 0.10). Tidak dapat melanjutkan ke perhitungan TOPSIS."
                        class="px-5 py-2 rounded-xl bg-[#b31d1d] text-white text-xs font-semibold shadow-xs cursor-not-allowed select-none transition">
                    Lanjut ke TOPSIS &rarr;
                </button>
            @endif
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
                <span class="text-xs text-slate-400 font-medium">Skala Saaty A = [a<sub>ij</sub>]</span>
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
                    <div class="mt-3 p-4 bg-slate-50 rounded-xl text-xs text-slate-600 font-mono flex flex-col gap-3 leading-relaxed">
                        @foreach($criteria as $colIndex => $col)
                            @php
                                $colParts = [];
                                foreach($criteria as $row) {
                                    $v = $detailData['matrix'][$row->id][$col->id] ?? 1;
                                    $colParts[] = ($v < 1) ? ('1/' . round(1/$v)) : round($v);
                                }
                                $colSum = $detailData['colSums'][$col->id] ?? 0;
                            @endphp
                            <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs flex flex-col gap-1.5">
                                <div class="font-bold text-slate-800 font-sans text-xs">
                                    {{ $col->code }} - {{ $col->name }} (Kolom {{ $colIndex + 1 }}):
                                </div>
                                <div class="text-slate-700">
                                    {{ implode(' + ', $colParts) }} = <strong class="text-emerald-800">{{ number_format($colSum, 4) }}</strong>
                                </div>
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
                <span class="text-xs text-slate-400 font-medium">Formula: r<sub>ij</sub> = a<sub>ij</sub> / &sum; a<sub>kj</sub></span>
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
                    <span>Lihat Detail Perhitungan Normalisasi</span>
                    <span>{{ $showStep2 ? '▲ Sembunyikan' : '▼ Tampilkan' }}</span>
                </button>
                @if($showStep2)
                    <div class="mt-3 p-4 bg-slate-50 rounded-xl text-xs text-slate-600 font-mono space-y-4 leading-relaxed">
                        @foreach($criteria as $rowIndex => $row)
                            <div class="space-y-1.5 border-b border-slate-200/60 pb-3 last:border-b-0 last:pb-0">
                                <div class="font-bold text-slate-800 font-sans">
                                    Baris {{ $rowIndex + 1 }} ({{ $row->name }}):
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2 pl-2">
                                    @foreach($criteria as $colIndex => $col)
                                        @php
                                            $val = $detailData['matrix'][$row->id][$col->id] ?? 1;
                                            $valFormatted = ($row->id == $col->id) 
                                                ? '1' 
                                                : (($val < 1) ? round($val, 4) : round($val));
                                            $colSum = $detailData['colSums'][$col->id] ?? 1;
                                            $norm = $detailData['normalizedMatrix'][$row->id][$col->id] ?? 0;
                                        @endphp
                                        <div class="bg-white p-2 rounded-lg border border-slate-200/60 shadow-2xs">
                                            <span class="text-slate-400">r<sub>{{ $rowIndex + 1 }}{{ $colIndex + 1 }}</sub></span> = {{ $valFormatted }} / {{ number_format($colSum, 4) }}<br>
                                            = <strong class="text-emerald-800">{{ number_format($norm, 4) }}</strong>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- 3. BOBOT PRIORITAS (EIGEN VECTOR) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">3. Bobot Prioritas (Eigen Vector)</h3>
                <span class="text-xs text-slate-400 font-medium">Formula: w<sub>i</sub> = (1/n) &times; &sum; r<sub>ij</sub></span>
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

            <!-- Accordion formula Step 3 -->
            <div class="pt-2 border-t border-slate-100">
                <button type="button" wire:click="toggleStep(3)" class="w-full flex items-center justify-between text-xs font-semibold text-slate-500 hover:text-emerald-700 transition py-1">
                    <span>Lihat Detail Perhitungan Bobot Prioritas</span>
                    <span>{{ $showStep3 ? '▲ Sembunyikan' : '▼ Tampilkan' }}</span>
                </button>
                @if($showStep3)
                    <div class="mt-3 p-4 bg-slate-50 rounded-xl text-xs text-slate-600 font-mono space-y-3 leading-relaxed">
                        @php
                            $n = count($criteria);
                        @endphp
                        @foreach($criteria as $rowIndex => $row)
                            @php
                                $rowParts = [];
                                foreach($criteria as $col) {
                                    $norm = $detailData['normalizedMatrix'][$row->id][$col->id] ?? 0;
                                    $rowParts[] = number_format($norm, 4);
                                }
                                $rowSum = $detailData['rowSums'][$row->id] ?? 0;
                                $weight = $detailData['weights'][$row->id] ?? 0;
                                $pct = $detailData['weightPercentages'][$row->id] ?? 0;
                            @endphp
                            <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs space-y-1">
                                <div class="font-bold text-slate-800 font-sans text-xs">
                                    {{ $row->code }} - {{ $row->name }} (w<sub>{{ $rowIndex + 1 }}</sub>):
                                </div>
                                <div class="text-slate-500">
                                    w<sub>{{ $rowIndex + 1 }}</sub> = ({{ implode(' + ', $rowParts) }}) / {{ $n }}
                                </div>
                                <div>
                                    w<sub>{{ $rowIndex + 1 }}</sub> = {{ number_format($rowSum, 4) }} / {{ $n }} = <strong class="text-emerald-800">{{ number_format($weight, 4) }}</strong>
                                    <span class="text-slate-400 ml-1">(&times; 100% = <strong class="text-slate-700">{{ $pct }}%</strong>)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
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

            <!-- Accordion formula Step 4 (Detail Uji Konsistensi) -->
            <div class="pt-2 border-t border-slate-100">
                <button type="button" wire:click="toggleStep(4)" class="w-full flex items-center justify-between text-xs font-semibold text-slate-500 hover:text-emerald-700 transition py-1">
                    <span>Lihat Detail Perhitungan Uji Konsistensi</span>
                    <span>{{ $showStep4 ? '▲ Sembunyikan' : '▼ Tampilkan' }}</span>
                </button>
                @if($showStep4)
                    <div class="mt-3 p-4 bg-slate-50 rounded-xl text-xs text-slate-600 font-mono space-y-4 leading-relaxed">
                        @php
                            $n = count($criteria);
                            $awList = [];
                            $totalLambda = 0;
                            foreach($criteria as $row) {
                                $yi = 0;
                                foreach($criteria as $col) {
                                    $a_val = (float)($detailData['matrix'][$row->id][$col->id] ?? 1);
                                    $w_val = (float)($detailData['weights'][$col->id] ?? 0);
                                    $yi += $a_val * $w_val;
                                }
                                $awList[$row->id] = $yi;
                                $totalLambda += ($detailData['lambdaList'][$row->id] ?? 0);
                            }
                        @endphp

                        <!-- 1. Vektor Bobot (A * w) -->
                        <div class="space-y-2">
                            <div class="font-bold text-slate-800 font-sans text-xs">
                                1. Perkalian Matriks dengan Vektor Bobot: (Aw)<sub>i</sub> = &sum; (a<sub>ij</sub> &times; w<sub>j</sub>)
                            </div>
                            <div class="flex flex-col gap-2">
                                @foreach($criteria as $rowIndex => $row)
                                    @php
                                        $multParts = [];
                                        foreach($criteria as $colIndex => $col) {
                                            $a_val = (float)($detailData['matrix'][$row->id][$col->id] ?? 1);
                                            $a_str = ($a_val < 1) ? ('1/' . round(1/$a_val)) : round($a_val);
                                            $w_val = number_format($detailData['weights'][$col->id] ?? 0, 4);
                                            $multParts[] = "({$a_str} &times; {$w_val})";
                                        }
                                        $awVal = $awList[$row->id] ?? 0;
                                    @endphp
                                    <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs space-y-1">
                                        <div class="font-bold text-slate-800 font-sans text-xs">
                                            {{ $row->code }} - {{ $row->name }} ((Aw)<sub>{{ $rowIndex + 1 }}</sub>):
                                        </div>
                                        <div class="text-slate-500 overflow-x-auto">
                                            (Aw)<sub>{{ $rowIndex + 1 }}</sub> = {!! implode(' + ', $multParts) !!}
                                        </div>
                                        <div>
                                            = <strong class="text-slate-800">{{ number_format($awVal, 4) }}</strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2. Nilai Eigen Tiap Baris (lambda_i) -->
                        <div class="space-y-2 pt-2 border-t border-slate-200/60">
                            <div class="font-bold text-slate-800 font-sans text-xs">
                                2. Nilai Rasio Konsistensi Tiap Kriteria: &lambda;<sub>i</sub> = (Aw)<sub>i</sub> / w<sub>i</sub>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($criteria as $rowIndex => $row)
                                    @php
                                        $awVal = $awList[$row->id] ?? 0;
                                        $wVal = $detailData['weights'][$row->id] ?? 0;
                                        $lVal = $detailData['lambdaList'][$row->id] ?? 0;
                                    @endphp
                                    <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs space-y-1">
                                        <div class="font-bold text-slate-800 font-sans text-xs">
                                            {{ $row->code }} - {{ $row->name }} (&lambda;<sub>{{ $rowIndex + 1 }}</sub>):
                                        </div>
                                        <div>
                                            &lambda;<sub>{{ $rowIndex + 1 }}</sub> = {{ number_format($awVal, 4) }} / {{ number_format($wVal, 4) }} = <strong class="text-emerald-800">{{ number_format($lVal, 4) }}</strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 3. Lambda Max -->
                        <div class="space-y-1.5 pt-2 border-t border-slate-200/60">
                            <div class="font-bold text-slate-800 font-sans text-xs">
                                3. Nilai Eigen Maksimum (&lambda;<sub>max</sub>):
                            </div>
                            <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs space-y-1">
                                @php
                                    $lParts = [];
                                    foreach($criteria as $row) {
                                        $lParts[] = number_format($detailData['lambdaList'][$row->id] ?? 0, 4);
                                    }
                                @endphp
                                <div class="text-slate-500 overflow-x-auto">
                                    &lambda;<sub>max</sub> = ({{ implode(' + ', $lParts) }}) / {{ $n }}
                                </div>
                                <div>
                                    &lambda;<sub>max</sub> = {{ number_format($totalLambda, 4) }} / {{ $n }} = <strong class="text-emerald-800">{{ number_format($detailData['lambdaMax'] ?? 0, 4) }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- 4. CI & CR -->
                        <div class="space-y-1.5 pt-2 border-t border-slate-200/60">
                            <div class="font-bold text-slate-800 font-sans text-xs">
                                4. Consistency Index (CI) &amp; Consistency Ratio (CR):
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs space-y-1">
                                    <div class="font-bold text-slate-800 font-sans text-xs">Consistency Index (CI):</div>
                                    <div class="text-slate-500">CI = (&lambda;<sub>max</sub> - n) / (n - 1)</div>
                                    <div>
                                        CI = ({{ number_format($detailData['lambdaMax'] ?? 0, 4) }} - {{ $n }}) / ({{ $n }} - 1)<br>
                                        = {{ number_format(($detailData['lambdaMax'] ?? 0) - $n, 4) }} / {{ $n - 1 }} = <strong class="text-emerald-800">{{ number_format($detailData['ci'] ?? 0, 4) }}</strong>
                                    </div>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs space-y-1">
                                    <div class="font-bold text-slate-800 font-sans text-xs">Consistency Ratio (CR):</div>
                                    <div class="text-slate-500">CR = CI / RI (RI untuk n={{ $n }} adalah {{ $detailData['ri'] ?? 0.90 }})</div>
                                    <div>
                                        CR = {{ number_format($detailData['ci'] ?? 0, 4) }} / {{ $detailData['ri'] ?? 0.90 }}<br>
                                        = <strong class="{{ ($detailData['isConsistent'] ?? true) ? 'text-emerald-800' : 'text-red-700' }}">{{ number_format($detailData['cr'] ?? 0, 4) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @endif
            </div>

            <!-- Status Banner matching PDF Page 7 & 9 -->
            @if($detailData['isConsistent'] ?? true)
                <div class="rounded-2xl bg-[#085a3c] text-white flex overflow-hidden shadow-sm items-stretch">
                    <div class="bg-[#064830] px-5 flex items-center justify-center shrink-0">
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-xs">
                            <svg class="w-6 h-6 text-[#085a3c]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="p-4 sm:p-5 space-y-1">
                        <div class="font-bold text-sm tracking-wide">
                            Konsistensi Diterima
                        </div>
                        <p class="text-xs text-emerald-50 leading-relaxed">
                            Nilai Consistency Ratio (CR) sebesar <strong>{{ number_format($detailData['cr'] ?? 0, 4) }}</strong> berada di bawah syarat &le; 0,10. Hasil perbandingan berpasangan dinyatakan konsisten dan bobot kriteria dapat digunakan pada proses penilaian risiko stunting.
                        </p>
                    </div>
                </div>
            @else
                <div class="rounded-2xl bg-red-700 text-white flex overflow-hidden shadow-sm items-stretch">
                    <div class="bg-red-800 px-5 flex items-center justify-center shrink-0">
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-xs">
                            <svg class="w-6 h-6 text-red-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="p-4 sm:p-5 space-y-1">
                        <div class="font-bold text-sm tracking-wide">
                            Konsistensi Tidak Diterima
                        </div>
                        <p class="text-xs text-red-50 leading-relaxed">
                            Nilai Consistency Ratio (CR) sebesar <strong>{{ number_format($detailData['cr'] ?? 0, 4) }} &gt; 0,10</strong>. Hasil perbandingan berpasangan dinyatakan tidak konsisten dan perlu diperiksa kembali sebelum digunakan dalam proses penilaian risiko.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
