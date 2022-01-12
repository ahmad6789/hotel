@extends('backend.layouts.app')
@section('content')

    <div class="container ">
        <center>
            <h1 class="text-success mt-4">{{ __("payment.profitloss") }} @if($from) {{__('reservation.from')}} {{$from}} {{__('reservation.to')}} {{$to}} @endif</h1>
        </center>
		
		<div>
			{{ Form::open(array('url' => route('report.profitloss'))) }}
				<div class="form-row align-items-end">
					<div class="col col-md-4">
						<div class="form-group">
							<label for="from">{{ __("reservation.from") }}</label>
							<input type="date" name="from" class="form-control" @if($from) value="{{$from}}" @endif/>
						</div>
					</div>
					<div class="col col-md-4">
						<div class="form-group">
							<label for="to">{{ __("reservation.to") }}</label>
							<input type="date" name="to" class="form-control" @if($to) value="{{$to}}" @endif/>
						</div>
					</div>
					<div class="col col-md-4">
						<div class="form-group">
							<button class="btn btn-primary" type="submit">{{__('payment.go')}}</button>
						</div>
					</div>
				</div>
			{{ Form::close() }}
		</div>
		
        <div clsss="table-responsive">
            
            <table class="table data-table table-bordered table-condensed table-hover table-striped bg-white">
                <thead>
					<tr class="text-center">
                        <th class=''>{{__("payment.details")}}</th>
                        <th class='' style="width:70%">{{__("payment.sum")}}</th>
                    </tr>
                </thead>
                <tbody>

					@php
						$grandtotal = 0;
						$totalincome = 0;
						$totalexpenses = 0;
						$totaldrawings = 0;
					@endphp
					@foreach ($income as $row)
						<tr>
							<td>{{__("payment.".$row->type)}}</td>
							<td>{{$row->total}}</td>
						</tr>
						@php
						$totalincome += $row->total;
						@endphp
					@endforeach
					
					<tr class="table-success">
						<td>{{__("payment.totalincome")}}</td>
						<td>{{ $totalincome}}</td>
					</tr>
					

					
					@foreach ($expenses as $row)
						<tr>
							<td>{{__("expense.".$row->type)}}</td>
							<td>{{$row->total}}</td>
						</tr>
						@php
						$totalexpenses += $row->total;
						@endphp
					@endforeach
					
					<tr class="table-warning">
						<td>{{__("payment.totalexpenses")}}</td>
						<td>{{ $totalexpenses}}</td>
					</tr>
					

					
					@foreach ($drawings as $row)
						<tr>
							<td>{{$row->type}}</td>
							<td>{{$row->total}}</td>
						</tr>
						@php
						$totaldrawings += $row->total;
						@endphp
					@endforeach
					
					<tr class="table-warning">
						<td>{{__("payment.totaldrawings")}}</td>
						<td>{{ $totaldrawings}}</td>
					</tr>
					
					<tr>
						<td>{{__("payment.grandtotal")}}</td>
						<td>{{ $totalincome - $totalexpenses - $totaldrawings}}</td>
					</tr>
                </tbody>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
			$.noConflict();
			$('.data-table').DataTable({
				bSort : false,
				searching: false, 
				paging: false, 
				info: false,
				"pageLength": 50,
				"dom": 'B<"toolbar"><"top fg-toolbar ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix"flip>t<"bottom fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix"ip><"clear">',
				buttons: [
					{ extend: 'excelHtml5', className: 'btn btn-primary' },
					{ extend: 'csvHtml5', className: 'btn btn-success' },
				]
			} );
		} );
    </script>
@endsection