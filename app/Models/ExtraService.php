<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraService extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'price','room_id','type'
    ];
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}

