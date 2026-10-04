<div class="p-6 max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Gestión de Usuarios y Técnicos</h1>
            <p class="text-sm text-slate-500">Administra el personal técnico, supervisores y administradores del GMAO.</p>
        </div>
        <button wire:click="openModal()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nuevo Usuario
        </button>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif

    <!-- Filtros -->
    <div class="flex flex-col md:flex-row gap-4 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
        <div class="flex-1">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre o correo..." class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-sm">
        </div>
        <div class="w-full md:w-48">
            <select wire:model.live="roleFilter" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-sm">
                <option value="">Todos los roles</option>
                <option value="admin">Administrador</option>
                <option value="supervisor">Supervisor</option>
                <option value="technician">Técnico</option>
            </select>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <th class="p-4">Usuario</th>
                    <th class="p-4">Rol</th>
                    <th class="p-4">Contacto / Especialidad</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4">
                            <div class="font-bold text-slate-800">{{ $user->name }}</div>
                            <div class="text-xs text-slate-400">{{ $user->email }}</div>
                        </td>
                        <td class="p-4">
                            @if($user->role === 'admin')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">Administrador</span>
                            @elseif($user->role === 'supervisor')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">Supervisor</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Técnico</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="text-slate-700">{{ $user->phone ?? 'Sin teléfono' }}</div>
                            <div class="text-xs text-slate-400">{{ $user->specialty ?? 'General' }}</div>
                        </td>
                        <td class="p-4">
                            <button wire:click="toggleStatus({{ $user->id }})" class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </td>
                        <td class="p-4 text-right">
                            <button wire:click="openModal({{ $user->id }})" 
                                  class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 hover:text-blue-800 border border-blue-200/70 rounded-lg shadow-sm transition-all duration-150 active:scale-95">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <span>Editar</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-slate-400">No se encontraron usuarios.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal Formulario -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-lg p-6 space-y-4">
                <h3 class="text-lg font-bold text-slate-800">{{ $userId ? 'Editar Usuario' : 'Nuevo Usuario' }}</h3>
                
                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Nombre Completo</label>
                        <input type="text" wire:model="name" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        @error('name') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Email</label>
                            <input type="email" wire:model="email" autocomplete="off" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            @error('email') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Rol</label>
                            <select wire:model="role" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                <option value="technician">Técnico</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="admin">Administrador</option>
                            </select>
                            @error('role') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Teléfono</label>
                            <input type="text" wire:model="phone" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Especialidad</label>
                            <input type="text" wire:model="specialty" placeholder="Ej: Hidráulica" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Contraseña {{ $userId ? '(Dejar en blanco para conservar)' : '' }}</label>
                        <input type="password" wire:model="password" autocomplete="new-password" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        @error('password') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" wire:model="is_active" id="is_active" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_active" class="text-xs text-slate-600 font-medium">Usuario Activo</label>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-100 rounded-xl transition">Cancelar</button>
                        <button type="submit" class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-sm transition">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
