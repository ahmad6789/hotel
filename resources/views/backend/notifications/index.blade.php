@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ $module_title }} @endsection

 

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <h4 class="card-title mb-0">
                    <i class="{{ $module_icon }}"></i> {{__('notify.The Notifications ')}}
                    ({{__('notify.unread')}} {{$unread_notifications_count}})
                
                </h4>
            
            </div>
            <!--/.col-->
            <div class="col-4">
                <div class="float-right">
                    <a href="{{ route("backend.$module_name.markAllAsRead") }}" class="btn btn-success mt-1 btn-sm" data-toggle="tooltip" title=" {{__('notify.Mark All As Read')}}"><i class="fas fa-check-square"></i> {{__('notify.Mark All As Read')}}</a>
                    <a href="{{route("backend.$module_name.deleteAll")}}" class="btn btn-danger mt-1 btn-sm" data-method="DELETE" data-token="{{csrf_token()}}" data-toggle="tooltip" title="{{__('notify.Delete All Notifications')}}"><i class="fas fa-trash-alt"></i></a>
                </div>
            </div>
        </div>
        <!--/.row-->

        <div class="row mt-4">
            <div class="col">
                <table id="datatable" class="table table-bordered table-hover table-responsive-sm">
                    <thead>
                        <tr>
                            <th>
                                {{__('notify.Text')}}
                           
                            </th>
                            <th>
                                {{__('notify.Module')}}
                                
                            </th>
                            <th>
                                {{__('notify.Updated At')}}
                      
                            </th>
                            <th class="text-right">
                                
                                {{__('notify.Action')}}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($$module_name as $module_name_singular)
                        <?php
                        $row_class = '';
                        $span_class = '';
                        if ($module_name_singular->read_at == ''){
                            $row_class = 'table-info';
                            $span_class = 'font-weight-bold';
                        }
                        ?>
                        <tr class="{{$row_class}}">
                            <td>
                                <a href="{{ route("backend.$module_name.show", $module_name_singular->id) }}">
                                    <span class="{{$span_class}}">
                                        {{ $module_name_singular->data['title'] }}
                                    </span>
                                </a>
                            </td>
                            <td>
                                {{ $module_name_singular->data['module'] }}
                            </td>
                            <td>
                                {{ $module_name_singular->updated_at->diffForHumans() }}
                            </td>
                            <td class="text-right">
                                <a href='{!!route("backend.$module_name.show", $module_name_singular)!!}' class='btn btn-sm btn-success mt-1' data-toggle="tooltip" title=" {{__('notify.Show')}} {{__('notify.Notification')}} "><i class="fas fa-tv"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-7">
                <div class="float-left">
                  {{ $$module_name->total() }}  {{__('notify.Notification')}}
                </div>
            </div>
            <div class="col-5">
                <div class="float-right">
                    {!! $$module_name->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
