<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bathroom extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'price'
    ];
    public function order()
    {
        return $this->hasOne(Order::class,'bathroom_id','id');
    }
}
