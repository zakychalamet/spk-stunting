<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Manajemen Pengguna</h2>
            <p class="text-xs text-slate-500 mt-0.5">Kelola akses dan hak akses staf administratif serta klinis.</p>
        </div>

        <button type="button" 
                wire:click="openCreateModal"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#085a3c] hover:bg-[#064830] text-white text-xs font-semibold shadow-xs transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Pengguna</span>
        </button>
    </div>

    <!-- Table Card matching PDF page 18 -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">Daftar Pengguna</h3>
            <span class="text-xs text-slate-400 font-medium">Total: {{ $users->count() }} Pengguna</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-100">
                        <th class="py-3.5 px-4">Pengguna</th>
                        <th class="py-3.5 px-4">Peran</th>
                        <th class="py-3.5 px-4">Email</th>
                        <th class="py-3.5 px-4">Terakhir Login</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-4 font-semibold text-slate-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs">
                                        {{ substr($u->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $u->name }}</div>
                                        <div class="text-xs text-slate-400 font-normal">@<span>{{ $u->username }}</span></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                @php
                                    $roleBadge = match($u->role) {
                                        'admin', 'administrator' => 'bg-cyan-50 text-cyan-800 border-cyan-200',
                                        'bidan' => 'bg-sky-50 text-sky-800 border-sky-200',
                                        'ahli_gizi' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        default => 'bg-slate-50 text-slate-700 border-slate-200',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full border text-xs font-semibold {{ $roleBadge }}">
                                    {{ $u->role_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 font-mono text-xs sm:text-sm">
                                {{ $u->email }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $u->last_login_at ? $u->last_login_at->diffForHumans() : 'Belum pernah login' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <button type="button" wire:click="editUser({{ $u->id }})" class="p-1.5 text-slate-400 hover:text-blue-600 transition" title="Edit Pengguna">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    @if(auth()->id() !== $u->id)
                                        <button type="button" wire:click="confirmDelete({{ $u->id }}, '{{ addslashes($u->name) }}')" class="p-1.5 text-slate-400 hover:text-rose-600 transition" title="Hapus Pengguna">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400">Tidak ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-slate-100 text-xs text-slate-500">
            Menampilkan 1-{{ $users->count() }} dari {{ $users->count() }} pengguna
        </div>
    </div>

    <!-- MODAL USER -->
    @if($showUserModal)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between border-b pb-3">
                    <h3 class="font-bold text-sm text-slate-800">{{ $editingUserId ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}</h3>
                    <button type="button" wire:click="$set('showUserModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="space-y-3.5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" wire:model="name" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                        @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Username</label>
                        <input type="text" wire:model="username" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                        @error('username') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                        <input type="email" wire:model="email" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                        @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Peran / Role</label>
                        <select wire:model="role" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                            <option value="admin">Administrator (Admin Puskesmas)</option>
                            <option value="bidan">Bidan (Bidan Kesehatan)</option>
                            <option value="ahli_gizi">Ahli Gizi</option>
                        </select>
                        @error('role') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Kata Sandi {{ $editingUserId ? '(Kosongkan jika tidak ingin mengubah)' : '' }}
                        </label>
                        <input type="password" wire:model="password" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                        @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t">
                    <button type="button" wire:click="$set('showUserModal', false)" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50">Batal</button>
                    <button type="button" wire:click="saveUser" class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold">Simpan Pengguna</button>
                </div>
            </div>
        </div>
    @endif

    <!-- DELETE CONFIRM MODAL -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="text-center space-y-1">
                    <h3 class="font-bold text-base text-slate-800">Hapus Pengguna</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Yakin ingin menghapus pengguna <strong>{{ $deleteUserName }}</strong>?
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" wire:click="$set('showDeleteModal', false)" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50">Batal</button>
                    <button type="button" wire:click="deleteUser" class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>
