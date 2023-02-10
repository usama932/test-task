<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CleaningType;
use Illuminate\Support\Facades\Session;

class CleaningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Cleaning Types';
	    return view('admin.cleaningtype.index',compact('title'));
    }

   
    public function getcleaningtypes(Request $request){
        $columns = array(
			0 => 'id',
			1 => 'title',
			2 => 'price',
			4 => 'created_at',
			5 => 'action'
		);
		
		$totalData = CleaningType::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		
		if(empty($request->input('search.value'))){
			$cleanings = CleaningType::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
			$totalFiltered = CleaningType::count();
		}else{
			$search = $request->input('search.value');
			$cleanings = CleaningType::where([
				['title', 'like', "%{$search}%"],
			])
				->orWhere('price','like',"%{$search}%")
				->orWhere('created_at','like',"%{$search}%")
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();
			$totalFiltered = CleaningType::where([
				
				['title', 'like', "%{$search}%"],
			])
				->orWhere('title', 'like', "%{$search}%")
				->orWhere('price','like',"%{$search}%")
				->orWhere('created_at','like',"%{$search}%")
				->count();
		}
		
		
		$data = array();
		
		if($cleanings){
			foreach($cleanings as $r){
				$edit_url = route('cleaning_types.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="cleanings[]" value="'.$r->id.'"><span></span></label></td>';
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
    public function getcleaningtype(Request $request){
        $cleaning_type = CleaningType::findOrFail($request->id);
		
		
		return view('admin.cleaningtype.detail', ['title' => 'Cleaning_type Detail', 'cleaning_type' => $cleaning_type]);
    }
    public function create()
    {
        $title = 'Add New Cleaning-Type';
        return view('admin.cleaningtype.create',compact('title'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'price' => 'required|integer',
           
        ]);
        $room = CleaningType::create([
            'title' => $request->title,
            'price' => $request->price,
        ]);
        Session::flash('success_message', 'Great! CleaningType has been saved successfully!');
      
        return redirect()->route('cleaning_types.index');
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
        $cleaning_type = CleaningType::find($id);
        $title = 'Edit  CleaningType';
        return view('admin.cleaningtype.edit',compact('title','cleaning_type'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'price' => 'required|integer',
           
        ]);
        $room = CleaningType::where('id',$id)->update([
            'title' => $request->title,
            'price' => $request->price,
        ]);
        Session::flash('success_message', 'Great! CleaningType has been Updated successfully!');
      
        return redirect()->route('cleaning_types.index');
    }

    public function destroy($id)
    {
        $cleaning_type = CleaningType::find($id);
	    if($cleaning_type){
		    $cleaning_type->delete();
		    Session::flash('success_message', 'Cleaning_type successfully deleted!');
	    }
	    return redirect()->route('cleaning_types.index');
    }
    public function deleteSelectedCleaningTypes(Request $request)
	{
        
		$input = $request->all();
		$this->validate($request, [
			'cleanings' => 'required',
		
		]);
		foreach ($input['cleanings'] as $index => $id) {
			
			$cleaning_type = CleaningType::find($id);
			if($cleaning_type){
				$cleaning_type->delete();
			}
			
		}
		Session::flash('success_message', ' successfully deleted!');
		return redirect()->back();
		
	}
}
