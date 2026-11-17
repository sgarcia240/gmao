<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="space-y-6">
    <!-- Encabezado Identidad de Usuario -->
    <header class="flex items-start gap-3.5 pb-5 border-b border-slate-100">
        <div class="w-10 h-10 rounded-xl bg-[#070b14] text-cyan-400 border border-slate-800 flex items-center justify-center shrink-0 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
        </div>
        <div>
            <span class="font-mono-tech text-[10px] font-bold uppercase tracking-wider text-blue-600 block">
                Identidad de Operador
            </span>
            <h2 class="text-base sm:text-lg font-extrabold text-slate-900">
                {{ __('Profile Information') }}
            </h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-500 leading-relaxed">
                {{ __("Update your account's profile information and email address.") }}
            </p>
        </div>
    </header>

    <form wire:submit="updateProfileInformation" class="space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Nombre -->
            <div>
                <x-input-label for="name" :value="__('Name')" class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5" />
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </span>
                    <x-text-input wire:model="name"
                                  id="name"
                                  name="name"
                                  type="text"
                                  class="block w-full pl-11 pr-4 py-3 bg-slate-50/80 border-slate-200 text-slate-900 rounded-2xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition"
                                  required
                                  autofocus
                                  autocomplete="name" />
                </div>
                <x-input-error class="mt-2 text-xs font-semibold text-rose-500" :messages="$errors->get('name')" />
            </div>

            <!-- Correo Electrónico -->
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
                                  name="email"
                                  type="email"
                                  class="block w-full pl-11 pr-4 py-3 bg-slate-50/80 border-slate-200 text-slate-900 rounded-2xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition"
                                  required
                                  autocomplete="username" />
                </div>
                <x-input-error class="mt-2 text-xs font-semibold text-rose-500" :messages="$errors->get('email')" />
            </div>
        </div>

        <!-- Verificación de correo pendiente -->
        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
            <div class="p-4 rounded-2xl bg-amber-50/90 border border-amber-200/80 space-y-2">
                <p class="text-xs sm:text-sm font-medium text-amber-900">
                    {{ __('Your email address is unverified.') }}

                    <button wire:click.prevent="sendVerification"
                            class="underline font-bold text-amber-800 hover:text-amber-950 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition cursor-pointer">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="text-xs font-bold text-emerald-700 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            </div>
        @endif

        <!-- Botón Guardar y Confirmación -->
        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-[0.99] disabled:opacity-60 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-cyan-500/20 transition cursor-pointer">
                <svg wire:loading.remove wire:target="updateProfileInformation" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                <span wire:loading.remove wire:target="updateProfileInformation">{{ __('Save') }}</span>

                <span wire:loading wire:target="updateProfileInformation" class="inline-flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Guardando...
                </span>
            </button>

            <x-action-message class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-emerald-600" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>