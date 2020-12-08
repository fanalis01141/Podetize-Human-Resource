<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'ZIAN') }}</title> --}}
    <title>HR - Podetize</title>

    <!-- Scripts -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/jquery.timepicker.min.css')}}">


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <style>
    .bodyPodetize{
        background: rgba(203,203,210,.15) !important;
        font-family: 'Poppins', sans-serif;
    }

    .bg-nav-grey{
        background-color: #3490dc !important;
        display: none !important;
    }
    </style>

</head>
<body class="bodyPodetize">

    <div id="app">

        <nav class="navbar sticky-top navbar-expand-md navbar-dark bg-nav-grey">
            <div class="container">
                @auth
                    @if (Auth::user()->priority == 'HI')
                        <a class="navbar-brand" href="{{ url('/homedashboard') }}">
                            HQ
                        </a>
                    @else
                        <a class="navbar-brand" href="{{ url('/home') }}">
                            HQ
                        </a>
                    @endif
                @endauth
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">


                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li> --}}
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->fname . ' ' . Auth::user()->lname }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @auth
            @if (Auth::user()->priority == 'HI')
            <div id="wrapper">
                <!-- Sidebar -->
                <div id="sidebar-wrapper" class="bg-dark">
                    <ul class="sidebar-nav">

                        <li class="sidebar-brand bg-primary px-5 text-center" style="background-color: #343a40 !important;">
                            <a href="{{route('homedashboard')}}" class="text-white mt-2">
                                <img src="{{ asset('/img/podetize-logo.png') }}" alt="..." class="img-fluid" style="width: 200px;" >
                            </a>
                        </li>

                        <div class="text-light text-center">
                            <h5 class="">Hello, {{Auth::user()->fname . ' ' . Auth::user()->lname}}!</h5>
                            <h5 class=" font-weight-bold">{{ date("h:i A") }}</h5>
                            <h5 class=""> {{ date("l") }}</h5>
                            <h5 class=""> {{ date("M. d, Y") }}</h5>
                        </div>

                        <li  class="{{ (request()->is('homedashboard')) ? 'active-sidebar' : '' }}">
                            <a href="{{route('homedashboard')}}"> <i class="fas fa-home m-3"></i> Dashboard</a>
                        </li>
                        <li  class="{{ (request()->is('employees')) ? 'active-sidebar' : '' }}">
                            <a href="{{route('dashboard')}}"><i class="fas fa-users m-3"></i> Employees</a>
                        </li>
                        <li  class="{{ (request()->is('item')) ? 'active-sidebar' : '' }}">
                            <a href="{{route('item.index')}}"> <i class="fas fa-boxes m-3"></i>Inventory</a>
                        </li>
                        <li  class="{{ (request()->is('announcements')) ? 'active-sidebar' : '' }}">
                            <a href="{{route('announcements.index')}}"> <i class="fas fa-bullhorn m-3"></i>Announcements</a>
                        </li>
                        <li  class="{{ (request()->is('attendance')) ? 'active-sidebar' : '' }}">
                            <a href="{{route('attendance')}}"><i class="fas fa-chart-bar m-3"></i>Attendance</a>
                        </li>
                        <li  class="{{ (request()->is('overtime')) ? 'active-sidebar' : '' }}">
                            <a href="{{route('overtime.index')}}"><i class="fas fa-clock m-3"></i></i></i>Overtime</a>
                        </li>
                        <li  class="{{ (request()->is('leave')) ? 'active-sidebar' : '' }}">
                            <a href="{{route('leave')}}"><i class="fas fa-plane-departure m-3"></i>Leaves <span style="color:black; background-color:yellow; padding : 5px; border-radius: 25px; font-size:15px;" id="leave_cntr"></span></a>
                        </li>

                        <li  class="{{ (request()->is('awards-and-rfi')) ? 'active-sidebar' : '' }}">
                            <a href="{{route('zz')}}"><i class="fas fa-award m-3"></i>Awards & RFI</a>
                        </li>

                        <li  class="{{ (request()->is('settings')) ? 'active-sidebar' : '' }}">
                            <a href="{{route('settings')}}"><i class="fas fa-cogs m-3"></i>Settings</a>
                        </li>

                        <li  class="{{ (request()->is('employee-archive')) ? 'active-sidebar' : '' }}">
                            <a href="{{route('employee.employeeArchive')}}"><i class="fas fa-archive m-3"></i>Employee Archive</a>
                        </li>

                        <li>
                            <a href="https://hq.podetize.com/" rel="nofollow noopener" target="_blank"><i class="fas fa-bookmark m-3" style="color:#7b7b7b"></i>SOP Site</a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();"><i class="fas fa-arrow-circle-right m-3" style="color:#7b7b7b"></i>
                                {{ __('Logout') }}
                            </a>
                        </li>

                    </ul>
                </div>
                <!-- /#sidebar-wrapper -->
                <div id="page-content-wrapper">
                <a href="#menu-toggle" id="menu-toggle"><i class="fas fa-bars fa-2x"></i></a>
                    <main class="py-4">
                        @yield('content-dashboard')
                    </main>
                </div>
                </div>
            @endif
        @endauth

        <main class="py-4">
            @yield('content')
        </main>



    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{asset('js/jquery.timepicker.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

</body>
@stack('scripts')

<script>
    $(document).ready(function(){
        $.ajax({
            type:'POST',
            url:'/count-leaves',
            data : { "_token": "{{ csrf_token() }}"} ,
            success:function(data) {
                $('#leave_cntr').text(data);
            }
        });
    });
</script>
</html>
