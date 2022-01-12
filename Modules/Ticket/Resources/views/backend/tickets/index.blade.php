@extends('backend.layouts.app')
@section('content')

    <div class="container ">
        <center>
            <h1 class="text-success mt-4">{{ __('Ticket.The Tickets') }} </h1>
        </center>
        <div clsss="table-responsive">
            {{-- testcode --}}
            <div class="row">

                <div class="float-right">
                    <a href="{{ route('ticket.create') }}" class="btn btn-success  " title="Create Room">
                        <i class="fas fa-plus-circle"></i>

                    </a>

                    <label class="font-weight-bold"> {{ __('Ticket.Add') }} {{ __('Ticket.Ticket') }}</label>
                </div>
            </div>
            <br>
            {{-- testcode --}}

            <table class="table table-bordered data-table table-condensed table-hover">
                <thead>
                    <tr>
                        <th class='text-success'>{{ __('Ticket.Roomid') }}</th>
                        <th class='text-warning '>{{ __('Ticket.assignedto') }}</th>
                        <th class='text-info '>{{ __('Ticket.type') }}</th>
                        <th class='text-muted '>{{ __('Ticket.priority') }}</th>
                        <th width="100px" class='Action'>{{ __('Ticket.Action') }}</th>

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

                        <h4 class="modal-title">{{ __('Ticket.Delete') }} {{ __('Ticket.Ticket') }} </h4>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('Ticket.the item will be deleted , Are you sure?') }} </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-id="" id="ok" class="btn btn-danger"
                            data-dismiss="modal">{{ __('Ticket.Submit') }}</button>
                        <button type="button" class="btn btn-primary"
                            data-dismiss="modal">{{ __('Ticket.Cancel') }}</button>

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
                            "search": "{{ __('Ticket.Serach') }} :",
                            "info": "{{ __('Ticket.Showing') }} _START_  {{ __('Ticket.to') }} _END_  {{ __('Ticket.of') }}  _TOTAL_ {{ __('Ticket.entries') }} ",
                            "processing": "{{ __('Ticket.Processing...') }}",
                            "lengthMenu": "{{ __('Ticket.Show') }} _MENU_  {{ __('Ticket.entries') }}",
                            "emptyTable": "",
                            "paginate": {
                                "first": "{{ __('Ticket.first') }}",
                                "last": "{{ __('Ticket.last') }} ",
                                "next": "{{ __('Ticket.Next') }} ",
                                "previous": "{{ __('Ticket.Previous') }} ",
                                "emptyTable": "{{ __('Ticket.o data available in table') }}",

                            }
                        },

                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('ticket.gettable') }}",
                        columns: [{
                                data: 'code',
                                name: 'code',

                            },
                            {
                                data: 'name',
                                name: 'name'
                            },

                            {
                                data: 'type',
                                name: 'type',
                                className: 'type'
                            },
                            {
                                data: 'priority',


                                "render": function(data, type, row) {

                                    switch (data) {
                                        case 0:
                                            return "{{ __('Ticket.priority_0') }}";
                                            break;
                                        case 1:
                                            return "{{ __('Ticket.priority_1') }}";
                                            break;
                                        case 2:
                                            return "{{ __('Ticket.priority_2') }}";
                                            break;

                                        default:
                                            return data;
                                    }
                                }
                            },

                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            },
                        ],

                    });

                    $(document).on('click', '.delete', function() {
                        $('#ok').attr('data-id', $(this).attr('data-id'));
                    });
                    $(document).on('click', '#ok', function(e) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        var id = $(this).attr('data-id');
                        $.ajax({
                            url: "{{ route('ticket.destroy') }}/" + id,
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
                @isset($editNotify)
                    displayPopupNotification(success);

                @endisset
                $(document).on('click', '.edit', function() {
                    var id = $(this).attr('data-id');

                    location.href = "{{ route('ticket.edit') }}/" + id;

                });
                //
                $(document).on('click', '.add', function() {
                    var id = $(this).attr('data-id');

                    location.href = "{{ route('ticketsActivities.show') }}/" + id;

                });
                //


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
