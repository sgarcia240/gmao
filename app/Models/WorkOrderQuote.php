<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderQuote extends Model
{
    protected $fillable = [
        'work_order_id', 'provider_name', 'amount', 'file_path', 'is_selected', 'notes',
    ];

    protected $casts = [
        'is_selected' => 'boolean',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}