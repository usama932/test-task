<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentHistory;
use App\Models\Order;
use App\Models\OrderExtra;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Orders';
	    return view('admin.order.index',compact('title'));
    }

    public function getorders(Request $request){
       
        $columns = array(
			0 => 'id',
			1 => 'email',
			2 => 'phone_number',
            3 => 'room_id',
			4 => 'created_at',
			5 => 'action'
		);
		
		$totalData = Order::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		
		if(empty($request->input('search.value'))){
			$orders = Order::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
                ->with('room')
				->get();
			$totalFiltered = Order::count();
		}else{
			$search = $request->input('search.value');
			$orders = Order::where([
				['order_id', 'like', "%{$search}%"],
			])
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
                ->with('room')
				->get();
			$totalFiltered = OrderExtra::where([
				
				['order_id', 'like', "%{$search}%"],
			])
				->orWhere('created_at','like',"%{$search}%")
				->count();
		}
		
		
		$data = array();
		
		if($orders){
			foreach($orders as $r){
				$edit_url = route('orders.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="orders[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['email'] = $r->email;
				$nestedData['phone_number'] = $r->phone_number;
				$nestedData['room_id'] = $r->room->title;
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
    public function getorder(Request $request){
        $order = Order::where('id',$request->id)->with('room','bathroom','discount','time_slot','extraorder','cleaningtype')->first();
	
		return view('admin.order.detail', ['title' => 'Order Detail', 'order' => $order]);
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

    public function destroy($id)
    {
        $order = Order::find($id);
	    if($order){
		    $order->delete();
		    Session::flash('success_message', 'Order successfully deleted!');
	    }
	    return redirect()->route('orders.index');
    }
    public function deleteSelectedorders(Request $request)
	{
        
		$input = $request->all();
		$this->validate($request, [
			'orders' => 'required',
		
		]);
		foreach ($input['orders'] as $index => $id) {
			
			$order = Order::find($id);
			if($order){
				$order->delete();
			}
			
		}
		Session::flash('success_message', ' successfully deleted!');
		return redirect()->back();
		
	}
}
