@extends('backend.layouts.app')
@section('content')

    <div class="container ">
        <center>
            <h1 class="text-success mt-4">{{ __("payment.paymentdetails") }}</h1>
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
                        <th class='text-success'>{{ __("payment.description") }}</th>
                        <th class='text-danger '>{{ __("payment.cost") }}</th>
                        <th class=''>{{ __("payment.quantity") }}</th>
                        <th class='text-warning '>{{ __("payment.total") }}</th>
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
                          
                        <h4 class="modal-title">{{ __("payment.deletepayment") }}</h4>
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
                ajax: "{{ route('payment.linegettable', ['id'=> $payment->id])}}",
                columns: [{
                        data: 'id',
                        name: 'id',
						visible:false
                    },
                    {
                        data: 'description',
                        name: '{{ __("payment.description") }}'
                    },
                    {
                        data: 'cost',
                        name: '{{ __("payment.cost") }}'
                    },
                    {
                        data: 'quantity',
                        name: '{{ __("payment.quantity") }}',
                    },
                    {
                        data: 'total',
                        name: '{{ __("payment.total") }}'
                    }
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