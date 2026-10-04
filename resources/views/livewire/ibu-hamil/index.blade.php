<div class="space-y-6">
    <!-- TOP TOOLBAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Data Ibu Hamil</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola seluruh data ibu hamil untuk analisis risiko stunting.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Filter Button -->
            <button type="button" wire:click="toggleFilter" 
                    class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                <span>Filter Lanjutan</span>
                @if($filterAnemia || $filterDesa || $filterKehamilan)
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                @endif
            </button>

            <!-- Impor Excel -->
            <button type="button" wire:click="$set('showImportModal', true)"
                    class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                <span>Impor Excel</span>
            </button>

            <!-- Ekspor Excel -->
            <a href="{{ route('ibu-hamil.export') }}" target="_blank"
               class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span>Ekspor Excel</span>
            </a>

            <!-- Tambah Ibu Hamil -->
            <a href="{{ route('ibu-hamil.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#085a3c] hover:bg-[#064830] text-white text-xs font-semibold shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Ibu Hamil</span>
            </a>
        </div>
    </div>

    <!-- FILTER COLLAPSIBLE PANEL -->
    @if($showFilter)
        <div class="p-5 bg-white border border-slate-200 rounded-2xl shadow-xs space-y-4 animate-in fade-in duration-150">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Filter Lanjutan Data</span>
                <button type="button" wire:click="resetFilters" class="text-xs text-rose-600 hover:underline font-medium">Reset Semua Filter</button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status Anemia</label>
                    <select wire:model.live="filterAnemia" class="w-full text-xs rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                        <option value="">Semua Status Anemia</option>
                        <option value="normal">Normal (≥ 11)</option>
                        <option value="ringan">Ringan (10 - 10,9)</option>
                        <option value="sedang">Sedang (7 - 9,9)</option>
                        <option value="berat">Berat (&lt; 7)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Desa / Kelurahan</label>
                    <select wire:model.live="filterDesa" class="w-full text-xs rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                        <option value="">Semua Desa / Kelurahan</option>
                        @foreach($desaList as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status Kehamilan</label>
                    <select wire:model.live="filterKehamilan" class="w-full text-xs rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                        <option value="">Semua Status Kehamilan</option>
                        <option value="Trimester I">Trimester I</option>
                        <option value="Trimester II">Trimester II</option>
                        <option value="Trimester III">Trimester III</option>
                    </select>
                </div>
            </div>
        </div>
    @endif

    <!-- SEARCH & TABLE CARD -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <!-- Search Input Bar -->
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="relative w-full max-w-sm">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Cari nama, kode, nomor HP, atau desa..."
                       class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 bg-slate-50/70 focus:bg-white text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <div class="text-xs text-slate-500 font-medium">
                Total: <strong class="text-slate-700">{{ $items->total() }}</strong> Ibu Hamil
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-600 font-semibold border-b border-slate-200">
                        <th class="py-3.5 px-4">Nama Ibu Hamil</th>
                        <th class="py-3.5 px-3">Anemia</th>
                        <th class="py-3.5 px-3">LILA</th>
                        <th class="py-3.5 px-3">IMT</th>
                        <th class="py-3.5 px-3">Usia</th>
                        <th class="py-3.5 px-3">Tanggal HPHT</th>
                        <th class="py-3.5 px-3">Tanggal HPL</th>
                        <th class="py-3.5 px-3">Usia Kehamilan</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($items as $row)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 font-medium text-slate-900">
                                <div class="font-semibold text-slate-900">{{ $row->nama }}</div>
                                <div class="text-xs text-slate-400">{{ $row->kode_ibu_hamil ?? '-' }} • {{ $row->desa_kelurahan ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-3">
                                @php
                                    $statusText = $row->status_anemia_label;
                                    $badgeColor = match(true) {
                                        str_contains(strtolower($statusText), 'berat') => 'bg-red-50 text-red-700 border-red-200',
                                        str_contains(strtolower($statusText), 'sedang') => 'bg-amber-50 text-amber-700 border-amber-200',
                                        str_contains(strtolower($statusText), 'ringan') => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg border text-xs font-semibold {{ $badgeColor }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 font-semibold {{ $row->lila < 23 ? 'text-red-600' : 'text-slate-700' }}">
                                {{ $row->lila }} cm
                            </td>
                            <td class="py-3.5 px-3">
                                {{ number_format($row->imt, 2) }}
                            </td>
                            <td class="py-3.5 px-3">
                                {{ $row->usia }} thn
                            </td>
                            <td class="py-3.5 px-3 text-slate-500">
                                {{ $row->hpht ? $row->hpht->format('d/m/Y') : '-' }}
                            </td>
                            <td class="py-3.5 px-3 text-slate-500">
                                {{ $row->hpl ? $row->hpl->format('d/m/Y') : '-' }}
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="text-slate-700 font-medium">{{ $row->usia_kehamilan_minggu }} minggu</span>
                                <div class="text-xs text-slate-400">{{ $row->status_kehamilan ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- View Detail -->
                                    <a href="{{ route('ibu-hamil.show', $row->id) }}" 
                                       title="Lihat Detail"
                                       class="p-1.5 text-slate-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>

                                    <!-- Edit -->
                                    <a href="{{ route('ibu-hamil.edit', $row->id) }}" 
                                       title="Edit Data"
                                       class="p-1.5 text-slate-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    <!-- Delete -->
                                    <button type="button" 
                                            wire:click="confirmDelete({{ $row->id }}, '{{ addslashes($row->nama) }}')" 
                                            title="Hapus Data"
                                            class="p-1.5 text-slate-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Tidak ada data ibu hamil yang sesuai dengan kriteria pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Summary -->
        <div class="p-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="text-xs text-slate-500 font-medium">
                @if($items->total() > 0)
                    Menampilkan {{ $items->firstItem() }} sampai {{ $items->lastItem() }} dari {{ $items->total() }} data
                @else
                    Menampilkan 0 data
                @endif
            </div>
            <div>
                {{ $items->links() }}
            </div>
        </div>
    </div>

    <!-- DELETE CONFIRMATION MODAL -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl space-y-4 animate-in fade-in zoom-in-95 duration-150">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="text-center space-y-1">
                    <h3 class="font-bold text-base text-slate-800">Konfirmasi Hapus</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Apakah Anda yakin ingin menghapus data <strong>{{ $deleteName }}</strong>? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" wire:click="$set('showDeleteModal', false)" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="button" wire:click="delete" class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold shadow-xs transition">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- IMPORT MODAL -->
    @if($showImportModal)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="font-bold text-base text-slate-800">Impor Data Ibu Hamil</h3>
                    <button type="button" wire:click="$set('showImportModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="space-y-3">
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Unggah file CSV atau Excel yang berisi data ibu hamil. Anda dapat mengunduh format template di bawah.
                    </p>

                    <a href="{{ route('ibu-hamil.template') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-700 hover:underline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Unduh Format Template CSV</span>
                    </a>

                    <div class="pt-2">
                        <input type="file" wire:model="importFile" accept=".csv, .xlsx, .xls" class="w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        @error('importFile') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    @if($importMessage)
                        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl">
                            {{ $importMessage }}
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t">
                    <button type="button" wire:click="$set('showImportModal', false)" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                        Batal
                    </button>
                    <button type="button" wire:click="importExcel" wire:loading.attr="disabled" class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs transition">
                        <span wire:loading.remove wire:target="importExcel">Mulai Impor</span>
                        <span wire:loading wire:target="importExcel">Mengunggah...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
