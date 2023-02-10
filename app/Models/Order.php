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
}
