<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $roleFilter = '';
    public bool $showModal = false;

    // Campos del formulario
    public ?int $userId = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'technician';
    public string $phone = '';
    public string $specialty = '';
    public bool $is_active = true;

    public function mount()
    {
        // Restricción a nivel de componente
        abort_if(! auth()->user()->hasRole('admin', 'supervisor'), 403, 'Acceso no autorizado.');
    }

    protected function rules()
    {
        return [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $this->userId,
            'password'  => $this->userId ? 'nullable|min:8' : 'required|min:8',
            'role' => ['required', Rule::in(array_keys(User::roles()))],
            'phone'     => 'nullable|string|max:20',
            'specialty' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ];
    }

    public function openModal(?int $id = null)
    {
        $this->resetValidation();
        $this->userId = $id;

        if ($id) {
            $user = User::findOrFail($id);
            $this->name      = $user->name;
            $this->email     = $user->email;
            $this->role      = $user->role;
            $this->phone     = $user->phone ?? '';
            $this->specialty = $user->specialty ?? '';
            $this->is_active = $user->is_active;
            $this->password  = '';
        } else {
            $this->reset(['name', 'email', 'password', 'phone', 'specialty']);
            $this->role = 'technician';
            $this->is_active = true;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            if (empty($validated['password'])) {
                unset($validated['password']);
            } else {
                $validated['password'] = bcrypt($validated['password']);
            }
           
            $user->update($validated);
        } else {
            $validated['password'] = bcrypt($validated['password']);
            User::create($validated);
        }

        $this->showModal = false;
        session()->flash('message', 'Usuario guardado correctamente.');
    }

    public function toggleStatus(int $id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) => $q->where(fn($sq) => $sq->where('name', 'like', "%{$this->search}%")->orWhere('email', 'like', "%{$this->search}%")))
            ->when($this->roleFilter, fn($q) => $q->where('role', $this->roleFilter))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.user-index', [
            'users' => $users,
        ])->layout('layouts.app');
    }
}
