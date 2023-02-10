<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bathroom;
use Illuminate\Support\Facades\Session;

class BathroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Bath-Rooms';
	    return view('admin.bathroom.index',compact('title'));
    }
    public function getbathRooms(Request $request){
        $columns = array(
			0 => 'id',
			1 => 'title',
			2 => 'price',
			4 => 'created_at',
			5 => 'action'
		);
		
		$totalData = Bathroom::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		
		if(empty($request->input('search.value'))){
			$bathrooms = Bathroom::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
			$totalFiltered = Bathroom::count();
		}else{
			$search = $request->input('search.value');
			$bathrooms = Bathroom::where([
				['title', 'like', "%{$search}%"],
			])
				->orWhere('price','like',"%{$search}%")
				->orWhere('created_at','like',"%{$search}%")
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();
			$totalFiltered = Bathroom::where([
				
				['title', 'like', "%{$search}%"],
			])
				->orWhere('title', 'like', "%{$search}%")
				->orWhere('price','like',"%{$search}%")
				->orWhere('created_at','like',"%{$search}%")
				->count();
		}
		
		
		$data = array();
		
		if($bathrooms){
			foreach($bathrooms as $r){
				$edit_url = route('bathrooms.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="bathrooms[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['title'] = $r->title;
				$nestedData['price'] = $r->price;
				
				$nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));
				$nestedData['action'] = '
                                <div>
                                <td>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" title="View Client" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>
                                    <a title="Edit Bathroom" class="btn btn-sm btn-clean btn-icon"
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
    public function bathRoomDetail(Request $request){
        $bathroom = Bathroom::findOrFail($request->id);
		
		
		return view('admin.bathroom.detail', ['title' => 'Room Detail', 'bathroom' => $bathroom]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add New BathRoom';
        return view('admin.bathroom.create',compact('title'));
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
        $bathroom = Bathroom::create([
            'title' => $request->title,
            'price' => $request->price,
        ]);
        Session::flash('success_message', 'Great! Room has been saved successfully!');
      
        return redirect()->route('bathrooms.index');
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
        $bathroom = BathRoom::find($id);
        $title = 'Edit  Bathroom';
        return view('admin.bathroom.edit',compact('title','bathroom'));
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
        $bathroom = Bathroom::where('id', $id)->update([
            'title' => $request->title,
            'price' => $request->price,
        ]);
        Session::flash('success_message', 'Great! Bathroom has been updated successfully!');
      
        return redirect()->route('bathrooms.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bathroom = Bathroom::find($id);
	    if($bathroom){
		    $bathroom->delete();
		    Session::flash('success_message', 'Bathroom successfully deleted!');
	    }
	    return redirect()->route('bathrooms.index');
    }
    public function deleteSelectedbathRooms(Request $request)
	{
        
		$input = $request->all();
		$this->validate($request, [
			'bathrooms' => 'required',
		
		]);
		foreach ($input['bathrooms'] as $index => $id) {
			
			$bathrooms = Bathroom::find($id);
			if($bathrooms){
				$bathrooms->delete();
			}
			
		}
		Session::flash('success_message', ' successfully deleted!');
		return redirect()->back();
		
	}
}
