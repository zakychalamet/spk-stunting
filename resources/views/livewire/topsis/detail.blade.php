<div class="max-w-5xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Detail Perhitungan TOPSIS</h2>
            <p class="text-xs text-slate-500 mt-0.5">Penjelasan transparan setiap tahapan matematis algoritma TOPSIS.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('topsis.index') }}" class="px-5 py-2.5 rounded-xl bg-[#085a3c] hover:bg-[#064830] text-white text-xs font-semibold shadow-xs transition flex items-center gap-2">
                <span>&larr;</span>
                <span>Kembali ke Perhitungan TOPSIS</span>
            </a>
        </div>
    </div>

    @if(empty($topsisData['hasData']))
        <div class="p-8 bg-white rounded-2xl border border-slate-200 text-center text-slate-500 space-y-3">
            <p class="text-sm">Belum ada data perhitungan TOPSIS yang dapat ditampilkan.</p>
            <a href="{{ route('topsis.index') }}" class="inline-block px-4 py-2 bg-emerald-700 text-white text-xs font-semibold rounded-xl">Hitung TOPSIS</a>
        </div>
    @else

        <!-- 1. PENYUSUNAN MATRIKS KEPUTUSAN (X) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">1. Penyusunan Matriks Keputusan (X)</h3>
                <span class="text-xs text-slate-400 font-medium">Skor Penilaian Risiko Kriteria (1–4)</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center text-xs sm:text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                            <th class="py-3 px-4 text-left">ALTERNATIF</th>
                            <th class="py-3 px-3">Status Anemia (K1)</th>
                            <th class="py-3 px-3">IMT (K2)</th>
                            <th class="py-3 px-3">LILA (K3)</th>
                            <th class="py-3 px-3">Usia (K4)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-mono">
                        @foreach($topsisData['ibuHamilCollection'] as $item)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-2.5 px-4 font-bold text-slate-800 text-left font-sans">
                                    {{ $item->nama }}
                                </td>
                                <td class="py-2.5 px-3 font-bold">{{ $topsisData['decisionMatrix'][$item->id]['K1'] }}</td>
                                <td class="py-2.5 px-3 font-bold">{{ $topsisData['decisionMatrix'][$item->id]['K2'] }}</td>
                                <td class="py-2.5 px-3 font-bold">{{ $topsisData['decisionMatrix'][$item->id]['K3'] }}</td>
                                <td class="py-2.5 px-3 font-bold">{{ $topsisData['decisionMatrix'][$item->id]['K4'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. NORMALISASI MATRIKS KEPUTUSAN (R) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">2. Normalisasi Matriks Keputusan (R)</h3>
                <span class="text-xs text-slate-400 font-medium">Formula: r<sub>ij</sub> = x<sub>ij</sub> / &radic;(&sum; x<sub>kj</sub>&sup2;)</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center text-xs sm:text-sm border-collapse font-mono">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 font-sans">
                            <th class="py-3 px-4 text-left">ALTERNATIF</th>
                            <th class="py-3 px-3">Status Anemia</th>
                            <th class="py-3 px-3">IMT</th>
                            <th class="py-3 px-3">LILA</th>
                            <th class="py-3 px-3">Usia</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($topsisData['ibuHamilCollection'] as $item)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-2.5 px-4 font-bold text-slate-800 text-left font-sans">
                                    {{ $item->nama }}
                                </td>
                                <td class="py-2.5 px-3">{{ number_format($topsisData['normalizedMatrix'][$item->id]['K1'], 6) }}</td>
                                <td class="py-2.5 px-3">{{ number_format($topsisData['normalizedMatrix'][$item->id]['K2'], 6) }}</td>
                                <td class="py-2.5 px-3">{{ number_format($topsisData['normalizedMatrix'][$item->id]['K3'], 6) }}</td>
                                <td class="py-2.5 px-3">{{ number_format($topsisData['normalizedMatrix'][$item->id]['K4'], 6) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Accordion formula Step 2 -->
            <div class="pt-2 border-t border-slate-100">
                <button type="button" wire:click="toggleStep(2)" class="w-full flex items-center justify-between text-xs font-semibold text-slate-500 hover:text-emerald-700 transition py-1">
                    <span>Lihat Detail Perhitungan Normalisasi &amp; Pembagi (S<sub>j</sub>)</span>
                    <span>{{ $showStep2Formula ? '▲ Sembunyikan' : '▼ Tampilkan' }}</span>
                </button>
                @if($showStep2Formula)
                    <div class="mt-3 p-4 bg-slate-50 rounded-xl text-xs text-slate-600 font-mono space-y-4 leading-relaxed">
                        <!-- Pembagi Sj -->
                        <div class="space-y-2">
                            <div class="font-bold text-slate-800 font-sans text-xs">
                                1. Nilai Pembagi Kriteria (S<sub>j</sub> = &radic;&sum; x<sub>ij</sub>&sup2;):
                            </div>
                            <div class="flex flex-col gap-2">
                                @foreach($topsisData['criterionCodes'] as $code)
                                    @php
                                        $criterionObj = $criteria->firstWhere('code', $code);
                                        $critName = $criterionObj ? $criterionObj->name : $code;
                                        $sqParts = [];
                                        $sumSq = 0;
                                        foreach($topsisData['ibuHamilCollection'] as $item) {
                                            $val = $topsisData['decisionMatrix'][$item->id][$code] ?? 1;
                                            $sqParts[] = "{$val}&sup2;";
                                            $sumSq += ($val * $val);
                                        }
                                        $div = $topsisData['divisors'][$code] ?? 1;
                                    @endphp
                                    <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs space-y-1">
                                        <div class="font-bold text-slate-800 font-sans text-xs">
                                            {{ $code }} - {{ $critName }} (S<sub>{{ $code }}</sub>):
                                        </div>
                                        <div class="text-slate-500 overflow-x-auto">
                                            S<sub>{{ $code }}</sub> = &radic;({!! implode(' + ', $sqParts) !!})
                                        </div>
                                        <div>
                                            = &radic;({{ $sumSq }}) = <strong class="text-emerald-800">{{ number_format($div, 4) }}</strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Contoh / Rincian r_ij -->
                        <div class="space-y-2 pt-2 border-t border-slate-200/60">
                            <div class="font-bold text-slate-800 font-sans text-xs">
                                2. Perhitungan Normalisasi Elemen (r<sub>ij</sub> = x<sub>ij</sub> / S<sub>j</sub>):
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($topsisData['ibuHamilCollection'] as $item)
                                    <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs space-y-1">
                                        <div class="font-bold text-slate-800 font-sans text-xs border-b border-slate-100 pb-1">
                                            {{ $item->nama }}
                                        </div>
                                        @foreach($topsisData['criterionCodes'] as $code)
                                            @php
                                                $x = $topsisData['decisionMatrix'][$item->id][$code] ?? 1;
                                                $div = $topsisData['divisors'][$code] ?? 1;
                                                $r = $topsisData['normalizedMatrix'][$item->id][$code] ?? 0;
                                            @endphp
                                            <div class="text-slate-600 flex justify-between items-center text-[11px]">
                                                <span>r({{ $code }}): {{ $x }} / {{ number_format($div, 4) }}</span>
                                                <strong class="text-emerald-800 font-mono">{{ number_format($r, 6) }}</strong>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- 3. MATRIKS KEPUTUSAN TERBOBOT (V) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">3. Matriks Keputusan Terbobot (V)</h3>
                <span class="text-xs text-slate-400 font-medium">Formula: v<sub>ij</sub> = w<sub>j</sub> &times; r<sub>ij</sub></span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center text-xs sm:text-sm border-collapse font-mono">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 font-sans">
                            <th class="py-3 px-4 text-left">ALTERNATIF</th>
                            <th class="py-3 px-3">Status Anemia (w={{ $topsisData['weights']['K1'] }})</th>
                            <th class="py-3 px-3">IMT (w={{ $topsisData['weights']['K2'] }})</th>
                            <th class="py-3 px-3">LILA (w={{ $topsisData['weights']['K3'] }})</th>
                            <th class="py-3 px-3">Usia (w={{ $topsisData['weights']['K4'] }})</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($topsisData['ibuHamilCollection'] as $item)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-2.5 px-4 font-bold text-slate-800 text-left font-sans">
                                    {{ $item->nama }}
                                </td>
                                <td class="py-2.5 px-3">{{ number_format($topsisData['weightedMatrix'][$item->id]['K1'], 6) }}</td>
                                <td class="py-2.5 px-3">{{ number_format($topsisData['weightedMatrix'][$item->id]['K2'], 6) }}</td>
                                <td class="py-2.5 px-3">{{ number_format($topsisData['weightedMatrix'][$item->id]['K3'], 6) }}</td>
                                <td class="py-2.5 px-3">{{ number_format($topsisData['weightedMatrix'][$item->id]['K4'], 6) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Accordion formula Step 3 -->
            <div class="pt-2 border-t border-slate-100">
                <button type="button" wire:click="toggleStep(3)" class="w-full flex items-center justify-between text-xs font-semibold text-slate-500 hover:text-emerald-700 transition py-1">
                    <span>Lihat Detail Perhitungan Perkalian Bobot (v<sub>ij</sub>)</span>
                    <span>{{ $showStep3Formula ? '▲ Sembunyikan' : '▼ Tampilkan' }}</span>
                </button>
                @if($showStep3Formula)
                    <div class="mt-3 p-4 bg-slate-50 rounded-xl text-xs text-slate-600 font-mono space-y-3 leading-relaxed">
                        <div class="font-bold text-slate-800 font-sans text-xs">
                            Formula: v<sub>ij</sub> = w<sub>j</sub> &times; r<sub>ij</sub>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($topsisData['ibuHamilCollection'] as $item)
                                <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs space-y-1">
                                    <div class="font-bold text-slate-800 font-sans text-xs border-b border-slate-100 pb-1">
                                        {{ $item->nama }}
                                    </div>
                                    @foreach($topsisData['criterionCodes'] as $code)
                                        @php
                                            $w = $topsisData['weights'][$code] ?? 0;
                                            $r = $topsisData['normalizedMatrix'][$item->id][$code] ?? 0;
                                            $v = $topsisData['weightedMatrix'][$item->id][$code] ?? 0;
                                        @endphp
                                        <div class="text-slate-600 flex justify-between items-center text-[11px]">
                                            <span>v({{ $code }}): {{ number_format($w, 4) }} &times; {{ number_format($r, 4) }}</span>
                                            <strong class="text-emerald-800 font-mono">{{ number_format($v, 6) }}</strong>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- 4. SOLUSI IDEAL POSITIF (A+) & NEGATIF (A-) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">4. Solusi Ideal Positif (A+) dan Solusi Ideal Negatif (A-)</h3>
                <span class="text-xs text-slate-400 font-medium">Sifat Benefit (Risiko Maksimal = Positif, Risiko Minimal = Negatif)</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center text-xs sm:text-sm border-collapse font-mono">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 font-sans">
                            <th class="py-3 px-4 text-left">SOLUSI IDEAL</th>
                            <th class="py-3 px-3">Status Anemia</th>
                            <th class="py-3 px-3">IMT</th>
                            <th class="py-3 px-3">LILA</th>
                            <th class="py-3 px-3">Usia</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <tr class="bg-emerald-50/50">
                            <td class="py-3 px-4 font-bold text-emerald-800 text-left font-sans">
                                Solusi Ideal Positif (A+)
                            </td>
                            <td class="py-3 px-3 font-bold">{{ number_format($topsisData['idealPositive']['K1'], 6) }}</td>
                            <td class="py-3 px-3 font-bold">{{ number_format($topsisData['idealPositive']['K2'], 6) }}</td>
                            <td class="py-3 px-3 font-bold">{{ number_format($topsisData['idealPositive']['K3'], 6) }}</td>
                            <td class="py-3 px-3 font-bold">{{ number_format($topsisData['idealPositive']['K4'], 6) }}</td>
                        </tr>
                        <tr class="bg-rose-50/50">
                            <td class="py-3 px-4 font-bold text-rose-800 text-left font-sans">
                                Solusi Ideal Negatif (A-)
                            </td>
                            <td class="py-3 px-3 font-bold">{{ number_format($topsisData['idealNegative']['K1'], 6) }}</td>
                            <td class="py-3 px-3 font-bold">{{ number_format($topsisData['idealNegative']['K2'], 6) }}</td>
                            <td class="py-3 px-3 font-bold">{{ number_format($topsisData['idealNegative']['K3'], 6) }}</td>
                            <td class="py-3 px-3 font-bold">{{ number_format($topsisData['idealNegative']['K4'], 6) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Accordion formula Step 4 -->
            <div class="pt-2 border-t border-slate-100">
                <button type="button" wire:click="toggleStep(4)" class="w-full flex items-center justify-between text-xs font-semibold text-slate-500 hover:text-emerald-700 transition py-1">
                    <span>Lihat Penjelasan Penentuan Solusi Ideal (A+ / A-)</span>
                    <span>{{ $showStep4Formula ? '▲ Sembunyikan' : '▼ Tampilkan' }}</span>
                </button>
                @if($showStep4Formula)
                    <div class="mt-3 p-4 bg-slate-50 rounded-xl text-xs text-slate-600 font-mono space-y-3 leading-relaxed">
                        <div class="text-slate-600 font-sans text-xs">
                            Karena semua kriteria berorientasi pada <strong>risiko stunting (benefit/makin besar skor makin berisiko)</strong>:
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs space-y-1.5">
                                <div class="font-bold text-emerald-800 font-sans text-xs">
                                    Solusi Ideal Positif (A<sup>+</sup> = max(v<sub>ij</sub>)):
                                </div>
                                @foreach($topsisData['criterionCodes'] as $code)
                                    @php
                                        $criterionObj = $criteria->firstWhere('code', $code);
                                        $critName = $criterionObj ? $criterionObj->name : $code;
                                    @endphp
                                    <div class="text-[11px] flex justify-between items-center">
                                        <span>{{ $code }} ({{ $critName }}):</span>
                                        <strong class="text-emerald-800">{{ number_format($topsisData['idealPositive'][$code], 6) }}</strong>
                                    </div>
                                @endforeach
                            </div>
                            <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs space-y-1.5">
                                <div class="font-bold text-rose-800 font-sans text-xs">
                                    Solusi Ideal Negatif (A<sup>-</sup> = min(v<sub>ij</sub>)):
                                </div>
                                @foreach($topsisData['criterionCodes'] as $code)
                                    @php
                                        $criterionObj = $criteria->firstWhere('code', $code);
                                        $critName = $criterionObj ? $criterionObj->name : $code;
                                    @endphp
                                    <div class="text-[11px] flex justify-between items-center">
                                        <span>{{ $code }} ({{ $critName }}):</span>
                                        <strong class="text-rose-800">{{ number_format($topsisData['idealNegative'][$code], 6) }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- 5. JARAK SOLUSI IDEAL POSITIF (D+) DAN NEGATIF (D-) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">5. Jarak Solusi Ideal Positif (D+) dan Solusi Ideal Negatif (D-)</h3>
                <span class="text-xs text-slate-400 font-medium">Jarak Euclidean</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center text-xs sm:text-sm border-collapse font-mono">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 font-sans">
                            <th class="py-3 px-4 text-left">ALTERNATIF</th>
                            <th class="py-3 px-3">Jarak Ideal Positif (D+)</th>
                            <th class="py-3 px-3">Jarak Ideal Negatif (D-)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($topsisData['ibuHamilCollection'] as $item)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-2.5 px-4 font-bold text-slate-800 text-left font-sans">
                                    {{ $item->nama }}
                                </td>
                                <td class="py-2.5 px-3">{{ number_format($topsisData['dPlus'][$item->id], 6) }}</td>
                                <td class="py-2.5 px-3">{{ number_format($topsisData['dMinus'][$item->id], 6) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Accordion formula Step 5 -->
            <div class="pt-2 border-t border-slate-100">
                <button type="button" wire:click="toggleStep(5)" class="w-full flex items-center justify-between text-xs font-semibold text-slate-500 hover:text-emerald-700 transition py-1">
                    <span>Lihat Detail Perhitungan Jarak Euclidean (D+ &amp; D-)</span>
                    <span>{{ $showStep5Formula ? '▲ Sembunyikan' : '▼ Tampilkan' }}</span>
                </button>
                @if($showStep5Formula)
                    <div class="mt-3 p-4 bg-slate-50 rounded-xl text-xs text-slate-600 font-mono space-y-3 leading-relaxed">
                        <div class="text-slate-600 font-sans text-xs">
                            Formula Jarak Euclidean: D<sub>i</sub><sup>+</sup> = &radic;&sum;(v<sub>ij</sub> - v<sub>j</sub><sup>+</sup>)&sup2; dan D<sub>i</sub><sup>-</sup> = &radic;&sum;(v<sub>ij</sub> - v<sub>j</sub><sup>-</sup>)&sup2;
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($topsisData['ibuHamilCollection'] as $item)
                                @php
                                    $dPlusParts = [];
                                    $dMinusParts = [];
                                    foreach($topsisData['criterionCodes'] as $code) {
                                        $v = $topsisData['weightedMatrix'][$item->id][$code] ?? 0;
                                        $vPlus = $topsisData['idealPositive'][$code] ?? 0;
                                        $vMinus = $topsisData['idealNegative'][$code] ?? 0;

                                        $dPlusParts[] = "(" . number_format($v, 4) . " - " . number_format($vPlus, 4) . ")&sup2;";
                                        $dMinusParts[] = "(" . number_format($v, 4) . " - " . number_format($vMinus, 4) . ")&sup2;";
                                    }
                                @endphp
                                <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs space-y-2">
                                    <div class="font-bold text-slate-800 font-sans text-xs border-b border-slate-100 pb-1">
                                        {{ $item->nama }}
                                    </div>
                                    <div class="space-y-0.5 text-[11px]">
                                        <div class="text-emerald-800 font-bold">D<sup>+</sup>:</div>
                                        <div class="text-slate-500 overflow-x-auto">
                                            &radic;({!! implode(' + ', $dPlusParts) !!})
                                        </div>
                                        <div>
                                            = <strong class="text-emerald-800 font-mono">{{ number_format($topsisData['dPlus'][$item->id], 6) }}</strong>
                                        </div>
                                    </div>
                                    <div class="space-y-0.5 text-[11px] pt-1 border-t border-slate-100">
                                        <div class="text-rose-800 font-bold">D<sup>-</sup>:</div>
                                        <div class="text-slate-500 overflow-x-auto">
                                            &radic;({!! implode(' + ', $dMinusParts) !!})
                                        </div>
                                        <div>
                                            = <strong class="text-rose-800 font-mono">{{ number_format($topsisData['dMinus'][$item->id], 6) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- 6. NILAI PREFERENSI (V) DAN PERINGKAT -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">6. Nilai Preferensi (V) dan Peringkat Prioritas</h3>
                <span class="text-xs text-slate-400 font-medium">Formula: V = D- / (D+ + D-)</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center text-xs sm:text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                            <th class="py-3 px-3">Peringkat</th>
                            <th class="py-3 px-4 text-left">ALTERNATIF</th>
                            <th class="py-3 px-3">Nilai Preferensi (V)</th>
                            <th class="py-3 px-3">Prioritas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($topsisData['results'] as $row)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3 px-3 font-bold text-slate-900 font-mono text-sm">
                                    {{ $row['rank'] }}
                                </td>
                                <td class="py-3 px-4 font-semibold text-slate-900 text-left">
                                    {{ $row['ibu_hamil']->nama ?? 'Pasien' }}
                                </td>
                                <td class="py-3 px-3 font-mono font-bold text-slate-900">
                                    {{ number_format($row['preference_score'], 6) }}
                                </td>
                                <td class="py-3 px-3">
                                    @php
                                        $badgeColor = match($row['priority']) {
                                            'Tinggi' => 'bg-red-50 text-red-700 border-red-200',
                                            'Sedang' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-lg border text-xs font-bold {{ $badgeColor }}">
                                        {{ $row['priority'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Accordion formula Step 6 -->
            <div class="pt-2 border-t border-slate-100">
                <button type="button" wire:click="toggleStep(6)" class="w-full flex items-center justify-between text-xs font-semibold text-slate-500 hover:text-emerald-700 transition py-1">
                    <span>Lihat Detail Perhitungan Nilai Preferensi (V<sub>i</sub>)</span>
                    <span>{{ $showStep6Formula ? '▲ Sembunyikan' : '▼ Tampilkan' }}</span>
                </button>
                @if($showStep6Formula)
                    <div class="mt-3 p-4 bg-slate-50 rounded-xl text-xs text-slate-600 font-mono space-y-3 leading-relaxed">
                        <div class="text-slate-600 font-sans text-xs">
                            Formula: V<sub>i</sub> = D<sub>i</sub><sup>-</sup> / (D<sub>i</sub><sup>+</sup> + D<sub>i</sub><sup>-</sup>)
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($topsisData['results'] as $row)
                                @php
                                    $dp = $row['d_plus'];
                                    $dm = $row['d_minus'];
                                    $sumD = $dp + $dm;
                                    $vScore = $row['preference_score'];
                                @endphp
                                <div class="bg-white p-3 rounded-xl border border-slate-200/60 shadow-2xs space-y-1">
                                    <div class="font-bold text-slate-800 font-sans text-xs">
                                        #{{ $row['rank'] }} - {{ $row['ibu_hamil']->nama ?? 'Pasien' }}:
                                    </div>
                                    <div class="text-slate-500 overflow-x-auto text-[11px]">
                                        V = {{ number_format($dm, 4) }} / ({{ number_format($dp, 4) }} + {{ number_format($dm, 4) }})
                                    </div>
                                    <div class="text-[11px]">
                                        = {{ number_format($dm, 4) }} / {{ number_format($sumD, 4) }} = <strong class="text-emerald-800 font-mono">{{ number_format($vScore, 6) }}</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

    @endif
</div>
