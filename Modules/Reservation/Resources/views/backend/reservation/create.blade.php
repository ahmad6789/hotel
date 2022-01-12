@extends('backend.layouts.app')
@section('content')

    <div class='container-sm text-info ' style="align: center">
        <form action="" id="addReservationForm"
            style="width:60%;margin: 0px auto; box-shadow: 0 0 10px  #303c54;  border-radius:25px; background-color:#fff;padding:25px;  border: solid 1px #555;">
            <h1 class="text-center" style="color:#303c54">
			@if(isset($reservation))
				{{ __("reservation.editbooking")}}
			@else
				{{ __("reservation.bookaroom")}}
			@endif

            </h1>

            <input type="hidden" name="employeeid" value="{{ $userid }}" />
            <div class="form-group">
				<label for="customerid">{{ __("reservation.customer")}}</label>
				<div class="input-group mb-3">
					<select class="form-control" name="customerid" id="customerid"  @isset($reservation) value="{{ $reservation->customerid}}" disabled @endisset required>
						<option value="">{{ __("reservation.selectcustomer")}}</option>
							@isset($customers)
								@foreach ($customers as $customer)
									<option value="{{ $customer->id }}" @isset($reservation) @if($customer->id == $reservation->customerid) selected="selected" @endif @endisset>{{ $customer->firstname }} {{ $customer->lastname }} ({{ $customer->idnumber }})</option>
								@endforeach
							@endisset
					</select>
					@isset($reservation)
						<input type="hidden" name="customerid" value="{{ $reservation->customerid}}" />
						<input type="hidden" name="reservationid" value="{{ $reservation->id}}" />
					@endisset
					<div class="input-group-append">
						<a type="button" href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addCustomer" title="{{ __('reservation.addcustomer')}}"><i class="fa fa-plus"></i></a>
					</div>
				</div>

            </div>

			<div class="form-group">
                <label for="roomid">{{ __("reservation.room")}}</label>
				<select class="form-control date-field" name="roomid" id="roomid" required @isset($reservation) value="{{ $reservation->roomid}}" @endisset @isset($roomid) value="{{ $roomid}}" @endisset required>
					<option value="">{{ __("reservation.selectroom")}}</option>
						@isset($rooms)
							@foreach ($rooms as $room)
								<option value="{{ $room->id }}"
								@isset($roomid)
									@if($roomid == $room->id)
										selected="selected"
									@endif
								@endisset

								@isset($reservation)
									@if($room->id == $reservation->roomid)
										selected="selected"
									@endif
								@endisset
							>
							{{ $room->code }}
					</option>
							@endforeach
						@endisset
				</select>


            </div>
			<div class="form-group">
			<label for="cost"> {{ __("reservation.price")}}</label>
					<input id="roomprice"  @isset($roomid) data-price="{{$roomprice}}" @endisset @isset($reservation) data-price="{{$room->price}}" @endisset name="cost" class="form-control date-field" type="number" value="" required>


				</div>
			<!--<div class="form-group">
				<label for="type">Booking Type</label>
				<div>
					<input type="radio" class="bookingtype" name="bookingtype" id="bookingtype-r" value="r">
					<label class="" for="bookingtype-r" >Room</label>

					<input type="radio" class="" name="bookingtype" id="bookingtype-b" value="b">
					<label class="" for="bookingtype-b">Bed</label>
				</div>
			</div>

			<div id="beds" class="form-group" style="display:none">
                <label for="bedid">Bed</label>
				<label id="bedsnone" style="display:none">No available beds for this room</label>
				<select class="form-control" name="bedid" id="bedid">

				</select>
            </div>-->

			<div class="row">
				<div class="col col-sm-6">
					<div class="form-group">
						<label for="bookingstart">{{ __("reservation.bookingstart")}}</label>
						<input type="date" required id="bookingstart" name="bookingstart" required class="form-control date-field" required @isset($reservation) value="{{$reservation->bookingstart}}" @endisset/>
					</div>
				</div>
				<div class="col col-sm-6">
					<div class="form-group">
						<label for="bookingend">{{ __("reservation.bookingend")}}</label>
						<input type="date"  id="bookingend" name="bookingend"  class="form-control date-field" required @isset($reservation) value="{{$reservation->bookingend}}" @endisset/ required>
						<span id="duration"></span>
					</div>
				</div>
			</div>

            <center>
				<h5><label id="price-label" ></label></h5>
                <button type="submit" id="reservation-submit" class="btn  btn-lg  btn-primary ml-10">{{ __("reservation.submit")}}</button>
                <button type="button" class="btn btn-warning btn-lg" onclick="history.back(-1)"> {{ __("reservation.cancel")}}</button>
            </center>
        </form>
        <div id="sticky" style="width:350px; font-size:14px;">
        </div>
    </div>

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

	<script>
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
					$('#customerid').append('<option value="'+data.data.id+'">'+data.data.name+'</option>');
					$('#customerid').val(data.data.id);
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

	$(document).on('click', '#reservation-submit', function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		var id = $(this).attr('data-id');
		var form = $('#addReservationForm');
		//console.log(form.serialize());
		//return;

        var empty=false;
        var x = $('#addReservationForm').serializeArray();
        $.each(x, function(i, field){
            if( field.value ==''){
                empty=true;
                return false;

            }
        });
            if(!empty){
                $.ajax({
                    @if(@isset($reservation))
                    url: "{{ route('reservation.update') }}",
                    @else
                    url: "{{ route('reservation.store') }}",
                    @endif

                    type: 'get',
                    dataType: 'json',
                    data: form.serialize(),

                    success: function(data) {
                        if (data.response == true) {
                            form.trigger("reset");
                            $('#price-label').html('');
                            $('#roomprice').html('');
                            window.location.href = "{{ route('reservation.index') }}";
                        } else if(data.booked == true){
                            var string = 'The room is already booked at the following dates:';
                            jQuery.each(data.data, function(index, value){
                                string += '<br>{{ __("reservation.bookingstart")}} ' + value.bookingstart + ' {{ __("reservation.bookingend")}}' + value.bookingend;
                            });
                            $("#sticky").html(string);
                            $("#sticky").show();
                        }
                    },
                    statusCode: {
                        500: function() {
                            alert({{ __("reservation.unknown")}});
                        }
                    }
                });
            }
            else {
                $("#sticky").html("الرجاء تعببئة جميع الحقول");
                $("#sticky").show();
                setTimeout(function() {
                    $('#sticky').fadeOut('fast');
                }, 2000)


            }

	});


	var getroom = function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		var select = $('#roomid');
		// $('#beds').hide();
		// $('#bedid').html('<option value="">Select a bed</option>');
		// $('#bedid').val('');

		$.ajax({
			url: "{{ route('reservation.getRoomForReservation') }}",
			type: 'get',
			dataType: 'json',
			data: {roomid:select.val()},
			success: function(data) {
				$('#roomprice').attr('value',data.room.price);
				$('#roomprice').attr('data-price', data.room.price);
				$('#roomprice').val($('#roomprice').attr('data-price')) ;
			}
		});
		calculateprice(e
        );
	};
	$("#roomprice").keyup(function(){
 		$(this).attr('value', $(this).val());

	});
	$("#roomprice").change(function(){
 		$(this).attr('value', $(this).val());

	});
	var calculateprice = function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		var pricelabel = $('#price-label');
		pricelabel.html('');
		pricelabel.attr('data-price', 0);

		var price = $('#roomprice').attr('data-price');
		if(price){
			var start = $('#bookingstart').val();
			var end = $('#bookingend').val();
			var diff = new Date(end) - new Date(start);
			if(diff > 0){
				var days = (diff / (1000 * 60 * 60 * 24)) ;
				var total = days * price;


				pricelabel.html('{{ __("reservation.total")}}: '+total);
				pricelabel.attr('data-price', total);
			}
		}
	};

	// var checkbeds = function(e) {
		// e.preventDefault();
		// e.stopImmediatePropagation();
		// var select = $('#roomid');
        // if ($(this).is(':checked') && $(this).val() == 'b' && select.val()) {

			// $.ajax({
				// url: "{{ route('reservation.getRoomBedsForReservation') }}",
				// type: 'get',
				// dataType: 'json',
				// data: {roomid:select.val()},
				// success: function(data) {
					// if(data.beds){
						// $('#beds').show();
						// $('#bedsnone').hide();
						// $('#bedid').html('<option value="">Select a bed</option>');
						// jQuery.each(data.beds, function( index, bed ) {
							// $('#bedid').append('<option value="'+bed.id+'">'+bed.name+'</option>');
						// });

					// } else {
						// $('#beds').show();
						// $('#bedsnone').show();
						// $('#bedid').html('<option value="">Select a bed</option>');
						// $('#bedid').val('');
					// }
				// }
			// });
        // } else if ($(this).is(':checked') && $(this).val() == 'r' && select.val()){
			// $('#beds').hide();
			// $('#bedid').html('<option value="">Select a bed</option>');
			// $('#bedid').val('');
		// }
    // };

	$(document).on('change', '#roomid', getroom);

	// $(document).on('change', 'input:radio[name="bookingtype"]', checkbeds);

	$(document).on('change', '.date-field', calculateprice);

	</script>

@endsection
