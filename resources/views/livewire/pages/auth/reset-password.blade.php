<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="space-y-6">
    <!-- Cabecera de Restablecimiento de Credenciales -->
    <div class="flex items-start gap-3.5 pb-5 border-b border-slate-100">
        <div class="w-11 h-11 rounded-2xl bg-[#070b14] text-cyan-400 border border-slate-800 flex items-center justify-center shrink-0 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
        </div>
        <div>
            <span class="font-mono-tech text-[10px] font-bold uppercase tracking-wider text-blue-600 block">
                Actualización de Credenciales
            </span>
            <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">
                Nueva Contraseña
            </h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-500 leading-relaxed">
                Define una nueva contraseña segura para acceder de nuevo a tu cuenta de TrackHelm.
            </p>
        </div>
    </div>

    <form wire:submit="resetPassword" class="space-y-4">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5" />

            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </span>

                <x-text-input wire:model="email"
                              id="email"
                              class="block w-full pl-11 pr-4 py-3 bg-slate-50/80 border-slate-200 text-slate-900 rounded-2xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition"
                              type="email"
                              name="email"
                              required
                              autofocus
                              autocomplete="username" />
            </div>

            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-semibold text-rose-500" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5" />

            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                    </svg>
                </span>

                <x-text-input wire:model="password"
                              id="password"
                              class="block w-full pl-11 pr-4 py-3 bg-slate-50/80 border-slate-200 text-slate-900 rounded-2xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition"
                              type="password"
                              name="password"
                              placeholder="••••••••"
                              required
                              autocomplete="new-password" />
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-semibold text-rose-500" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5" />

            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </span>

                <x-text-input wire:model="password_confirmation"
                              id="password_confirmation"
                              class="block w-full pl-11 pr-4 py-3 bg-slate-50/80 border-slate-200 text-slate-900 rounded-2xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition"
                              type="password"
                              name="password_confirmation"
                              placeholder="••••••••"
                              required
                              autocomplete="new-password" />
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs font-semibold text-rose-500" />
        </div>

        <div class="pt-2">
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-[0.99] disabled:opacity-60 text-white font-bold text-sm rounded-2xl shadow-lg shadow-cyan-500/20 transition cursor-pointer">
                <svg wire:loading.remove wire:target="resetPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <span wire:loading.remove wire:target="resetPassword">{{ __('Reset Password') }}</span>

                <span wire:loading wire:target="resetPassword" class="inline-flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Actualizando...
                </span>
            </button>
        </div>
    </form>
</div>