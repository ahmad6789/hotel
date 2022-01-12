@extends('backend.layouts.app')

@section('content')

    <div class='container-sm text-info ' style="align: center">
        <form action=" " id="additem"
            style="width:80%;margin: 0px auto; box-shadow:  0 0 10px  #303c54;  border-radius:25px; background-color:#fff;padding:25px;  border: solid 1px #555;">
            <h1 class="text-center" style="color:#303c54">
                @if($edit==true)
                {{__('expense.edit')}} {{__('expense.expense')}}
                @else

                {{__('expense.add')}} {{__('expense.expense')}}
                @endisset
            </h1>

            <div class="form-row">
				<div class="col-md-6 form-group">
					<label for="type">{{__('expense.type')}}</label>
					<select class="form-control" id="type" name="type">
						<option value="">{{__('expense.selecttype')}}</option>
						<option value="wages">{{__('expense.wages')}}</option>
						<option value="purchases">{{__('expense.purchases')}}</option>
						<option value="repairs">{{__('expense.repairs')}}</option>
						<option value="bills">{{__('expense.bills')}}</option>
						<option value="items">{{__('expense.items')}}</option>
						<option value="drawings">{{__('expense.drawings')}}</option>
					</select>

				</div>
				<div class="col-md-6 form-group">
					<label for="description">{{__('expense.description')}}</label>
					<input class="form-control" type="text"	id="description" value="@if($edit==true) {{$expense->description}}@endif" name="description" required>
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-12 form-group" id="context">

				</div>
			</div>


			<center>
                <h2>{{__('expense.details')}}</h2>
            </center>
			<div id="field-wrapper">
                @isset($edit)
                    @if($edit==false)
				<div class="form-row align-items-end">
					<div class="col-md-4 form-group">
						<label for="item_description">{{__('expense.description')}}</label>
						<input class="form-control" type="text" id="item_description" name="item_description[]" value="" required>
					</div>

					<div class="col-md-4 form-group">
						<label for="item_cost">{{__('expense.cost')}}</label>
						<input class="form-control calc-c" type="number" name="item_cost[]" required>
					</div>

					<div class="col-md-4 form-group">
						<label for="item_quantity">{{__('expense.quantity')}}</label>
						<input class="form-control calc-q" type="number" name="item_quantity[]" required>
					</div>
					<span class="total" data-total="0"></span>
					<hr>
				</div>
                        @endif
                @endisset

                    <input type="hidden" name="exID" value='@if($edit==true){{$expense->id}}@endif'>

                @if($edit==true)
                    @foreach( $paymentLine as $pl)
                        <div class="form-row align-items-end"><div class="col-md-4 form-group"><input class="form-control" type="hidden" name="pl_id[]" value="{{$pl->id}}" ><label for="item_description">{{__("expense.description")}}</label><input class="form-control" type="text" name="item_description[]" value="{{$pl->description}}"></div><div class="col-md-4 form-group"><label for="item_cost">{{__("expense.cost")}}</label><input class="form-control calc-c" type="number" name="item_cost[]" value="{{$pl->cost}}"></div><div class="col-md-3 form-group"><label for="item_quantity">{{__("expense.quantity")}}</label><input class="form-control calc-q" type="number" id="item_quantity" name="item_quantity[]" value="{{$pl->quantity}}"></div><div class="col-md-1 form-group"><a href="javascript:void(0);" class="remove-button btn btn-danger" title="{{__("expense.removerow")}}"><i class="fa fa-trash"></i></a></div><span class="total" data-total="0"></span><hr></div>
                    @endforeach
                @endif
			</div>

			<div class="form-group">
                @if($edit==false)
                    <a href="javascript:void(0);" class="add-button btn btn-success" title="{{__('expense.addrow')}}"><i class="fa fa-plus"></i> {{__('expense.addrow')}}</a>
                @endif
			</div>

            <center>
				<h3><span id="grand-total"></span></h3>
                <button type="submit" id="submit" class="btn  btn-lg  btn-primary ml-10">{{__('expense.submit')}}</button>
                <button type="button" class="btn btn-warning btn-lg" onclick="history.back(-1)">{{__('expense.cancel')}} </button>
            </center>
        </form>
        <div id="sticky">

        </div>
    </div>


    <script>
        success = "<i class=' c-icon cil-check'></i> {{__('Items.SUCCESS')}} <p>{{__('Items.Adding Successfully')}}</p>";
        exist = "<i class=' c-icon cil-warning'></i> {{__('Items.ERROR')}}! <p>{{__('Items.Item Already Exist')}} </p>";

		$(document).ready(function(){
			var maxField = 10; //Input fields increment limitation
			var addButton = $('.add-button'); //Add button selector
			var wrapper = $('#field-wrapper'); //Input field wrapper
			var fieldHTML = '<div class="form-row align-items-end"><div class="col-md-4 form-group"><label for="item_description">{{__("expense.description")}}</label><input class="form-control" type="text" name="item_description[]"></div><div class="col-md-4 form-group"><label for="item_cost">{{__("expense.cost")}}</label><input class="form-control calc-c" type="number" name="item_cost[]"></div><div class="col-md-3 form-group"><label for="item_quantity">{{__("expense.quantity")}}</label><input class="form-control calc-q" type="number" id="item_quantity" name="item_quantity[]"></div><div class="col-md-1 form-group"><a href="javascript:void(0);" class="remove-button btn btn-danger" title="{{__("expense.removerow")}}"><i class="fa fa-trash"></i></a></div><span class="total" data-total="0"></span><hr></div>'; //New input field html
			var x = 1; //Initial field counter is 1

			//Once add button is clicked
			$(addButton).click(function(){
				//Check maximum number of input fields
				if(x < maxField){
					x++; //Increment field counter
					$(wrapper).append(fieldHTML); //Add field html
				}
			});

			//Once remove button is clicked
			$(wrapper).on('click', '.remove-button', function(e){
				e.preventDefault();
				$(this).closest('.form-row').remove(); //Remove field html
				x--; //Decrement field counter
				var grandtotal = 0;
				$('.total').each(function () {
					grandtotal += parseFloat($(this).attr('data-total')) || 0;
				});
				$('#grand-total').html("{{__('expense.total')}}: " + grandtotal);
			});

			$(document).on('change', '.calc-q', function(e){
				var cost = $(this).closest('.form-row').find('.calc-c').val();
				var quantity = $(this).val();
				var total = cost * quantity;
				$(this).closest('.form-row').find('.total').attr('data-total', total);

				var grandtotal = 0;
				$('.total').each(function () {
					grandtotal += parseFloat($(this).attr('data-total')) || 0;
				});
				$('#grand-total').html("{{__('expense.total')}}: " + grandtotal);
			});

			$(document).on('change', '.calc-c', function(e){
				var quantity = $(this).closest('.form-row').find('.calc-q').val();
				var cost = $(this).val();
				var total = cost * quantity;
				$(this).closest('.form-row').find('.total').attr('data-total', total);
				var grandtotal = 0;
				$('.total').each(function () {
					grandtotal += parseFloat($(this).attr('data-total')) || 0;
				});
				$('#grand-total').html("{{__('expense.total')}}: " + grandtotal);
			});

			$(document).on('change', '#type', function(e){
				var context = $(this).find(":selected").val();
				var contextarea = $('#context');
				contextarea.html('');
				var users = "{{ route('expense.getUsersList') }}";
				var rooms = "{{ route('expense.getRoomsList') }}";
				var items = "{{ route('expense.getItemsList') }}";
				var url = "";
				var required = "required";
				if(context == 'wages' || context == 'drawings'){
					url = users;
				} else if(context == 'items'){
					url = items;
					required = "";
				} else if(context == 'repairs'){
					required = "";
					url = rooms;
				} else {
					return true;
				}

				$.ajax({
					url: url,
					type:'get',
					dataType: 'json',
					success:function(data){
						if(data.response==true){
							var selectlist = '<label for="contextid">{{__("expense.contextid")}}</label><select id="contextid" name="contextid" class="form-control" '+required+'> <option value="">{{__("expense.selectone")}}</option>';
							$.each(data.data, function(id, option){
								selectlist += '<option value="'+option.id+'">'+option.name+'</option>';
							});
							selectlist += '</select>';
							contextarea.html(selectlist);
						}
						else{

						}
					}
				});
			});

			$('#additem').on('submit', function(e) {
				e.preventDefault();
				e.stopImmediatePropagation();
				var form = $(this);
				var data = form.serialize();
				$.ajax({

                    @if($edit==true)
                    url: "{{ route('expense.update') }}",
                    @else
					url: "{{ route('expense.store') }}",
                    @endif

					type:'get',
					dataType: 'json',
					data:data,
					success:function(data){
						location.href = "{{ route('expense.index')}}";
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
