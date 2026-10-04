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
                <h3 class="text-sm font-bold text-slate-800">1. Penyusunan Matriks Keputusan ($X$)</h3>
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
                <h3 class="text-sm font-bold text-slate-800">2. Normalisasi Matriks Keputusan ($R$)</h3>
                <span class="text-xs text-slate-400 font-medium">Formula: $r_{ij} = \frac{x_{ij}}{\sqrt{\sum x_{kj}^2}}$</span>
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

            <!-- Accordion formula -->
            <div class="pt-2 border-t border-slate-100">
                <button type="button" wire:click="toggleFormula" class="w-full flex items-center justify-between text-xs font-semibold text-slate-500 hover:text-emerald-700 transition py-1">
                    <span>Lihat Detail Perhitungan Pembagi Normalisasi ($S_j$)</span>
                    <span>{{ $showStep2Formula ? '▲ Sembunyikan' : '▼ Tampilkan' }}</span>
                </button>
                @if($showStep2Formula)
                    <div class="mt-3 p-4 bg-slate-50 rounded-xl text-xs text-slate-600 font-mono space-y-2 leading-relaxed">
                        @foreach($topsisData['criterionCodes'] as $code)
                            <div>
                                Pembagi <strong>{{ $code }}</strong>: $\sqrt{\sum x_{kj}^2}$ = <strong>{{ number_format($topsisData['divisors'][$code], 4) }}</strong>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- 3. MATRIKS KEPUTUSAN TERBOBOT (V) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">3. Matriks Keputusan Terbobot ($V$)</h3>
                <span class="text-xs text-slate-400 font-medium">Formula: $v_{ij} = w_j \cdot r_{ij}$</span>
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
        </div>

        <!-- 4. SOLUSI IDEAL POSITIF (A+) & NEGATIF (A-) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">4. Solusi Ideal Positif ($A^+$) dan Solusi Ideal Negatif ($A^-$)</h3>
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
                                Solusi Ideal Positif ($A^+$)
                            </td>
                            <td class="py-3 px-3 font-bold">{{ number_format($topsisData['idealPositive']['K1'], 6) }}</td>
                            <td class="py-3 px-3 font-bold">{{ number_format($topsisData['idealPositive']['K2'], 6) }}</td>
                            <td class="py-3 px-3 font-bold">{{ number_format($topsisData['idealPositive']['K3'], 6) }}</td>
                            <td class="py-3 px-3 font-bold">{{ number_format($topsisData['idealPositive']['K4'], 6) }}</td>
                        </tr>
                        <tr class="bg-rose-50/50">
                            <td class="py-3 px-4 font-bold text-rose-800 text-left font-sans">
                                Solusi Ideal Negatif ($A^-$)
                            </td>
                            <td class="py-3 px-3 font-bold">{{ number_format($topsisData['idealNegative']['K1'], 6) }}</td>
                            <td class="py-3 px-3 font-bold">{{ number_format($topsisData['idealNegative']['K2'], 6) }}</td>
                            <td class="py-3 px-3 font-bold">{{ number_format($topsisData['idealNegative']['K3'], 6) }}</td>
                            <td class="py-3 px-3 font-bold">{{ number_format($topsisData['idealNegative']['K4'], 6) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 5. JARAK SOLUSI IDEAL POSITIF (D+) DAN NEGATIF (D-) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">5. Jarak Solusi Ideal Positif ($D^+$) dan Solusi Ideal Negatif ($D^-$)</h3>
                <span class="text-xs text-slate-400 font-medium">Jarak Euclidean</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center text-xs sm:text-sm border-collapse font-mono">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 font-sans">
                            <th class="py-3 px-4 text-left">ALTERNATIF</th>
                            <th class="py-3 px-3">Jarak Ideal Positif ($D^+$)</th>
                            <th class="py-3 px-3">Jarak Ideal Negatif ($D^-$)</th>
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
        </div>

        <!-- 6. NILAI PREFERENSI (V) DAN PERINGKAT -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800">6. Nilai Preferensi ($V_i$) dan Peringkat Prioritas</h3>
                <span class="text-xs text-slate-400 font-medium">Formula: $V_i = \frac{D_i^-}{D_i^+ + D_i^-}$</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-center text-xs sm:text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                            <th class="py-3 px-3">Peringkat</th>
                            <th class="py-3 px-4 text-left">ALTERNATIF</th>
                            <th class="py-3 px-3">Nilai Preferensi ($V_i$)</th>
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
        </div>

    @endif
</div>
