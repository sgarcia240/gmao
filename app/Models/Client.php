<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cif_nif',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
    ];

    public function locations()
    {
        return $this->hasMany(Location::class);
    }
    public function invoices()
{
    return $this->hasMany(\App\Models\Invoice::class);
}
}