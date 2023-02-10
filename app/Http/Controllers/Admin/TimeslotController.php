<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TimeSlots;
use Illuminate\Support\Facades\Session;

class TimeslotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Time Slot';
	    return view('admin.time_slot.index',compact('title'));
    }

    public function gettimeslots(Request $request){
        $columns = array(
			0 => 'id',
			1 => 'day',
			2 => 'time_slot',
			4 => 'created_at',
			5 => 'action'
		);
		
		$totalData = TimeSlots::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		
		if(empty($request->input('search.value'))){
			$time_slots = TimeSlots::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
			$totalFiltered = TimeSlots::count();
		}else{
			$search = $request->input('search.value');
			$time_slots = TimeSlots::where([
				['day', 'like', "%{$search}%"],
			])
				->orWhere('time_slot','like',"%{$search}%")
				->orWhere('created_at','like',"%{$search}%")
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();
			$totalFiltered = TimeSlots::where([
				
				['day', 'like', "%{$search}%"],
			])
				->orWhere('day', 'like', "%{$search}%")
				->orWhere('time_slot','like',"%{$search}%")
				->orWhere('created_at','like',"%{$search}%")
				->count();
		}
		
		
		$data = array();
		
		if($time_slots){
			foreach($time_slots as $r){
				$edit_url = route('time_slots.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="time_slots[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['day'] =date('d-m-Y',strtotime($r->day)); 
				$nestedData['time_slot'] = $r->time_slot;
				
				$nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));
				$nestedData['action'] = '
                                <div>
                                <td>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" title="View time_slot" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>
                                    <a title="Edit time_slot" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();del('.$r->id.');" title="Delete time_slot" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-delete"></i>
                                    </a>
                                </td>
                                </div>
                            ';
				$data[] = $nestedData;
			}
		}
		
		$json_data = array(
			"draw"			=> intval($request->input('draw')),
			"recordsTotal"	=> intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"			=> $data
		);
		
		echo json_encode($json_data);
		
      
    }

    public function gettimeslot(Request $request){
        $time_slot = TimeSlots::findOrFail($request->id);
		
		
		return view('admin.time_slot.detail', ['title' => 'Time slot Detail', 'time_slot' => $time_slot]);
    }
    public function create()
    {
        $title = 'Add New Time_slot';
        return view('admin.time_slot.create',compact('title'));
    }
  
    public function store(Request $request)
    {
      
        $this->validate($request, [
            'day' => 'required',
            'time_slot' => 'required',
           
        ]);
        $room = TimeSlots::create([
            'day' => $request->day,
            'time_slot' => $request->time_slot,
        ]);
        Session::flash('success_message', 'Great! TimeSlots has been saved successfully!');
      
        return redirect()->route('time_slots.index');
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
        $time_slot = TimeSlots::find($id);
        $title = 'Edit  TimeSlots';
        return view('admin.time_slot.edit',compact('title','time_slot'));
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
        $this->validate($request, [
            'day' => 'required',
            'time_slot' => 'required',
           
        ]);
        $room = TimeSlots::where('id',$id)->update([
            'day' => $request->day,
            'time_slot' => $request->time_slot,
        ]);
        Session::flash('success_message', 'Great! TimeSlots has been Update successfully!');
      
        return redirect()->route('time_slots.index');
    }

    public function destroy($id)
    {
        $time_slot = TimeSlots::find($id);
	    if($time_slot){
		    $time_slot->delete();
		    Session::flash('success_message', 'time_Slots successfully deleted!');
	    }
	    return redirect()->route('time_slots.index');
    }
    public function deleteSelectedTimeslot(Request $request)
	{
        
		$input = $request->all();
		$this->validate($request, [
			'time_slots' => 'required',
		
		]);
		foreach ($input['time_slots'] as $index => $id) {
			
			$time_slot = TimeSlots::find($id);
			if($time_slot){
				$time_slot->delete();
			}
			
		}
		Session::flash('success_message', ' successfully deleted!');
		return redirect()->back();
		
	}
}
