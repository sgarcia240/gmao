<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-6">
    <!-- Cabecera de Verificación de Seguridad -->
    <div class="flex items-start gap-3.5 pb-5 border-b border-slate-100">
        <div class="w-11 h-11 rounded-2xl bg-[#070b14] text-cyan-400 border border-slate-800 flex items-center justify-center shrink-0 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
        </div>
        <div>
            <span class="font-mono-tech text-[10px] font-bold uppercase tracking-wider text-blue-600 block">
                Verificación de Seguridad
            </span>
            <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">
                Área Protegida
            </h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-500 leading-relaxed">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </p>
        </div>
    </div>

    <form wire:submit="confirmPassword" class="space-y-5">
        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5" />

            <x-text-input wire:model="password"
                          id="password"
                          class="block w-full px-4 py-3 bg-slate-50/80 border-slate-200 text-slate-900 rounded-2xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition"
                          type="password"
                          name="password"
                          placeholder="••••••••"
                          required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-semibold text-rose-500" />
        </div>

        <div class="pt-1">
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-[0.99] disabled:opacity-60 text-white font-bold text-sm rounded-2xl shadow-lg shadow-cyan-500/20 transition cursor-pointer">
                <svg wire:loading.remove wire:target="confirmPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
                <span wire:loading.remove wire:target="confirmPassword">{{ __('Confirm') }}</span>

                <span wire:loading wire:target="confirmPassword" class="inline-flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Verificando...
                </span>
            </button>
        </div>
    </form>
</div>
