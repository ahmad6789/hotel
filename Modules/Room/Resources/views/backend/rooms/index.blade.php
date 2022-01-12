@extends('backend.layouts.app')
@section('content')

    <div class="container ">
        <center>
            <h1 class="text-success mt-4">{{ __('Room.The Rooms') }}</h1>
        </center>
        <div clsss="table-responsive">
            {{-- testcode --}}
            <div class="row">


                <div class=" float-right">

                    <a href="{{ route('room.create') }}" class="btn btn-success  ">
                        <i class="fas fa-plus-circle"></i>

                    </a>
                    <label class="font-weight-bold"> {{ __('Room.Add') }} {{ __('Room.Room') }}</label>

                </div>
            </div>
            <br>
            {{-- testcode --}}

            <table class="table table-bordered data-table table-condensed table-hover">

                <thead>
                    <tr>

                        <th class='text-success'>{{ __('Room.code') }}</th>
                        <th class='text-danger '>{{ __('Room.capacity') }}</th>
                        <th class='text-warning '>{{ __('Room.price') }}</th>
                        <th class='text-info '>{{ __('Room.categoryid') }}</th>
                        <th class='text-muted '>{{ __('Room.status') }}</th>
                        <th width="100px" class='Action'>{{ __('Room.Action') }}</th>

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

                        <h4 class="modal-title">{{ __('Room.Delete') }} {{ __('Room.Room') }}</h4>
                    </div>
                    <div class="modal-body">
                        <p class="font-weight-bold">{{ __('Room.the item will be deleted , Are you sure?') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-id="" id="ok" class="btn btn-danger"
                            data-dismiss="modal">{{ __('Room.Submit') }}</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __('Room.Cancel') }}</button>

                    </div>
                </div>

            </div>
        </div>

        <div id="sticky">
            <div>

            </div>
            <script type="text/javascript">
                success = "<i class=' c-icon cil-check'></i> SUCCESS! <p>Editing Successfully</p>";
                $(document).ready(function populate() {

                            $.noConflict();
                            var table = $('.data-table').DataTable({
                                    "language": {
                                        "search": "{{ __('Room.Serach') }} :",
                                        "info": "{{ __('Room.Showing') }} _START_  {{ __('Room.to') }} _END_  {{ __('Room.of') }}  _TOTAL_ {{ __('Room.entries') }} ",
                                        "processing": "{{ __('Room.Processing...') }}",
                                        "lengthMenu": "{{ __('Room.Show') }} _MENU_  {{ __('Room.entries') }}",
                                        "emptyTable": "",
                                        "paginate": {
                                            "first": "{{ __('Room.first') }}",
                                            "last": "{{ __('Room.last') }} ",
                                            "next": "{{ __('Room.Next') }} ",
                                            "previous": "{{ __('Room.Previous') }} ",
                                        }

                                    },

                                    processing: true,
                                    serverSide: true,
                                    ajax: "{{ route('room.gettable') }}",
                                    columns: [{
                                            data: 'code',
                                            name: 'code'
                                        },
                                        {
                                            data: 'capacity',
                                            name: 'capacity'
                                        },
                                        {
                                            data: 'price',
                                            name: 'price'
                                        },
                                        {
                                            data: 'categoryid',
                                            name: 'categoryid'
                                        },
                                        {
                                            data: 'status',
                                            "render": function(data, type, row) {

                                                switch (data) {
                                                    case 0:
                                                        return '{{__("Room.status_0")}}';
                                                        break;
                                                    case 1:
                                                        return "{{__('Room.status_1')}}";
                                                        break;
                                                    case 2:
                                                        return "{{__('Room.status_2')}}";
                                                        break;
                                                    case 3:
                                                        return "{{__('Room.status_3')}}";
                                                        break;
                                                    default:
                                                        return data;
                                                }}
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
                                }); $(document).on('click', '#ok', function(e) {
                                    e.preventDefault();
                                    e.stopImmediatePropagation();
                                    var id = $(this).attr('data-id');
                                    $.ajax({
                                        url: "{{ route('room.destroy') }}/" + id,
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


                            @isset($editNotify)
                                displayPopupNotification(success);
                            
                            @endisset $(document).on('click', '.edit', function() {
                            var id = $(this).attr('data-id');

                            location.href = "{{ route('room.edit') }}/" + id;

                        });

                        $(document).on('click', '.add', function() {
                            var id = $(this).attr('data-id');

                            location.href = "{{ route('roomItems.index') }}/" + id;

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
                    
                    
                    });

            </script>
        @endsection
