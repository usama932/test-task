<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PaymentHistory;
use App\Models\TimeSlots;
use App\Models\Bathroom;
use App\Models\Room;
use App\Models\OrderExtra;
use App\Models\ExtraService;
use App\Models\Discount;
use App\Models\CleaningType;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Session;
use Stripe;
use App\Models\OrderCleanType;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms          = Room::all();
        $bathrooms      = Bathroom::all();
        $services       = ExtraService::all();
        $discounts       = Discount::all();
        $cleaning_types = CleaningType::all();
        $time_slots     = TimeSlots::all();


        return view('front_home' ,compact('rooms' ,'bathrooms' ,'services','discounts','cleaning_types','time_slots'));
    }


    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
            'state' => 'required',
            'zipcode' => 'required',
            'room_id' => 'required',
            'totalbill' => 'required',
           
        ]);
        
      
        $order  = Order::create([
            'first_name' =>  $request->first_name,
            
            'last_name'          =>  $request->last_name,
            'email'              =>  $request->email,
            'phone_number'       =>  $request->phone_number,
            'address'            =>  $request->address,
            'zipcode'            =>  $request->zipcode,
            'apt_suite_number'   => $request->apt_suite_number,
            'city'               =>  $request->city,
            'state'              =>  $request->state,
            'room_id'            =>  $request->room_id,
            'date'               =>  $request->date,
            'bathroom_id'        =>  $request->bathroom_id,
            'discount_id'        =>  $request->discount_id,
            'time_slot_id'       =>  $request->time_slot_id,
            'total_bill'          =>  $request->totalbill,
            'contact_with_covid_person' =>  $request->date,

        ]);
        if(!empty($request->services)){
            $services = $request->services;
                foreach ($services as $key => $service){ 
                    OrderExtra::create([
                        'extra_service_id' => $service,
                        'order_id'         => $order->id
                    ]);
                }
        }
        if(!empty($request->cleaning_types)){
            $cleaning_types = $request->cleaning_types;
                foreach ($cleaning_types as $key => $cleaning_type){ 
                    OrderCleanType::create([
                        'cleantype_id' => $cleaning_type,
                        'order_id'         => $order->id
                    ]);
                }
        }
        if(!empty($request->order_id) && !empty($strip->id) ){
            PaymentHistory::create([
                'payment_id'    => $strip->id,
                'order_id'      => $request->order_id
            ]);
        }
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $stripe = Stripe\Charge::create ([
            "amount" => $request->total_bill * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Test payment from itsolutionstuff.com." 
    ]);

        Session::flash('success', 'Payment successful!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
