@extends('backend.layouts.app')

@section('title') @lang("Dashboard") @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs/>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-8">
                <h4 class="card-title mb-0">{{__('app.welcometo') . __('app.appname')}}</h4>
                <div class="small text-muted">{{ date_today() }}</div>
            </div>

            <div class="col-sm-4 hidden-sm-down">
                <!--<div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                    <button type="button" class="btn btn-info float-right">
                        <i class="c-icon cil-bullhorn"></i>
                    </button>
                </div>-->
            </div>
        </div>
        <hr>

        <!-- Dashboard Content Area -->

        <!-- / Dashboard Content Area -->

    </div>
</div>
<!-- / card -->

<div class="row text-center">
@can('view_rooms')
  	<div class="col col-md-4">
		<div class="card text-white bg-gradient-primary mb-3" style="max-width: 18rem;">
			<div class="card-header">{{ __('reservation.availablerooms')}}</div>
			<div class="card-body">
				<h5 class="card-title" id="available-count"></h5>
				<p class="card-text"></p>
			</div>
		</div>
	</div>
@endcan

@can('view_reservations')
	<div class="col col-md-4">
		<div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
		<div class="card-header">{{ __('reservation.occupiedrooms')}}</div>
			<div class="card-body">
				<h5 class="card-title" id="booked-count"></h5>
				<p class="card-text"></p>
			</div>
		</div>
	</div>
@endcan

@can('view_tickets')
	<div class="col col-md-4">
		<div class="card text-white bg-gradient-warning mb-3" style="max-width: 18rem;">
			<div class="card-header">{{ __('reservation.roomissues')}}</div>
			<div class="card-body">
				<h5 class="card-title" id="issues-count"></h5>
				<p class="card-text"></p>
			</div>
		</div>
	</div>
</div>
@endcan

<script>
var refreshCards = function(){
	$.ajax({
		url: "{{ route('reservation.getRoomCountStatus') }}",
		type: 'get',
		dataType: 'json',

		success: function(data) {
			$('#available-count').html(data.available);
			$('#booked-count').html(data.booked);
			$('#issues-count').html(data.issues);
		}
	});
};
$(document).ready(refreshCards);
</script>
@endsection
