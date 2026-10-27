<?php

namespace App\Livewire\Setting;

use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    // Biodata Pengguna (Untuk Semua Role)
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public ?string $phone = '';
    public $photo = null;
    public ?string $currentPhotoUrl = null;

    // Pengaturan Sistem Khusus Admin
    public string $namaPuskesmas = '';
    public float $thresholdHigh = 0.80;
    public float $thresholdMedium = 0.60;
    public string $desaListText = '';

    public ?array $notification = null;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name ?? '';
        $this->username = $user->username ?? '';
        $this->email = $user->email ?? '';
        $this->phone = $user->phone ?? '';
        $this->currentPhotoUrl = $user->profile_photo_url;

        // Hanya load konfigurasi sistem jika role adalah Admin
        if ($user->isAdmin()) {
            $this->namaPuskesmas = Setting::get('nama_puskesmas', 'Puskesmas Kecamatan Taman Sari');
            $this->thresholdHigh = (float) Setting::get('threshold_high', '0.80');
            $this->thresholdMedium = (float) Setting::get('threshold_medium', '0.60');

            $desaArray = json_decode(Setting::get('desa_list', '[]'), true) ?: [
                'Parakancanggah', 'Cendana', 'Krandegan', 'Semarang', 'Sokayasa', 'Sokanandi'
            ];
            $this->desaListText = implode(', ', $desaArray);
        }
    }

    public function dismissNotification()
    {
        $this->notification = null;
    }

    public function deletePhoto()
    {
        $user = auth()->user();
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->update(['profile_photo_path' => null]);
        $this->currentPhotoUrl = null;
        $this->photo = null;

        session()->flash('success', 'Foto profil berhasil dihapus.');
        $this->notification = [
            'type' => 'success',
            'title' => 'Foto Profil Dihapus',
            'message' => 'Foto profil Anda telah dikembalikan ke avatar inisial default.',
        ];
    }

    public function saveProfile()
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:25',
            'photo' => 'nullable|image|max:2048', // Maksimal 2MB
        ];

        $this->validate($rules, [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Alamat email sudah terdaftar di sistem.',
            'photo.image' => 'File foto profil harus berupa gambar (JPG, PNG, WEBP).',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $user->name = $this->name;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->phone = $this->phone;

        // Upload foto baru jika ada
        if ($this->photo) {
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $this->photo->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
            $this->currentPhotoUrl = asset('storage/' . $path);
            $this->photo = null;
        }

        $user->save();

        session()->flash('success', 'Biodata profil berhasil diperbarui.');
        $this->notification = [
            'type' => 'success',
            'title' => 'Biodata Berhasil Disimpan!',
            'message' => 'Informasi profil dan foto akun Anda telah berhasil diperbarui di sistem.',
        ];
    }

    public function save()
    {
        return $this->saveSystem();
    }

    public function saveSystem()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Administrator yang memiliki akses mengubah pengaturan sistem.');
        }

        $this->validate([
            'namaPuskesmas' => 'required|string|max:255',
            'thresholdHigh' => 'required|numeric|min:0|max:1',
            'thresholdMedium' => 'required|numeric|min:0|max:1',
        ], [
            'namaPuskesmas.required' => 'Nama puskesmas wajib diisi.',
            'thresholdHigh.required' => 'Ambang batas prioritas tinggi wajib diisi.',
            'thresholdMedium.required' => 'Ambang batas prioritas sedang wajib diisi.',
        ]);

        Setting::set('nama_puskesmas', $this->namaPuskesmas);
        Setting::set('threshold_high', (string) $this->thresholdHigh);
        Setting::set('threshold_medium', (string) $this->thresholdMedium);

        // Process desa list
        $desaArray = array_values(array_filter(array_map('trim', explode(',', $this->desaListText))));
        Setting::set('desa_list', json_encode($desaArray));

        session()->flash('success', 'Pengaturan sistem berhasil disimpan.');
        $this->notification = [
            'type' => 'success',
            'title' => 'Pengaturan Berhasil Disimpan!',
            'message' => 'Perubahan identitas layanan kesehatan dan ambang batas prioritas TOPSIS telah berhasil disimpan.',
        ];
    }

    public function render()
    {
        return view('livewire.setting.index')->layout('layouts.app', [
            'title' => auth()->user()->isAdmin() ? 'Pengaturan Akun & Sistem' : 'Pengaturan Profil Pengguna',
            'breadcrumb' => 'Pengaturan',
        ]);
    }
}
