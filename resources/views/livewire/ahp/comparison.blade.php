<div class="space-y-8 relative">
    <!-- Centered Modal Alert Notification (Muncul di Tengah Konten Utama, Bukan Dari Sidebar) -->
    @if($notification)
        <div class="fixed inset-0 left-0 md:left-64 bg-slate-900/40 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="animate-modal-pop bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border {{ $notification['type'] === 'success' ? 'border-emerald-100 shadow-emerald-950/15' : 'border-red-100 shadow-red-950/15' }} space-y-5 text-center relative">
                <!-- Close (X) button in top-right corner -->
                <button type="button" 
                        wire:click="dismissNotification" 
                        class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition"
                        title="Tutup">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Icon in Center -->
                <div class="mx-auto w-16 h-16 rounded-2xl {{ $notification['type'] === 'success' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100' }} flex items-center justify-center shadow-inner">
                    @if($notification['type'] === 'success')
                        <svg class="w-9 h-9 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        <svg class="w-9 h-9 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    @endif
                </div>

                <!-- Text Content -->
                <div class="space-y-2">
                    <h3 class="text-lg font-bold {{ $notification['type'] === 'success' ? 'text-slate-900' : 'text-red-900' }}">
                        {{ $notification['title'] }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed px-2">
                        {{ $notification['message'] }}
                    </p>
                </div>

                <!-- Action Button at Bottom -->
                <div class="pt-2">
                    <button type="button" 
                            wire:click="dismissNotification" 
                            class="w-full py-2.5 px-4 rounded-xl {{ $notification['type'] === 'success' ? 'bg-[#085a3c] hover:bg-[#064830]' : 'bg-red-600 hover:bg-red-700' }} text-white text-xs font-bold shadow-md transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Header Title -->
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Perhitungan Analytical Hierarchy Process (AHP)</h2>
        <p class="text-xs text-slate-500 mt-0.5">Hitung bobot kriteria dan uji konsistensi dengan metode AHP.</p>
    </div>

    <!-- 1. MANAJEMEN KRITERIA -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-800">Manajemen Kriteria</h3>
            <a href="{{ route('kriteria.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[#085a3c] hover:bg-[#064830] text-white text-xs font-semibold shadow-xs transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Kelola Kriteria</span>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach($criteria as $c)
                <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70 flex items-center">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-white border border-slate-200 rounded text-xs font-bold text-slate-700 shadow-2xs">{{ $c->code }}</span>
                        <span class="text-xs font-bold text-slate-800">{{ $c->name }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 2. PERBANDINGAN ANTAR KRITERIA -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-6">
        <div>
            <h3 class="text-sm font-bold text-slate-800 mb-1">Perbandingan Antar Kriteria</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Bandingkan tingkat kepentingan relatif masing-masing kriteria terhadap kriteria lainnya menggunakan skala Saaty (1–9).
            </p>
        </div>

        <!-- Saaty Scale Legend -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-600 grid grid-cols-1 sm:grid-cols-2 gap-2">
            <div><strong>1:</strong> Sama penting.</div>
            <div><strong>3:</strong> Sedikit lebih penting.</div>
            <div><strong>5:</strong> Jelas lebih penting.</div>
            <div><strong>7:</strong> Sangat jelas penting.</div>
            <div><strong>9:</strong> Mutlak lebih penting.</div>
            <div><strong>2, 4, 6, 8:</strong> Apabila ragu antara 2 nilai yang berdekatan.</div>
        </div>

        <!-- Pairwise Sliders / Selectors -->
        <div class="space-y-6 pt-2">
            @php $n = $criteria->count(); @endphp
            @for($i = 0; $i < $n; $i++)
                @for($j = $i + 1; $j < $n; $j++)
                    @php
                        $c1 = $criteria[$i];
                        $c2 = $criteria[$j];
                        $key = $c1->id . '_' . $c2->id;
                        $currentScale = (int)($pairwise[$key]['scale'] ?? 1);
                        $scaleLabels = [
                            1 => 'Sama Penting',
                            2 => 'Mendekati Sedikit Lebih Penting',
                            3 => 'Sedikit Lebih Penting',
                            4 => 'Mendekati Jelas Lebih Penting',
                            5 => 'Jelas Lebih Penting',
                            6 => 'Mendekati Sangat Jelas Penting',
                            7 => 'Sangat Jelas Penting',
                            8 => 'Mendekati Mutlak Lebih Penting',
                            9 => 'Mutlak Lebih Penting',
                        ];
                        $currentLabel = $scaleLabels[$currentScale] ?? 'Lebih Penting';
                    @endphp

                    <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/40 hover:bg-slate-50 transition space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <!-- Left Criterion Button -->
                            <button type="button" 
                                    wire:click="$set('pairwise.{{ $key }}.dominant', {{ $c1->id }})"
                                    class="flex-1 px-4 py-2.5 rounded-xl border text-xs font-bold transition text-left flex items-center justify-between gap-2 {{ ($pairwise[$key]['dominant'] ?? null) == $c1->id ? 'bg-emerald-800 text-white border-emerald-800 shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                                <span>{{ $c1->code }} - {{ $c1->name }}</span>
                                @if(($pairwise[$key]['dominant'] ?? null) == $c1->id)
                                    <span class="text-xs bg-emerald-700/60 px-2 py-0.5 rounded font-medium shrink-0 whitespace-nowrap">{{ $currentLabel }}</span>
                                @endif
                            </button>

                            <!-- Scale Selector (1-9) -->
                            <div class="flex items-center justify-center gap-1.5 shrink-0 px-2">
                                <span class="text-xs font-semibold text-slate-400 mr-1">Skala:</span>
                                @foreach([1, 2, 3, 4, 5, 6, 7, 8, 9] as $scaleVal)
                                    <button type="button"
                                            wire:click="$set('pairwise.{{ $key }}.scale', {{ $scaleVal }})"
                                            class="w-7 h-7 rounded-lg text-xs font-bold transition flex items-center justify-center {{ $currentScale == $scaleVal ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                                        {{ $scaleVal }}
                                    </button>
                                @endforeach
                            </div>

                            <!-- Right Criterion Button -->
                            <button type="button" 
                                    wire:click="$set('pairwise.{{ $key }}.dominant', {{ $c2->id }})"
                                    class="flex-1 px-4 py-2.5 rounded-xl border text-xs font-bold transition text-right flex items-center justify-between gap-2 {{ ($pairwise[$key]['dominant'] ?? null) == $c2->id ? 'bg-emerald-800 text-white border-emerald-800 shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                                @if(($pairwise[$key]['dominant'] ?? null) == $c2->id)
                                    <span class="text-xs bg-emerald-700/60 px-2 py-0.5 rounded font-medium shrink-0 whitespace-nowrap">{{ $currentLabel }}</span>
                                @else
                                    <span></span>
                                @endif
                                <span>{{ $c2->code }} - {{ $c2->name }}</span>
                            </button>
                        </div>
                    </div>
                @endfor
            @endfor
        </div>

        <!-- Calculate Action -->
        <div class="flex justify-center pt-3">
            <button type="button" 
                    wire:click="calculate"
                    wire:loading.attr="disabled"
                    wire:target="calculate"
                    class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-[#085a3c] hover:bg-[#064830] disabled:opacity-60 disabled:cursor-not-allowed text-white text-xs font-bold shadow-md transition">
                <span wire:loading.remove wire:target="calculate" class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    <span>Hitung AHP</span>
                </span>
                <span wire:loading.inline-flex wire:target="calculate" class="items-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span>Sedang Menghitung AHP...</span>
                </span>
            </button>
        </div>
    </div>

    <!-- 3. HASIL PEMBOBOTAN & UJI KONSISTENSI -->
    @php
        $activeCalc = $calculationResult ? null : $savedCalculation;
    @endphp

    @if($calculationResult || $activeCalc)
        @php
            $crVal = $calculationResult ? $calculationResult['cr'] : (float)$activeCalc->cr;
            $isCons = $calculationResult ? $calculationResult['isConsistent'] : (bool)$activeCalc->is_consistent;
            $lambdaVal = $calculationResult ? $calculationResult['lambdaMax'] : (float)$activeCalc->lambda_max;
            $ciVal = $calculationResult ? $calculationResult['ci'] : (float)$activeCalc->ci;
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-in fade-in duration-200">
            <!-- Left 2 Cols: Bobot Prioritas Table -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3">Bobot Prioritas Kriteria</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                                <th class="py-2.5 px-3">Kriteria</th>
                                <th class="py-2.5 px-3">Eigen Vector</th>
                                <th class="py-2.5 px-3">Bobot (%)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @if($calculationResult)
                                @foreach($criteria as $c)
                                    @php
                                        $eigen = $calculationResult['weights'][$c->id] ?? 0;
                                        $pct = $calculationResult['weightPercentages'][$c->id] ?? 0;
                                    @endphp
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="py-2.5 px-3 font-semibold text-slate-800">
                                            <span class="px-1.5 py-0.5 rounded bg-slate-100 font-bold mr-1">{{ $c->code }}</span>
                                            {{ $c->name }}
                                        </td>
                                        <td class="py-2.5 px-3 font-mono font-bold text-slate-800">
                                            {{ number_format($eigen, 4) }}
                                        </td>
                                        <td class="py-2.5 px-3">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-1 bg-slate-100 h-2 rounded-full overflow-hidden max-w-xs">
                                                    <div class="bg-emerald-700 h-full rounded-full" style="width: {{ min(100, $pct) }}%"></div>
                                                </div>
                                                <span class="font-bold text-slate-700 w-12 text-right">{{ $pct }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif($activeCalc)
                                @foreach($activeCalc->weights as $w)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="py-2.5 px-3 font-semibold text-slate-800">
                                            <span class="px-1.5 py-0.5 rounded bg-slate-100 font-bold mr-1">{{ $w->criterion->code }}</span>
                                            {{ $w->criterion->name }}
                                        </td>
                                        <td class="py-2.5 px-3 font-mono font-bold text-slate-800">
                                            {{ number_format($w->eigen_vector, 4) }}
                                        </td>
                                        <td class="py-2.5 px-3">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-1 bg-slate-100 h-2 rounded-full overflow-hidden max-w-xs">
                                                    <div class="bg-emerald-700 h-full rounded-full" style="width: {{ min(100, $w->weight_percentage) }}%"></div>
                                                </div>
                                                <span class="font-bold text-slate-700 w-12 text-right">{{ $w->weight_percentage }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right 1 Col: Consistency Card matching Gambar 1 & Gambar 2 -->
            <div class="rounded-3xl p-6 sm:p-8 shadow-sm flex flex-col items-center justify-center text-center space-y-4 {{ $isCons ? 'bg-[#04683d]' : 'bg-[#b31d1d]' }}">
                <!-- Icon Circle Badge -->
                <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-xs mx-auto shrink-0">
                    @if($isCons)
                        <svg class="w-9 h-9 text-[#04683d]" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        <svg class="w-9 h-9 text-[#b31d1d]" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    @endif
                </div>

                <!-- Title -->
                <span class="text-sm sm:text-base font-bold text-white tracking-wide block">
                    Consistency Ratio (CR)
                </span>

                <!-- Big Value -->
                <div class="text-5xl sm:text-6xl font-black text-white tracking-tight my-1">
                    {{ number_format($crVal, 4) }}
                </div>

                <!-- Criteria Condition & Description -->
                <div class="space-y-1.5">
                    <div class="text-sm sm:text-base font-bold text-white">
                        {{ $isCons ? 'Nilai CR ≤ 0.10' : 'Nilai CR > 0.10' }}
                    </div>
                    <p class="text-xs sm:text-sm text-white/95 leading-relaxed max-w-xs mx-auto">
                        {{ $isCons ? 'Perbandingan kriteria dinyatakan konsisten dan dapat diterima.' : 'Perbandingan kriteria perlu diperiksa kembali.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Footer -->
        <div class="flex flex-wrap items-center justify-between gap-4 pt-2">
            <a href="{{ route('ahp.detail') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-semibold shadow-xs transition">
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <span>Lihat Detail Perhitungan</span>
            </a>

            <div class="flex items-center gap-3">
                <button type="button" wire:click="resetPairs" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border {{ $isCons ? 'border-slate-200 text-slate-600 hover:bg-slate-50' : 'border-red-300 text-red-600 hover:bg-red-50' }} bg-white text-xs font-semibold shadow-xs transition">
                    <svg class="w-4 h-4 {{ $isCons ? 'text-slate-400' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Reset</span>
                </button>

                @if($isCons)
                    <a href="{{ route('topsis.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#085a3c] hover:bg-[#064830] text-white text-xs font-semibold shadow-xs transition">
                        <span>Lanjut ke Perhitungan TOPSIS</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                @else
                    <button type="button" 
                            disabled 
                            title="Perbandingan kriteria perlu diperiksa kembali (CR > 0.10). Tidak dapat melanjutkan ke perhitungan TOPSIS."
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#b31d1d] text-white text-xs font-semibold shadow-xs cursor-not-allowed select-none transition">
                        <span>Lanjut Ke Perhitungan TOPSIS</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
