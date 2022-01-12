@extends('backend.layouts.app')

@section('content')
 
    <div class='container-sm text-info ' style="align: center">
        <form action=" " id="additem"
            style="width:40%; height: 50%;margin: 0px auto; box-shadow:  0 0 10px  #303c54;  border-radius:25px; background-color:#fff;padding:25px;  border: solid 1px #555;">
            <h1 class="text-center" style="color:#303c54">
                @isset($bedEdit)
                {{__('Items.Edit')}} {{__('Items.Item')}}    
                @else

                {{__('Items.Add')}} {{__('Items.Item')}}     
                @endisset
            </h1>
             
            
            <div class="form-group">
                <label for="name">{{__('Items.Name')}}</label>
                <input class="form-control" @isset($itemEdit)value="{{ $itemEdit->name }}" @endisset type="text"
                    id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">{{__('Items.Description')}}</label>
                <input class="form-control" @isset($itemEdit)value="{{ $itemEdit->description }}" @endisset type="text"
                    id="description" value="" name="description" required>
            </div>
            @isset($itemEdit)
                <input type="hidden" name="id" value="{{ $itemEdit->id }}">
            @endisset

 
            <center>
                <button type="submit" id="submit" class="btn  btn-lg  btn-primary ml-10">{{__('Items.Submit')}}</button>
                <button type="button" class="btn btn-warning btn-lg" onclick="history.back(-1)">{{__('Items.Cancel')}} </button>
            </center>
        </form>
        <div id="sticky">
        </div>
        <form action="{{ route('item.index') }}" id="formId">
            <input type="hidden" name="edit" value="1" >

        </form>
    </div>


    <script>
        success = "<i class=' c-icon cil-check'></i> {{__('Items.SUCCESS')}} <p>{{__('Items.Adding Successfully')}}</p>";
        exist = "<i class=' c-icon cil-warning'></i> {{__('Items.ERROR')}}! <p>{{__('Items.Item Already Exist')}} </p>"
 
        $('#additem').on('submit', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var form = $(this);
            var data = form.serialize();


            @isset($itemEdit)
                $.ajax({
                url: "{{ route('item.update') }}",
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
                url: "{{ route('item.store') }}",
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
