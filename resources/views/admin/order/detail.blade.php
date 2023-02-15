<div class="card-datatable table-responsive">
	<table id="clients" class="datatables-demo table table-striped table-bordered">
		<tbody>
		<tr>
			<td>First Name</td>
			<td>{{$order->first_name}}</td>
		</tr>
		<tr>
			<td>Last Name</td>
			<td>{{$order->last_name}}</td>
		</tr>
		<tr>
			<td>Email</td>
			<td>{{$order->email}}</td>
		</tr>
        <tr>
			<td>Phone #</td>
			<td>{{$order->phone_number}}</td>
		</tr>
        <tr>
			<td>Room</td>
			<td>{{$order->room->title}}</td>
		</tr>
        <tr>
			<td>Bathroom</td>
			<td>{{$order->bathroom->title}}</td>
		</tr>
        <tr>
			<td>Discount</td>
			<td>{{$order->discount->title}}</td>
		</tr>
        <tr>
			<td>TimeSlot</td>
			<td>{{$order->time_slot->time_slot}}</td>
		</tr>
		<tr>
			<td>Extra Services</td>
			<td>
				@foreach($order->extraorder as $service)
				{{$service->service->title}},&nbsp
				@endforeach
			</td>
		</tr>
        <tr>
			<td>Clean Type</td>
			<td>
				@foreach($order->cleaningtype as $cleantype)
				{{$cleantype->cleantype->title}},&nbsp
				@endforeach
			</td>
		</tr>
        <tr>
			<td>Contact With Covid Person</td>
			<td>{{$order->contact_with_covid_person}}</td>
		</tr>
        <tr>
			<td>Date</td>
			<td>{{$order->date}}</td>
		</tr>
        <tr>
			<td>Total Bill</td>
			<td>{{$order->total_bill}}</td>
		</tr>
		<tr>
			<td>Created at</td>
			<td>{{$order->created_at}}</td>
		</tr>
		
		</tbody>
	</table>
</div>