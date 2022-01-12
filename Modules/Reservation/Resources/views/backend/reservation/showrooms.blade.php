@extends('backend.layouts.app')
@section('content')
 
    <div class='container-sm text-info ' style="align: center">
      	<!--<div class="row text-center">
			<div class="col col-md-4">
				<div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
					<div class="card-header">{{ __('reservation.availablerooms')}}</div>
					<div class="card-body">
						<h5 class="card-title" id="available-count"></h5>
						<p class="card-text"></p>
					</div>
				</div>
			</div>
			<div class="col col-md-4">
				<div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
					<div class="card-header">{{ __('reservation.occupiedrooms')}}</div>
					<div class="card-body">
						<h5 class="card-title" id="booked-count"></h5>
						<p class="card-text"></p>
					</div>
				</div>
			</div>
			<div class="col col-md-4">
				<div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
					<div class="card-header">{{ __('reservation.roomissues')}}</div>
					<div class="card-body">
						<h5 class="card-title" id="issues-count"></h5>
						<p class="card-text"></p>
					</div>
				</div>
			</div>
		</div>-->
		
		<div clsss="table-responsive">
			<div class="row">
				<div class="col-8">
					<h4 class="card-title mb-0">
						<i class="fa fa-book" ></i> {{ __('reservation.reservations')}} <small class="text-muted"></small>
					</h4>
					<div class="small text-muted">
						{{ __('reservation.reservationmanagement')}}
					</div>
				</div>
				<div class="col-4">
					<div class="float-right">
						<a href="{{route("reservation.create")}}" class="btn btn-success  "  title="{{ __('reservation.addreservation')}}"  >
							<i class="fas fa-plus-circle"></i>
						</a>
					</div>
				</div>
			</div>
		</div>
		
		<table id="reservation-table" class="table table-bordered data-table table-condensed table-hover">
			<thead>
				<tr>
					<th class='text-success'>{{ __('reservation.room')}}</th>
					<th class='text-info '>{{ __('reservation.customer')}}</th>
					<th class='text-muted '></th>
					<th class='text-muted '>{{ __('reservation.status')}}</th>
					<th class='text-muted '>{{ __('reservation.bookingstart')}}</th>
					<th class='text-muted '>{{ __('reservation.bookingend')}}</th>
					<th width="100px" class='Action'>{{ __('reservation.action')}}</th>
				</tr>
			</thead>
			<tbody>
			
			</tbody>
		</table>
	
	<div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                          
                        <h4 class="modal-title">{{ __('reservation.checkout')}}</h4>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('reservation.checkoutconfirm')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-id="" id="ok" class="btn btn-danger" data-dismiss="modal">{{ __('reservation.ok')}}</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __('reservation.cancel')}}</button>

                    </div>
                </div>

            </div>
        </div>
		
	  <div id="sticky">
        </div>
    </div>
	
	<script type="text/javascript">
		
		$(document).on('click', '.checkout', function() {
                $('#ok').attr('data-id', $(this).attr('data-id'));
		});
		$(document).on('click', '#ok', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                var id = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('reservation.checkout') }}/" + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(data) {
                        if (data.response == true) {
                            var table = $('.data-table').DataTable();
                            table.draw();
							displayPopupNotification("{{__('reservation.success')}}", 'success');
                        } else {
							displayPopupNotification("{{__('reservation.fail')}}", 'fail');
						}
                    }
                });
                $('#ok').attr('data-id', '');

            });
			
        $(document).ready(function populate() {
            $.noConflict();
            var table = $('#reservation-table');
			table.DataTable({
                processing: true,
                serverSide: true,
				"order": [[ 3, 'asc' ], [ 4, 'asc' ]],
                ajax: "{{ route('reservation.showroomsgettable') }}",
                columns: [
                    {
                        data: 'code',
                        name: '{{ __("reservation.code")}}'
                    },
                    {
                        data: 'customer',
                        name: '{{ __("reservation.customer")}}'
                    },
                    {
                        data: 'status',
                        name: '{{ __("reservation.status")}}',
						visible:false
                    },{
                        data: 'statusname',
                        name: '{{ __("reservation.status")}}'
                    },
                    {
                        data: 'bookingstart',
                        name: '{{ __("reservation.bookingstart")}}'
                    },
                    {
                        data: 'bookingend',
                        name: '{{ __("reservation.bookingend")}}'
                    },
                    {
                        data: 'action',
                        name: '{{ __("reservation.action")}}',
                        orderable: false,
                        searchable: false
                    },
                ],
				language: {
					"infoEmpty": "{{ __('reservation.nodata')}}",
					"info": "{{ __('reservation.showing')}} _START_ {{ __('reservation.to')}} _END_ {{ __('reservation.of')}} _TOTAL_",
					"lengthMenu": "{{ __('reservation.display')}} _MENU_ {{ __('reservation.records')}}",
					"search": "{{ __('reservation.search')}}",
					"paginate": {
						"next": "{{ __('reservation.next')}}",
						"previous": "{{ __('reservation.previous')}}"
					}
				},
				"columnDefs": [
					{ "width": "20%", "targets": 6 }
				  ]
            });

        });
		

        function displayPopupNotification(Message, type) {
            sticky = $('#sticky');
            if (type == 'success') {
                sticky.css("color", "green");
                sticky.css("box-shadow", "0 0 10px  green");
            } else {
                sticky.css("color", "red");
                sticky.css("box-shadow", "0 0 10px  red");
            }

            sticky.html(Message);
            sticky.show();
            setTimeout(function() {
                sticky.hide();
            }, 3000);
        }
    </script>
@endsection