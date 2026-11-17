<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="space-y-6">
    <!-- Encabezado Seguridad de Acceso -->
    <header class="flex items-start gap-3.5 pb-5 border-b border-slate-100">
        <div class="w-10 h-10 rounded-xl bg-[#070b14] text-cyan-400 border border-slate-800 flex items-center justify-center shrink-0 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
        </div>
        <div>
            <span class="font-mono-tech text-[10px] font-bold uppercase tracking-wider text-blue-600 block">
                Seguridad de Acceso
            </span>
            <h2 class="text-base sm:text-lg font-extrabold text-slate-900">
                {{ __('Update Password') }}
            </h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-500 leading-relaxed">
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            </p>
        </div>
    </header>

    <form wire:submit="updatePassword" class="space-y-5">
        <!-- Contraseña Actual -->
        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5" />
            <x-text-input wire:model="current_password"
                          id="update_password_current_password"
                          name="current_password"
                          type="password"
                          placeholder="••••••••"
                          class="block w-full px-4 py-3 bg-slate-50/80 border-slate-200 text-slate-900 rounded-2xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition"
                          autocomplete="current-password" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2 text-xs font-semibold text-rose-500" />
        </div>

        <!-- Nueva Contraseña y Confirmación -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="update_password_password" :value="__('New Password')" class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5" />
                <x-text-input wire:model="password"
                              id="update_password_password"
                              name="password"
                              type="password"
                              placeholder="••••••••"
                              class="block w-full px-4 py-3 bg-slate-50/80 border-slate-200 text-slate-900 rounded-2xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition"
                              autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-semibold text-rose-500" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5" />
                <x-text-input wire:model="password_confirmation"
                              id="update_password_password_confirmation"
                              name="password_confirmation"
                              type="password"
                              placeholder="••••••••"
                              class="block w-full px-4 py-3 bg-slate-50/80 border-slate-200 text-slate-900 rounded-2xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition"
                              autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs font-semibold text-rose-500" />
            </div>
        </div>

        <!-- Botón Guardar y Confirmación -->
        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-[0.99] disabled:opacity-60 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-cyan-500/20 transition cursor-pointer">
                <svg wire:loading.remove wire:target="updatePassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <span wire:loading.remove wire:target="updatePassword">{{ __('Save') }}</span>

                <span wire:loading wire:target="updatePassword" class="inline-flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Guardando...
                </span>
            </button>

            <x-action-message class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-emerald-600" on="password-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>