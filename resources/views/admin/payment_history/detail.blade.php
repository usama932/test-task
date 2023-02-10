<div class="card-datatable table-responsive">
	<table id="clients" class="datatables-demo table table-striped table-bordered">
		<tbody>
		<tr>
			<td>Payment_id</td>
			<td>{{$payment_history->payment_id}}</td>
		</tr>
		<tr>
			<td>Order_id</td>
			<td>{{$payment_history->order_id}}</td>
		</tr>
		
		<tr>
			<td>Created at</td>
			<td>{{$payment_history->created_at}}</td>
		</tr>
		
		</tbody>
	</table>
</div>

