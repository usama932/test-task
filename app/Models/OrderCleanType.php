<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCleanType extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id', 'cleantype_id'
    ];
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }
    public function cleantype(){
        return $this->belongsTo(CleaningType::class,'cleantype_id','id');
    }
  
}
