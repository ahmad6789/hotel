@extends('backend.layouts.app')
@section('content')

    <div class="container ">
        <center>
            <h1 class="text-success mt-4">{{__('expense.expenses')}}</h1>
        </center>
        <div clsss="table-responsive">
            {{-- testcode --}}
            <div class="row">
				<div class="float-right col col-md-8">
					<a href="{{ route('expense.create') }}" class="btn btn-success  " title="Create Items">
						<i class="fas fa-plus-circle"></i>

					</a>
					<label class="font-weight-bold"> {{__('expense.addexpense')}}</label>
				</div>
				<div class="col col-md-4">
					<select class="form-control" id="type">
						<option value="">{{__('expense.selecttype')}}</option>
						<option value="wages">{{__('expense.wages')}}</option>
						<option value="purchases">{{__('expense.purchases')}}</option>
						<option value="repairs">{{__('expense.repairs')}}</option>
						<option value="bills">{{__('expense.bills')}}</option>
						<option value="items">{{__('expense.items')}}</option>
						<option value="drawings">{{__('expense.drawings')}}</option>
					</select>
				</div>
             </div>
            <br>
            {{-- testcode --}}
            <table id="data-table" class="table table-bordered data-table table-condensed table-hover">
                <thead>
                    <tr>
                        <th class='text-warning '></th>
                        <th class='text-warning '>{{__('expense.type')}}</th>
                        <th class='text-muted '>{{__('expense.description')}}</th>
                        <th class='text-muted '>{{__('expense.total')}}</th>
                        <th width="100px" class='Action'>{{__('expense.action')}}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title">{{__('expense.delete')}} {{__('expense.expense')}} </h4>
                    </div>
                    <div class="modal-body">
                        <p>{{__('expense.deleteconfirm')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-id="" id="ok" class="btn btn-danger" data-dismiss="modal">{{__('expense.ok')}}</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">{{__('expense.cancel')}}</button>

                    </div>
                </div>

            </div>
        </div>
        <div id="sticky">
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function populate() {

            $.noConflict();
            var table = $('.data-table').DataTable({
				searching: true,
                processing: true,
                serverSide: true,
                "language": {
                        "search": "{{ __('Room.Serach') }} :",
                        "info":      "{{ __('Room.Showing') }} _START_  {{ __('Room.to') }} _END_  {{ __('Room.of') }}  _TOTAL_ {{ __('Room.entries') }} ",
                        "processing":     "{{ __('Room.Processing...') }}",
                        "lengthMenu":     "{{ __('Room.Show') }} _MENU_  {{ __('Room.entries') }}",
                        "emptyTable":     "",
                        "paginate": {
                            "first": "{{ __('Room.first') }}",
                            "last": "{{ __('Room.last') }} ",
                            "next": "{{ __('Room.Next') }} ",
                            "previous": "{{ __('Room.Previous') }} ",
                        }
                    },
                @if(@isset($type))
					ajax: "{{ route('expense.gettable') }}/{{$type}}",
				@else
					ajax: "{{ route('expense.gettable') }}/all",
				@endif
                columns: [
                    {
                        data: 'type',
                        name: 'type',
						visible:false
                    },
					{
                        data: 'typename',
                        name: 'typename'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },{
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $(document).on('click', '.delete', function() {
                $('#ok').attr('data-id', $(this).attr('data-id'));
            });
            $(document).on('click', '#ok', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                var id = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('expense.destroy') }}/" + id,
                    type: 'get',
                    dataType: 'json',

                    success: function(data) {
                        if (data.response == true) {
                            var table = $('.data-table').DataTable();
                            table.draw();
                        } else {}
                    }
                });
                $('#ok').attr('data-id', '');

            });


        });

        $(document).on('click', '.edit', function() {
            var id = $(this).attr('data-id');

            location.href = "{{ route('expense.edit') }}/" + id;

        });

		$(document).on('change', '#type', function() {
			var type = $(this).find(':selected').val();
            location.href = "{{ route('expense.index') }}/" + type;
        });

        //notifations
        success = "<i class=' c-icon cil-check'></i> {{__('Items.SUCCESS')}} <p>{{__('Items.Editing Successfully')}}</p>";
        @isset($editNotify)
            displayPopupNotification(success);

        @endisset

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
