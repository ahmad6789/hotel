@extends('backend.layouts.app')
@section('content')
 
    <div class='container-sm text-info ' style="align: center">
		<center>
			<h1 class="text-success mt-4">{{ __('reservation.customer')}}</h1>
		</center>
		
		<div clsss="table-responsive">
			<div class="row">
				<div class="col-8">
					<h4 class="card-title mb-0">
						<i class="fa fa-book" ></i> {{ __('reservation.customer')}} <small class="text-muted"></small>
					</h4>
					<div class="small text-muted">
						{{ __('reservation.reservationmanagement')}}
					</div>
				</div>
				<div class="col-4">
					<div class="float-right">
						<a type="button" href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addCustomer" title="{{ __('reservation.addcustomer')}}"><i class="fa fa-plus"></i></a>
					</div>
				</div>
			</div>
		</div>
		
		<table class="table table-bordered data-table table-condensed table-hover">
			<thead>
				<tr>
					<th class='text-success'></th>
					<th class='text-success'>{{ __('reservation.firstname')}}</th>
					<th class='text-success'>{{ __('reservation.lastname')}}</th>
					<th class='text-info '>{{ __('reservation.idnumber')}}</th>
					<th class='text-muted '>{{ __('reservation.phone')}}</th>
					<th class='text-muted '>{{ __('reservation.email')}}</th>
					<th width="100px" class='Action'>{{ __('reservation.action')}}</th>
				</tr>
			</thead>
			<tbody>
			
			</tbody>
		</table>
		<div id="sticky">
		</div>
	</div>
	
	<!-- Modal -->
	<div id="addCustomer" class="modal fade" role="dialog">
		<div class="modal-dialog" style="max-width:80%">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">{{ __("reservation.addcustomer")}}</h4>
				</div>
				<div class="modal-body">
					<form action="" id="addCustomerForm"
						style="width:70%;margin: 0px auto; box-shadow:  0 0 10px  #303c54;  border-radius:25px; background-color:#fff;padding:25px;  border: solid 1px #555;">
						<h1 class="text-center" style="color:#303c54">
							{{ __("reservation.addcustomertitle")}}
						</h1>
						 
						<div class="row">
							<div class="col col-sm-6">
								<div class="form-group">
									<label for="firstname">{{ __("reservation.firstname")}}</label>
									<input type="text" id="firstname" name="firstname" class="form-control" required />
								</div>
							</div>
							<div class="col col-sm-6">
								<div class="form-group">
									<label for="lastname">{{ __("reservation.lastname")}}</label>
									<input type="text" id="lastname" name="lastname" class="form-control" required />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col col-sm-6">
								<div class="form-group">
									<label for="birthdate">{{ __("reservation.birthdate")}}</label>
									<input type="date" id="birthdate" name="birthdate" class="form-control" required />
								</div>
							</div>
							<div class="col col-sm-6">
								<div class="form-group">
									<label for="name">{{ __("reservation.sex")}}</label>
									<div>
										<input type="radio" class="" name="sex" id="sex-m" value="m">
										<label class="" for="sex-m">{{ __("reservation.male")}}</label>

										<input type="radio" class="" name="sex" id="sex-f" value="f">
										<label class="" for="sex-f">{{ __("reservation.female")}}</label>

										<input type="radio" class="" name="sex" id="sex-o" value="o">
										<label class="" for="sex-o">{{ __("reservation.other")}}</label>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col col-sm-6">
								<div class="form-group">
									<label for="idnumber">{{ __("reservation.idnumber")}}</label>
									<input type="text" id="idnumber" name="idnumber" class="form-control" required />
								</div>
							</div>
							<div class="col col-sm-6">
								<div class="form-group">
									<label for="name">{{ __("reservation.idtype")}}</label>
									<div>
										<input type="radio" class="" name="idtype" id="id-id" value="id">
										<label class="" for="id-id">{{ __("reservation.id")}}</label>

										<input type="radio" class="" name="idtype" id="id-pass" value="passport">
										<label class="" for="id-pass">{{ __("reservation.passport")}}</label>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
						<div class="col col-sm-6">
								<div class="form-group">
									<label for="email">{{ __("reservation.email")}}</label>
									<input id="email" name="email" type="text" class="form-control" />
								</div>
							</div>
							<div class="col col-sm-6">
								<div class="form-group">
									<label for="phone1">{{ __("reservation.phone1")}}</label>
									<input id="phone1" name="phone1" type="text" class="form-control" required />
								</div>
							</div>
							<div class="col col-sm-6">
								<div class="form-group">
									<label for="phone2">{{ __("reservation.phone2")}}</label>
									<input id="phone2" name="phone2" type="text" class="form-control" />
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col col-sm-6">
								<div class="form-group">
									<label for="address1">{{ __("reservation.address1")}}</label>
									<input id="address1" name="address1" type="text" class="form-control" />
								</div>
							</div>
							<div class="col col-sm-6">
								<div class="form-group">
									<label for="address2">{{ __("reservation.address2")}}</label>
									<input id="address2" name="address2" type="text" class="form-control" />
									<span id="duration"></span>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col col-sm-4">
								<div class="form-group">
									<label for="city">{{ __("reservation.city")}}</label>
									<input id="city" name="city" type="text" class="form-control" />
								</div>
							</div>
							<div class="col col-sm-4">
								<div class="form-group">
									<label for="country">{{ __("reservation.country")}}</label>
									<input id="country" name="country" type="text" class="form-control" />
								</div>
							</div>
							<div class="col col-sm-4">
								<div class="form-group">
									<label for="nationality">{{ __("reservation.nationality")}}</label>
									<input id="nationality" name="nationality" type="text" class="form-control" />
								</div>
							</div>
						</div>
						<center>
							<button type="submit" id="customer-submit" class="btn  btn-lg  btn-primary ml-10">{{ __("reservation.submit")}}</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">{{ __("reservation.cancel")}}</button>
						</center>
					</form>
				</div>
				<div class="modal-footer">
				</div>
			</div>

		</div>
	</div>
	
	
	<script type="text/javascript">
		$(document).on('click', '#customer-submit', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			var modal = $('#addCustomer');
			var id = $(this).attr('data-id');
			var form = $('#addCustomerForm');
			$.ajax({
				url: "{{ route('customer.store') }}",
				type: 'get',
				dataType: 'json',
				data: form.serialize(),
				success: function(data) {
					if (data.response == true) {
						 var table = $('.data-table').DataTable();
						 table.draw();
						form.trigger("reset");
						$(".modal-backdrop").hide();
						$(".modal").hide();
					}  else {
						$("#sticky").html("unknown error");
						$("#sticky").show();
						$(".modal-backdrop").hide();
						$(".modal").hide();
					}
				},
			});
		});
		
        $(document).ready(function populate() {

            $.noConflict();
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('customer.gettable') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
						visible:false
                    },{
                        data: 'firstname',
                        name: '{{ __("reservation.firstname")}}'
                    },{
                        data: 'lastname',
                        name: '{{ __("reservation.lastname")}}'
                    },{
                        data: 'idnumber',
                        name: '{{ __("reservation.idnumber")}}'
                    },{
                        data: 'phone1',
                        name: '{{ __("reservation.phone")}}'
                    },{
                        data: 'email',
                        name: '{{ __("reservation.email")}}'
                    },{
                        data: 'action',
                        name: '{{ __("reservation.action")}}',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });



        function displayPopupNotification(Message) {
            sticky = $('#sticky');
            if (Message == success) {
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