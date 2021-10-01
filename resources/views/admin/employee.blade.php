
@extends('layouts.app')

<style>
.card-header{
    background-color: #3490DC !important;
}
</style>

@section('content-dashboard')

    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                <i class="far fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $err)
                    <li>{{$err}}</li>
                @endforeach
            </div>
        @endif


        <div class="d-flex justify-content-end mb-3">
            @if (strpos(URL::previous(), 'archive'))
                <div>
                    <button class="btn mr-2 btn-success reac-employee"><i class="fas fa-unlock"></i>&nbsp; Reactivate Employee</button>
                    <button class="btn mr-2 btn-danger delete-employee"><i class="fas fa-times"></i>&nbsp; Delete Employee</button>
                </div>
            @else
                <div>
                    <button class="btn mr-2 btn-warning deac-employee"><i class="fas fa-user-lock"></i>&nbsp; Deactivate Employee</button>
                    <button class="btn mr-2 btn-danger delete-employee"><i class="fas fa-times"></i>&nbsp; Delete Employee</button>
                </div>
            @endif
        </div>

                
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-light"><h4>Commendations</h4></div>
                    <div class="card-body">
                        @if ($commend->isEmpty())
                            <div class="text-center">
                                <h6>No commendations for this employee. </h6>
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
                            @foreach ($certs as $c)
                            <a href="">{{$c->cert_title}}</a>
                            @endforeach
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
                    <div class="card-header bg-primary text-light"><h4>Room for Improvement</h4></div>
                    <div class="card-body">
                        @if ($rfi->isEmpty())
                            <div class="text-center">
                                <h6>No RFI for this employee. </h6>
                                
                            </div>
                        @else
                            @foreach ($rfi as $c)
                                <i class="{{$c->icon}} fa-2x rfi-icon rmv-icon" style="color: #e01818; margin-left: 20px;" 
                                    data-title="{{$c->award_title}}"  data-content="{{$c->award_content}}" data-date="{{ date('F j, Y', strtotime($c->created_at)) }}"></i>

                            @endforeach
                        @endif
                            
                    </div>
                    <hr>
                    <div class="text-center">
                        <small>Hover over the icon to know what rfi you got!</small>
                    </div>
                </div>
            </div> --}}
        </div>

        <div class="row mt-3">
                <div class="col-lg-12">
                        <div class="card shadow">
                            <h4 class="card-header text-light d-flex justify-content-between">
                                EMPLOYEE DETAILS
                                <button class="btn btn-secondary btn-sm" id='btn-employee'>Edit Details</button>
                            </h4>
                            <div class="card-body">
                                <div class="container">
                                    <form action="{{route('employee.update', $user->id)}}" method="POST">
                                        @method('PATCH')
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                @if ($user->prof_pic != NULL)
                                                    <img style='height: 200px; height: 240px; margin-top: 50px;' src="/storage/profilePic/{{$user->prof_pic}}" alt="..." class="rounded-circle img-fluid" >
                                                @else
                                                    <img src="{{ asset('/img/wew.png') }}" alt="..." class="rounded-circle img-fluid"style="margin-top:50px;">
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">First name</label>
                                                <input class="form-control mb-3 emp-edit" type="text" name="fname" id="fname" disabled value="{{$user->fname}}">

                                                <label for="">Last name</label>
                                                <input class="form-control mb-3 emp-edit" type="text" name="lname" id="lname" disabled value="{{$user->lname}}">

                                                <label for="">Birthday:</label>
                                                <input class="form-control mb-3 emp-edit" type="date" name="birthday" id="birthday" disabled value="{{$user->birth_date}}">

                                                <label for="">Username</label>
                                                <input class="form-control mb-3 emp-edit" type="text" name="username" id="username" disabled value="{{$user->username}}">

                                                <label for="">Date Hired:</label>
                                                <input class="form-control mb-3 emp-edit" type="date" name="date_hired" id="date_hired" disabled value="{{$user->date_hired}}">

                                            </div>
                                            <div class="col-md-4">

                                                <label for="">Department</label>

                                                <select name="new_department" id="department" class="form-control mb-3" required disabled>
                                                     @foreach ($dis_dep as $d)
                                                        <option value="{{ $d->department_name }}" {{ $d->department_name == $user->department ? 'selected' : '' }}>{{$d->department_name}}</option>
                                                    @endforeach
                                                </select>


                                                <label for="select2">Position</label>
                                                
                                                <select name="new_position" id="position" class="form-control mb-3" required disabled>
                                                    @foreach ($dis_pos as $d)
                                                        <option value="{{ $d->position }}" {{ $d->position == $user->position ? 'selected' : '' }}>{{$d->position}}</option>
                                                    @endforeach
                                                </select>


                                                <label for="">Employment Status</label>
                                                <select name="emp_status" id="emp_status" class="form-control mb-3 emp-edit" disabled>
                                                    <option value="FULL" {{$user->emp_status == 'Full time' ? 'selected' : '' }}>Full Time</option>
                                                    <option value="PART" {{$user->emp_status != 'Full time' ? 'selected' : '' }}>Part Time</option>
                                                </select>

                                                <label for="select2">Referred By</label>
                                                <select name="referred_by" id="referred_by" class="form-control mb-3 emp-edit" disabled>
                                                    @if ($user->referred_by == '' || null)
                                                        <option value="">---</option>
                                                        @foreach ($referrers as $r)
                                                            <option value="{{$r->fname . ' ' . $r->lname}}"
                                                                    {{$user->referred_by == $r->fname . ' ' . $r->lname ? 'selected' : '' }}>
                                                                    {{$r->fname . ' ' . $r->lname}}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        @foreach ($referrers as $r)
                                                            <option value="{{$r->fname . ' ' . $r->lname}}"
                                                                    {{$user->referred_by == $r->fname . ' ' . $r->lname ? 'selected' : '' }}>
                                                                    {{$r->fname . ' ' . $r->lname}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-success" id='update-details'hidden>Update Details</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
        <div class="row mt-3" id="detailsss">

            {{-- CONTACT COLUMN --}}
            <div class="col-lg-4">
                <div class="card shadow">
                    <h4 class="card-header text-light d-flex justify-content-between">
                            Contact Infomation
                            <button class="btn btn-secondary btn-sm" id='btn-contact'>Edit Contact</button>
                    </h4>
                    <div class="card-body">
                        <div class="container">
                            <form action="{{route('contact.update', $user->id)}}" method="POST">
                                @method('PATCH')
                                @csrf
                                <div class="row">
                                    <label for="">Email</label>
                                    <input class="form-control mb-3 contact-edit" type="text" name="email" id="email" disabled value="{{$contact->email}}">

                                    <label for="">Address</label>
                                    <input class="form-control mb-3 contact-edit" type="text" name="address" id="address" disabled value="{{$contact->address}}">

                                    <label for="">Contact Number</label>
                                    <input class="form-control mb-3 contact-edit" type="text" name="contact_number" id="contact_number" disabled value="{{$contact->contact_number}}">

                                    <label for="">Emergency Contact Person</label>
                                    <input class="form-control mb-3 contact-edit" type="text" name="emergency_contact_person" id="emergency_contact_person" disabled value="{{$contact->emergency_contact_person}}">
                                    <label for="">Emergency Contact Number</label>
                                    <input class="form-control mb-3 contact-edit" type="text" name="emergency_contact_number" id="emergency_contact_number" disabled value="{{$contact->emergency_contact_number}}">
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-success" id='update-contact'hidden>Update Contact</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- VEEM COLUMN --}}
            <div class="col-lg-4">
                <div class="card shadow">
                    <h4 class="card-header text-light d-flex justify-content-between">
                            Veem Infomation
                            <button class="btn btn-secondary btn-sm" id='btn-veem'>Edit Veem</button>
                    </h4>
                    <div class="card-body">
                        <div class="container">
                            <form action="{{route('veem.update', $user->id)}}" method="POST">
                                @method('PATCH')
                                @csrf
                                <div class="row">
                                    <label for="">Veem Email</label>
                                    <input class="form-control mb-3 veem-edit" type="text" name="veem_email" id="veem_email" disabled value="{{$veem->veem_email}}">
                                    <label for="">Complete Bank Account Name</label>
                                    <input class="form-control mb-3 veem-edit" type="text" name="complete_bank_account_name" id="complete_bank_account_name" disabled value="{{$veem->complete_bank_account_name}}">
                                    <label for="">Veem Mobile Number</label>
                                    <input class="form-control mb-3 veem-edit" type="text" name="veem_mobile_number" id="veem_mobile_number" disabled value="{{$veem->veem_mobile_number}}">

                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-success" id='update-veem'hidden>Update Veem</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow">
                    <h4 class="card-header text-light d-flex justify-content-between">
                            Documents
                            <button class="btn btn-secondary btn-sm" id='btn-docu'>Edit Documents</button>
                    </h4>
                    <div class="card-body">
                        <div class="container">
                            <form action="{{route('documents.update', $user->id)}}" method="POST">
                                @method('PATCH')
                                @csrf
                                <div class="row">
                                    <a href="{{$docu->employee_packet}}" target="_blank">Employee Packet</a>
                                    <input class="form-control mb-3 docu-edit" type="text" name="employee_packet" id="employee_packet" disabled value="{{$docu->employee_packet}}">
                                    <a href="{{$docu->pay_stubs}}" target="_blank">Pay Stubs</a>
                                    <input class="form-control mb-3 docu-edit" type="text" name="pay_stubs" id="pay_stubs" disabled value="{{$docu->pay_stubs}}">
                                    <a href="{{$docu->metrics_sheet}}" target="_blank">Metrics Sheet</a>
                                    <input class="form-control mb-3 docu-edit" type="text" name="metrics_sheet" id="metrics_sheet" disabled value="{{$docu->metrics_sheet}}">
                                    <a href="{{$docu->evaluation_sheet}}" target="_blank">Evaluation Sheet</a>
                                    <input class="form-control mb-3 docu-edit" type="text" name="evaluation_sheet" id="evaluation_sheet" disabled value="{{$docu->evaluation_sheet}}">
                                    <a href="{{$docu->warning_memo}}" target="_blank">Warning Memo</a>
                                    <input class="form-control mb-3 docu-edit" type="text" name="warning_memo" id="warning_memo" disabled value="{{$docu->warning_memo}}">

                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-success" id='update-docu'hidden>Update Documents</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>



    <div class="modal fade" id="promote" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{route('employee.promote',$user->id)}}" method="POST">
        <div class="modal-dialog" role="document">
          <div class="modal-content modal-lg">
            <div class="modal-header text-light bg-success">
              <h5 class="modal-title" id="exampleModalLabel">PROMOTION</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body text-center">
                @csrf
                {{-- @method('PATCH') --}}
                <h3>
                    <div class="alert alert-success">
                        <i class="fas fa-user-check fa-1x"></i>
                        <p class="ml-3">Are you sure you want to promote  </p>
                            <strong><label id="myname" class="ml-3"></label></strong>
                    </div>
                    <p class="ml-3">Employee will be promoted to:  </p>
                    <strong>
                        <h3>
                        <label id="mystatus" class="ml-3 badge badge-success" ></label>
                        </h3>
                    </strong>
                </h3>
                <input type="text" name="myid" id="myid" hidden>
                <a href="" class="badge badge-light" name="myname" id="myname"></a>
                <div class="row">

                    <div class="col-md-6">
                        <label for="new_rate">Enter New Rate / Day:</label>
                        <input type="number" name="new_rate" min="1" max="999999" class="form-control" required value="{{old('new_rate')}}">
                    </div>
                    <div class="col-md-6">
                        @if ($user->emp_status == 'TRAINEE')
                            <label for="new_rate">Enter Weeks as Probationary:</label>
                            <input type="number" name="new_training" min="1" max="999999" class="form-control" required value="{{old('new_training')}}">
                        @endif
                    </div>
                </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Continue</button>
                </div>
            </div>
        </div>
        </form>
    </div>

    <div class="modal fade" id="terminate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <form action="{{route('employee.terminate',$user->id)}}" method="POST">
            <div class="modal-dialog" role="document">
              <div class="modal-content modal-lg">
                <div class="modal-header text-light bg-danger">
                  <h5 class="modal-title" id="exampleModalLabel">TERMINATE</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <div class="modal-body text-center">
                    @csrf
                    <div class="alert alert-danger">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                        <h3>
                            <p class="ml-3">Are you sure you want to TERMINATE:  </p>
                                <strong><label id="mynameterminate" class="text-center"></label></strong>
                                <br>
                            </h3>
                        </div>
                        <p class="font-weight-bold">This cannot be undone!
                            </p>
                    <input type="text" name="myidterminate" id="myid" hidden>
                    <a href="" class="badge badge-light" name="myname" id="myname"></a>
                </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-danger">Continue</button>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                @csrf
                <div class="modal-header bg-danger text-light">
                    <h5 class="modal-title">Confirm delete employee?</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('employee.destroy', $user->id)}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="text-center p-5">
                        <i class="fas fa-user-slash fa-9x" style="color:#e3342f"></i>
                        <hr>
                        <h2>Are you sure you want to delete this employee?</h2>
                        <h5>By confirming this action, this will delete all of the records of the user in the whole system.</h5>
                        <h5><strong>Please be mindful of this action. This action cannot be undone.</strong></h5>
                        <hr>
                        <button type="submit" class="btn btn-danger">I am aware of my action. Confirm delete of employee</button>
                        <button type="button" class="btn btn-secondary mt-3" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm-deac" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                @csrf
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Confirm deactivation of employee?</h5>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('employee.deacEmployee', $user->id)}}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="text-center p-5">
                        <i class="fas fa-user-lock fa-9x" style="color: #FFED4A"></i>
                        <hr>
                        <h2>Are you sure you want to deactivate this employee?</h2>
                        <input type="text" name="id" value="{{$user->id}}" hidden>
                        <h5>By confirming this action, this will move the system to the archive.</h5>
                        <h3 style="font-weight: 700;">Enter reason for deactivating of employee.</h3>
                        <input type="text" class="form-control" name="reason" placeholder="Enter reason of deactivation." required>
                        <hr>
                        <button type="submit" class="btn btn-warning">I am aware of my action. Confirm deactivation of employee</button>
                        <button type="button" class="btn btn-secondary mt-3" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm-reac" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    @csrf
                    <div class="modal-header bg-success text-light">
                        <h5 class="modal-title">Confirm deactivation of employee?</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{route('employee.reacEmployee', $user->id)}}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="text-center p-5">
                            <i class="fas fa-user-lock fa-9x" style="color: #38C172"></i>
                            <hr>
                            <h2>Are you sure you want to reactivate this employee?</h2>
                            <input type="text" name="id" value="{{$user->id}}" hidden>
                            <h5>By confirming this action, this wil remove the employee from archive.</h5>
                            <hr>
                            <button type="submit" class="btn btn-success">I am aware of my action. Confirm reactivation of employee</button>
                            <button type="button" class="btn btn-secondary mt-3" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

