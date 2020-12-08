@extends('layouts.app')

@section('content-dashboard')

<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-5">

        <div class="card shadow">
            <h5 class="card-header text-light bg-primary">
                Incoming Birthday
            </h5>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col" class="text-info">Name</th>
                            <th scope="col" class="text-info">Date</th>
                            <th scope="col" class="text-info">Days Left</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($upcoming_b as $b)
                            <tr>
                                <td scope="col">{{$b['name']}}</td>
                                <td scope="col">{{{date("F jS, Y", strtotime($b['birth_date']))}}}</td>
                                <td scope="col">{{ $b['days_left'] == 0 ? 'Today' : $b['days_left'] . ' days left'}} 
                                    <button class="btn btn-primary btn-sm hbd float-right" {{ $b['days_left'] == 0 ? '' : 'hidden'}} data-myID="{{$b['id']}}" data-name="{{$b['name']}}">Send a greeting!</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow mt-3">
            <h5 class="card-header text-light bg-primary">
                Incoming Workversary
            </h5>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col" class="text-info">Name</th>
                            <th scope="col" class="text-info">Date</th>
                            <th scope="col" class="text-info">Days Left</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($upcoming_w as $w)
                            <tr>
                                <td scope="col">{{$w['name']}}</td>
                                <td scope="col">{{{date("F jS, Y", strtotime($w['workversary']))}}}</td>
                                <td scope="col">{{$w['days_left'] == 0 ? 'Today' : $w['days_left'] + 1}}
                                    <button class="btn btn-success btn-sm greet" data-myID="{{$w['id']}}" data-name="{{$w['name']}}" {{$w['days_left'] == 0 ? '' : 'hidden'}}>Send a greeting!</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow mt-3">
            <h5 class="card-header text-light bg-primary">
                For evaluation for the month of {{date('F')}}
            </h5>
            <div class="card-body">
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr>
                        <th scope="col" class="text-info">Employee Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($for_eval_this_month as $x)
                            <tr  class="text-muted">
                                <td><a href="http://127.0.0.1:8000/employee/{{$x['emp_id']}}">{{$x['emp_name']}}</a></td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow mt-3">
            <h5 class="card-header text-light bg-primary">
                Incoming Holidays
            </h5>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                        <th scope="col" class="text-info">Holiday Name</th>
                        <th scope="col" class="text-info">Date</th>
                        <th scope="col" class="text-info">Day</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($holidays as $item)
                        <tr  class="text-muted">
                            <td>{{$item->holiday_name}}</td>
                            <td>{{date("M. d, Y", strtotime($item->holiday_date))}}</td>
                            <td>{{$item->holiday_day}}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                    <small><i> <p class="text-muted">Make sure to always update the list of Holidays in the settings.</p></i></small>
            </div>
        </div>


        </div>
        <div class="col-lg-7">
            <div class="row">
                <div class="col-4">
                    <div class="card text-white bg-primary mb-3 shadow" style="max-width: 18rem;">
                    <div class="card-body">
                    <h2><i class="far fa-building mr-3"></i></i> {{count($department)}}</h2>
                        <p class="card-text text-white">Departments</p>
                        <hr class="bg-white">
                    <div class="text-right"><small><a href="{{'/settings'}}"class="text-light">Show All</a></small></div>
                    </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card text-white bg-primary mb-3 shadow" style="max-width: 18rem;">
                    <div class="card-body">
                        <h2><i class="fas fa-user-friends mr-3"></i>{{count($users)}}</h2>
                        <p class="card-text text-white">Total Employees</p>
                        <hr class="bg-white">
                    <div class="text-right"><small><a href="{{route('dashboard')}}"class="text-light">Show All</a></small></div>
                    </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card text-white bg-primary mb-3 shadow" style="max-width: 18rem;">
                    <div class="card-body">
                    <h2><i class="fas fa-plane-departure mr-3"></i>{{count($leave)}}</h2>
                        <p class="card-text text-white">On Leave</p>
                        <hr class="bg-white">
                    <div class="text-right"><small><a href="{{route('leave')}}"class="text-light">Show All</a></small></div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card mb-3 shadow">
                        <div class="card-header bg-primary text-light">
                            <h3><small>Today's Roster</small></h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-info">Name</th>
                                        <th scope="col" class="text-info">Position</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roster_today as $r)
                                        <tr>
                                            <td scope="col">{{$r->fname . ' ' . $r->lname}}</td>
                                            <td scope="col">{{$r->position}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mb-3 shadow">
                        <div class="card-header bg-primary text-light">
                            <h3><small>Tomorrow's Roster</small></h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-info">Name</th>
                                        <th scope="col" class="text-info">Position</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roster_tomorrow as $r)
                                        <tr>
                                            <td scope="col">{{$r->fname . ' ' . $r->lname}}</td>
                                            <td scope="col">{{$r->position}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="greeting" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title" id="exampleModalLabel">Happy Workversary!</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{route('workversarry.update')}}" method="POST">
            @csrf
            <div class="modal-body">

                <div class="text-center">
                    <h3 id='greet_title'></h3>
                    <input type="text" name='workversary_id' id='workversary_id' hidden>
                </div>
                <br>
                <p > By pressing the accept button below, you will trigger all functionalities listed.</p>
                <p > This will also remove the employee from the 'Workversary list'.</p>


                <br>
                <ul class="list-group">
                    <li class="list-group-item active">Triggers:</li>
                    <li class="list-group-item">• Send an announcement greeting to everyone</li>
                    <li class="list-group-item">• Add an additional days to the employee's paid leave and vacation leave record.
                    </li>
                </ul>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Continue action, send announcement and accept triggers.</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
        </div>
    </div>
</div>

<div class="modal fade" id="hbd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title text-light" id="exampleModalLabel">Birthday greeting!</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{route('employee.greet-birthday')}}" method="POST">
            @csrf
            <div class="modal-body">

                <div class="text-center">
                    <h3 id='greet_title text'></h3>
                    <input type="text" name="hbd_title" id="hbd-title" class="form-control" required hidden>
                    <input type="text" name="hbd_content" id="hbd-content" class="form-control" required>
                </div>
                <br>
                
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Announce</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>


        $('.greet').click(function(){
            var id = $(this).attr('data-myID');
            var name = $(this).attr('data-name');

            $('#greet_title').html("Today is <b>" + name + "'s</b> workversary!'")
            $('#workversary_id').val(id);
            $('#greeting').modal();
        });


        $('.hbd').click(function(){
            var id = $(this).attr('data-myID');
            var name = $(this).attr('data-name');

            $('#greet_title').html("Today is <b>" + name + "'s</b> birthday!'")
            $('#hbd-title').val('Happy birthday, ' + name + '!')
            $('#hbd-content').val('Greetings from your Podetize family. ♥')
            $('#hbd').modal();
        });


    </script>

@endpush
