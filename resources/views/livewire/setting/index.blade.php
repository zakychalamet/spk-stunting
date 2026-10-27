<div class="max-w-4xl mx-auto space-y-6 relative">
    <!-- Centered Modal Alert Notification -->
    @if($notification)
        <div class="fixed inset-0 left-0 md:left-64 bg-slate-900/40 backdrop-blur-xs z-50 flex items-center justify-center p-4"
             wire:click.self="dismissNotification">
            <div class="animate-modal-pop bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border {{ $notification['type'] === 'success' ? 'border-emerald-100 shadow-emerald-950/15' : 'border-red-100 shadow-red-950/15' }} space-y-5 text-center relative">
                <!-- Close (X) button in top-right corner -->
                <button type="button" 
                        wire:click="dismissNotification" 
                        class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer"
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
                            class="w-full py-2.5 px-4 rounded-xl {{ $notification['type'] === 'success' ? 'bg-[#085a3c] hover:bg-[#064830]' : 'bg-red-600 hover:bg-red-700' }} text-white text-xs font-bold shadow-md transition cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Message Banner -->
    @if(session()->has('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                {{ auth()->user()->isAdmin() ? 'Pengaturan Akun & Sistem' : 'Pengaturan Profil Pengguna' }}
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ auth()->user()->isAdmin() 
                    ? 'Kelola biodata profil, foto akun, identitas layanan kesehatan, serta ambang batas prioritas TOPSIS.' 
                    : 'Perbarui biodata profil pribadi, foto akun, dan kata sandi Anda.' }}
            </p>
        </div>
    </div>

    <!-- 1. FORM BIODATA & FOTO PROFIL (UNTUK SEMUA ROLE: ADMIN, BIDAN, AHLI GIZI) -->
    <form wire:submit.prevent="saveProfile" class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Biodata &amp; Foto Profil
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Informasi akun pengguna yang dapat diubah secara mandiri.</p>
                </div>
            </div>

            <!-- Bagian Foto Profil Interaktif -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-center gap-5">
                <div class="relative shrink-0">
                    @if($photo)
                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview Foto" class="w-20 h-20 rounded-full object-cover shadow-md border-2 border-emerald-600">
                    @elseif($currentPhotoUrl)
                        <img src="{{ $currentPhotoUrl }}" alt="Foto Profil" class="w-20 h-20 rounded-full object-cover shadow-md border-2 border-emerald-600">
                    @else
                        <div class="w-20 h-20 rounded-full bg-emerald-700 text-white flex items-center justify-center font-bold text-2xl uppercase shadow-md border-2 border-emerald-800">
                            {{ substr($name ?: 'U', 0, 1) }}
                        </div>
                    @endif

                    <div wire:loading wire:target="photo" class="absolute inset-0 bg-slate-900/60 rounded-full flex items-center justify-center text-white">
                        <svg class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </div>
                </div>

                <div class="flex-1 text-center sm:text-left space-y-2">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                        <label class="px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs transition cursor-pointer flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Unggah Foto Baru</span>
                            <input type="file" wire:model="photo" accept="image/png,image/jpeg,image/webp" class="hidden">
                        </label>

                        @if($currentPhotoUrl || $photo)
                            <button type="button" wire:click="deletePhoto" wire:confirm="Apakah Anda yakin ingin menghapus foto profil ini?" class="px-3 py-2 rounded-xl bg-white hover:bg-rose-50 text-rose-600 hover:text-rose-700 border border-slate-200 text-xs font-medium transition cursor-pointer flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span>Hapus Foto</span>
                            </button>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400">Format yang didukung: JPG, PNG, atau WEBP. Maksimal ukuran file 2 MB.</p>
                    @error('photo') <p class="text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Input Fields Biodata -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="name" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                    @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Username <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="username" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                    @error('username') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" wire:model="email" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                    @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor Telepon / WhatsApp</label>
                    <input type="text" wire:model="phone" placeholder="Contoh: 081234567890" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                    @error('phone') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Bagian Ubah Kata Sandi (Akses ke Halaman Ubah Sandi) -->
            <div class="pt-4 border-t border-slate-100 space-y-3">
                <div>
                    <h4 class="text-xs font-bold text-slate-700">Keamanan &amp; Kata Sandi</h4>
                    <p class="text-xs text-slate-400">Ingin memperbarui kata sandi akun? Buka halaman khusus ubah sandi.</p>
                </div>
                <div>
                    <a href="{{ route('setting.password') }}" 
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition cursor-pointer border border-slate-200">
                        <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span>Ubah Kata Sandi</span>
                    </a>
                </div>
            </div>

            <!-- Tombol Simpan Biodata -->
            <div class="flex justify-end pt-2">
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs transition disabled:opacity-50 flex items-center gap-2 cursor-pointer">
                    <svg wire:loading wire:target="saveProfile" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Simpan Biodata &amp; Profil</span>
                </button>
            </div>
        </div>
    </form>

    <!-- 2. PENGATURAN SISTEM & TOPSIS (KHUSUS ROLE ADMIN) -->
    @if(auth()->user()->isAdmin())
        <form wire:submit.prevent="saveSystem" class="space-y-6">
            <!-- 2.1 IDENTITAS LAYANAN KESEHATAN -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Identitas Layanan Kesehatan
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Nama puskesmas atau instansi pelayanan ibu hamil dan desa binaan.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Puskesmas / Faskes</label>
                    <input type="text" wire:model="namaPuskesmas" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                    @error('namaPuskesmas') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Daftar Desa / Kelurahan Binaan (Pisahkan dengan koma)</label>
                    <textarea wire:model="desaListText" rows="3" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30"></textarea>
                    <p class="text-xs text-slate-400 mt-1">Daftar ini akan otomatis muncul pada opsi pilihan data ibu hamil dan filter.</p>
                </div>
            </div>

            <!-- 2.2 AMBANG BATAS PRIORITAS TOPSIS -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Ambang Batas Kategori Prioritas TOPSIS
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Atur batas nilai preferensi (V) untuk mengelompokkan kategori intervensi.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl border border-red-100 bg-red-50/40 space-y-2">
                        <label class="block text-xs font-bold text-red-800">Batas Minimal Prioritas Tinggi (V &ge;)</label>
                        <input type="number" step="0.01" min="0" max="1" wire:model="thresholdHigh" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-red-600/30">
                        <p class="text-xs text-slate-500">Default: 0.80. Pasien dengan V &ge; 0.80 akan dikategorikan Prioritas Tinggi.</p>
                        @error('thresholdHigh') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="p-4 rounded-xl border border-amber-100 bg-amber-50/40 space-y-2">
                        <label class="block text-xs font-bold text-amber-800">Batas Minimal Prioritas Sedang (V &ge;)</label>
                        <input type="number" step="0.01" min="0" max="1" wire:model="thresholdMedium" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-600/30">
                        <p class="text-xs text-slate-500">Default: 0.60. Pasien dengan 0.60 &le; V &lt; 0.80 akan dikategorikan Prioritas Sedang.</p>
                        @error('thresholdMedium') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl text-xs text-slate-500">
                    Nilai preferensi di bawah batas sedang (&lt; 0.60) secara otomatis dikategorikan sebagai <strong>Prioritas Rendah</strong>.
                </div>
            </div>

            <!-- Tombol Simpan Sistem Admin -->
            <div class="flex justify-end pt-2">
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="px-7 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs transition disabled:opacity-50 flex items-center gap-2 cursor-pointer">
                    <svg wire:loading wire:target="saveSystem, save" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    @endif
</div>
