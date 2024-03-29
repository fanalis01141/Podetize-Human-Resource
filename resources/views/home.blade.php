
@extends('layouts.app')

@section('content')
{{-- user view --}}
    <div class="container">
        <form action="{{route('test.pdf')}}" method="POST">
            @csrf
            <button class="btn btn-success text-light" type="submit">SEE PDF</button>
        </form>


        {{-- Announcements Section --}}
        <div class="row">
            <div class="col-md-12">
                <div class="text-right mb-3 d-flex justify-content-between">

                        
                        <form action="{{route('employee.attendance')}}" method="POST">
                            @csrf
                            
                            <button class="btn btn-success text-light" type="submit">View Attendance</button>
                        </form>
                        <button class="btn btn-warning" style="border-color: black;" data-toggle="modal" data-target="#salary-record">View Salary Record</button>
                        <a href="{{ route('logout') }}" class="btn btn-secondary"
                        onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();"></i>
                            {{ __('LOGOUT') }}
                        </a>
                    {{-- <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        
                    </form> --}}
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('warning'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('warning') }}
                    </div>
                @endif

                <div class="card shadow">
                    <div class="card-header bg-primary text-light">
                        <h5>Announcements</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-header">
                                        Company Announcement
                                    </div>
                                    <div class="card-body text-center">
                                        @foreach ($ann as $a)
                                            <button class="btn btn-outline-primary open-ann btn-sm mb-1"  data-title="{{$a->title}}" data-content="{{$a->content}}">{{$a->title}}</button>
                                            <br>
                                        @endforeach
                                    </div>
                                    <div class="card-footer">
                                        <a href="#" data-toggle="modal" data-target="#all_company">View All</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-header">
                                        {{Auth::user()->department}} Announcement
                                    </div>
                                    <div class="card-body text-center">
                                        @foreach ($ann2 as $a)
                                            <button class="btn btn-outline-primary open-ann btn-sm mb-1"  data-title="{{$a->title}}" data-content="{{$a->content}}">{{$a->title}}</button>
                                            <br>
                                        @endforeach
                                    </div>
                                    <div class="card-footer">
                                        <a href="#" data-toggle="modal" data-target="#all_department">View All</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-header">
                                        {{Auth::user()->position}} Announcement
                                    </div>
                                    <div class="card-body text-center">
                                        @foreach ($ann3 as $a)
                                            <button class="btn btn-outline-primary open-ann btn-sm mb-1"  data-title="{{$a->title}}" data-content="{{$a->content}}">{{$a->title}}</button>
                                            <br>
                                        
                                        @endforeach
                                    </div>
                                    <div class="card-footer">
                                        <a href="#" data-toggle="modal" data-target="#all_position">View All</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-header">
                                        For you
                                    </div>
                                    <div class="card-body text-center">
                                        @foreach ($ann1 as $a)
                                            <button class="btn btn-outline-primary open-ann btn-sm mb-1"  data-title="{{$a->title}}" data-content="{{$a->content}}">{{$a->title}}</button>
                                            <br> 
                                        @endforeach
                                    </div>
                                    <div class="card-footer">
                                        <a href="#" data-toggle="modal" data-target="#all_private">View All</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Announcements Section --}}

        {{-- CMD & RFI --}}
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-light"><h5>Commendations</h5></div>
                    <div class="card-body">
                        @if ($commend->isEmpty())
                            <div class="text-center">
                                <h6>You have no commendations yet, work your way to the top!</h6>
                                <i class="fas fa-award fa-2x" style="color:gold"></i>
                            </div>
                        @else
                            @foreach ($commend as $c)
                                <i class="{{$c->icon}} fa-2x cmd-icon rmv-icon" style="color: #004FBE"
                                    data-title="{{$c->award_title}}"  data-content="{{$c->award_content}}" data-date="{{ date('F j, Y', strtotime($c->created_at)) }}"></i>
                            @endforeach
                        @endif
                    </div>
                    <hr>
                    <div class="text-center">
                        <small>Hover over the icon to know what award you got!</small>
                    </div>

                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-light"><h4>Certificates Given</h4></div>
                    <div class="card-body">
                        @if ($certs->isEmpty())
                            <div class="text-center">
                                <h6>No certificates for this employee. </h6>
                                
                            </div>
                        @else
                            Click award to see certificate
                            <br>
                            <div class="d-flex justify-content-start">

                                @foreach ($certs as $c)
                                    <form action="{{route('test.pdf')}}" method="POST">
                                        @csrf
                                        <input type="text" name="certid" id="certid" value="{{$c->id}}" hidden>
                                        <button class="btn btn-outline-success mr-2" type="submit">{{$c->cert_title}}
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        @endif
                            
                    </div>
                    <hr>
                    <div class="text-center">
                        <small>Hover over the icon to know what rfi you got!</small>
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-light"><h5>Room for Improvement</h5></div>
                    <div class="card-body">
                        @if ($rfi->isEmpty())
                            <div class="text-center">
                                <h6>You have no RFI record, keep up the good work!</h6>
                                <i class="far fa-thumbs-up fa-2x" style="color:blue"></i>
                            </div>
                        @else
                            @foreach ($rfi as $c)
                                <i class="{{$c->icon}} fa-2x rfi-icon rmv-icon" style="color: #e01818; margin-left: 20px;" 
                                    data-title="{{$c->award_title}}"  data-content="{{$c->award_content}}" data-date="{{ date('F j, Y', strtotime($c->created_at)) }}"></i>
                            @endforeach
                        @endif
                    </div>
                    <hr>
                    <div style="margin:auto">
                        <small>Hover over the icon to know what rfi you got!</small>
                    </div>
                </div>
            </div> --}}
        </div>
        {{-- End CMD & RFI --}}

        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-light">
                        <h5>Overview of Employee Info</h5>
                    </div>
                    <div class="card-body">
                       <div class="row">
                            <div class="col-lg-3 text-center">
                                @if (Auth::user()->prof_pic != NULL)
                                    <img style='height: 200px; height: 240px;' src="/storage/profilePic/{{Auth::user()->prof_pic}}" alt="..." class="rounded-circle img-fluid" >
                                @else
                                    <img src="{{ asset('/img/wew.png') }}" alt="..." class="rounded-circle img-fluid" >
                                @endif
                                <form action="{{route('profilePic')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="profilePic" id="" class="form-control">
                                    <button type="submit" class="btn btn-success">Upload</button>
                                </form>

                            </div>
                            <div class="col-lg-4">
                                <form action="{{route('selfUpdate')}}" method="POST">
                                @csrf
                                    <div class="row d-flex justify-content-between">
                                        <label class="text-secondary ml-3"><b>First Name</b></label>
                                        <a class="btn btn-secondary btn-sm mb-1 mr-3 btn-edit text-light">Edit Details</a>
                                    </div>
                                    <input type="text" name="fname" class="form-control self-details mb-3" value="{{Auth::user()->fname}}" required disabled>

                                    <label class="text-secondary"><b>Last Name</b></label>
                                    <input type="text" name="lname" class="form-control self-details mb-3" value="{{Auth::user()->lname}}" required disabled>

                                    <label for="">Birthday:</label>
                                    <input class="form-control mb-3 self-details" type="date" name="birthday" id="birthday" disabled value="{{Auth::user()->birth_date}}">

                                    <label class="text-secondary"><b>Username</b></label>
                                    <input type="text" name="username" class="form-control mb-3" value="{{Auth::user()->username}}" required disabled>
                                    <div class="text-right">
                                        <button type="submit" id='edit-details' class=" btn btn-success" hidden>Save Edit</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-4">
                                <label class="text-secondary"><b>Department:</b></label>
                                <p class ="mb-2">{{Auth::user()->department}}</p>

                                <label class="text-secondary"><b>Position:</b></label>
                                <p class ="mb-2">{{Auth::user()->position}}</p>

                                <label class="text-secondary"><b>Employment Status:</b></label>
                                <p class ="mb-2">{{Auth::user()->emp_status}}</p>

                                <label class="text-secondary"><b>Referred by:</b></label>
                                <p class ="mb-2">
                                    @if (Auth::user()->referred_by == NULL)
                                        ---
                                    @else
                                        
                                        {{Auth::user()->referred_by}}
                                    @endif
                                </p>

                                <div class="card ">
                                    <div class="card-header"><i class="far fa-file-alt"></i>&nbsp;Documents</div>

                                    <a class="ml-2" href="{{$docu->employee_packet}}" target="_blank"></i>Employee Packet</a>
                                    <a  class="ml-2" href="{{$docu->pay_stubs}}" target="_blank"></i>Pay Stubs</a>
                                    <a  class="ml-2" href="{{$docu->metrics_sheet}}" target="_blank"></i>Metrics Sheet</a>
                                    <a  class="ml-2" href="{{$docu->evaluation_sheet}}" target="_blank"></i>Evaluation Sheet</a>
                                    <a  class="ml-2" href="{{$docu->warning_memo}}" target="_blank"></i>Warning memo</a>

                                </div>
                            </div>
                       </div>

                    </div>
                </div><br>
            </div>

        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-light">
                        <h5><i class="fas fa-birthday-cake" style="color:pink"></i> Birthday celebrants for {{date('F')}}! &nbsp; </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered table-striped">

                            @if ($upcoming_b == null)
                                <div class="text-center">
                                    <p>No birthday  celebrants for this month </p> <i class="far fa-frown fa-3x"></i>
                                </div>
                            @else
                                
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-info">Name</th>
                                        <th scope="col" class="text-info">Date</th>
                                        <th scope="col" class="text-info">Days Left</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <div class="text-center">
                                        @if ($upcoming_b == null)
                                            <p>No birthday  celebrants for this month </p> <i class="far fa-frown fa-3x"></i>
                                        @endif
                                    </div>
                                    @foreach ($upcoming_b as $b)
                                        <tr>
                                            <td scope="col">{{$b['name']}}</td>
                                            <td scope="col">{{{date("F jS, Y", strtotime($b['birth_date']))}}}</td>
                                            <td scope="col">{{$b['days_left'] == 0 ? 'Today' : $b['days_left']}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow">

                    <div class="card-header bg-primary text-light" >
                            <h5>  <i class="fas fa-glass-cheers" style="color:yellow"></i> Workversary celebrants for {{date('F')}}!</h5>
                        </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered table-striped">

                            @if ($upcoming_w == null)
                                <div class="text-center">

                                    <p>No workversary  celebrants for this month </p> <i class="far fa-frown fa-3x"></i>
                                </div>
                            @endif
                                
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-info">Name</th>
                                        <th scope="col" class="text-info">Date</th>
                                        <th scope="col" class="text-info">Days Left</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <div class="text-center">
                                        @if ($upcoming_b == null)
                                            <p>No workversary  celebrants for this month </p> <i class="far fa-frown fa-3x"></i>
                                        @endif
                                    </div>
                                    @foreach ($upcoming_w as $w)
                                    <tr>
                                        <td scope="col">{{$w['name']}}</td>
                                        <td scope="col">{{{date("F jS, Y", strtotime($w['workversary']))}}}</td>
                                        <td scope="col">{{$w['days_left']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-light d-flex justify-content-between">
                        <h5><i class="fas fa-plane-departure"></i>&nbsp; Quick Leave Request</h5>
                        <button data-toggle="modal" data-target="#leave_request" class="btn btn-secondary btn-sm">Apply Leave</button>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Date of Leave</th>
                                    <th scope="col">Reason</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leave as $x)
                                <tr>
                                    <td>{{date("F jS, Y", strtotime($x->dates))}}</td>
                                    <td>{{$x->type}}</td>
                                    <td>
                                        @if ($x->status == 'PENDING')
                                            <h5>
                                                <span class="badge badge-warning text-dark">PENDING</span>
                                            </h5>
                                        @elseif ($x->status == 'APPROVED')
                                            <h5>
                                                <span class="badge badge-success text-light">APPROVED</span>
                                            </h5>
                                        @elseif( strpos( $x->type , 'Marked as absent'))
                                            <h5>
                                                <span class="badge badge-danger text-light">ABSENT</span>
                                            </h5>
                                        @else
                                            <h5>
                                                <span class="badge badge-danger text-light">REJECTED</span>
                                            </h5>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{$leave->links()}}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-light d-flex justify-content-between">

                            
                        <h5><i class="far fa-clock" style="color:darkseagreen"></i>&nbsp;Overtime Record</h5>

                        @if ($eligible == true)
                            <button data-toggle="modal" data-target="#overtime_request" class="btn btn-secondary btn-sm">Apply Overtime</button>
                        @endif

                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Date of OT</th>
                                    <th scope="col">Task / Episode</th>
                                    <th scope="col">Hours</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ot as $x)
                                <tr>
                                    <td>{{date("F jS, Y", strtotime($x->date))}}</td>
                                    <td>{{$x->task_or_eps}}</td>
                                    <td>{{$x->hours}}</td>
                                    <td>
                                        @if ($x->status == 'PENDING')
                                            <h5>
                                                <span class="badge badge-warning text-dark">PENDING</span>
                                            </h5>
                                        @elseif ($x->status == 'ACCEPTED')
                                            <h5>
                                                <span class="badge badge-success text-light">ACCEPTED</span>
                                            </h5>
                                        @else
                                            <h5>
                                                <span class="badge badge-danger text-light">REJECTED</span>
                                            </h5>

                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$ot->links()}}
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal fade bd-example-modal-lg" id="salary-record" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-light" ><h5>Salary Record</h5></div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-secondary text-light d-flex justify-content-between">
                                <h5>Current salary</h5>
                            </div>
                            <table class="table table-hover table-striped table-bordered text-center">
                                <thead class="text-secondary">
                                    <tr>
                                        <th scope="col">Current Daily Rate</th>
                                        <th scope="col">Current Biweekly Rate</th>
                                        <th scope="col">Current Monthly Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td scope="col">
                                            ${{Auth::user()->daily_rate}}
                                        </td>
                                        <td scope="col">
                                            ${{Auth::user()->bi_weekly_rate}}
                                        </td>
                                        <td scope="col">
                                            ${{Auth::user()->monthly_rate}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-secondary text-light"><h5>Salary History</h5></div>
                            <div class="card-body">
                                <table class="table table-hover table-bordered text-center">
                                    <thead class="text-secondary">
                                        <tr>
                                            <th scope="col">Daily Rate</th>
                                            <th scope="col">Biweekly Rate</th>
                                            <th scope="col">Monthly Rate</th>
                                            <th scope="col">Note / Comment</th>
                                            <th scope="col" class="text-center">Modified on</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($salary as $s)
                                            <tr class="font-weight-bold">
            
                                                <td scope="col">
                                                    ${{$s->daily_rate}}
                                                    <h5>
                                                        <p class="badge badge-{{($s->status!='INCREASE') ? 'danger' : 'success'}}">
                                                            ({{$s->add_or_ded_daily}})
                                                        </p>
                                                    </h5>
                                                </td>
                                                <td scope="col">
                                                    ${{$s->bi_weekly_rate}}
                                                    <h5>
                                                        <p class="badge badge-{{($s->status!='INCREASE') ? 'danger' : 'success'}}">
                                                            ({{$s->add_or_ded_biweekly}})
                                                        </p>
                                                    </h5>
                                                </td>
                                                <td scope="col d-flex justify-content-between">
                                                    ${{$s->monthly_rate}}
                                                    <h5>
                                                        <p class="badge badge-{{($s->status!='INCREASE') ? 'danger' : 'success'}}">
                                                            ({{$s->add_or_ded_monthly}})
                                                        </p>
                                                    </h5>
                                                </td>
                                                <td class="align-middle">
                                                    {{$s->note}}
                                                </td>
                                                <td scope="col" class="align-middle">{{{date("F jS, Y", strtotime($s->created_at))}}}</td>
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

    <div class="modal fade" id="open-list" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-light">
                    <h5 class="modal-title" id='ann-title'></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea name="" id="ann-content" cols="30" rows="3" class="form-control" disabled></textarea>
                </div>
                <small class="m-1"><i style="color:red">*Click outside the window or press ESC button on your keyboard to dismiss panel.</i></small>
            </div>
        </div>
    </div>

    <div class="modal fade" id="overtime_request" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-light">
                    <h5 class="modal-title">Overtime Application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('overtime.store')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label for="">Select a team member from your department</label>
                        <select name="member_id" id="member_id" class="form-control">
                            @foreach ($team_members as $t)
                                <option value="{{$t->id}}">{{$t->fname . ' ' . $t->lname}}</option>
                            @endforeach
                        </select>
                        <br>

                        <label for="">Task or Episode</label>
                        <input type="text" class="form-control mb-2" required name='task_or_eps'>

                        <label for="">Date of Overtime</label>
                        <input type="date" class="form-control mb-2" required name='date_of_ot' value="{{date('Y-m-d')}}">

                        <label for="">Number of Hours</label>
                        <input type="number" class="form-control mb-2" required name='hours' step=".5">

                        <label for="">Who gave you the task? &nbsp;*For approval</label>
                        <input type="text" class="form-control mb-2" required name='for_approval'>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Request OT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="leave_request" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-light">
                    <h5 class="modal-title">Leave Application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('leave.file')}}" method="POST">
                    @csrf
                    <div class="modal-body">

                        Choose type of Time Off:
                        <select name="type" id="type" class="form-control">
                            <option value="unpaid leave">Unpaid leave</option>

                            @if ($diffYears > 0)
                                <option value="Paid sick leave" {{$remaining_sick == 0 ? 'disabled' : ''}}>Paid sick leave ({{$remaining_sick}})</option>
                                <option value="Paid vacation leave" {{$remaining_vac == 0 ? 'disabled' : ''}}>Paid vacation leave  ({{$remaining_vac}})</option>
                            @endif

                            <option value="absence">Absence</option>
                            <option value="others">Others</option>

                        </select>

                        <div class="card mt-3 p-2">

                            <div class="row">
                                <div class="col-md-6">
                                    From
                                    <input type="date" name="from" class="form-control" value="{{date('Y-m-d')}}">
                                </div>
                                <div class="col-md-6">
                                    To
                                    <input type="date" name="to" class="form-control" value="{{date('Y-m-d')}}">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    Comments (This can be left blank)
                                    
                                    <textarea name="note" id="" cols="50" rows="2" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Request Leave</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="all_company" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="margin-left: -500px !important; height: 100% !important; ">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h5 class="modal-title" id="exampleModalCenterTitle">Displaying All Company Announcements</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    @foreach ($all_ann as $a)
                        <li class="list-group-item"><a href="#" class="open-ann mb-1 text-dark"  data-title="{{$a->title}}" data-content="{{$a->content}}"><h5>{{$a->title}}</h5></a>
                            <small><b>Posted on:</b> {{{date("F jS, Y", strtotime($a->created_at))}}}</small>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close Panel</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="all_department" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="margin-left: -500px !important; height: 100% !important; ">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h5 class="modal-title" id="exampleModalCenterTitle">Displaying All Company Announcements</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <ul class="list-group">
                    @foreach ($all_dept as $a)
                        <li class="list-group-item"><a href="#" class="open-ann mb-1 text-dark"  data-title="{{$a->title}}" data-content="{{$a->content}}"><h5>{{$a->title}}</h5></a>
                        <small><b>Posted on:</b> {{{date("F jS, Y", strtotime($a->created_at))}}}</small>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close Panel</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="all_position" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="margin-left: -500px !important; height: 100% !important; ">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h5 class="modal-title" id="exampleModalCenterTitle">Displaying All Company Announcements</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    @foreach ($all_pos as $a)
                        <li class="list-group-item"><a href="#" class="open-ann mb-1 text-dark"  data-title="{{$a->title}}" data-content="{{$a->content}}"><h5>{{$a->title}}</h5></a>
                            <small><b>Posted on:</b> {{{date("F jS, Y", strtotime($a->created_at))}}}</small>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close Panel</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="all_private" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="margin-left: -500px !important; height: 100% !important; ">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h5 class="modal-title" id="exampleModalCenterTitle">Displaying All Company Announcements</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    @foreach ($all_pvt as $a)
                        <li class="list-group-item"><a href="#" class="open-ann mb-1 text-dark"  data-title="{{$a->title}}" data-content="{{$a->content}}"><h5>{{$a->title}}</h5></a>
                            <small><b>Posted on:</b> {{{date("F jS, Y", strtotime($a->created_at))}}}</small>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close Panel</button>
            </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        $('.btn-edit').click(function(){
            $('.self-details').prop('disabled', false);
            $('#edit-details').prop('hidden', false);
        });

        $('.open-ann').click(function(e){
            var title = $(this).attr('data-title');
            var content = $(this).attr('data-content');

            $('#ann-title').html(title);
            $('#ann-content').val(content);
            $('#open-list').modal('show');
        });

        $(".rfi-icon").hover(function(){
            var title = $(this).attr('data-title');
            var content = $(this).attr('data-content');
            var date = $(this).attr('data-date');


            Swal.fire({
                // icon: 'error',
                // title: title,
                // text: content
                title: title,
                html: content + '<hr>' + 'Given on: ' + date,
                icon: 'error',
            });
        });

        $(".cmd-icon").hover(function(){
            var title = $(this).attr('data-title');
            var content = $(this).attr('data-content');
            var date = $(this).attr('data-date');
            Swal.fire({
                title: title,
                html: content + '<hr>' + 'Given on: ' + date,
                icon: 'success',
            });
        });


    </script>
@endpush
