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
        'issue_description',
        'work_done',
        'failure_category',
        'started_at',
        'completed_at',
        'client_signature',
        'signed_at',
    ];
    

    public function elevator()
    {
        return $this->belongsTo(Elevator::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
