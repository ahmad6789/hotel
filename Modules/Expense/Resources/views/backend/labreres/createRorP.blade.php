@extends('backend.layouts.app')

@section('content')

    <div class='container-sm text-info ' style="align: center">
        <form action=" " id="additem"
            style="width:50%;margin: 0px auto; box-shadow:  0 0 10px  #303c54;  border-radius:25px; background-color:#fff;padding:25px;  border: solid 1px #555;">
            @csrf

            <h1 class="text-center" style="color:#303c54">
                @if($type=='r')
                {{__('reservation.addReward')}}
                @else

                {{__('reservation.addPunishment')}}
                @endisset
            </h1>



            <div class="form-row">
                <div class="col-md-12 form-group">
                    <label for="type">{{__('reservation.laborere')}}</label>
                    <select class="form-control" id="type" name="labrere">
                        @php $labrere = \App\Models\Labrere::select()->get(); @endphp
                        @foreach($labrere as $item)
                        <option value="{{$item->id}}">{{$item->firstname . ' ' . $item->lastname}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-12 form-group">
                    <label for="type">{{__('reservation.Why')}}</label>
                    <textarea class="form-control calc-q" name="why"></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 form-group">
                    <label for="type">{{ __('reservation.value') }}</label>
                    <input  class="form-control calc-q" name="price">
                </div>

            </div>

            <div class="form-row">
                <div class="col-md-12 form-group">
                    <label for="type">{{ __('reservation.date') }}</label>
                    <input  class="form-control calc-q" type="date" name="date">
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

                    @if($type=='p')
                    url: "{{ route('frontend.users.storePunishment') }}"  ,
                    @else
					url: "{{ route('frontend.users.storeReward') }}",
                    @endif
					type:'post',
					dataType: 'json',
					data:data,
					success:function(data){

					    @if($type = 'r')
						    location.href = "{{ route('frontend.users.showReward')}}";
                        @else
                            location.href = "{{ route('frontend.users.showPunishment')}}";
                        @endif
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
