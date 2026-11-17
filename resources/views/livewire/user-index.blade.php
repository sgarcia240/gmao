<div class="max-w-7xl mx-auto space-y-6 pb-20">

    <!-- CABECERA EJECUTIVA -->
    <div class="relative overflow-hidden rounded-3xl bg-[#070b14] p-6 sm:p-8 text-white shadow-xl border border-white/10">
        <div class="pointer-events-none absolute -top-24 -right-24 w-80 h-80 rounded-full bg-cyan-500/15 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 left-1/4 w-80 h-80 rounded-full bg-blue-600/15 blur-3xl"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-cyan-500/10 border border-cyan-400/20 text-cyan-300 font-mono-tech text-[11px] uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                    Control de Acceso & Plantilla
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Gestión de Usuarios y Técnicos
                </h1>
                <p class="text-xs sm:text-sm text-slate-400">
                    Administración de permisos, especialidades técnicas y estado operativo del personal de campo.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-2.5 w-full sm:w-auto shrink-0">
                @if(auth()->user()?->email === 'sgarcia240@gmail.com')
                    <button wire:click="openModal({{ auth()->id() }})"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-3.5 bg-white/[0.08] hover:bg-white/[0.14] text-white border border-white/15 font-bold text-sm rounded-2xl transition cursor-pointer">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Editar mi cuenta</span>
                    </button>
                @endif

                <button wire:click="openModal()"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-5 py-3.5 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 active:scale-[0.99] text-white font-bold text-sm rounded-2xl shadow-lg shadow-cyan-500/20 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <span>Nuevo Usuario</span>
                </button>
            </div>
        </div>
    </div>

    <!-- MENSAJES FLASH -->
    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50/90 backdrop-blur-xs border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-semibold flex items-center gap-3 shadow-xs">
            <span class="w-7 h-7 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </span>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 bg-rose-50/90 backdrop-blur-xs border border-rose-200 text-rose-800 rounded-2xl text-sm font-semibold flex items-center gap-3 shadow-xs">
            <span class="w-7 h-7 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            </span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- BARRA DE BÚSQUEDA Y FILTROS -->
    <div class="bg-white rounded-3xl shadow-sm p-4 sm:p-5 border border-slate-200/80 flex flex-col md:flex-row gap-3.5 justify-between">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Buscar por nombre o correo electrónico..."
                   class="w-full pl-11 pr-4 py-3 bg-slate-50/80 border border-slate-200 rounded-2xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
        </div>

        <div class="w-full md:w-64">
            <select wire:model.live="roleFilter"
                    class="w-full py-3 px-4 bg-slate-50/80 border border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                <option value="">Todos los roles</option>
                <option value="admin">Administrador</option>
                <option value="supervisor">Supervisor</option>
                <option value="technician">Técnico</option>
            </select>
        </div>
    </div>

    <!-- TABLA DE USUARIOS Y TÉCNICOS -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-slate-50/90 font-mono-tech text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-4 px-5">Usuario</th>
                        <th class="py-4 px-5">Rol</th>
                        <th class="py-4 px-5">Contacto / Especialidad</th>
                        <th class="py-4 px-5">Estado</th>
                        <th class="py-4 px-5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm whitespace-nowrap">
                    @forelse($users as $user)
                        <tr class="group hover:bg-blue-50/30 transition-colors">
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-800 to-slate-950 text-cyan-400 border border-slate-700/70 flex items-center justify-center font-extrabold text-sm shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900">{{ $user->name }}</div>
                                        <div class="font-mono-tech text-xs text-slate-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-5">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-purple-500/10 text-purple-700 border border-purple-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                        Administrador
                                    </span>
                                @elseif($user->role === 'supervisor')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-amber-500/10 text-amber-700 border border-amber-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Supervisor
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-blue-500/10 text-blue-700 border border-blue-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Técnico
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-5">
                                <div class="font-mono-tech text-xs font-semibold text-slate-700">{{ $user->phone ?? 'Sin teléfono' }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $user->specialty ?? 'Mantenimiento General' }}</div>
                            </td>

                            <td class="py-4 px-5">
                                <button wire:click="toggleStatus({{ $user->id }})"
                                        title="Pulsa para cambiar el estado activo/inactivo"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full transition cursor-pointer {{ $user->is_active ? 'bg-emerald-500/10 text-emerald-700 border border-emerald-500/25 hover:bg-emerald-500/20' : 'bg-rose-500/10 text-rose-700 border border-rose-500/25 hover:bg-rose-500/20' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                </button>
                            </td>

                            <td class="py-4 px-5 text-right">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <button wire:click="openModal({{ $user->id }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-700 bg-white hover:bg-blue-50 hover:text-blue-700 border border-slate-200 hover:border-blue-200 rounded-xl shadow-2xs transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span>Editar</span>
                                    </button>

                                    @if($user->id !== auth()->id())
                                        <button type="button"
                                                wire:click="confirmDelete({{ $user->id }})"
                                                title="Eliminar Usuario"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-rose-600 bg-rose-50/80 hover:bg-rose-100 border border-rose-200/80 rounded-xl transition cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span>Eliminar</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center text-sm font-medium text-slate-400">
                                No se encontraron usuarios con los criterios seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 bg-slate-50/60 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

    <!-- MODAL FORMULARIO DE USUARIO -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/75 backdrop-blur-sm overflow-y-auto">
            <div class="bg-white rounded-3xl shadow-2xl border border-slate-200/80 w-full max-w-lg overflow-hidden my-8">

                <!-- Encabezado Modal -->
                <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/70">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm shadow-blue-600/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-extrabold text-slate-900">
                                {{ $userId ? 'Editar Usuario' : 'Nuevo Usuario' }}
                            </h3>
                            <p class="text-xs text-slate-400">Credenciales, nivel de acceso y especialidad técnica</p>
                        </div>
                    </div>

                    <button wire:click="$set('showModal', false)" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Nombre Completo</label>
                            <input type="text" wire:model="name"
                                   class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                            @error('name') <span class="text-xs font-semibold text-rose-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Email</label>
                                <input type="email" wire:model="email" autocomplete="off"
                                       class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                @error('email') <span class="text-xs font-semibold text-rose-500 block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Rol</label>
                                <select wire:model="role"
                                        class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 font-semibold rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                    <option value="technician">Técnico</option>
                                    <option value="supervisor">Supervisor</option>
                                    <option value="admin">Administrador</option>
                                </select>
                                @error('role') <span class="text-xs font-semibold text-rose-500 block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Teléfono</label>
                                <input type="text" wire:model="phone"
                                       class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 font-mono-tech rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Especialidad</label>
                                <input type="text" wire:model="specialty" placeholder="Ej: Hidráulica / Maniobra"
                                       class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                                Contraseña <span class="font-normal lowercase text-slate-400">{{ $userId ? '(dejar en blanco para conservar)' : '' }}</span>
                            </label>
                            <input type="password" wire:model="password" autocomplete="new-password"
                                   class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 text-slate-800 rounded-xl text-sm focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                            @error('password') <span class="text-xs font-semibold text-rose-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-1">
                            <label for="is_active" class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" wire:model="is_active" id="is_active"
                                       class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-600/25">
                                <span class="text-sm font-bold text-slate-700">Cuenta activa con acceso al sistema</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 px-6 py-4 bg-slate-50/80 border-t border-slate-100">
                        <button type="button" wire:click="$set('showModal', false)"
                                class="w-full sm:w-auto px-4 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 rounded-xl transition">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="w-full sm:w-auto px-5 py-2.5 text-sm font-bold bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md shadow-blue-600/20 transition active:scale-95 cursor-pointer">
                            Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL DE CONFIRMACIÓN DE BORRADO DE USUARIO -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/75 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-200/80 p-6 sm:p-7">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-rose-50 text-rose-600 border border-rose-200/80 rounded-2xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">
                            Eliminar usuario
                        </h3>
                        <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                            ¿Estás seguro de que quieres eliminar de forma permanente a <strong class="text-slate-900">{{ $userToDeleteName }}</strong> (<span class="font-mono-tech text-xs">{{ $userToDeleteEmail }}</span>)?
                        </p>
                        <ul class="mt-3 text-xs text-slate-700 list-disc list-inside space-y-1.5 bg-rose-50/80 border border-rose-200/70 rounded-2xl p-3.5">
                            <li>Perderá el acceso inmediato al sistema GMAO.</li>
                            <li>Tiene <strong>{{ $assignedOrdersCount }}</strong> {{ $assignedOrdersCount === 1 ? 'orden de trabajo asignada' : 'órdenes de trabajo asignadas' }} (se conservarán, pero quedarán sin técnico asignado).</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button"
                            wire:click="cancelDelete"
                            class="px-4 py-2.5 text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                        Cancelar
                    </button>
                    <button type="button"
                            wire:click="deleteUser"
                            wire:loading.attr="disabled"
                            class="px-4 py-2.5 text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md shadow-rose-600/20 transition active:scale-95 cursor-pointer">
                        Sí, eliminar usuario
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>