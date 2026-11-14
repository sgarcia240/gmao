<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'elevator_id',
        'technician_id',
        'type',
        'priority',
        'status',
        'user_id',
        'issue_description',
        'work_done',
        'failure_category',
        'started_at',
        'completed_at',
        'client_signature',
        'signed_at',
        'installer_name', 
        'scheduled_date', 
        'accepted_budget_amount', 
        'budget_provider'
    ];
    

    public function elevator()
    {
        return $this->belongsTo(Elevator::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
    // Añadir al $fillable existente:
// 'installer_name', 'scheduled_date', 'accepted_budget_amount', 'budget_provider'

public function photos()
{
    return $this->hasMany(WorkOrderPhoto::class);
}

public function quotes()
{
    return $this->hasMany(WorkOrderQuote::class);
}

public function reformItems()
{
    return $this->hasMany(ReformItem::class);
}

public function getMaterialProgressAttribute(): int
{
    $selected = $this->reformItems()->where('is_selected', true)->count();
    if ($selected === 0) return 0;

    $received = $this->reformItems()
        ->where('is_selected', true)
        ->where('material_received', true)
        ->count();

    return (int) round(($received / $selected) * 100);
}
}
