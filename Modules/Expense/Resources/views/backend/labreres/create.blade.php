@extends('backend.layouts.app')

@section('content')

    <div class='container-sm text-info ' style="align: center">
        <form action=" " id="additem"
            style="width:50%;margin: 0px auto; box-shadow:  0 0 10px  #303c54;  border-radius:25px; background-color:#fff;padding:25px;  border: solid 1px #555;">
            @csrf
            <h1 class="text-center" style="color:#303c54">
                @if($edit==true)
                {{__('reservation.EditLaborere')}}
                @else

                {{__('reservation.addLaborere')}}
                @endisset
            </h1>

            <div class="form-row">
				<div class="col-md-12 form-group">
					<label for="type">{{__('reservation.firstname')}}</label>
					<input @if($edit==true)   value="{{$labrere['firstname']}}" @endif class="form-control calc-q"  name="firstname">
				</div>
			</div>
            <div class="form-row">
                <div class="col-md-12 form-group">
                    <label for="type">{{__('reservation.lastname')}}</label>
                    <input  @if($edit==true)   value="{{$labrere['lastname']}}" @endif class="form-control calc-q" name="lastname">
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 form-group">
                    <label for="type">{{__('reservation.birthdate')}}</label>
                    <input @if($edit==true)   value="{{$labrere['birthdate']}}" @endif class="form-control calc-q" type="date" name="birthdate">
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 form-group">
                    <label for="type">{{__('reservation.sex')}}</label>
                    <select class="form-control" id="type" name="sex">
                        <option value="">{{__('reservation.select')}}</option>
                            <option @if($edit==true) @if($labrere['sex'] == 'male' ) selected @endif @endif value="male">{{__('reservation.male')}}</option>
                            <option @if($edit==true) @if($labrere['sex'] == 'female' ) selected @endif @endif value="female">{{__('reservation.female')}}</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 form-group">
                    <label for="type">{{__('reservation.phone1')}}</label>
                    <input @if($edit==true)   value="{{$labrere['phone']}}" @endif class="form-control calc-q" name="phone">
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-12 form-group">
                    <label for="type">{{__('reservation.address1')}}</label>
                    <input @if($edit==true)   value="{{$labrere['address']}}" @endif class="form-control calc-q" name="address">
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 form-group">
                    <label for="type">{{__('reservation.city')}}</label>
                    <input @if($edit==true)   value="{{$labrere['city']}}" @endif class="form-control calc-q" name="city">
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 form-group">
                    <label for="type">{{__('reservation.country')}}</label>
                    <input @if($edit==true)   value="{{$labrere['country']}}" @endif class="form-control calc-q" name="country">
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 form-group">
                    <label for="type">{{__('reservation.nationality')}}</label>
                    <input  @if($edit==true)   value="{{$labrere['nationality']}}" @endif class="form-control calc-q" name="nationality">
                </div>

            </div>

            <center>
                <h3><span id="grand-total"></span></h3>
                <button type="submit" id="submit" class="btn  btn-lg  btn-primary ml-10">{{__('expense.submit')}}</button>
                <button type="button" class="btn btn-warning btn-lg" onclick="history.back(-1)">{{__('expense.cancel')}} </button>
            </center>
        </form>
    </div>


    <script>
        success = "<i class=' c-icon cil-check'></i> {{__('Items.SUCCESS')}} <p>{{__('Items.Adding Successfully')}}</p>";
        exist = "<i class=' c-icon cil-warning'></i> {{__('Items.ERROR')}}! <p>{{__('Items.Item Already Exist')}} </p>";

		$(document).ready(function(){

			$('#additem').on('submit', function(e) {
				e.preventDefault();
				e.stopImmediatePropagation();
				var form = $(this);
				var data = form.serialize();
				console.log(data);
				$.ajax({

                    @if($edit==true)
                    url: "{{ route('frontend.users.updatelabrere') }}/" + "{{$labrere['id']}}" ,
                    @else
					url: "{{ route('frontend.users.storelabrere') }}",
                    @endif

					type:'post',
					dataType: 'json',
					data:data,
					success:function(data){
						location.href = "{{ route('frontend.users.index')}}";
					}
				});

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
