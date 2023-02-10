<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentHistory;
use App\Models\Order;
use App\Models\OrderExtra;
use Illuminate\Support\Facades\Session;

class OrderExtraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Extra-order';
	    return view('admin.extra_order.index',compact('title'));
    }

   
    public function getextraorders(Request $request){
       
        $columns = array(
			0 => 'id',
			1 => 'extra_service_id',
			2 => 'order_id',
			4 => 'created_at',
			5 => 'action'
		);
		
		$totalData = OrderExtra::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		
		if(empty($request->input('search.value'))){
			$extra_orders = OrderExtra::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
			$totalFiltered = OrderExtra::count();
		}else{
			$search = $request->input('search.value');
			$extra_orders = OrderExtra::where([
				['order_id', 'like', "%{$search}%"],
			])
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();
			$totalFiltered = OrderExtra::where([
				
				['order_id', 'like', "%{$search}%"],
			])
				->orWhere('created_at','like',"%{$search}%")
				->count();
		}
		
		
		$data = array();
		
		if($extra_orders){
			foreach($extra_orders as $r){
				$edit_url = route('extra_orders.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="extra_orders[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['extra_service_id'] = $r->extra_service_id;
				$nestedData['order_id'] = $r->order_id;
				
				$nestedData['created_at'] = date('d-m-Y',strtotime($r->created_at));
				$nestedData['action'] = '
                                <div>
                                <td>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();viewInfo('.$r->id.');" title="View payemnts" href="javascript:void(0)">
                                        <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                    </a>
                                    <a title="Edit Client" class="btn btn-sm btn-clean btn-icon"
                                       href="'.$edit_url.'">
                                       <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();del('.$r->id.');" title="Delete payments" href="javascript:void(0)">
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
    public function getextraorder(Request $request){
        $extra_order = OrderExtra::findOrFail($request->id);
		
		
		return view('admin.extra_order.detail', ['title' => 'Extra_order Detail', 'extra_order' => $extra_order]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $extra_order = OrderExtra::find($id);
	    if($extra_order){
		    $extra_order->delete();
		    Session::flash('success_message', 'extra_order successfully deleted!');
	    }
	    return redirect()->route('payment_histories.index');
    }
    public function deleteSelectedextra_orders(Request $request)
	{
        
		$input = $request->all();
		$this->validate($request, [
			'extra_orders' => 'required',
		
		]);
		foreach ($input['extra_orders'] as $index => $id) {
			
			$extra_order = OrderExtra::find($id);
			if($extra_order){
				$extra_order->delete();
			}
			
		}
		Session::flash('success_message', ' successfully deleted!');
		return redirect()->back();
		
	}
}
