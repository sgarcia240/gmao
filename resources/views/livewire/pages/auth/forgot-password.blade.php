<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="space-y-6">
    <!-- Cabecera de Recuperación de Acceso -->
    <div class="flex items-start gap-3.5 pb-5 border-b border-slate-100">
        <div class="w-11 h-11 rounded-2xl bg-[#070b14] text-cyan-400 border border-slate-800 flex items-center justify-center shrink-0 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
            </svg>
        </div>
        <div>
            <span class="font-mono-tech text-[10px] font-bold uppercase tracking-wider text-blue-600 block">
                Recuperación de Credenciales
            </span>
            <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">
                Restablecer Contraseña
            </h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-500 leading-relaxed">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </p>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs sm:text-sm font-semibold text-emerald-800" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-5">
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
                              placeholder="tecnico@trackhelm.com"
                              required autofocus />
            </div>

            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-semibold text-rose-500" />
        </div>

        <div class="space-y-3 pt-1">
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-[0.99] disabled:opacity-60 text-white font-bold text-sm rounded-2xl shadow-lg shadow-cyan-500/20 transition cursor-pointer">
                <svg wire:loading.remove wire:target="sendPasswordResetLink" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                </svg>
                <span wire:loading.remove wire:target="sendPasswordResetLink">{{ __('Email Password Reset Link') }}</span>

                <span wire:loading wire:target="sendPasswordResetLink" class="inline-flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Enviando enlace...
                </span>
            </button>

            @if (Route::has('login'))
                <div class="text-center pt-1">
                    <a href="{{ route('login') }}" wire:navigate
                       class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        <span>Volver al inicio de sesión</span>
                    </a>
                </div>
            @endif
        </div>
    </form>
</div>