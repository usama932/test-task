<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CleaningType extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'price'
    ];
    
    public function cleaning_type_order()
    {
        return $this->hasMany(OrderCleanType::class,'cleantype_id','id');
    }
}
