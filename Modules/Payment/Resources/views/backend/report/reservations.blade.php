@extends('backend.layouts.app')
@section('content')

    <div class="container ">
        <center>
            <h1 class="text-success mt-4">{{ __("payment.payments") }}</h1>
        </center>
        <div clsss="table-responsive">
            {{-- testcode --}}
            <div class="row">
                <div class="col-12">
                    <div class="float-right">
                        <!--<a href="{{route("payment.create")}}" class="btn btn-success  "  title="Create Payment"  >
        <i class="fas fa-plus-circle"></i>
        
    </a>-->
     
                    </div>
                </div>
            </div>
            <br>
     {{-- testcode --}}
            
            <table class="table table-bordered data-table table-condensed table-hover">
                <thead>
					<tr>
                        <th class=''></th>
                        <th class=''></th>
                        <th class='text-success'>{{ __("payment.type") }}</th>
                        <th class='text-danger '></th>
                        <th class='text-danger '>{{ __("payment.context") }}</th>
                        <th class=''></th>
                        <th class='text-warning '>{{ __("payment.payee") }}</th>
                        <th class='text-info '>{{ __("payment.receivedby") }}</th>
                        <th class='text-info '>{{ __("payment.total") }}</th>
                        <th class='text-muted '>{{ __("payment.numberlines") }}</th>
                        <th class='text-muted '>{{ __("payment.date") }}</th>
                        <th width="100px" class='Action'>{{ __("reservation.action") }}</th>

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
                          
                        <h4 class="modal-title">{{ __("payment.delete") }}</h4>
                    </div>
                    <div class="modal-body">
                        <p>{{ __("payment.deleteconfirm") }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-id="" id="ok" class="btn btn-danger" data-dismiss="modal">{{ __("payment.ok") }}</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __("payment.cancel") }}</button>

                    </div>
                </div>

            </div>
        </div>
           
        <div id="sticky"><div>

    </div>
    <script type="text/javascript">
        success ="<i class=' c-icon cil-check'></i> SUCCESS! <p>Editing Successfully</p>";
        $(document).ready(function populate() {

            $.noConflict();
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('payment.gettable') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
						visible:false
                    },
                    {
                        data: 'type',
                        name: '{{ __("payment.type") }}',
						visible:false
                    },{
                        data: 'typename',
                        name: '{{ __("payment.type") }}'
                    },
                    {
                        data: 'context',
                        name: '{{ __("payment.context") }}',
						visible:false
                    },{
                        data: 'contextname',
                        name: '{{ __("payment.context") }}'
                    },
                    {
                        data: 'contextid',
                        name: 'contextid',
						visible:false
                    },
                    {
                        data: 'payeename',
                        name: '{{ __("payment.payee") }}'
                    },
                    {
                        data: 'receivername',
                        name: '{{ __("payment.receivedby") }}'
                    },{
                        data: 'paymenttotal',
                        name: '{{ __("payment.total") }}'
                    },{
                        data: 'paymentlinescount',
                        name: '{{ __("payment.numberlines") }}'
                    },{
                        data: 'created_at',
                        name: '{{ __("payment.date") }}'
                    },
                    {
                        data: 'action',
                        name: '{{ __("reservation.action") }}',
                        orderable: false,
                        searchable: false
                    },
                ],
				language: {
					"infoEmpty": "{{ __('reservation.nodata')}}",
					"info": "{{ __('reservation.showing')}} _START_ {{ __('reservation.to')}} _END_ {{ __('reservation.of')}} _TOTAL_",
					"lengthMenu": "{{ __('reservation.display')}} _MENU_ {{ __('reservation.records')}}",
					"search": "{{ __('reservation.search')}}",
					"paginate": {
						"next": "{{ __('reservation.next')}}",
						"previous": "{{ __('reservation.previous')}}"
					}
			  }
            });

            $(document).on('click', '.delete', function() {
                $('#ok').attr('data-id', $(this).attr('data-id'));
            });
            $(document).on('click', '#ok', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                var id = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('payment.destroy') }}/" + id,
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

            location.href = "{{ route('payment.edit') }}/" + id;

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