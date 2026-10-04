<div class="space-y-6">
    <!-- Header Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Skala Penilaian Kriteria</h2>
            <p class="text-xs text-slate-500 mt-0.5">Parameter medis dan skor penilaian risiko stunting untuk metode AHP dan TOPSIS.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" 
                    wire:click="openAddCriterionModal" 
                    class="px-4 py-2.5 rounded-xl bg-[#085a3c] hover:bg-[#064830] text-white text-xs font-semibold shadow-sm transition flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Kriteria</span>
            </button>
        </div>
    </div>

    <!-- 4 Criteria Cards in 2x2 Grid matching PDF Page 16 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($criteria as $criterion)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden flex flex-col justify-between">
                <!-- Card Header -->
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-emerald-700 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                            {{ $criterion->code }}
                        </span>
                        <div>
                            <h3 class="font-bold text-sm text-slate-800">Kriteria: {{ $criterion->name }}</h3>
                            <p class="text-[11px] text-slate-400 capitalize">Sifat: {{ $criterion->type == 'benefit' ? 'Benefit (Prioritas Risiko)' : 'Cost' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <button type="button" 
                                wire:click="editCriterion({{ $criterion->id }})" 
                                title="Ubah Info Kriteria"
                                class="p-1.5 text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <button type="button" 
                                wire:click="deleteCriterion({{ $criterion->id }})"
                                wire:confirm="Apakah Anda yakin ingin menghapus kriteria {{ $criterion->name }} beserta seluruh skala penilaiannya?"
                                title="Hapus Kriteria"
                                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                        <button type="button" 
                                wire:click="openAddScaleModal({{ $criterion->id }})" 
                                title="Tambah Skala"
                                class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition border border-emerald-100">
                            <span>+ Skala</span>
                        </button>
                    </div>
                </div>

                <!-- Scales Table -->
                <div class="p-0 overflow-x-auto flex-1">
                    <table class="w-full text-left text-xs sm:text-sm border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 text-slate-500 font-semibold border-b border-slate-100">
                                <th class="py-2.5 px-4">Parameter</th>
                                <th class="py-2.5 px-3 text-center">Skor</th>
                                <th class="py-2.5 px-3">Keterangan</th>
                                <th class="py-2.5 px-3">Kategori</th>
                                <th class="py-2.5 px-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($criterion->scales as $scale)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="py-2.5 px-4 font-semibold text-slate-800">
                                        {{ $scale->parameter }}
                                    </td>
                                    <td class="py-2.5 px-3 text-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-100 text-slate-700 font-bold text-xs">
                                             {{ $scale->score }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 font-medium">
                                        {{ $scale->label }}
                                    </td>
                                    <td class="py-2.5 px-3">
                                        @php
                                            $catColor = match($scale->category) {
                                                'Tinggi' => 'bg-red-50 text-red-700 border-red-200',
                                                'Sedang' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'Rendah' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                                default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-md border text-xs font-semibold {{ $catColor }}">
                                            {{ $scale->category }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-center">
                                        <div class="inline-flex items-center gap-1">
                                            <button type="button" wire:click="editScale({{ $scale->id }})" class="p-1 text-slate-400 hover:text-blue-600 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                            <button type="button" wire:click="deleteScale({{ $scale->id }})" class="p-1 text-slate-400 hover:text-rose-600 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-slate-400 text-xs">Belum ada skala penilaian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Description -->
                @if($criterion->description)
                    <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-100 text-xs text-slate-500">
                        {{ $criterion->description }}
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Bottom Action: Lanjut ke Perhitungan Bobot AHP -->
    <div class="flex items-center justify-end pt-2">
        <a href="{{ route('ahp.index') }}" class="px-5 py-3 rounded-xl bg-[#085a3c] hover:bg-[#064830] text-white text-xs font-bold shadow-sm transition flex items-center gap-2">
            <span>Lanjut ke Perhitungan Bobot AHP</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>

    <!-- MODAL ADD/EDIT CRITERION -->
    @if($showCriterionModal)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="font-bold text-sm text-slate-800">{{ $editingCriterionId ? 'Ubah Informasi Kriteria' : 'Tambah Kriteria Baru' }}</h3>
                    <button type="button" wire:click="$set('showCriterionModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="space-y-3.5">
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kode</label>
                            <input type="text" wire:model="criterionCode" placeholder="Misal: K1" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30 font-bold uppercase">
                            @error('criterionCode') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Kriteria</label>
                            <input type="text" wire:model="criterionName" placeholder="Misal: Anemia" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                            @error('criterionName') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Sifat Kriteria</label>
                        <select wire:model="criterionType" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                            <option value="benefit">Benefit (Makin tinggi skor makin prioritas)</option>
                            <option value="cost">Cost (Makin rendah skor makin prioritas)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Kriteria</label>
                        <textarea wire:model="criterionDescription" rows="3" placeholder="Penjelasan mengenai kriteria..." class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t">
                    <button type="button" wire:click="$set('showCriterionModal', false)" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50">Batal</button>
                    <button type="button" wire:click="saveCriterion" class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold">Simpan</button>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL ADD/EDIT SCALE -->
    @if($showScaleModal)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="font-bold text-sm text-slate-800">{{ $editingScaleId ? 'Ubah Skala Penilaian' : 'Tambah Skala Penilaian' }}</h3>
                    <button type="button" wire:click="$set('showScaleModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="space-y-3.5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Parameter (Rentang Penilaian)</label>
                        <input type="text" wire:model="scaleParameter" placeholder="Misal: 10 - 10.9 atau < 23" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                        @error('scaleParameter') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Skor Risiko (1 - 4)</label>
                            <input type="number" min="1" max="9" wire:model="scaleScore" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                            @error('scaleScore') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                            <select wire:model="scaleCategory" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                                <option value="Normal">Normal</option>
                                <option value="Rendah">Rendah</option>
                                <option value="Sedang">Sedang</option>
                                <option value="Tinggi">Tinggi</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Keterangan Medis</label>
                        <input type="text" wire:model="scaleLabel" placeholder="Misal: Anemia Ringan, KEK, Usia Reproduksi Ideal" class="w-full text-xs px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                        @error('scaleLabel') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t">
                    <button type="button" wire:click="$set('showScaleModal', false)" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50">Batal</button>
                    <button type="button" wire:click="saveScale" class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold">Simpan Skala</button>
                </div>
            </div>
        </div>
    @endif
</div>
