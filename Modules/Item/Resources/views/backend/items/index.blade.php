@extends('backend.layouts.app')
@section('content')

    <div class="container ">
        <center>
            <h1 class="text-success mt-4">{{__('Items.The Items')}}</h1>
        </center>
        <div clsss="table-responsive">
            {{-- testcode --}}
            <div class="row">
              
         
                    <div class="float-right">
                        <a href="{{ route('item.create') }}" class="btn btn-success  " title="Create Items">
                            <i class="fas fa-plus-circle"></i>

                        </a>
                                    <label class="font-weight-bold"> {{__('Items.Add')}} {{__('Items.Item')}}</label>
                    </div>
             </div>
            <br>
            {{-- testcode --}}
            <table class="table table-bordered data-table table-condensed table-hover">
                <thead>
                    <tr>
                         <th class='text-warning '>{{__('Items.Name')}}</th>
                        <th class='text-muted '>{{__('Items.Description')}}</th>
                        <th width="100px" class='Action'>{{__('Items.Action')}}</th>

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

                        <h4 class="modal-title">{{__('Items.Delete')}} {{__('Items.Item')}} </h4>
                    </div>
                    <div class="modal-body">
                        <p>{{__('Items.the item will be deleted , Are you sure?')}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-id="" id="ok" class="btn btn-danger" data-dismiss="modal">{{__('Items.Submit')}}</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">{{__('Items.Cancel')}}</button>

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
                ajax: "{{ route('item.gettable') }}",
                columns: [ 
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
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
                    url: "{{ route('item.destroy') }}/" + id,
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

            location.href = "{{ route('item.edit') }}/" + id;

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
