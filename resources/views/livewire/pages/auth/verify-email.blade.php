<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="space-y-6">
    <!-- Cabecera de Activación de Cuenta -->
    <div class="flex items-start gap-3.5 pb-5 border-b border-slate-100">
        <div class="w-11 h-11 rounded-2xl bg-[#070b14] text-cyan-400 border border-slate-800 flex items-center justify-center shrink-0 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.98l7.5-4.04a2.25 2.25 0 012.134 0l7.5 4.04a2.25 2.25 0 011.183 1.98V19.5z" />
            </svg>
        </div>
        <div>
            <span class="font-mono-tech text-[10px] font-bold uppercase tracking-wider text-blue-600 block">
                Activación de Cuenta
            </span>
            <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">
                Verifica tu Correo Electrónico
            </h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-500 leading-relaxed">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </p>
        </div>
    </div>

    <!-- Confirmación de reenvío -->
    @if (session('status') == 'verification-link-sent')
        <div class="p-3.5 bg-emerald-50/90 border border-emerald-200 text-emerald-800 rounded-2xl text-xs sm:text-sm font-semibold flex items-start gap-2.5">
            <span class="w-6 h-6 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </span>
            <span>{{ __('A new verification link has been sent to the email address you provided during registration.') }}</span>
        </div>
    @endif

    <!-- Acciones -->
    <div class="space-y-3 pt-1">
        <button wire:click="sendVerification"
                wire:loading.attr="disabled"
                type="button"
                class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-[0.99] disabled:opacity-60 text-white font-bold text-sm rounded-2xl shadow-lg shadow-cyan-500/20 transition cursor-pointer">
            <svg wire:loading.remove wire:target="sendVerification" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
            </svg>
            <span wire:loading.remove wire:target="sendVerification">{{ __('Resend Verification Email') }}</span>

            <span wire:loading wire:target="sendVerification" class="inline-flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Reenviando correo...
            </span>
        </button>

        <button wire:click="logout"
                type="button"
                class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 text-xs sm:text-sm font-bold text-slate-600 hover:text-rose-600 bg-slate-100/80 hover:bg-rose-50 border border-slate-200/80 hover:border-rose-200 rounded-2xl transition cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
            </svg>
            <span>{{ __('Log Out') }}</span>
        </button>
    </div>
</div>