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
}
