<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlots extends Model
{
    use HasFactory;
    protected $fillable = [
        'day', 'time_slot'
    ];
    public function order()
    {
        return $this->hasOne(Order::class,'time_slot_id','id');
    }
}
