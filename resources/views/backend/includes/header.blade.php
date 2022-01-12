<header class="c-header c-header-light c-header-fixed c-header-with-subheader">
    <button class="c-header-toggler c-class-toggler d-lg-none mr-auto" type="button" data-target="#sidebar"
        data-class="c-sidebar-show"><span class="c-header-toggler-icon"></span></button><a class="c-header-brand d-sm-none"
        href="{{ route('backend.dashboard') }}">
        <img class="c-header-brand" src="{{ asset('img/backend-logo.jpg') }}" style="max-height:50px;min-height:40px;"
            alt="{{ app_name() }}"></a>
    <button class="c-header-toggler c-class-toggler ml-3 d-md-down-none" type="button" data-target="#sidebar"
        data-class="c-sidebar-lg-show" responsive="true"><span class="c-header-toggler-icon"></span></button>

    <ul class="c-header-nav d-md-down-none">
        <li class="c-header-nav-item px-3">
            <a class="c-header-nav-link" href="{{ url('/') }}" target="_blank">
                Samarqand Hotel
            </a>
        </li>
    </ul>
	
    <ul class="c-header-nav @if (str_contains(url()->current(), '/ar/')) mr-auto ml-4 @else ml-auto mr-4 @endif ">
	
	{{-- for multilingual implementation --}}
		@include('backend.includes.language-switcher') 


        <li class="c-header-nav-item dropdown d-md-down-none mx-2">


            <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                aria-expanded="false">
                <i class="c-icon cil-bell"></i>&nbsp;
                <span class="hid badge badge-pill badge-danger">
                    <div class="notifications_count"></div>
                </span>
            </a>
            <div class="hid dropdown-menu dropdown-menu-right dropdown-menu-lg pt-0">
                <div class="dropdown-header bg-light">
                    <strong class="">لديك :<span class="notifications_count"></span> اشعار</strong>
                </div>
                <div id="noti">

                </div>
            </div>
        </li>
        <style>
            @media print {
                .noprint {
                    visibility: hidden;
                }
            }

        </style>
        <li class="c-header-nav-item dropdown">
            <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                aria-expanded="false">
                <div class="noprint c-avatar">
                    <img class="c-avatar-img" src="{{ asset(auth()->user()->avatar) }}"
                        alt="{{ auth()->user()->name }}">
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right pt-0">
                <!--<div class="dropdown-header bg-light py-2"><strong>@lang('Account')</strong></div>-->

                <!--<a class="dropdown-item" href="{{ route('backend.users.profile', Auth::user()->id) }}">-->
                <!--    <i class="c-icon cil-user"></i>&nbsp;-->
                <!--    {{ Auth::user()->name }}-->
                <!--</a>-->
                <!--<a class="dropdown-item" href="{{ route('backend.users.profile', Auth::user()->id) }}">-->
                <!--    <i class="c-icon cil-at"></i>&nbsp;-->
                <!--    {{ Auth::user()->email }}-->
                <!--</a>-->
                <!--<a class="dropdown-item" href="{{ route('backend.notifications.index') }}">-->
                <!--    <i class="c-icon cil-bell"></i>&nbsp;-->
                {{-- <!--    @lang('Notifications') <span class="badge badge-danger ml-auto">{{ $notifications_count }}</span>--> --}}
                <!--</a>-->

                <!--<div class="dropdown-header bg-light py-2"><strong>@lang('Settings')</strong></div>-->


                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="c-icon cil-account-logout"></i>&nbsp;
                    {{__('app.logout')}}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf
                </form>
            </div>
        </li>
    </ul>
    <div class="c-subheader justify-content-between px-3">
        <ol class="breadcrumb border-0 m-0">
            @yield('breadcrumbs')
        </ol>
        <div class="c-subheader-nav d-md-down-none mfe-2">
            <span class="c-subheader-nav-link">
                <div class="btn-group" role="group" aria-label="Button group">
                    {{ date_today() }}&nbsp;<div id="liveClock" class="clock" onload="showTime()"></div>
                </div>
            </span>
        </div>
    </div>
</header>

@push('after-scripts')
    <script type="text/javascript">
        $(function() {
            // Show the time
            showTime();
            getNotify();
        })

        function getNotify() {
            // notification update
            $.ajax({
                url: "{{ route('room.getNotify') }}",
                type: 'get',
                dataType: 'json',
                success: function(data) {



                    $('#noti').html(data['noti']);
                if(data['notifications_count']!=0){
                    $('.hid').css('display',"");
                    $('.notifications_count').html(data['notifications_count']);
                }

                    else
                    $('.hid').css('display',"none");


                }
            });

            //
            setTimeout(getNotify, 3000);

        }

        function showTime() {
            var date = new Date();
            var hours = date.getHours();
            var minutes = date.getMinutes();
            var seconds = date.getSeconds();

            var session = hours >= 12 ? 'pm' : 'am';

            hours = hours % 12;
            hours = hours ? hours : 12;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            var time = hours + ":" + minutes + ":" + seconds + " " + session;
            document.getElementById("liveClock").innerText = time;
            document.getElementById("liveClock").textContent = time;

            setTimeout(showTime, 1000);
        }
    </script>
@endpush
