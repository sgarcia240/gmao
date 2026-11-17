<?php

use App\Livewire\Forms\LoginForm;
use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    $this->redirectIntended(default: route('analytics', absolute: false), navigate: true);
};

?>

<div>
    <div class="w-full sm:max-w-xl bg-white shadow-2xl rounded-3xl overflow-hidden border border-slate-200/80 mx-auto">

        <!-- CABECERA EJECUTIVA OSCURA CON LOGO CORPORATIVO -->
        <div class="relative overflow-hidden bg-[#070b14] px-8 py-9 text-center text-white border-b border-white/10">
            <div class="pointer-events-none absolute -top-20 -right-20 w-64 h-64 rounded-full bg-cyan-500/15 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-20 -left-20 w-64 h-64 rounded-full bg-blue-600/15 blur-3xl"></div>

            <div class="relative z-10 flex flex-col items-center justify-center">
                <a href="/" wire:navigate class="inline-flex flex-col items-center gap-3 group">
                    <div class="p-2.5 rounded-2xl bg-white/[0.06] border border-white/10 backdrop-blur-xs shadow-lg group-hover:border-cyan-400/30 transition">
                        <img src="{{ asset('images/logo.png') }}"
                             alt="{{ config('app.name') }}"
                             class="h-16 sm:h-20 w-auto max-w-[220px] object-contain rounded-xl">
                    </div>
                </a>

                <div class="mt-4 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-400/20 text-cyan-300 font-mono-tech text-[11px] uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                    Acceso al Sistema · GMAO 4.0
                </div>

                <h2 class="mt-3 text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Iniciar Sesión
                </h2>
                <p class="mt-1 text-xs sm:text-sm text-slate-400">
                    Introduce tus credenciales técnicas o de administración
                </p>
            </div>
        </div>

        <!-- CUERPO DEL FORMULARIO -->
        <div class="p-7 sm:p-10">

            <!-- Mensaje de estado -->
            @if (session('status'))
                <div class="mb-6 p-3.5 bg-emerald-50/90 border border-emerald-200 text-emerald-800 rounded-2xl text-xs sm:text-sm font-semibold flex items-center gap-2.5">
                    <span class="w-6 h-6 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </span>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form wire:submit="login" class="space-y-5">
                <!-- Correo Electrónico -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                        Correo Electrónico
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </span>
                        <input wire:model="form.email"
                               id="email"
                               type="email"
                               name="email"
                               placeholder="usuario@trackhelm.com"
                               required
                               autofocus
                               autocomplete="username"
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50/80 border border-slate-200 text-slate-900 rounded-2xl text-sm sm:text-base focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                    </div>
                    @error('form.email')
                        <span class="text-rose-500 text-xs font-semibold mt-1.5 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                        Contraseña
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </span>
                        <input wire:model="form.password"
                               id="password"
                               type="password"
                               name="password"
                               placeholder="••••••••"
                               required
                               autocomplete="current-password"
                               class="w-full pl-11 pr-4 py-3.5 bg-slate-50/80 border border-slate-200 text-slate-900 rounded-2xl text-sm sm:text-base focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                    </div>
                    @error('form.password')
                        <span class="text-rose-500 text-xs font-semibold mt-1.5 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Recordarme y Olvidé contraseña -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm pt-1">
                    <label for="remember" class="inline-flex items-center cursor-pointer select-none">
                        <input wire:model="form.remember"
                               id="remember"
                               type="checkbox"
                               class="rounded-md border-slate-300 text-blue-600 shadow-2xs focus:ring-blue-600/25 w-4 h-4">
                        <span class="ms-2 text-xs sm:text-sm font-semibold text-slate-600">Recordarme</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-xs sm:text-sm text-blue-600 hover:text-blue-700 font-bold hover:underline transition"
                           href="{{ route('password.request') }}"
                           wire:navigate>
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <!-- Botón de Envío -->
                <div class="pt-2">
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="w-full py-3.5 px-5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-[0.99] disabled:opacity-60 text-white font-extrabold text-sm sm:text-base rounded-2xl shadow-lg shadow-cyan-500/25 transition inline-flex items-center justify-center gap-2 uppercase tracking-wider cursor-pointer">
                        <svg wire:loading.remove wire:target="login" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        <span wire:loading.remove wire:target="login">Iniciar Sesión</span>

                        <span wire:loading wire:target="login" class="inline-flex items-center gap-2 normal-case tracking-normal">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Accediendo al sistema...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>