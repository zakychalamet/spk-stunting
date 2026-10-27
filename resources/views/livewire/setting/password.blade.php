<div class="max-w-xl mx-auto space-y-6 relative">
    <!-- Centered Modal Alert Notification -->
    @if($notification)
        <div class="fixed inset-0 left-0 md:left-64 bg-slate-900/40 backdrop-blur-xs z-50 flex items-center justify-center p-4"
             wire:click.self="dismissNotification">
            <div class="animate-modal-pop bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border {{ $notification['type'] === 'success' ? 'border-emerald-100 shadow-emerald-950/15' : 'border-red-100 shadow-red-950/15' }} space-y-5 text-center relative">
                <button type="button" 
                        wire:click="dismissNotification" 
                        class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer"
                        title="Tutup">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

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

                <div class="space-y-2">
                    <h3 class="text-lg font-bold {{ $notification['type'] === 'success' ? 'text-slate-900' : 'text-red-900' }}">
                        {{ $notification['title'] }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed px-2">
                        {{ $notification['message'] }}
                    </p>
                </div>

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

    <!-- Header Page & Back Button -->
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Ubah Kata Sandi</h2>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui kata sandi akun Anda untuk menjaga keamanan akses sistem.</p>
        </div>
        <a href="{{ route('setting.index') }}" 
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form Ubah Kata Sandi -->
    <form wire:submit.prevent="updatePassword" class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-5">
            <div class="border-b border-slate-100 pb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <h3 class="text-sm font-bold text-slate-800">Form Kata Sandi Baru</h3>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi Saat Ini <span class="text-rose-500">*</span></label>
                <input type="password" wire:model="currentPassword" placeholder="Masukkan kata sandi saat ini" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                @error('currentPassword') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi Baru <span class="text-rose-500">*</span></label>
                <input type="password" wire:model="newPassword" placeholder="Minimal 6 karakter" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                @error('newPassword') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span></label>
                <input type="password" wire:model="newPasswordConfirmation" placeholder="Ulangi kata sandi baru" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                @error('newPasswordConfirmation') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                <a href="{{ route('setting.index') }}" class="text-xs font-medium text-slate-500 hover:text-slate-800 transition">
                    Batal
                </a>
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-xs transition disabled:opacity-50 flex items-center gap-2 cursor-pointer">
                    <svg wire:loading wire:target="updatePassword" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Simpan Kata Sandi</span>
                </button>
            </div>
        </div>
    </form>
</div>
