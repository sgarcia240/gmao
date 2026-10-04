<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Elevator extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id', 'rae_code', 'brand', 'model', 
        'stops_count', 'max_load_kg', 'type_maneuver', 
        'installation_date', 'next_ite_date', 'status'
    ];
    // Convierte la columna automáticamente a fecha/Carbon
    protected $casts = [
        'next_ite_date' => 'date',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }
    
}