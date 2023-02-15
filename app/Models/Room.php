<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'price'
    ];
    public function services()
    {
        return $this->hasMany(ExtraService::class);
    }
    public function order()
    {
        return $this->hasOne(Discount::class,'room_id','id');
    }
}
