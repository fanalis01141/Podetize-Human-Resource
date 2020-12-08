@extends('layouts.app')

@section('content-dashboard')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            @if ($errors->any())
                <p class="badge badge-danger"><h3>Error in adding new employee:</h3></p>
                @foreach ($errors->all() as $error)
                    <p style="color:red">{{$error}}</p>
                @endforeach
            @endif

            <div class="text-right">
                    <button class="btn btn-primary shadow" data-toggle="modal" data-target="#addnewP"><i class="fa fa-plus"></i> New Employee</button>

            </div>
            <div class="card shadow">
                <h4 class="card-header text-light bg-secondary">
                    <div class="row">
                    <div class="col-lg-8">
                        EMPLOYEES
                    </div>
                    <div class="col-lg-4 text-right">
                        <select name="positionFilter" id="positionFilter" style="width: 100px;">
                            <option value="ALL">-- ALL --</option>
                            @foreach ($showDep as $s)
                                <option value="{{$s->department_name}}">{{$s->department_name}}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary filter" >FILTER</button>
                        {{-- <div class="row ml-5">
                            <button class="btn btn-primary open-filter ml-3" >Open Filter Panel</button>
                        </div> --}}
                    </div>
                    </div>
                </h4>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12 d-flex justify-content-end">
                            <input type="text" name="" id="by-name" class="form-control" placeholder="Search employee by first name " style="max-width: 300px;">
                        </div>
                    </div>

                    {{-- Success message --}}
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Employee table --}}
                    <table class="table table-hover table-striped table-bordered text-center">
                        <thead class="text-secondary">
                            <tr>
                                <th scope="col">Employee Name</th>
                                <th scope="col">Position</th>
                                <th scope="col">Date Hired</th>
                                <th scope="col">Monthly Rate</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id='emp_body'>
                            @foreach ($users as $user)
                            <tr>
                                <td scope="col">{{$user->fname. ' ' . $user->lname}}</td>
                                <td scope="col">{{$user->position}}</td>
                                <td scope="col">{{date("F d, Y", strtotime($user->date_hired))}}</td>
                                <td scope="col">${{$user->monthly_rate}}</td>
                                <td class="">

                                    <div class="text-center">
                                        <a href="{{route('employee.show', $user->id)}}" class="btn btn-success btn-sm">
                                            <i class="far fa-eye"></i>&nbsp;View Details</a>

                                        <button data-myID="{{$user->id}}"
                                            class="btn btn-primary btn-sm timeoff">
                                            <i class="far fa-clock"></i>&nbsp;Schedule</button>

                                        <a href="{{route('salary.show', $user->id)}}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-dollar-sign"></i>&nbsp;Salary Record</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            {{$users->links()}}
        </div>
    </div>

