<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name', 'last_name' , 'email','address' ,'zipcode','phone_number',
        'state','room_id' ,'apt_suite_number','bathroom_id','total_bill', 'discount_id',
        'date' , 'city','time_slot_id' ,'clean_type_id','contact_with_covid_person','inform_maid'
    ];
    public function room()
    {
        return $this->belongsTo(Room::class,'room_id','id');
    }
    public function bathroom()
    {
        return $this->belongsTo(Room::class,'bathroom_id','id');
    }
    public function time_slot()
    {
        return $this->belongsTo(TimeSlots::class,'time_slot_id','id');
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class,'discount_id','id');
    }
    public function extraorder()
    {
        return $this->hasMany(OrderExtra::class,'order_id','id');
    }
    public function cleaningtype()
    {
        return $this->hasMany(OrderCleanType::class,'order_id','id');
    }
}
