<div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto space-y-4 sm:space-y-6 bg-slate-50 dark:bg-slate-900 min-h-screen">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800 dark:text-white">Gestión de Usuarios y Técnicos</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Administra el personal técnico, supervisores y administradores.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
    @if(auth()->user()?->email === 'sgarcia240@gmail.com')
        <button wire:click="openModal({{ auth()->id() }})" class="w-full sm:w-auto px-4 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-medium text-sm rounded-xl shadow-sm transition flex items-center justify-center gap-2">
            Editar mi cuenta
        </button>
    @endif

    <button wire:click="openModal()" class="w-full sm:w-auto px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl shadow-sm transition flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nuevo Usuario
    </button>
</div>
        
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 rounded-xl text-xs sm:text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
    <div class="p-3 sm:p-4 text-xs sm:text-sm text-rose-800 bg-rose-100 rounded-xl border border-rose-200">
        {{ session('error') }}
    </div>
    @endif

    <!-- Filtros -->
    <div class="flex flex-col md:flex-row gap-3 sm:gap-4 bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
        <div class="flex-1">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre o correo..." class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-xl focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-xs sm:text-sm">
        </div>
        <div class="w-full md:w-48">
            <select wire:model.live="roleFilter" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-xl focus:bg-white dark:focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-xs sm:text-sm">
                <option value="">Todos los roles</option>
                <option value="admin">Administrador</option>
                <option value="supervisor">Supervisor</option>
                <option value="technician">Técnico</option>
            </select>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[640px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 text-xs font-semibold text-slate-400 dark:text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                        <th class="p-4">Usuario</th>
                        <th class="p-4">Rol</th>
                        <th class="p-4">Contacto / Especialidad</th>
                        <th class="p-4">Estado</th>
                        <th class="p-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-xs sm:text-sm">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="p-4">
                                <div class="font-bold text-slate-800 dark:text-slate-100">{{ $user->name }}</div>
                                <div class="text-xs text-slate-400 dark:text-slate-400">{{ $user->email }}</div>
                            </td>
                            <td class="p-4">
                                @if($user->role === 'admin')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300">Administrador</span>
                                @elseif($user->role === 'supervisor')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">Supervisor</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300">Técnico</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="text-slate-700 dark:text-slate-300">{{ $user->phone ?? 'Sin teléfono' }}</div>
                                <div class="text-xs text-slate-400 dark:text-slate-400">{{ $user->specialty ?? 'General' }}</div>
                            </td>
                            <td class="p-4">
                                <button wire:click="toggleStatus({{ $user->id }})" class="px-2.5 py-1 text-xs font-semibold rounded-full transition {{ $user->is_active ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : 'bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300' }}">
                                    {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                </button>
                            </td>
                            <td class="p-4 text-right">
                                <button wire:click="openModal({{ $user->id }})" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 hover:text-blue-800 dark:hover:text-blue-300 border border-blue-200/70 dark:border-blue-800/60 rounded-lg shadow-sm transition-all duration-150 active:scale-95">
                                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span>Editar</span>
                                </button>
                               @if($user->id !== auth()->id())
                                <button type="button"
                                        wire:click="confirmDelete({{ $user->id }})"
                                        title="Eliminar Usuario"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg transition active:scale-95">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Eliminar
                                </button>
                            @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-400 dark:text-slate-500">No se encontraron usuarios.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100 dark:border-slate-700">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal Formulario -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 w-full max-w-lg p-5 sm:p-6 space-y-4 my-8">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                    <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white">{{ $userId ? 'Editar Usuario' : 'Nuevo Usuario' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1">Nombre Completo</label>
                        <input type="text" wire:model="name" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        @error('name') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1">Email</label>
                            <input type="email" wire:model="email" autocomplete="off" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            @error('email') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1">Rol</label>
                            <select wire:model="role" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                <option value="technician">Técnico</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="admin">Administrador</option>
                            </select>
                            @error('role') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1">Teléfono</label>
                            <input type="text" wire:model="phone" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1">Especialidad</label>
                            <input type="text" wire:model="specialty" placeholder="Ej: Hidráulica" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-300 mb-1">Contraseña {{ $userId ? '(Dejar en blanco para conservar)' : '' }}</label>
                        <input type="password" wire:model="password" autocomplete="new-password" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        @error('password') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" wire:model="is_active" id="is_active" class="rounded border-slate-300 dark:border-slate-700 text-blue-600 dark:bg-slate-900 focus:ring-blue-500">
                        <label for="is_active" class="text-xs text-slate-600 dark:text-slate-300 font-medium">Usuario Activo</label>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <button type="button" wire:click="$set('showModal', false)" class="w-full sm:w-auto px-4 py-2 text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition">Cancelar</button>
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 text-xs sm:text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-sm transition">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
    <!-- MODAL DE CONFIRMACIÓN DE BORRADO DE USUARIO -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 p-6">
                <div class="flex items-start gap-3.5">
                    <div class="p-2.5 bg-rose-100 text-rose-600 rounded-xl shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">
                            Eliminar usuario
                        </h3>
                        <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                            ¿Estás seguro de que quieres eliminar de forma permanente a <strong>{{ $userToDeleteName }}</strong> ({{ $userToDeleteEmail }})?
                        </p>
                        <ul class="mt-2.5 text-xs text-slate-600 list-disc list-inside space-y-1 bg-rose-50/70 border border-rose-100 rounded-xl p-3">
                            <li>Perderá el acceso inmediato al sistema GMAO.</li>
                            <li>Tiene <strong>{{ $assignedOrdersCount }}</strong> {{ $assignedOrdersCount === 1 ? 'orden de trabajo asignada' : 'órdenes de trabajo asignadas' }} (se conservarán, pero quedarán sin técnico asignado).</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2.5">
                    <button type="button"
                            wire:click="cancelDelete"
                            class="px-4 py-2 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                        Cancelar
                    </button>
                    <button type="button"
                            wire:click="deleteUser"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 text-sm font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-sm transition active:scale-95">
                        Sí, eliminar usuario
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
</div>