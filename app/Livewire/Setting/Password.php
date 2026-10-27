<?php

namespace App\Livewire\Setting;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Password extends Component
{
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';

    public ?array $notification = null;

    public function dismissNotification()
    {
        $this->notification = null;
    }

    public function updatePassword()
    {
        $user = auth()->user();

        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|string|min:6|same:newPasswordConfirmation',
            'newPasswordConfirmation' => 'required',
        ], [
            'currentPassword.required' => 'Kata sandi saat ini wajib diisi.',
            'newPassword.required' => 'Kata sandi baru wajib diisi.',
            'newPassword.min' => 'Kata sandi baru minimal 6 karakter.',
            'newPassword.same' => 'Konfirmasi kata sandi baru tidak sesuai.',
            'newPasswordConfirmation.required' => 'Konfirmasi kata sandi baru wajib diisi.',
        ]);

        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'Kata sandi saat ini tidak cocok.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']);

        session()->flash('success', 'Kata sandi berhasil diperbarui.');
        $this->notification = [
            'type' => 'success',
            'title' => 'Kata Sandi Diperbarui!',
            'message' => 'Kata sandi akun Anda telah berhasil diubah. Gunakan kata sandi baru saat login kembali.',
        ];
    }

    public function render()
    {
        return view('livewire.setting.password')->layout('layouts.app', [
            'title' => 'Ubah Kata Sandi',
            'breadcrumb' => 'Pengaturan / Ubah Kata Sandi',
        ]);
    }
}