</div>


    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id='addnewP'>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <form action="{{route('users.create')}}" method="POST">
            @csrf

                <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="basic-info-tab" data-toggle="tab" href="#basic-info" role="tab" aria-controls="basic-info" aria-selected="true"><h5>Basic Info</h5></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-info-tab" data-toggle="tab" href="#contact-info" role="tab" aria-controls="contact-info" aria-selected="false"><h5>Contact Info</h5></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="veem-tab" data-toggle="tab" href="#veem" role="tab" aria-controls="veem" aria-selected="false"><h5>Veem</h5></a>
                </li>

            </ul>

        <!-- Tab panes -->
            <div class="tab-content">

                {{-- BASIC INFO TAB --}}
                <div class="tab-pane active" id="basic-info" role="tabpanel" aria-labelledby="basic-info-tab">
                    <div class="container">
                        <h5 class="mt-3"><b>Personal Information</b></h5>

                        <div class="row">
                            <div class="col-md-4">
                                <label for="name">First Name</label>
                                <input type="text" name="fname" id="fname" class="form-control new-employee-input" required value="{{old('fname')}}">
                            </div>

                            <div class="col-md-4">
                                <label for="name">Last Name</label>
                                <input type="text" name="lname" id="lname" class="form-control new-employee-input" required value="{{old('lname')}}">
                            </div>

                            <div class="col-md-4">
                                <label for="hired">Birthday</label>
                                <input type="date" name="birthday" id="birthday" class="form-control new-employee-input mb-3" required value="{{date('Y-m-d')}}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mt-2">
                                <label for="email">Username</label>
                                <input type="text" name="username" class="form-control new-employee-input" required value="{{old('username')}}">
                            </div>
                            <div class="col-md-4 mt-2">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control new-employee-input" required value="{{old('password')}}"><span></span>
                                <a class="btn btn-success" style="color:white" id='generate_pass'>Generate password</a>
                            </div>
                            <div class="col-md-4 mt-2">
                                    <label for="hired">Date Hired</label>
                                    <input type="date" name="hired" id="hired" class="form-control new-employee-input mb-3" required value="{{date('Y-m-d')}}">
                            </div>
                        </div>

                        <hr class="col-md-5">

                        <div class="row">
                            <div class="col-md-4">
                                <label for="rate">Enter daily rate (USD $):<label>
                                <input type="number" name="daily_rate" class="form-control new-employee-input" required value="{{old('daily_rate')}}" min="1" max="10000" step=".01">
                            </div>
                            <div class="col-md-4">
                                <label for="rate">Enter bi-weekly rate (USD $):<label>
                                <input type="number" name="bi_weekly_rate" class="form-control new-employee-input" required value="{{old('bi_weekly_rate')}}" min="1" max="10000" step=".01">
                            </div>
                            <div class="col-md-4">
                                <label for="rate">Enter monthly rate (USD $):<label>
                                <input type="number" name="monthly_rate" class="form-control new-employee-input" required value="{{old('monthly_rate')}}" min="1" max="10000" step=".01">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label for="inlineRadio1">Enter WEEKS of training:</label>
                                <input type="number" name="weeks_of_training" class="form-control new-employee-input" required value="{{old('rate')}}" min="1" max="10000">
                            </div>
                            <div class="col-md-4">
                                <label for="salary_type">Employment Type:</label>
                                <select class="form-control" name="employment_type" id="employment_type">
                                    <option disabled selected value="">--Choose Salary Type--</option>
                                    <option value="FULL">Full time</option>
                                    <option value="PART">Part time</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="inlineRadio1">Referred by:</label>
                                <select name="referred_by" id="referred_by" class="form-control new-employee-input">
                                    <option disabled selected value="">--Select Referral--</option>
                                    @foreach ($users as $u)
                                        <option value="{{$u->fname . ' ' . $u->lname}}">{{$u->fname . ' ' . $u->lname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="select1">Department</label>
                                <select name="department" id="department" class="form-control new-employee-input" required>
                                    <option disabled selected>--Select Department--</option>
                                    @foreach ($showDep as $rowDep)
                                    <option value="{{$selectedDep=$rowDep->department_name}}">{{$rowDep->department_name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="select2">Position</label>
                                <select name="position" id="position" class="form-control new-employee-input" required>
                                    <option disabled selected value="">--Select Position--</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CONTACT INFO --}}
                <div class="tab-pane" id="contact-info" role="tabpanel" aria-labelledby="contact-info-tab">
                    <div class="container">
                        <h5 class="mt-3"><b>Contact Information</b></h5>

                        <div class="row">
                            <div class="col-md-4">
                                <label for="name">Email</label>
                                <input type="email" name="email" id="email" class="form-control new-employee-input"  value="{{old('email')}}">
                            </div>
                            <div class="col-md-8">
                                <label for="name">Address</label>
                                <input type="text" name="address" id="address" class="form-control new-employee-input"  value="{{old('address')}}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label for="name">Contact Number</label>
                                <input type="text" name="contact_number" id="contact_number" class="form-control new-employee-input"  value="{{old('contact_number')}}">
                            </div>
                            <div class="col-md-4">
                                <label for="name">Emergency Contact Person</label>
                                <input type="text" name="emergency_contact_person" id="emergency_contact_person" class="form-control new-employee-input"  value="{{old('emergency_contact_person')}}">
                            </div>
                            <div class="col-md-4">
                                <label for="name">Emergency Contact Number</label>
                                <input type="text" name="emergency_contact_number" id="emergency_contact_number" class="form-control new-employee-input"  value="{{old('emergency_contact_number')}}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- VEEM --}}
                <div class="tab-pane" id="veem" role="tabpanel" aria-labelledby="veem-tab">
                    <div class="container">

                        <h5 class="mt-3"><b>Veem Details</b></h5>

                        <div class="row">
                            <div class="col-md-4">
                                <label for="name">Veem Email</label>
                                <input type="email" name="veem_email" id="veem_email" class="form-control new-employee-input"  value="{{old('veem_email')}}">
                            </div>
                            <div class="col-md-8">
                                <label for="name">Complete Bank Account Name</label>
                                <input type="text" name="complete_bank_account_name" id="complete_bank_account_name" class="form-control new-employee-input"  value="{{old('complete_bank_account_name')}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="name">Veem Mobile Number</label>
                                <input type="text" name="veem_mobile_number" id="veem_mobile_number" class="form-control new-employee-input"  value="{{old('veem_mobile_number')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success" id="btnSave">Save</button>
            </div>

            </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="time_off" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('schedule.update', '893459384')}}" method="POST">
                @method('PATCH')
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input mon" type="checkbox" id="inlineCheckbox1" name="Monday" value="ON">
                                    <label class="form-check-label" for="inlineCheckbox1">Monday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input tue" type="checkbox" id="inlineCheckbox2" name="Tuesday" value="ON">
                                    <label class="form-check-label" for="inlineCheckbox2">Tuesday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input wed" type="checkbox" id="inlineCheckbox3" name="Wednesday" value="ON">
                                    <label class="form-check-label" for="inlineCheckbox3">Wednesday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input thu" type="checkbox" id="inlineCheckbox4" name="Thursday" value="ON">
                                    <label class="form-check-label" for="inlineCheckbox4">Thursday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input fri" type="checkbox" id="inlineCheckbox5" name="Friday" value="ON">
                                    <label class="form-check-label" for="inlineCheckbox5">Friday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input sat" type="checkbox" id="inlineCheckbox6" name="Saturday" value="ON">
                                    <label class="form-check-label" for="inlineCheckbox6">Saturday</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input sun" type="checkbox" id="inlineCheckbox7" name="Sunday" value="ON">
                                    <label class="form-check-label" for="inlineCheckbox7">Sunday</label>
                                </div>

                                <input type="text" id="emp_id" name="emp_id" hidden>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    $('.open-filter').click(function(){
        $('#filter-panel').modal();
    });

    $('#generate_pass').click(function(e){
        var hired = $('#hired').val();
        var fname = $('#fname').val();
        var lname = $('#lname').val();

        var first_letter = fname.charAt(0);
        var second_letter = lname.charAt(0);
        var splitter = hired.split("-");

        var generated = first_letter.toUpperCase() + second_letter.toUpperCase() + splitter[1] + splitter[2] + splitter[0] + "_!"

        if(fname == '' || fname == null || lname == '' || lname == null){
            Swal.fire(
                'First and last name not found',
                'Please fill out first and last name before generating password',
                'warning'
            )
        }else{
            Swal.fire(
                "Generated Password:",
                generated,
                'success'
            )
            $('#password').val(generated);
        }

    });

    $('.timeoff').click(function(e){
        var id = $(this).attr('data-myID');
        $.ajax({
            type:'POST',
            url:'/check-time-off',
            data : { "_token": "{{ csrf_token() }}" , "id" : id} ,
            success:function(data) {
                data.Sunday == 'OFF' ? $('.sun').prop('checked', false) : $('.sun').prop('checked', true);
                data.Saturday == 'OFF' ? $('.sat').prop('checked', false) : $('.sat').prop('checked', true);
                data.Monday == 'OFF' ? $('.mon').prop('checked', false) : $('.mon').prop('checked', true);
                data.Tuesday == 'OFF' ? $('.tue').prop('checked', false) : $('.tue').prop('checked', true);
                data.Wednesday == 'OFF' ? $('.wed').prop('checked', false) : $('.wed').prop('checked', true);
                data.Thursday == 'OFF' ? $('.thu').prop('checked', false) : $('.thu').prop('checked', true);
                data.Friday == 'OFF' ? $('.fri').prop('checked', false) : $('.fri').prop('checked', true);
                $('#emp_id').val(id);
                $('#time_off').modal();
            }
        });
    });

    $('.filter').click(function(){
        var department = $('#positionFilter').val();

        $.ajax({
            type:'POST',
            url:'/filter-employee-list',
            data : { "_token": "{{ csrf_token() }}" , "department" : department} ,
            success:function(data) {
                console.log(data);
                $('#emp_body').empty();
                $.each(data, function(key, val){

                    $('#emp_body').append("<tr><td>"+val.name+"</td><td>"+val.position+"</td><td>"+val.date_hired+"</td><td>"+val.monthly_rate+"</td>"+
                        "<td><button  data-myID="+val.id+" class='btn btn-success btn-sm viewDetails'><i class='far fa-eye'></i>&nbsp;View Details</button>"+
                        "<button data-myID="+val.id+" class='ml-1 btn btn-primary btn-sm timeoff' ><i class='far fa-clock'></i>&nbsp;Schedule</button>"+
                        "<button data-myID="+val.id+" onclick=details("+val.id+")  class='ml-1 btn btn-secondary btn-sm salaryRecord'><i class='fas fa-dollar-sign'></i>&nbsp;Salary Record</a></td></tr>")
                });

                $('.timeoff').click(function(e){
                        var id = $(this).attr('data-myID');
                        $.ajax({
                        type:'POST',
                        url:'/check-time-off',
                        data : { "_token": "{{ csrf_token() }}" , "id" : id} ,
                        success:function(data) {
                            data.Sunday == 'OFF' ? $('.sun').prop('checked', false) : $('.sun').prop('checked', true);
                            data.Saturday == 'OFF' ? $('.sat').prop('checked', false) : $('.sat').prop('checked', true);
                            data.Monday == 'OFF' ? $('.mon').prop('checked', false) : $('.mon').prop('checked', true);
                            data.Tuesday == 'OFF' ? $('.tue').prop('checked', false) : $('.tue').prop('checked', true);
                            data.Wednesday == 'OFF' ? $('.wed').prop('checked', false) : $('.wed').prop('checked', true);
                            data.Thursday == 'OFF' ? $('.thu').prop('checked', false) : $('.thu').prop('checked', true);
                            data.Friday == 'OFF' ? $('.fri').prop('checked', false) : $('.fri').prop('checked', true);
                            $('#emp_id').val(id);
                            $('#time_off').modal();
                        }
                    });
                });


                $('.viewDetails').click(function(e){
                        var id = $(this).attr('data-myID');
                        window.location.href = "http://127.0.0.1:8000/employee/"+id;
                });

                $('.salaryRecord').click(function(e){
                        var id = $(this).attr('data-myID');
                        window.location.href = "http://127.0.0.1:8000/salary/"+id;
                });
            }
        });
    });

    $('#by-name').keyup(function(){
        var name = $('#by-name').val();
        $.ajax({
            type:'POST',
            url:'/search-by-name',
            data : { "_token": "{{ csrf_token() }}" , "name" : name} ,
            success:function(data) {
                console.log(data);
                $('#emp_body').empty();
                $.each(data, function(key, val){
                    $('#emp_body').append("<tr><td>"+val.name+"</td><td>"+val.position+"</td><td>"+val.date_hired+"</td><td>"+val.monthly_rate+"</td>"+
                        "<td><button  data-myID="+val.id+" class='btn btn-success btn-sm viewDetails'><i class='far fa-eye'></i>&nbsp;View Details</button>"+
                        "<button data-myID="+val.id+" class='ml-1 btn btn-primary btn-sm timeoff' ><i class='far fa-clock'></i>&nbsp;Schedule</button>"+
                        "<button data-myID="+val.id+" onclick=details("+val.id+")  class='ml-1 btn btn-secondary btn-sm salaryRecord'><i class='fas fa-dollar-sign'></i>&nbsp;Salary Record</a></td></tr>")
                });

                $('.timeoff').click(function(e){
                        var id = $(this).attr('data-myID');
                        $.ajax({
                        type:'POST',
                        url:'/check-time-off',
                        data : { "_token": "{{ csrf_token() }}" , "id" : id} ,
                        success:function(data) {
                            data.Sunday == 'OFF' ? $('.sun').prop('checked', false) : $('.sun').prop('checked', true);
                            data.Saturday == 'OFF' ? $('.sat').prop('checked', false) : $('.sat').prop('checked', true);
                            data.Monday == 'OFF' ? $('.mon').prop('checked', false) : $('.mon').prop('checked', true);
                            data.Tuesday == 'OFF' ? $('.tue').prop('checked', false) : $('.tue').prop('checked', true);
                            data.Wednesday == 'OFF' ? $('.wed').prop('checked', false) : $('.wed').prop('checked', true);
                            data.Thursday == 'OFF' ? $('.thu').prop('checked', false) : $('.thu').prop('checked', true);
                            data.Friday == 'OFF' ? $('.fri').prop('checked', false) : $('.fri').prop('checked', true);
                            $('#emp_id').val(id);
                            $('#time_off').modal();
                        }
                    });
                });


                $('.viewDetails').click(function(e){
                        var id = $(this).attr('data-myID');
                        window.location.href = "http://127.0.0.1:8000/employee/"+id;
                });

                $('.salaryRecord').click(function(e){
                        var id = $(this).attr('data-myID');
                        window.location.href = "http://127.0.0.1:8000/salary/"+id;
                });
            }
        });
    });
    
</script>
@endpush
