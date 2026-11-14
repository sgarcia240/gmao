<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderPhoto extends Model
{
    protected $fillable = ['work_order_id', 'photo_path', 'component_tag', 'notes'];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
}