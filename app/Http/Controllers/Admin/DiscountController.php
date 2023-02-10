<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discount;
use Illuminate\Support\Facades\Session;


class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Discount';
	    return view('admin.discount.index',compact('title'));
    }

    public function getdiscounts(Request $request){
        $columns = array(
			0 => 'id',
			1 => 'title',
			2 => 'price',
			3 => 'created_at',
			4 => 'action'
		);
		
		$totalData = Discount::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		
		if(empty($request->input('search.value'))){
			$discounts = Discount::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
			$totalFiltered = Discount::count();
		}else{
			$search = $request->input('search.value');
			$discounts = Discount::where([
				['title', 'like', "%{$search}%"],
			])
				->orWhere('price','like',"%{$search}%")
				->orWhere('created_at','like',"%{$search}%")
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();
			$totalFiltered = Discount::where([
				
				['title', 'like', "%{$search}%"],
			])
				->orWhere('title', 'like', "%{$search}%")
				->orWhere('price','like',"%{$search}%")
				->orWhere('created_at','like',"%{$search}%")
				->count();
		}
		
		
		$data = array();
		
		if($discounts){
			foreach($discounts as $r){
				$edit_url = route('discounts.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="discounts[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['title'] = $r->title;
				$nestedData['amount'] = $r->amount;
				
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
    public function getdisountDetail(Request $request){
        $discount = Discount::findOrFail($request->id);
		
		
		return view('admin.discount.detail', ['title' => 'Room Detail', 'discount' => $discount]);
    }
    public function create()
    {
        $title = 'Add New Discount';
        return view('admin.discount.create',compact('title'));
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
            'amount' => 'required|integer',
           
        ]);
        $discount = Discount::create([
            'title' => $request->title,
            'amount' => $request->amount,
        ]);
        Session::flash('success_message', 'Great! Room has been saved successfully!');
      
        return redirect()->route('discounts.index');
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
        $discount = Discount::find($id);
        $title = 'Edit  Discount';
        return view('admin.discount.edit',compact('title','discount'));
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
            'amount' => 'required|integer',
           
        ]);
        $discount = Discount::where('id', $id)->update([
            'title' => $request->title,
            'amount' => $request->amount,
        ]);
        Session::flash('success_message', 'Great! Discount has been updated successfully!');
      
        return redirect()->route('discounts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $discount = Discount::find($id);
	    if($discount){
		    $discount->delete();
		    Session::flash('success_message', 'Discount successfully deleted!');
	    }
	    return redirect()->route('discounts.index');
    }
    public function deleteSelectedDiscount(Request $request)
	{
        
		$input = $request->all();
		$this->validate($request, [
			'discounts' => 'required',
		
		]);
		foreach ($input['discounts'] as $index => $id) {
			
			$discount = Discount::find($id);
			if($discount){
				$discount->delete();
			}
			
		}
		Session::flash('success_message', ' successfully deleted!');
		return redirect()->back();
		
	}
}
