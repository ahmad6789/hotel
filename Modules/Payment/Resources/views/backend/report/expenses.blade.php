@extends('backend.layouts.app')
@section('content')

    <div class="container ">
        <center>
            <h1 class="text-success mt-4">{{ __("payment.expenses") }} @if(isset($from) && !empty($from)) {{__('reservation.from')}} {{$from}} {{__('reservation.to')}} {{$to}} @endif</h1>
        </center>
		
		<div>
			{{ Form::open(array('url' => route('report.expenses'))) }}
				<div class="form-row align-items-end">
					<div class="col col-md-4">
						<div class="form-group">
							<label for="from">{{ __("reservation.from") }}</label>
							<input type="date" name="from" class="form-control" @if(isset($from) && !empty($from)) value="{{$from}}" @endif/>
						</div>
					</div>
					<div class="col col-md-4">
						<div class="form-group">
							<label for="to">{{ __("reservation.to") }}</label>
							<input type="date" name="to" class="form-control" @if(isset($to) && !empty($to)) value="{{$to}}" @endif/>
						</div>
					</div>
					<div class="col col-md-4">
						<div class="form-group">
							<label for="type">{{__('expense.type')}}</label>
							<select class="form-control" id="type" name="type">
								<option value="">{{__('expense.selecttype')}}</option>
								<option value="wages">{{__('expense.wages')}}</option>
								<option value="purchases">{{__('expense.purchases')}}</option>
								<option value="repairs">{{__('expense.repairs')}}</option>
								<option value="bills">{{__('expense.bills')}}</option>
								<option value="items">{{__('expense.items')}}</option>
								<option value="drawings">{{__('expense.drawings')}}</option>
							</select>
						</div>
					</div>
				</div>
				
				<div class="form-row align-items-end">
					
					<div class="col col-md-4">
						<div class="form-group">
							<label for="addedby">{{__('payment.addedby')}}</label>
							<select class="form-control" id="addedby" name="addedby">
								<option value="">{{__('payment.selectemployee')}}</option>
								@foreach ($users as $user)
									<option value="{{$user->id}}" @if($addedby == $user->id) selected="selected" @endif>{{$user->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col col-md-4">
						<div class="form-group">
							<label for="employeeid">{{__('payment.employee')}}</label>
							<select class="form-control" id="employeeid" name="employeeid">
								<option value="">{{__('payment.selectemployee')}}</option>
								@foreach ($users as $user)
									<option value="{{$user->id}}" @if($employeeid == $user->id) selected="selected" @endif>{{$user->name}}</option>
								@endforeach
							</select>
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
						<th class='' >{{__("payment.id")}}</th>
                        <th class=''>{{__("payment.details")}}</th>
                        <th class=''>{{__("payment.total")}}</th>
                        <th class=''>{{__("payment.date")}}</th>
                        <th class='' >{{__("payment.type")}}</th>
                        <th class='' >{{__("payment.addedby")}}</th>
                    </tr>
                </thead>
                <tbody>
					
					@foreach ($rows as $row)
						<tr class="text-center">
							<td class='' >{{$row->id}}</th>
							<td class=''>{{$row->description}}</th>
							<td class=''>{{$row->total}}</th>
							<td class=''>{{$row->created_at}}</th>
							<td class='' >{{__("expense.".$row->type)}}</th>
							<td class='' >{{$row->addedbyname}}</th>
						</tr>
					@endforeach
					
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