@endsection

@push('scripts')
    <script>
        $('#btn-employee').click(function(){
            $('.emp-edit').prop('disabled', false); //makes it enabled
            $('#position').prop('disabled', false);
            $('#department').prop('disabled', false);
            $('#update-details').prop('hidden', false);
        });
        $('#btn-contact').click(function(){
            $('.contact-edit').prop('disabled', false); //makes it enabled
            $('#update-contact').prop('hidden', false);
        });

        $('#btn-veem').click(function(){
            $('.veem-edit').prop('disabled', false); //makes it enabled
            $('#update-veem').prop('hidden', false);
        });

        $('#btn-docu').click(function(){
            $('.docu-edit').prop('disabled', false); //makes it enabled
            $('#update-docu').prop('hidden', false);
        });

        $('.delete-employee').click(function(){
            $('#confirm-delete').modal();
        })

        $('.deac-employee').click(function(){
            $('#confirm-deac').modal();
        })

        $('.reac-employee').click(function(){
            $('#confirm-reac').modal();
        });

        
        $(".rfi-icon").hover(function(){
            var title = $(this).attr('data-title');
            var content = $(this).attr('data-content');

            Swal.fire({
                icon: 'error',
                title: title,
                html: content + '<hr>' + 'ASDASDASDAD'
                // text: content
            });
        });

        $(".rmv-icon").hover(function(){
            var title = $(this).attr('data-title');
            var content = $(this).attr('data-content');
            var empid = "{{ $user->id }}";
            var date = $(this).attr('data-date');

            Swal.fire({
                title: title,
                // text: content,
                html: content + '<hr>' + 'Given on: ' + date,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Dismiss',
                confirmButtonText: 'Remove Commendation / RFI'
                }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        html: '<h3>Are you sure you want to delete <b>' + title + '</b> of {{$user->fname}}?</h3><hr>'+
                        '<h5>This action cannot be undone.</h5>',
                        // text: 'This action cannot be undone.',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#32A532',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, confirm delete'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'DELETE',
                                url: '/award/'+empid,
                                data : { "_token": "{{ csrf_token() }}" , "emp_id" : "{{ $user->id }}", "title" : title, "content" : content} ,
                                success:function(data){
                                    Swal.fire({
                                        title: data,
                                    });
                                    setTimeout(function(){
                                        location.reload();
                                    }, 2000); //run this after 3 seconds
                                }
                            });
                        }
                    })
                }
            })
        });
    </script>
@endpush
