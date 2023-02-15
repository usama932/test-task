<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderExtra extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id', 'extra_service_id'
    ];
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }
    public function service(){
        return $this->belongsTo(ExtraService::class,'extra_service_id','id');
    }
}
