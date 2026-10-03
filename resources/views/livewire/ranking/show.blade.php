@php
    $ibu = $topsisResult->ibuHamil;
@endphp

<div class="max-w-5xl mx-auto space-y-6">
    <!-- Top Header Banner matching PDF page 15 -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $ibu->nama ?? 'Pasien' }}</h2>
                <a href="{{ route('ibu-hamil.show', $ibu->id) }}" class="text-xs text-emerald-700 hover:underline font-semibold">(Lihat Profil Lengkap)</a>
            </div>
            <p class="text-xs text-slate-500 mt-1 font-medium">
                Usia: {{ $ibu->usia ?? '-' }} | ID: {{ $ibu->kode_ibu_hamil ?? '-' }} | Desa: {{ $ibu->desa_kelurahan ?? '-' }}
            </p>
        </div>

        <div>
            @php
                $badgeStyle = match($topsisResult->priority) {
                    'Tinggi' => 'bg-red-600 text-white shadow-xs',
                    'Sedang' => 'bg-amber-500 text-white shadow-xs',
                    default => 'bg-emerald-600 text-white shadow-xs',
                };
            @endphp
            <span class="px-5 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider {{ $badgeStyle }}">
                PRIORITAS {{ strtoupper($topsisResult->priority) }}
            </span>
        </div>
    </div>

    <!-- 3 Metrics Cards in a Row matching PDF page 15 -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Card 1: Peringkat -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Peringkat</span>
                <div class="text-2xl font-black text-slate-800">#{{ $topsisResult->rank }}</div>
                <span class="text-[11px] text-slate-400">dari {{ $totalPatients }} Ibu Hamil</span>
            </div>
        </div>

        <!-- Card 2: Skor TOPSIS -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Skor TOPSIS ($V_i$)</span>
                <div class="text-2xl font-black text-slate-800 font-mono">{{ number_format($topsisResult->preference_score, 3) }}</div>
                <span class="text-[11px] text-slate-400">Nilai preferensi kedekatan</span>
            </div>
        </div>

        <!-- Card 3: Faktor Risiko Utama -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Faktor Risiko Utama</span>
                <div class="text-sm font-bold text-slate-800 line-clamp-1">
                    {{ $topsisResult->main_risk_factors ?: 'Normal / Risiko Rendah' }}
                </div>
                <span class="text-[11px] text-slate-400">Parameter klinis dominan</span>
            </div>
        </div>
    </div>

    <!-- 2 Cards Split at Bottom matching PDF page 15 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Left: Nilai Kriteria -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between space-y-4">
            <div>
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3">Nilai Kriteria Pasien</h3>

                <div class="overflow-x-auto mt-2">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-100">
                                <th class="py-2.5 px-3">Kriteria</th>
                                <th class="py-2.5 px-3">Nilai Aktual</th>
                                <th class="py-2.5 px-3 text-center">Skor</th>
                                <th class="py-2.5 px-3 text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <!-- Anemia -->
                            <tr>
                                <td class="py-3 px-3 font-semibold text-slate-800">Anemia</td>
                                <td class="py-3 px-3 font-medium">{{ $ibu->kadar_hb ? $ibu->kadar_hb . ' g/dL' : ($ibu->status_anemia ?? '-') }}</td>
                                <td class="py-3 px-3 text-center font-bold font-mono">{{ $topsisResult->score_anemia }}</td>
                                <td class="py-3 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $topsisResult->score_anemia >= 3 ? 'bg-red-50 text-red-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $topsisResult->score_anemia >= 4 ? 'Tinggi' : ($topsisResult->score_anemia >= 3 ? 'Sedang' : ($topsisResult->score_anemia >= 2 ? 'Rendah' : 'Normal')) }}
                                    </span>
                                </td>
                            </tr>

                            <!-- IMT -->
                            <tr>
                                <td class="py-3 px-3 font-semibold text-slate-800">IMT</td>
                                <td class="py-3 px-3 font-medium">{{ number_format($ibu->imt, 2) }}</td>
                                <td class="py-3 px-3 text-center font-bold font-mono">{{ $topsisResult->score_imt }}</td>
                                <td class="py-3 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $topsisResult->score_imt >= 3 ? 'bg-red-50 text-red-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $topsisResult->score_imt >= 4 ? 'Tinggi' : ($topsisResult->score_imt >= 3 ? 'Sedang' : ($topsisResult->score_imt >= 2 ? 'Rendah' : 'Normal')) }}
                                    </span>
                                </td>
                            </tr>

                            <!-- LILA -->
                            <tr>
                                <td class="py-3 px-3 font-semibold text-slate-800">LILA</td>
                                <td class="py-3 px-3 font-medium">{{ $ibu->lila }} cm</td>
                                <td class="py-3 px-3 text-center font-bold font-mono">{{ $topsisResult->score_lila }}</td>
                                <td class="py-3 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $topsisResult->score_lila >= 4 ? 'bg-red-50 text-red-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $topsisResult->score_lila >= 4 ? 'Tinggi' : 'Normal' }}
                                    </span>
                                </td>
                            </tr>

                            <!-- Usia -->
                            <tr>
                                <td class="py-3 px-3 font-semibold text-slate-800">Usia</td>
                                <td class="py-3 px-3 font-medium">{{ $ibu->usia }} tahun</td>
                                <td class="py-3 px-3 text-center font-bold font-mono">{{ $topsisResult->score_usia }}</td>
                                <td class="py-3 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $topsisResult->score_usia >= 4 ? 'bg-red-50 text-red-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $topsisResult->score_usia >= 4 ? 'Tinggi' : 'Normal' }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Score Legend matching PDF page 15 -->
            <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center gap-3 text-[11px] text-slate-500">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span> Skor 1 = Normal</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Skor 2 = Risiko Rendah</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Skor 3 = Risiko Sedang</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Skor 4 = Risiko Tinggi</span>
            </div>
        </div>

        <!-- Right: Analisis Kedekatan Solusi matching PDF page 15 -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between space-y-4">
            <div>
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3">Analisis Kedekatan Solusi</h3>
                
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Matriks kedekatan ini menunjukkan seberapa dekat profil pasien terhadap skenario terburuk (ideal negatif) dan skenario risiko tertinggi (ideal positif).
                </p>

                <!-- D+ Metric -->
                <div class="mt-4 p-3.5 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-700 block">JARAK KE SOLUSI IDEAL POSITIF ($D^+$)</span>
                        <span class="text-[10px] text-slate-400 font-medium">Mendekati $D^+$ (kecil) berarti risiko tinggi</span>
                    </div>
                    <span class="text-base font-extrabold text-slate-800 font-mono">
                        {{ number_format($topsisResult->d_plus, 4) }}
                    </span>
                </div>

                <!-- D- Metric -->
                <div class="mt-2.5 p-3.5 rounded-xl border border-slate-100 bg-slate-50 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-700 block">JARAK KE SOLUSI IDEAL NEGATIF ($D^-$)</span>
                        <span class="text-[10px] text-slate-400 font-medium">Menjauhi $D^-$ (besar) berarti risiko tinggi</span>
                    </div>
                    <span class="text-base font-extrabold text-slate-800 font-mono">
                        {{ number_format($topsisResult->d_minus, 4) }}
                    </span>
                </div>
            </div>

            <!-- Callout conclusion box matching PDF page 15 -->
            <div class="p-4 rounded-xl {{ $topsisResult->priority === 'Tinggi' ? 'bg-red-50 border border-red-100 text-red-900' : ($topsisResult->priority === 'Sedang' ? 'bg-amber-50 border border-amber-100 text-amber-900' : 'bg-emerald-50 border border-emerald-100 text-emerald-900') }} text-xs leading-relaxed">
                Berdasarkan nilai $V$ (Preferensi) sebesar <strong>{{ number_format($topsisResult->preference_score, 3) }}</strong>, pasien ini teridentifikasi sebagai <strong>Prioritas {{ $topsisResult->priority }}</strong> untuk intervensi penanganan stunting.
                @if($topsisResult->recommendation)
                    <div class="mt-1 font-semibold">Rekomendasi Tindakan: {{ $topsisResult->recommendation }}.</div>
                @endif
            </div>
        </div>

    </div>

    <!-- Back Button -->
    <div class="flex justify-end pt-2">
        <a href="{{ route('ranking.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition">
            &larr; Kembali ke Daftar Perangkingan
        </a>
    </div>
</div>
