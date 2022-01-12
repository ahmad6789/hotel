@extends('backend.layouts.app')

@section('content')

    <div class='container-sm text-info ' style="align: center">
        <form action=" " id="addRoom"
            style="width:40%; height: 50%;margin: 0px auto; box-shadow:  0 0 10px  #303c54;  border-radius:25px; background-color:#fff;padding:25px;  border: solid 1px #555;">
            <h1 class="text-center" style="color:#303c54">
                @isset($roomEdit)
                {{__('Room.Edit')}} {{__('Room.Room')}}  
                @else 

                {{__('Room.Add')}} {{__('Room.Room')}}
                @endisset
            </h1>
            <div class="form-group">
                <label for="code">{{__('Room.code')}}</label>
                @isset($roomEdit)
                    <label style="color: green; font-size: 15px;">{{ $roomEdit->code }}</label>
                    <input type="hidden" value='{{ $roomEdit->code }}' name="code">
                @else
                    <input class="form-control" type="text" id="code" name="code" required>
                @endisset

            </div>
            <div class="form-group">
                <label for="capacity">{{__('Room.capacity')}}</label>
                <input class="form-control" @isset($roomEdit)value="{{ $roomEdit->capacity }}" @endisset type="number"
                    id="capacity" name="capacity" required>
            </div>
            <div class="form-group">
                <label for="price">{{__('Room.price')}}</label>
                <input class="form-control" @isset($roomEdit)value="{{ $roomEdit->price }}" @endisset type="number"
                    id="price" name="price" required>
            </div>

            <div class="form-group">
                <label for="status">{{__('Room.status')}}:</label>
                <br>
                <input type="radio" id="s1" name="status" value="0" checked>
                <label for="s1">{{__('Room.status_0')}}</label>
                <input type="radio" id="s2" name="status" value="1" >
                <label for="s2">{{__('Room.status_1')}}</label>
                {{-- <input type="radio" id="s3" name="status" value="2">
                <label for="s3">{{__('Room.status_2')}}</label>
                <input type="radio" id="s4" name="status" value="3">
                <label for="s4">{{__('Room.status_3')}}</label> --}}
            </div>

            @isset($roomEdit)
                <input type="hidden" name="id" value="{{ $roomEdit->id }}">
            @endisset
            <div class="form-group">
                <label for="categoryid">{{__('Room.categoryid')}}</label>



                <select class="form-control" name="categoryid" id="categoryid" required>
                    @isset($categories)
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    @endisset



                </select>
            </div>
            <center>
                <button type="submit" id="submit" class="btn  btn-lg  btn-primary ml-10">{{__('Room.Submit')}}</button>
                <button type="button" class="btn btn-warning btn-lg" onclick="history.back(-1)">{{__('Room.Cancel')}}</button>
            </center>
        </form>
        <div id="sticky">
        </div>
        <form action="{{ route('room.index') }}" id="formId">
            <input type="hidden" name="edit" value="1" >

        </form>
    </div>


    <script>

        success = "<i class=' c-icon cil-check'></i> {{__('Room.SUCCESS')}}! <p>{{__('Room.Adding Successfully')}}</p>";
        exist = "<i class=' c-icon cil-warning'></i> {{__('Room.ERROR')}}! <p>{{__('Room.Room Already Exist')}}</p>"
   
        $('#addRoom').on('submit', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var form = $(this);
            var data = form.serialize();


            @isset($roomEdit)
                $.ajax({
                url: "{{ route('room.update') }}",
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
                url: "{{ route('room.store') }}",
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
