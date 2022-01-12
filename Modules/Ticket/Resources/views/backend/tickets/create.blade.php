@extends('backend.layouts.app')

@section('content')

    <div class='container-sm text-info ' style="align: center">
        <form action=" " id="addRoom"
            style="width:40%; height: 50%;margin: 0px auto; box-shadow:  0 0 10px  #303c54;  border-radius:25px; background-color:#fff;padding:25px;  border: solid 1px #555;">
            <h1 class="text-center" style="color:#303c54">
                @isset($ticketEdit)
                {{__('Ticket.Edit')}} {{__('Ticket.Ticket')}}
                @else

                {{__('Ticket.Add')}} {{__('Ticket.Ticket')}}
                @endisset
            </h1>

            <div class="form-group">
                <label for="roomid">{{ __("reservation.room")}}</label>
				<select class="form-control" name="roomid" id="roomid" required @isset($reservation) value="{{ $reservation->roomid}}" @endisset @isset($roomid) value="{{ $roomid}}" @endisset>
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

             {{-- <div class="form-group">
                <label for="status">{{__('Ticket.status')}}:</label>
                <br>
                <input type="radio" id="s1" name="status" value="0" checked>
                <label for="s1"> {{__('Ticket.status_0')}}  </label>
                <input type="radio" id="s2" name="status" value="1">
                <label for="s2"> {{__('Ticket.status_1')}}</label>
                <input type="radio" id="s3" name="status" value="2">
                <label for="s3"> {{__('Ticket.status_2')}}</label>
            </div> --}}



		   <div class="form-group">
                <label for="userid">{{__('Ticket.assignedto')}}:</label>
				<select class="form-control" name="assignedto" id="assignedto"  @isset($userid) value="{{ $userid}}" @endisset>
					<option value="">{{ __("Ticket.selectemployee")}}</option>
						@isset($users)
							@foreach ($users as $user)
								<option value="{{ $user->id }}"
								@isset($userid)
									@if($userid == $user->id)
										selected="selected"
									@endif
								@endisset
							>
							{{ $user->first_name }} {{$user->last_name}}
					</option>
							@endforeach
						@endisset
				</select>
            </div>


            <div class="form-group">
                <label for="type">{{__('Ticket.type')}}:</label>
                @isset($ticketEdit)
                    <label style="color: green; font-size: 15px;">{{ $ticketEdit->type }}</label>
                    <input type="hidden" value='{{ $ticketEdit->type }}' name="type">
                @else
                    <input class="form-control" type="text" id="type" name="type" required>
                @endisset
            </div>

            @isset($ticketEdit)
                <input type="hidden" name="id" value="{{ $ticketEdit->id }}">
            @endisset



            <div class="form-group">
                <label for="priority">{{__('Ticket.priority')}}:</label>
                <br>
                <input type="radio" id="s1" name="priority" value="0" checked>
                <label for="s1"> {{__('Ticket.priority_0')}}  </label>
                <input type="radio" id="s2" name="priority" value="1">
                <label for="s2"> {{__('Ticket.priority_1')}}</label>
                <input type="radio" id="s3" name="priority" value="2">
                <label for="s3"> {{__('Ticket.priority_2')}}</label>
            </div>


            <center>
                <button type="submit" id="submit" class="btn  btn-lg  btn-primary ml-10">{{__('Ticket.Submit')}}</button>
                <button type="button" class="btn btn-warning btn-lg" onclick="history.back(-1)"> {{__('Ticket.Cancel')}}</button>
            </center>
        </form>
        <div id="sticky">
        </div>
        <form action="{{ route('ticket.index') }}" id="formId">
            <input type="hidden" name="edit" value="1" >

        </form>
    </div>


    <script>

        success = "<i class=' c-icon cil-check'></i> {{__('Ticket.SUCCESS')}} <p>{{__('Ticket.Adding Successfully')}} </p>";
        exist = "<i class=' c-icon cil-warning'></i>{{__('Ticket.ERROR')}} ! <p>{{__('Ticket.Ticket Already Exist')}}</p>"

        $('#addRoom').on('submit', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var form = $(this);
            var data = form.serialize();


            @isset($ticketEdit)
                $.ajax({
                url: "{{ route('ticket.update') }}",
                type:'get',
                dataType: 'json',
                data:data,
                success:function(data){
                if(data.response==true){
                    $("#formId").submit();

                }
                else
                {
                displayPopupNotification(exist);
                }
                }
                });

            @else

                $.ajax({
                url: "{{ route('ticket.store') }}",
                type:'get',
                dataType: 'json',
                data:data,
                success:function(data){
                if(data.response==true){

                displayPopupNotification(success);
                location.href ="{{ route('ticket.index') }}";
                }

                else{
                displayPopupNotification(exist);

                }
            }
                });


            @endisset


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
