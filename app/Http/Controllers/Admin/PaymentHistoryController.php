<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentHistory;
use App\Models\Order;
use App\Models\OrderExtra;
use Illuminate\Support\Facades\Session;


class PaymentHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Payment Histories';
	    return view('admin.payment_history.index',compact('title'));
    }

    public function getpayments(Request $request){
       
        $columns = array(
			0 => 'id',
			1 => 'payment_id',
			2 => 'order_id',
			4 => 'created_at',
			5 => 'action'
		);
		
		$totalData = PaymentHistory::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		
		if(empty($request->input('search.value'))){
			$payments = PaymentHistory::offset($start)
				->limit($limit)
				->orderBy($order,$dir)
				->get();
			$totalFiltered = PaymentHistory::count();
		}else{
			$search = $request->input('search.value');
			$payments = PaymentHistory::where([
				['payment_id', 'like', "%{$search}%"],
			])
				->orWhere('order_id','like',"%{$search}%")
				->orWhere('created_at','like',"%{$search}%")
				->offset($start)
				->limit($limit)
				->orderBy($order, $dir)
				->get();
			$totalFiltered = PaymentHistory::where([
				
				['payment_id', 'like', "%{$search}%"],
			])
				->orWhere('payment_id', 'like', "%{$search}%")
				->orWhere('order_id','like',"%{$search}%")
				->orWhere('created_at','like',"%{$search}%")
				->count();
		}
		
		
		$data = array();
		
		if($payments){
			foreach($payments as $r){
				$edit_url = route('payment_histories.edit',$r->id);
				$nestedData['id'] = '<td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="payments[]" value="'.$r->id.'"><span></span></label></td>';
				$nestedData['payment_id'] = $r->payment_id;
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
    public function getpayment(Request $request){
        $payment_history = PaymentHistory::findOrFail($request->id);
		
		
		return view('admin.payment_history.detail', ['title' => 'Cleaning_type Detail', 'payment_history' => $payment_history]);
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        //
    }

  
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

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
        $payment = PaymentHistory::find($id);
	    if($payment){
		    $payment->delete();
		    Session::flash('success_message', 'payments successfully deleted!');
	    }
	    return redirect()->route('payment_histories.index');
    }
    public function deleteSelectedpayments(Request $request)
	{
        
		$input = $request->all();
		$this->validate($request, [
			'payments' => 'required',
		
		]);
		foreach ($input['payments'] as $index => $id) {
			
			$payment = PaymentHistory::find($id);
			if($payment){
				$payment->delete();
			}
			
		}
		Session::flash('success_message', ' successfully deleted!');
		return redirect()->back();
		
	}
}
