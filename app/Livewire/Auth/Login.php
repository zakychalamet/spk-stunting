<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class Login extends Component
{
    public string $username = '';
    public string $password = '';
    public bool $remember = false;

    protected $rules = [
        'username' => 'required|string',
        'password' => 'required|string',
    ];

    protected $messages = [
        'username.required' => 'Username atau email wajib diisi.',
        'password.required' => 'Kata sandi wajib diisi.',
    ];

    public function mount()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
    }

    public function login()
    {
        $this->validate();

        // Check if username or email
        $fieldType = filter_var($this->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $fieldType => $this->username,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();

            /** @var User $user */
            $user = Auth::user();
            $user->update(['last_login_at' => now()]);

            session()->flash('success', 'Selamat datang kembali, ' . $user->name . '!');
            return redirect()->intended(route('dashboard'));
        }

        $this->addError('login_failed', 'Username atau kata sandi yang Anda masukkan tidak sesuai.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
