<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExtraService;
use Illuminate\Support\Facades\Session;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Extra services';
	    return view('admin.extraservices.index',compact('title'));
    }

    public function getservices(Request $request){
       
        $columns = array(
			0 => 'id',
			1 => 'title',
			2 => 'price',
            3 => 'type',
			4 => 'created_at',
			5 => 'action'
		);
		
		$totalData = ExtraService::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		
		if(empty($request->input('search.value'))){
			$services = ExtraService::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
			$totalFiltered = ExtraService::count();
		}else{
			$search = $request->input('search.value');
			$services = ExtraService::where([
				['title', 'like', "%{$search}%"],
			])
				->orWhere('price','like',"%{$search}%")
				->orWhere('created_at','like',"%{$search}%")
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();
			$totalFiltered = ExtraService::where([
				
				['title', 'like', "%{$search}%"],
			])
				->orWhere('title', 'like', "%{$search}%")
				->orWhere('price','like',"%{$search}%")
				->orWhere('created_at','like',"%{$search}%")
				->count();
		}
		
		
		$data = array();
		
		if($services){
			foreach($services as $r){
				$edit_url = route('services.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="services[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['title'] = $r->title;
				$nestedData['price'] = $r->price;
                $nestedData['type'] = $r->type;
				
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
    public function bathServiceDetail(Request $request){
        
        $service = ExtraService::findOrFail($request->id);
		return view('admin.extraservices.detail', ['title' => 'Extra-Service Detail', 'service' => $service]);
    }
    public function create()
    {
        $title = 'Add New Extra-Service';
        return view('admin.extraservices.create',compact('title'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'price' => 'required|integer',
            'type'  => 'required',
           
        ]);
        $room = ExtraService::create([
            'title' => $request->title,
            'price' => $request->price,
            'type'  => $request->type,
        ]);
        Session::flash('success_message', 'Great! ExtraService has been saved successfully!');
      
        return redirect()->route('services.index');
           
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
        $service = ExtraService::find($id);
        $title = 'ExtraService  Room';
        return view('admin.extraservices.edit',compact('title','service'));
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
        $room = ExtraService::where('id', $id)->update([
            'title' => $request->title,
            'price' => $request->price,
            'type' => $request->type,
        ]);
        Session::flash('success_message', 'Great! ExtraService has been updated successfully!');
      
        return redirect()->route('services.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = ExtraService::find($id);
	    if($service){
		    $service->delete();
		    Session::flash('success_message', 'Extra-Service successfully deleted!');
	    }
	    return redirect()->route('services.index');
    }
    public function deleteSelectedbathRooms(Request $request)
	{
        
		$input = $request->all();
		$this->validate($request, [
			'services' => 'required',
		
		]);
		foreach ($input['services'] as $index => $id) {
			
			$services = ExtraService::find($id);
			if($services){
				$services->delete();
			}
			
		}
		Session::flash('success_message', ' successfully deleted!');
		return redirect()->back();
		
	}
}
