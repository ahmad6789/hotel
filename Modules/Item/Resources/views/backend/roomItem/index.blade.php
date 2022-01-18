                                                        @extends('backend.layouts.app')
                                                        @section('content')

                                                            <div class="container ">
                                                                <center>
                                                                    <h1 class="text-success mt-4">
                                                                        {{ __('Items.The Items in Room') }}@isset($code)
                                                                        {{ $code }}@endisset
                                                                 </h1>
                                                                </center>


                                                                <button id="addRow" class="btn btn-primary">{{ __('Items.Add') }}
                                                                    {{ __('Items.Item') }}   {{ __('Items.New') }}
                                                                     </button>
                                                                {{-- <button id="Delete"
                                                            title="{{ __('Items.To Delete the Item select the and click here') }}"
                                                            class="btn btn-danger">{{ __('Items.Delete') }}
                                                            {{ __('Items.Item') }}
                                                        </button> --}}

                                                                <br><br>
                                                                <form id="form">
                                                                    <input type="hidden"
                                                                        value=" @isset($id) {{ $id }}@endisset"
                                                                            name="roomid">
                                                                        <table id="data-table"
                                                                            class=" table table-bordered data-table table-condensed table-hover"
                                                                            style="width:100%">
                                                                            <thead>
                                                                                <tr>

                                                                                    <th class='text-primary'>{{ __('Items.Name') }}
                                                                                    </th>
                                                                                    <th class='text-success'>
                                                                                        {{ __('Items.Quantity') }}</th>
                                                                                    <th class='text-success'>{{ __('Items.Roomid') }}
                                                                                    </th>
                                                                                    <th class='text-success'>{{ __('Items.Itemid') }}
                                                                                    </th>
                                                                                    <th class='text-success'>{{ __('Items.Action') }}
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                                @foreach ($data as $item)

                                                                                    <tr
                                                                                        id="{{ $item->roomid }}-{{ $item->itemid }}">
                                                                                        <td>{{ $item->name }}
                                                                                            <input type="hidden"
                                                                                                value="{{ $item->itemid }}"
                                                                                                name="nameid[]">
                                                                                        </td>
                                                                                        <td>

                                                                                            <input type="number"
                                                                                                value="{{ $item->quantity }}"
                                                                                                name="quantity[]">
                                                                                        </td>


                                                                                        <td>
                                                                                            {{ $item->roomid }}
                                                                                            <input type="hidden"
                                                                                                value="{{ $item->roomid }}"
                                                                                                name="roomid[]">
                                                                                        </td>
                                                                                        <td>
                                                                                            {{ $item->itemid }}
                                                                                            <input type="hidden"
                                                                                                value="{{ $item->itemid }}"
                                                                                                name="itemid[]">
                                                                                        </td>
                                                                                        <td>
                                                                                            <a href="#"
                                                                                                class="btn btn-danger btn-sm  delete "
                                                                                                data-id="{{ $item->roomid }}-{{ $item->itemid }}"
                                                                                                data-toggle="modal"
                                                                                                data-target="#myModal">×</a>
                                                                                        </td>
                                                                                    </tr>

                                                                                @endforeach



                                                                            </tbody>
                                                                        </table>
                                                                        <button type="submit"
                                                                            class="btn btn-success">{{ __('Items.Submit') }}</button>

                                                                    </form>

                                                                    <div id="myModal" class="modal fade" role="dialog">
                                                                        <div class="modal-dialog">

                                                                            <!-- Modal content-->
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">

                                                                                    <h4 class="modal-title">
                                                                                        {{ __('Items.Delete') }}
                                                                                        {{ __('Items.Item') }}
                                                                                    </h4>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <p>{{ __('Items.the item will be deleted , Are you sure?') }}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" id="ok" class="btn btn-danger"
                                                                                        data-dismiss="modal">{{ __('Items.Submit') }}</button>
                                                                                    <button type="button" class="btn btn-primary"
                                                                                        data-dismiss="modal">{{ __('Items.Cancel') }}</button>

                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <script>
                                                                        $(document).ready(function() {
                                                                            $.noConflict();
                                                                            var table = $('.data-table').DataTable({
                                                                                "language": {
                                                                                    "search": "{{ __('Items.Serach') }} :",
                                                                                    "info": "{{ __('Items.Showing') }} _START_  {{ __('Items.to') }} _END_  {{ __('Items.of') }}  _TOTAL_ {{ __('Items.entries') }} ",
                                                                                    "processing": "{{ __('Items.Processing...') }}",
                                                                                    "lengthMenu": "{{ __('Items.Show') }} _MENU_  {{ __('Items.entries') }}",
                                                                                    "emptyTable": "{{ __('Items.No data available in table') }} ",
                                                                                    "paginate": {
                                                                                        "first": "{{ __('Items.first') }}",
                                                                                        "last": "{{ __('Items.last') }} ",
                                                                                        "next": "{{ __('Items.Next') }} ",
                                                                                        "previous": "{{ __('Items.Previous') }} ",
                                                                                    }
                                                                                },

                                                                                processing: true,
                                                                                "columnDefs": [{
                                                                                        "targets": [2],
                                                                                        "visible": false,

                                                                                    },
                                                                                    {
                                                                                        "targets": [3],
                                                                                        "visible": false
                                                                                    }
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
                                                                                    url: "{{ route('roomItems.destroy') }}",
                                                                                    data: {
                                                                                        itemRoomid: id
                                                                                    },
                                                                                    type: 'get',
                                                                                    dataType: 'json',
                                                                                    success: function(response) {
                                                                                        if (response.response == 1) {
                                                                                            var table = $('#data-table').DataTable();
                                                                                            $("#myDiv")
                                                                                            $("#" + id + "").addClass('selected');
                                                                                            table.row('.selected').remove().draw(false);


                                                                                        } else {}
                                                                                    }
                                                                                });
                                                                                $('#ok').attr('data-id', '');

                                                                            });
                                                                            $("#form").on('change', '.sel', (function() {
                                                                                var selectedval = $(this).children("option:selected").val();
                                                                                var row = $(this).closest("tr");
                                                                                row.find(".itemid").val(selectedval);

                                                                            }));
                                                                            let count = 0;
                                                                            $('#addRow').on('click', function() {



                                                                                $.ajax({
                                                                                    url: "{{ route('roomItems.getAvailableItems') }}",
                                                                                    type: 'get',
                                                                                    dataType: 'json',

                                                                                    success: function(response) {

                                                                                        '<option  value="">{{ __('Items.Select') }} {{ __('Items.Item') }}</option>'
                                                                                        for (i = 0; i < response.length; i++)
                                                                                            var str = str + '<option " value="' + response[i].id + '">' +
                                                                                                response[i].name + '</option>';
                                                                                        table.row.add([
                                                                                            '<select  name="nameid[]" class="sel" required >' +
                                                                                            str +
                                                                                            '</select>',
                                                                                            '<input type="number" value="" name="quantity[]" required>',
                                                                                            '<input type="number" value="            @isset($id){{ $id }}@endisset" name="roomid[]">',
                                                                                            '<input type="number" class="itemid" value="" name="itemid[]">',
                                                                                            '<a href="#" data-id="" class="btn btn-danger btn-sm  delete "data-toggle="modal" data-target="#myModal">×</a>',
                                                                                        ]).draw(true);

                                                                                    },





                                                                                });
                                                                            });



                                                                            // submit data
                                                                            $('#form').on('submit', function(e) {
                                                                                e.preventDefault();
                                                                                e.stopImmediatePropagation();
                                                                                var form = $(this);
                                                                                var data = form.serialize()
                                                                                console.log(form.serialize());
                                                                                $.ajax({
                                                                                    url: "{{ route('roomItems.update') }}",
                                                                                    type: 'get',
                                                                                    dataType: 'json',
                                                                                    data: data,
                                                                                    success: function(response) {
                                                                                        if (response.response == 1) {


                                                                                            location.reload();

                                                                                        } else {
                                                                                            console.log(response.response)
                                                                                        }
                                                                                    }
                                                                                });
                                                                            });

                                                                            $('#data-table tbody').on('click', 'tr', function() {
                                                                                if ($(this).hasClass('selected')) {
                                                                                    $(this).removeClass('selected');
                                                                                } else {
                                                                                    table.$('tr.selected').removeClass('selected');
                                                                                    $(this).addClass('selected');
                                                                                }
                                                                            });

                                                                            $('#Delete').click(function() {
                                                                                table.row('.selected').remove().draw(false);
                                                                            });

                                                                        });
                                                                    </script>

                                                                @endsection
