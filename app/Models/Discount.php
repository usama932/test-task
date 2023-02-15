<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'amount','redeem','expire_at','coupen'
    ];
    public function order()
    {
        return $this->hasOne(Order::class,'discount_id','id');
    }
}
