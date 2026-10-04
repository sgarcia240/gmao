<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // Claves de roles
    public const ROLE_ADMIN = 'admin';
    public const ROLE_SUPERVISOR = 'supervisor';
    public const ROLE_TECHNICIAN = 'technician';

    /**
     * Retorna el listado de roles con su etiqueta legible.
     */
    public static function roles(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrador',
            self::ROLE_SUPERVISOR => 'Supervisor',
            self::ROLE_TECHNICIAN => 'Técnico',
        ];
    }
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // admin, supervisor, technician
        'phone',
        'specialty',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSupervisor(): bool
    {
        return $this->role === 'supervisor';
    }

    public function isTechnician(): bool
    {
        return $this->role === 'technician';
    }

    /**
     * Comprueba si el usuario tiene alguno de los roles indicados
     */
    public function hasRole(...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }
}
