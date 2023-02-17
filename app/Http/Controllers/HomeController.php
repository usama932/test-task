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
use Response;
use Illuminate\Support\Facades\Validator;


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


        return view('frontend.front' ,compact('rooms' ,'bathrooms' ,'services','discounts','cleaning_types','time_slots'));
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
    //    dd($request->all());

        $validator = Validator::make($request->all(), [
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
                    
        if(!empty($request->totalbill)){
            $totalbills =   explode('$', $request->totalbill);
           
           
        }
                      
        if ($validator->fails())
            {
                return redirect()->back()->with('error','Fill Inputs Correctly');
            }
        else
            {
            $discount = 0 ;
            if(!empty($request->discount))
            {
                $coupen = Discount::where('coupen',$request->discount)->first();
                if(empty($coupen)){
        
                    $discount = 0 ;   
                    return redirect()->back()->with('warning','Something Wrong in Your Dis% Coupen');
                }
                else{
                    $discount = $coupen->amount;    
                }      
            }
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $stripe = Stripe\Charge::create([
                "amount" => $totalbills[1] * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Test payment from itsolutionstuff.com." 
            ]);

            $order  = Order::create([
                'first_name' =>  $request->first_name, 
                'last_name'          =>  $request->last_name,
                'email'              =>  $request->email,
                'phone_number'       =>  $request->phone_number,
                'address'            =>  $request->address,
                'zipcode'            =>  $request->zipcode,
                'apt_suite_number'   =>  $request->apt_suite_number,
                'city'               =>  $request->city,
                'state'              =>  $request->state,
                'room_id'            =>  $request->room_id,
                'date'               =>  date('Y-m-d' , strtotime($request->date)),
                'bathroom_id'        =>  $request->bathroom_id,
                'discount_id'        =>  $discount,
                'time_slot_id'       =>  $request->time_slot_id,
                'total_bill'         =>  $totalbills[1],
    
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

            if(!empty($order->id) && !empty($strip->id) ){
                PaymentHistory::create([
                    'payment_id'    => $strip->id,
                    'order_id'      => $request->order_id
                ]);
            }
            
            return redirect()->back()->with('success','Cogratulation..! Booking Successfully');
        }
       
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
    public function applydiscount(Request $request){
        if($request->coupen)
        {
            $totalbill = 0;
            $result    = "OK";
            $coupen = Discount::where('coupen',$request->coupen)->where('redeem','free')->first();
            
                if(empty($coupen)){
                    
                    $totalbill = $request->totalbill;
                    $result    = "Something went wrong in your coupen";
                }
                else{
                    if($request->totalbill >= $request->coupen && $request->totalbill != '0')
                    {
                        $totalbill = $request->totalbill - $coupen->amount;
                        $discount = $coupen->amount;  
                        Discount::where('id',$coupen->id)->update([
                            'redeem' => 'used'
                        ]);
                        $result    = "Valid Coupen";
                    }
                    else{
                        $totalbill = $request->totalbill;
                        $result    = "You can't avail this";
                    }
                }
              
                return Response::json(['totalbill' => $totalbill,'result' => $result], 200);    
                
        }
    }
}
