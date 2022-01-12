@extends('backend.layouts.app')
@section('content')


 <center>
	<div class="container" id="print">
		<table class='table table-borderless'>
            
					<tr>
							<td class="title">
								<!-- <img src="favicon.png" style="width: 100%; max-width: 300px" /> -->
                                <h2>Samarqand Hotel</h2>
							</td>
                           <td></td>
							<td >
								Invoice #: @isset($payment) {{$payment->id}} @endisset<br />
								Created:  @isset($payment) {{$payment->created_at}} @endisset<br />
								
							</td>
					
				
			</tr>
     
			<tr>
				<td>From : @isset($customer) {{$customer->firstname}} {{$customer->lastname}} @endisset</td>
                <td>     </td>
				<td>To :Samarqand hotel</td>
			</tr>
            
		<tr class="information ">
            <tr>
				<td>@isset($customer) {{$customer->address1}}@endisset</td>
                <td>     </td>
                <td>Address 1 :</td>

			  </tr>
			<tr>
		
				<td>@isset($customer) {{$customer->address2}}@endisset</td>
                <td>     </td>
                <td>Address 2 :</td>

			</tr>
			<tr>
			
				<td>@isset($customer) {{$customer->phone1}}@endisset</td>
                <td>     </td>
                <td>0965248481</td>
			</tr>
            <tr>
				
				<td>@isset($customer) {{$customer->phone2}}@endisset</td>
                <td>     </td>
                <td>5959595</td>
			</tr>
			<tr>	
				<td>@isset($customer) {{$customer->city}}@endisset ,@isset($customer) {{$customer->country}}@endisset</td>
                <td>     </td>
                <td>Damascus , Syria :</td>
			</tr>
        </tr>


			<tr>
             
    
				<table class="table">
                <h4 colspan="3" style="text-align: center;">The Content</h4>    
					<thead>
						<th>#</th>
						<th>Item</th>
						<th>Price</th>
						<th>Quantity</th>
						<th>Total</th>
					</thead>
					<tbody>
                        <?php 
                        $counter=0;
                        ?>
                        @foreach($paymentlines as $pl)
						<tr>
							<td>{{++$counter}}</td>
							<td>{{$pl->description}}</td>
							<td>{{$pl->cost}}</td>
							<td>{{$pl->quantity}}</td>
							<td>{{$pl->cost*$pl->quantity}}</td>
						</tr>
                        @endforeach
					
					</tbody>
                    
				</table>

			</tr>
	


                                <tr class="float-right">	
                                <div class="float-right">
                                The Total Cost : @isset( $totalcost) {{ $totalcost->sum}} @endisset
                                </div>
 
                                </tr>


                                
        </div>
</center>


	<script>

		var p = document.getElementById("print");

		window.print();

       

	</script>


@endsection