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
    <!-- Tarjeta ampliada: sm:max-w-xl y padding p-10 -->
    <div class="w-full sm:max-w-2xl bg-white shadow-2xl rounded-2xl p-10 border border-gray-100 mx-auto">
        
        <!-- Logo 'E' corporativo -->
        <div class="flex justify-center mb-8">
            <a href="/" wire:navigate>
                <svg class="w-20 h-20 shadow-md rounded-2xl" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <rect width="100" height="100" rx="22" fill="#2563EB"/>
                    <path d="M 28 22 L 72 22 L 72 34 L 42 34 L 42 44 L 68 44 L 68 56 L 42 56 L 42 66 L 72 66 L 72 78 L 28 78 Z" fill="#FFFFFF"/>
                </svg>
            </a>
        </div>

        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Iniciar Sesión</h2>

        <!-- Mensaje de estado -->
        @if (session('status'))
            <div class="mb-6 font-medium text-sm text-green-600 text-center bg-green-50 p-3 rounded-lg border border-green-200">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="login" class="space-y-6">
            <!-- Correo Electrónico -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Correo Electrónico</label>
                <input wire:model="form.email" 
                       id="email" 
                       type="email" 
                       name="email" 
                       required 
                       autofocus 
                       autocomplete="username" 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-base">
                @error('form.email')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Contraseña</label>
                <input wire:model="form.password" 
                       id="password" 
                       type="password" 
                       name="password" 
                       required 
                       autocomplete="current-password" 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition text-base">
                @error('form.password')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Recordarme y Olvidé contraseña -->
            <div class="flex items-center justify-between text-sm pt-1">
                <label for="remember" class="inline-flex items-center cursor-pointer">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 w-4 h-4">
                    <span class="ms-2 text-gray-600">Recordarme</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline" href="{{ route('password.request') }}" wire:navigate>
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <!-- Botón de Envío -->
            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-base rounded-lg shadow-md hover:shadow-lg transition duration-200 uppercase tracking-wider">
                    Iniciar Sesión
                </button>
            </div>
        </form>
    </div>
</div>