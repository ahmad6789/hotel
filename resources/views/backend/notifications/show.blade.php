@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ $module_title }} @endsection

 

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-8">
                <h4 class="card-title mb-0">
                    <i class="{{ $module_icon }}"></i> <small class="text-muted">{{__('notify.Show')}}  {{__('notify.Notification')}}</small>
                </h4>
                
            </div>
            <!--/.col-->
            <div class="col-4">
                <div class="float-right">
                    <a href="{{ route("backend.$module_name.index") }}" class="btn btn-secondary mt-1 btn-sm" data-toggle="tooltip" title=" {{__('notify.List')}} {{__('notify.The Notifications ')}}"><i class="fas fa-list"></i> </a>
                </div>
            </div>
            <!--/.col-->
        </div>
        <!--/.row-->

        <hr>

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <?php $data = json_decode($$module_name_singular->data); ?>
                        <tbody>
                            <tr>
                                <th>{{__('notify.Title')}}</th>
                                <th>
                                    {{ $data->title }}
                                </th>
                            </tr>
                            <tr>
                                <th>{{__('notify.Text')}}</th>
                                <td>
                                    {!! $data->text !!}
                                </td>
                            </tr>
                            @if($data->url_backend != '')
                            <tr>
                                <th>{{__('notify.URL Backend')}} </th>
                                <td>
                                <a href="{{$data->url_backend}}">{{$data->url_backend}}</a>
                                </td>
                            </tr>
                            @endif
                       
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col">
                <small class="float-right text-muted">
                    {{__('notify.Updated At')}}: {{$$module_name_singular->updated_at->diffForHumans()}},
                    {{__('notify.Created at')}}: {{$$module_name_singular->created_at->isoFormat('LLLL')}}
                </small>
            </div>
        </div>
    </div>
</div>

@endsection
