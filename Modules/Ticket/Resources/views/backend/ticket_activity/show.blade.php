@extends('backend.layouts.app')
@section('content')

    <div class="container " id="mydiv">
        <center>
            <h1 class="text-success mt-4">{{__('Ticket.The Tickets Activities')}} </h1>
            <h5 class="text-value-lg">{{__('Ticket.Ticket')}} : {{ $type }}</h5>
        </center>
        <div class="row">


                <div class="float-right">
                    <button type="" class="btn btn-success" data-toggle="modal" data-target="#myModal" >
                        <i class="fas fa-plus-circle"></i>
                    </button>




                   <label class="font-weight-bold"> {{__('Ticket.Add')}} {{__('Ticket.detail')}}</label>


                </div>

        </div>
        <br>
        @foreach ($activities as $act)

            <div class="card">

                    <div class="modal-header">

                        <h3 class="text-value-lg">{{__('Ticket.cAt')}} : {{ $act->created_at }}</h3>
                        <button class="btn btn-danger btn-sm  delete" id="{{ $act->id }}" ><i class="fa fa-trash"></i></button>
                     </div>






                <div class="card-body">

                    <small class="text-muted">{{ $act->descriptions }}</small>

                    <button type="submit" class=" close btn btn-danger" data-toggle="modal" data-target="#myModal" title="Create Room">

                </div>
            </div>
        @endforeach
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Ticket.Add')}} {{__('Ticket.Ticket')}}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                    </div>
                    <div class="modal-body ">

                        <form action="" id="addTicket"
                            style="height: 50%;margin: 0px auto; box-shadow:  0 0 10px  #303c54;  border-radius:25px; background-color:#fff;padding:25px;  border: solid 1px #555;">


                            <center>
                                <h4 for="ticketid ">{{__('Ticket.Ticket')}} {{$type}}</h4>


                                <input type="hidden" name="ticketid" value="{{$id}}">
                                <input type="hidden" name="type" value="{{$type}}">
                                <div class="form-group  ">
                                    <h4 for="descriptions ">{{__('Ticket.Description')}} :</h4>

                                    <textarea type=" " name="descriptions" id="descriptions" placeholder="discripe here."
                                        required> </textarea>
                                </div>

                                <button type="submit" data-id=""   class="btn btn-danger">{{__('Ticket.Add')}}</button>
                                <button type="button" id="cancel" class="btn btn-primary" data-dismiss="modal">{{__('Ticket.Cancel')}}</button>

                            </center>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <script>
        $('#addTicket').on('submit', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var form = $('#addTicket');
            var data = form.serialize();
            $.ajax({
                url: "{{ route('ticketsActivities.store') }}",
                type: 'get',
                dataType: 'json',
                data: data,
                success: function(data) {
                    if (data.response == true) {
                        form.each(function() {
                            this.reset();
                        });

                        location.reload();
                    } else {}
                }
            });


        });


        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var id =  new Array($(this).attr('id'));

            $.ajax({
            url: "{{ route('ticketsActivities.destroy') }}/"+id,
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    if (data.response == true) {
                        $("#mydiv").load(location.href + " #mydiv");
                    } else {}
                }
            });
        });
    </script>
@endsection
