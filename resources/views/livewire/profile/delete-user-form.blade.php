<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <!-- Encabezado Zona Crítica -->
    <header class="flex items-start gap-3.5 pb-5 border-b border-rose-100">
        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 border border-rose-200/80 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
        </div>
        <div>
            <span class="font-mono-tech text-[10px] font-bold uppercase tracking-wider text-rose-600 block">
                Zona Crítica · Acción Irreversible
            </span>
            <h2 class="text-base sm:text-lg font-extrabold text-slate-900">
                {{ __('Delete Account') }}
            </h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-500 leading-relaxed">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
            </p>
        </div>
    </header>

    <div>
        <button type="button"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-[0.99] text-white text-xs sm:text-sm font-bold rounded-xl shadow-md shadow-rose-600/20 transition cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <span>{{ __('Delete Account') }}</span>
        </button>
    </div>

    <!-- Modal de Confirmación -->
    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6 sm:p-7">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-rose-50 text-rose-600 border border-rose-200/80 rounded-2xl shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>

                <div class="flex-1">
                    <h2 class="text-base sm:text-lg font-extrabold text-slate-900">
                        {{ __('Are you sure you want to delete your account?') }}
                    </h2>

                    <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>

                    <div class="mt-5">
                        <x-input-label for="password" value="{{ __('Password') }}" class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5" />

                        <x-text-input
                            wire:model="password"
                            id="password"
                            name="password"
                            type="password"
                            class="block w-full px-4 py-3 bg-slate-50/80 border-slate-200 text-slate-900 rounded-2xl text-sm focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition"
                            placeholder="{{ __('Password') }}"
                        />

                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-semibold text-rose-500" />
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row justify-end gap-2.5">
                <button type="button"
                        x-on:click="$dispatch('close')"
                        class="w-full sm:w-auto px-4 py-2.5 text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition cursor-pointer">
                    {{ __('Cancel') }}
                </button>

                <button type="submit"
                        wire:loading.attr="disabled"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 disabled:opacity-60 rounded-xl shadow-md shadow-rose-600/20 transition active:scale-95 cursor-pointer">
                    <span wire:loading.remove wire:target="deleteUser">{{ __('Delete Account') }}</span>
                    <span wire:loading wire:target="deleteUser">Eliminando...</span>
                </button>
            </div>
        </form>
    </x-modal>
</section>