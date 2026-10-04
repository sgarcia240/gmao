<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'building_name',
        'address',
        'city',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function elevators()
    {
        return $this->hasMany(Elevator::class);
    }
}