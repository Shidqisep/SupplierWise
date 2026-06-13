<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     * Menggunakan Password::defaults() yang sudah dikonfigurasi di AppServiceProvider
     * untuk memastikan password kuat (min 8, huruf besar+kecil, angka, simbol).
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: false);
    }
}; ?>

<div>
    <form wire:submit="register">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password with Strength Meter -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <div style="position: relative;" class="mt-1">
                <x-text-input wire:model="password" id="password" class="block w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password"
                                style="padding-right: 2.5rem;"
                                oninput="checkPasswordStrength(this.value)" />
                <!-- Toggle Show/Hide Password -->
                <button type="button" onclick="togglePassword('password', this)"
                        style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 0; color: #6b7280;"
                        tabindex="-1">
                    <svg id="eye-icon-password" xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <!-- Password Strength Meter Bar -->
            <div style="margin-top: 0.5rem;">
                <div style="width: 100%; background-color: #e5e7eb; border-radius: 9999px; height: 8px; overflow: hidden;">
                    <div id="password-strength-bar" style="height: 8px; border-radius: 9999px; transition: all 0.3s; width: 0%; background-color: #ef4444;"></div>
                </div>
                <p id="password-strength-text" style="font-size: 0.75rem; margin-top: 0.25rem; color: #6b7280;">Masukkan password</p>
            </div>

            <!-- Password Requirements Checklist -->
            <div style="margin-top: 0.5rem; font-size: 0.75rem; display: flex; flex-direction: column; gap: 0.25rem;">
                <div id="req-length" style="display: flex; align-items: center; color: #9ca3af;">
                    <svg style="width: 14px; height: 14px; min-width: 14px; margin-right: 6px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>Minimal 8 karakter</span>
                </div>
                <div id="req-upper" style="display: flex; align-items: center; color: #9ca3af;">
                    <svg style="width: 14px; height: 14px; min-width: 14px; margin-right: 6px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>Huruf besar (A-Z)</span>
                </div>
                <div id="req-lower" style="display: flex; align-items: center; color: #9ca3af;">
                    <svg style="width: 14px; height: 14px; min-width: 14px; margin-right: 6px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>Huruf kecil (a-z)</span>
                </div>
                <div id="req-number" style="display: flex; align-items: center; color: #9ca3af;">
                    <svg style="width: 14px; height: 14px; min-width: 14px; margin-right: 6px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>Angka (0-9)</span>
                </div>
                <div id="req-symbol" style="display: flex; align-items: center; color: #9ca3af;">
                    <svg style="width: 14px; height: 14px; min-width: 14px; margin-right: 6px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>Simbol (!@#$%^&*)</span>
                </div>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />

            <div style="position: relative;" class="mt-1">
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password"
                                style="padding-right: 2.5rem;" />
                <button type="button" onclick="togglePassword('password_confirmation', this)"
                        style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 0; color: #6b7280;"
                        tabindex="-1">
                    <svg id="eye-icon-password_confirmation" xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('Sudah punya akun?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Daftar') }}
            </x-primary-button>
        </div>
    </form>
</div>

<script>
    // =====================================================
    // PASSWORD STRENGTH METER
    // Menampilkan kekuatan password secara real-time
    // =====================================================
    function checkPasswordStrength(password) {
        let score = 0;
        const checks = {
            length: password.length >= 8,
            upper: /[A-Z]/.test(password),
            lower: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            symbol: /[^A-Za-z0-9]/.test(password),
        };

        // Update requirement checklist colors
        Object.keys(checks).forEach(key => {
            const el = document.getElementById('req-' + key);
            if (el) {
                el.style.color = checks[key] ? '#16a34a' : '#9ca3af';
            }
        });

        // Calculate score
        if (checks.length) score++;
        if (checks.upper) score++;
        if (checks.lower) score++;
        if (checks.number) score++;
        if (checks.symbol) score++;
        if (password.length >= 12) score++; // Bonus for length

        // Update bar
        const bar = document.getElementById('password-strength-bar');
        const text = document.getElementById('password-strength-text');
        const levels = [
            { width: '0%', color: '#ef4444', label: 'Masukkan password' },
            { width: '20%', color: '#ef4444', label: 'Sangat Lemah' },
            { width: '40%', color: '#f97316', label: 'Lemah' },
            { width: '60%', color: '#eab308', label: 'Cukup' },
            { width: '80%', color: '#22c55e', label: 'Kuat' },
            { width: '90%', color: '#16a34a', label: 'Sangat Kuat' },
            { width: '100%', color: '#15803d', label: 'Sempurna' },
        ];

        const level = password.length === 0 ? levels[0] : levels[Math.min(score, levels.length - 1)];
        bar.style.width = level.width;
        bar.style.backgroundColor = level.color;
        text.textContent = level.label;
        text.style.color = level.color;
    }

    // Toggle show/hide password
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById('eye-icon-' + inputId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
        }
    }
</script>

