<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\ExtraServices;
use Illuminate\Support\Facades\Session;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Rooms';
	    return view('admin.rooms.index',compact('title'));
    }

    public function getRooms(Request $request){
        $columns = array(
			0 => 'id',
			1 => 'title',
			2 => 'price',
			4 => 'created_at',
			5 => 'action'
		);
		
		$totalData = Room::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		
		if(empty($request->input('search.value'))){
			$rooms = Room::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
			$totalFiltered = Room::count();
		}else{
			$search = $request->input('search.value');
			$rooms = Room::where([
				['title', 'like', "%{$search}%"],
			])
				->orWhere('price','like',"%{$search}%")
				->orWhere('created_at','like',"%{$search}%")
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();
			$totalFiltered = Room::where([
				
				['title', 'like', "%{$search}%"],
			])
				->orWhere('title', 'like', "%{$search}%")
				->orWhere('price','like',"%{$search}%")
				->orWhere('created_at','like',"%{$search}%")
				->count();
		}
		
		
		$data = array();
		
		if($rooms){
			foreach($rooms as $r){
				$edit_url = route('rooms.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="rooms[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['title'] = $r->title;
				$nestedData['price'] = $r->price;
				
				$nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));
				$nestedData['action'] = '
                                <div>
                                <td>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" title="View Client" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>
                                    <a title="Edit Client" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();del('.$r->id.');" title="Delete Client" href="javascript:void(0)">
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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function RoomDetail(Request $request){
        $room = Room::findOrFail($request->id);
		
		
		return view('admin.rooms.detail', ['title' => 'Room Detail', 'room' => $room]);
    }
    public function create()
    {
        $title = 'Add New Room';
        return view('admin.rooms.create',compact('title'));
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
        'title' => 'required|max:255',
        'price' => 'required|integer',
       
    ]);
    $room = Room::create([
        'title' => $request->title,
        'price' => $request->price,
    ]);
    Session::flash('success_message', 'Great! Room has been saved successfully!');
  
    return redirect()->route('rooms.index');
       
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
        $room = Room::find($id);
        $title = 'Edit  Room';
        return view('admin.rooms.edit',compact('title','room'));
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
            'title' => 'required|max:255',
            'price' => 'required|integer',
           
        ]);
        $room = Room::where('id', $id)->update([
            'title' => $request->title,
            'price' => $request->price,
        ]);
        Session::flash('success_message', 'Great! Room has been updated successfully!');
      
        return redirect()->route('rooms.index');
           
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $room = Room::find($id);
	    if($room){
		    $room->delete();
		    Session::flash('success_message', 'Room successfully deleted!');
	    }
	    return redirect()->route('rooms.index');
    }
    public function deleteSelectedRooms(Request $request)
	{
        
		$input = $request->all();
		$this->validate($request, [
			'rooms' => 'required',
		
		]);
		foreach ($input['rooms'] as $index => $id) {
			
			$rooms = Room::find($id);
			if($rooms){
				$rooms->delete();
			}
			
		}
		Session::flash('success_message', ' successfully deleted!');
		return redirect()->back();
		
	}
}
