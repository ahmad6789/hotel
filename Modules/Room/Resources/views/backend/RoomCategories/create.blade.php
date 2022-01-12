@extends('backend.layouts.app')

@section('content')

    <div class='container-sm text-info ' style="align: center">
        <form action=" " id="addRoomCategory"
            style="width:40%; height: 50%;margin: 0px auto; box-shadow:  0 0 10px  #303c54;  border-radius:25px; background-color:#fff;padding:25px;  border: solid 1px #555;">
            <h1 class="text-center" style="color:#303c54">
                @isset($RoomCategoryEdit)
                {{__('Room.Edit')}} {{__('Room.Category')}}
                @else

                {{__('Room.Add')}} {{__('Room.Category')}}
                @endisset
            </h1>


            <div class="form-group">
                <label for="name">{{__('Room.Name')}}</label>
                <input class="form-control" @isset($RoomCategoryEdit)value="{{ $RoomCategoryEdit->name }}" @endisset type="text"
                    id="name" name="name" required>
            </div>

            <div class="form-group">
{{--                <label for="status">{{__('Room.status')}}:</label>--}}
                <br>
                <input type="hidden" id="s1" name="status" value="20">
{{--                <label for="s1"> {{__('Room.Cat_status_0')}}  </label>--}}
{{--                <input type="radio" id="s2" name="status" value="1">--}}
{{--                <label for="s2"> {{__('Room.Cat_status_1')}}</label>--}}
            </div>

            @isset($RoomCategoryEdit)
                <input type="hidden" name="id" value="{{ $RoomCategoryEdit->id }}">
            @endisset



            <center>
                <button type="submit" id="submit" class="btn  btn-lg  btn-primary ml-10">{{__('Room.Submit')}}</button>

                <button type="button" class="btn btn-warning btn-lg" onclick="history.back(-1)"> {{__('Room.Cancel')}}</button>

            </center>

        </form>
        <div id="sticky">
        </div>
        <form action="{{ route('RoomCategory.index') }}" id="formId">
            <input type="hidden" name="edit" value="1" >

        </form>

    </div>


    <script>
        success = "<i class=' c-icon cil-check'></i>{{__('Room.SUCCESS')}}  <p>{{__('Room.Adding Successfully')}}</p>";
        exist = "<i class=' c-icon cil-warning'></i> {{__('Room.ERROR')}}! <p>{{__('Room.Category Already Exist')}}</p>"

        $('#addRoomCategory').on('submit', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var form = $(this);
            var data = form.serialize();


            @isset($RoomCategoryEdit)
                $.ajax({
                url: "{{ route('RoomCategory.update') }}",
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
                url: "{{ route('RoomCategory.store') }}",
                type:'get',
                dataType: 'json',
                data:data,
                success:function(data){
                if(data.response==true){

                displayPopupNotification(success);
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
