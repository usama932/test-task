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
}
