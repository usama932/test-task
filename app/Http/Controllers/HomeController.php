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

    public function gettotalbill(Request $request){
        $total_bill = 0;
        if(!empty($request->room_id) &&  $request->room_id != null && $request->room_id != 0){
            $room          = Room::where('id',$request->room_id)->first();
            $total_bill = $total_bill + $room->price;
        }
        if(!empty($request->bathroom_id) &&  $request->bathroom_id != null && $request->bathroom_id != 0){
            $bathroom      = Bathroom::where('id',$request->bathroom_id)->first();
            $total_bill = $total_bill + $bathroom->price;
        }
        return $total_bill;
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
        dd($request->all());
        
       
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
