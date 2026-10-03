<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Index extends Component
{
    public bool $showUserModal = false;
    public ?int $editingUserId = null;
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $role = 'bidan';
    public string $password = '';

    public bool $showDeleteModal = false;
    public ?int $deleteUserId = null;
    public string $deleteUserName = '';

    public function openCreateModal()
    {
        $this->reset(['editingUserId', 'name', 'username', 'email', 'role', 'password']);
        $this->role = 'bidan';
        $this->showUserModal = true;
    }

    public function editUser(int $id)
    {
        $user = User::findOrFail($id);
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';
        $this->showUserModal = true;
    }

    public function saveUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username,' . $this->editingUserId,
            'email' => 'required|email|max:255|unique:users,email,' . $this->editingUserId,
            'role' => 'required|in:admin,bidan,ahli_gizi',
        ];

        if (!$this->editingUserId || !empty($this->password)) {
            $rules['password'] = 'required|string|min:6';
        }

        $this->validate($rules, [
            'name.required' => 'Nama pengguna wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh akun lain.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        if ($this->editingUserId) {
            $user = User::findOrFail($this->editingUserId);
            $data = [
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'role' => $this->role,
            ];
            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }
            $user->update($data);
            session()->flash('success', "Pengguna {$user->name} berhasil diperbarui.");
        } else {
            User::create([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'role' => $this->role,
                'password' => Hash::make($this->password),
            ]);
            session()->flash('success', 'Pengguna baru berhasil ditambahkan.');
        }

        $this->showUserModal = false;
    }

    public function confirmDelete(int $id, string $name)
    {
        if (auth()->id() === $id) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            return;
        }

        $this->deleteUserId = $id;
        $this->deleteUserName = $name;
        $this->showDeleteModal = true;
    }

    public function deleteUser()
    {
        if ($this->deleteUserId && auth()->id() !== $this->deleteUserId) {
            User::destroy($this->deleteUserId);
            session()->flash('success', "Pengguna {$this->deleteUserName} berhasil dihapus.");
        }
        $this->showDeleteModal = false;
    }

    public function render()
    {
        $users = User::orderBy('id', 'asc')->get();

        return view('livewire.user.index', [
            'users' => $users,
        ])->layout('layouts.app', [
            'title' => 'Manajemen Pengguna',
            'breadcrumb' => 'Pengguna',
        ]);
    }
}
