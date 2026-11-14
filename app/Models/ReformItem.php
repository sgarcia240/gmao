<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReformItem extends Model
{
    protected $fillable = [
        'work_order_id', 'component_name', 'is_selected', 'material_received', 'received_at',
    ];

    protected $casts = [
        'is_selected' => 'boolean',
        'material_received' => 'boolean',
        'received_at' => 'date',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}