@extends('backend.layouts.app')

@section('content')
     
    <div class='container-sm text-info ' style="align: center">
        <form action=" " id="addBed"
            style="width:40%; height: 50%;margin: 0px auto; box-shadow:  0 0 10px  #303c54;  border-radius:25px; background-color:#fff;padding:25px;  border: solid 1px #555;">
            <h1 class="text-center" style="color:#303c54">
                @isset($bedEdit)
                {{__('Room.Edit')}} {{__('Room.Bed')}}
                @else

                {{__('Room.Add')}} {{__('Room.Bed')}}
                @endisset
            </h1>
             
            
            <div class="form-group">
                <label for="name">{{__('Room.Name')}}</label>
                <input class="form-control" @isset($bedEdit)value="{{ $bedEdit->name }}" @endisset type="text"
                    id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="status">{{__('Room.status')}}:</label>
                <br>
                <input type="radio" id="s1" name="status" value="0" checked>
                <label for="s1"> {{ __('Room.Bed_status_0') }} </label>
                <input type="radio" id="s2" name="status" value="1">
                <label for="s2">{{ __('Room.Bed_status_1') }}</label>
            </div>

            @isset($bedEdit)
                <input type="hidden" name="id" value="{{ $bedEdit->id }}">
            @endisset


            <div class="form-group">
                <label for="roomid">{{__('Room.Roomid')}}</label>

                <select class="form-control" name="roomid" id="roomid" required>
                    @isset($roomid)
                        @foreach ($roomid as $room)
                            <option value="{{ $room->id }}">{{ $room->code }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <center>
                <button type="submit" id="submit" class="btn  btn-lg  btn-primary ml-10">{{__('Room.Submit')}}</button>
                <button type="button" class="btn btn-warning btn-lg" onclick="history.back(-1)"> {{__('Room.Cancel')}}</button>
            </center>
        </form>
        <div id="sticky">
        </div>
        <form action="{{ route('bed.index') }}" id="formId">
            <input type="hidden" name="edit" value="1" >

        </form>
    </div>


    <script>
      
      success = "<i class=' c-icon cil-check'></i> {{__('Room.SUCCESS')}}<p>{{__('Room.Adding Successfully')}}</p>";
        exist = "<i class=' c-icon cil-warning'></i> {{__('Room.ERROR')}}! <p>{{__('Room.Bed Already Exist')}}</p>"
   
        $('#addBed').on('submit', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var form = $(this);
            var data = form.serialize();


            @isset($bedEdit)
                $.ajax({
                url: "{{ route('bed.update') }}",
                type:'get',
                dataType: 'json',
                data:data,
                success:function(data){
                if(data.response==true){
            
                    $("#formId").submit(); 
                }
                else{
                displayPopupNotification(exist);
                }
                }
                });
            
            @else
            
                $.ajax({
                url: "{{ route('bed.store') }}",
                type:'get',
                dataType: 'json',
                data:data,
                success:function(data){
                if(data.response==true){
            
                displayPopupNotification(success);
                $('#addBed')[0].reset();
                }
                else{
                displayPopupNotification(exist);
                }
                }
                });
            
            @endisset


        })


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
