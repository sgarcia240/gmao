<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElevatorSurvey extends Model
{
    protected $fillable = [
        'elevator_id', 'stops', 'machine_type', 'controller_type',
        'door_type', 'push_buttons_type', 'technical_notes',
    ];

    public function elevator()
    {
        return $this->belongsTo(Elevator::class);
    }
}