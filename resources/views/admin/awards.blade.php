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

            @if (session('success'))
            <div class="alert alert-success" role="alert">
                <i class="far fa-check-circle"></i>
                {{ session('success') }}
            </div>
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
                    </div>
                </h4>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12 d-flex justify-content-end">
                            <input type="text" name="" id="by-name" class="form-control" placeholder="Search employee by first name " style="max-width: 300px;">
                        </div>
                    </div>


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
                                        <button type="button" class="btn btn-success zzz" data-empid="{{$user->id}}" data-status="commend" data-name="{{$user->fname. ' ' . $user->lname}}">
                                            <i class="fas fa-award"></i>&nbsp;Commend
                                        </button>
                                        {{-- <button class="btn btn-primary open-all-awards" data-empid="{{$user->id}}" data-status="commend" data-name="{{$user->fname. ' ' . $user->lname}}">
                                            <i class="fas fa-eye"></i>&nbsp;All Awards</button> --}}
                                            
                                        <button class="btn btn-primary certificate" data-empid="{{$user->id}}" data-status="commend" data-name="{{$user->fname. ' ' . $user->lname}}">
                                            <i class="fas fa-award"></i>&nbsp;Certificate</button>


                                        {{-- <button class="btn btn-danger open-modal" data-empid="{{$user->id}}" data-status="rfi" data-name="{{$user->fname. ' ' . $user->lname}}">
                                            <i class="far fa-frown"></i>&nbsp;Give RFI</button> --}}

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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <form action="{{route('award.store')}}" method="POST">
                @method('POST')
                @csrf
                <div class="modal-body">
                    <div class="text-center">
                        <p>Give RFI/Commend to</p>
                        <h4><b id="chosen-one"></b></h4>
                    </div>
    
                    <hr>
    
                    <div class="row" id="commend-icons" hidden>
                        <div class="col-md-12">
                            Select icon to use<br>
                            @foreach ($commend_icons as $c)
                                <a href="#" class="btn btn-icon" style="color: #004FBE;" data-icon="{{$c->class}}">
                                    <i class="{{$c->class}} fa-2x"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
    
                    <div class="row" id="rfi-icons" hidden>
                        <div class="col-md-12">
                            Select icon to use<br>
                            @foreach ($rfi_icons as $c)
                                <a href="#" class="btn btn-icon" style="color: #e21111;" data-icon="{{$c->class}}">
                                    <i class="{{$c->class}} fa-2x"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
    
                </div>
    
                <div style="padding-left: 25px; padding-right: 25px">
    
                    <input type="text" placeholder="Award title" class="form-control" name="title" required>
                    <textarea id="" cols="30" rows="10" class="form-control mt-3" name="content" placeholder="Announcement content" required></textarea>
                </div>
    
                
                <input type="text" id="award-icon"  name="award_icon" hidden>
                <input type="text" id="award-status"  name="award_status" hidden>
                <input type="text" id="empid"  name="empid" hidden>
    
    
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="certificate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="exampleModalLabel">Give Certificate</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{route('cert.store')}}" method="POST">
            @method('POST')
            @csrf
            <div class="modal-body">
                <div class="text-center">
                    <p>You are giving certificate to</p>
                    <h4><b id="chosen-one"></b></h4>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <label for="">Select certificate to give:</label>
                        <select name="certificate" id="" class="form-control">
                            @foreach ($certs as $c)
                                <option value="{{$c->cert_title}}">{{$c->cert_title}}</option>
                                
                            @endforeach
                        </select>

                        <label for="" class="mt-5">Date given:</label>
                        <br>
                        <input type="date" class="form-control" name="date_given" required>
                    </div>
                </div>

            
            <input type="text" id="emp_id"  name="emp_id" hidden>
            <input type="text" id="emp_name"  name="emp_name" hidden>
            <input type="text" id="type"  name="type" value="GIVEN" hidden>



            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
        </div>
    </div>
</div>


<div class="modal fade" id="all-awards" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="exampleModalLabel">Total Awards</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
            @method('POST')
            @csrf
            <div class="modal-body">
                <div class="text-center">
                    <p>Displaying all awards of</p>
                    <h4><b id="awards-name"></b></h4>
                </div>

                <br>

                <div class="row mt-4" id="commend-icons">
                    <h4 class="ml-2">Commendations</h4>
                    <div class="col-md-12" id="cmd-row">

                    </div>
                </div>

                <br>
                <hr>
                <br>


                <div class="row" id="rfi-icons">
                    <h4 class="ml-2">RFI</h4>

                    <div class="col-md-12" id="rfi-row">
                        
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('.zzz').click(function(){
        alert('123');

        var status = $(this).attr('data-status');
        var empid = $(this).attr('data-empid');
        var name = $(this).attr('data-name');

        if( status == 'commend' ){
            $('#rfi-icons').prop('hidden', true);
            $('#commend-icons').prop('hidden', false);
        }else{
            $('#rfi-icons').prop('hidden', false);
            $('#commend-icons').prop('hidden', true);
        }
        $('#award-status').val(status);
        $('#empid').val(empid);
        $('#chosen-one').html(name);
        
        $('#exampleModal').modal();
    });

    $('.certificate').click(function(){
        var empid = $(this).attr('data-empid');
        var name = $(this).attr('data-name');

 
        $('#emp_id').val(empid);
        $('#emp_name').val(name);

        $('#chosen-one').html(name);

        $('#certificate').modal();
    });

    $('.open-modal').click(function(){

        var status = $(this).attr('data-status');
        var empid = $(this).attr('data-empid');
        var name = $(this).attr('data-name');

        if( status == 'commend' ){
            $('#rfi-icons').prop('hidden', true);
            $('#commend-icons').prop('hidden', false);
        }else{
            $('#rfi-icons').prop('hidden', false);
            $('#commend-icons').prop('hidden', true);
        }
        $('#award-status').val(status);
        $('#empid').val(empid);
        $('#chosen-one').html(name);

        $('#award').modal();

    });

    $('.btn-icon').click(function(){
        var icon = $(this).attr('data-icon');
        $('#award-icon').val(icon);

        Swal.fire({
            title: 'You selected',
            icon: 'info',
            html: '<i class="'+icon+' fa-3x"></i>',
        })
    });

    $('#by-name').keyup(function(){
        var name = $('#by-name').val();
        $.ajax({
            type:'POST',
            url:'/search-by-name',
            data : { "_token": "{{ csrf_token() }}" , "name" : name} ,
            success:function(data) {
                $('#emp_body').empty();
                console.log(data);
                $.each(data, function(key, val){
                    $('#emp_body').append("<tr><td>"+val.name+"</td><td>"+val.position+"</td><td>"+val.date_hired+"</td><td>"+val.monthly_rate+"</td>"+
                        "<td>"+
                        "<button class='btn btn-primary certificate mr-1' data-empid="+val.id+" data-status='commend' data-name='"+val.name+"'><i class='fas fa-award'></i>&nbsp;Award</button>"+
                        "<button class='btn btn-success open-modal mr-1' data-empid="+val.id+" data-status='commend' data-name="+val.name+"><i class='fas fa-award'></i>&nbsp;Commend</button></td></tr>")
                        // "<button class='btn btn-danger open-modal' data-empid="+val.id+" data-status='rfi' data-name="+val.name+"><i class='far fa-frown'></i>&nbsp;Give RFI</button></td></tr>")
                });


                $('.open-modal').click(function(){
                    var status = $(this).attr('data-status');
                    var empid = $(this).attr('data-empid');
                    var name = $(this).attr('data-name');

                    if( status == 'commend' ){
                        $('#rfi-icons').prop('hidden', true);
                        $('#commend-icons').prop('hidden', false);
                    }else{
                        $('#rfi-icons').prop('hidden', false);
                        $('#commend-icons').prop('hidden', true);
                    }

                    $('#award-status').val(status);
                    $('#empid').val(empid);
                    $('#chosen-one').html(name);
                    $('#award').modal();

                });

                $('.certificate').click(function(){
                    var empid = $(this).attr('data-empid');
                    var name = $(this).attr('data-name');

            
                    $('#emp_id').val(empid);
                    $('#emp_name').val(name);

                    $('#chosen-one').html(name);

                    $('#certificate').modal();
                });

                $('.btn-icon').click(function(){
                    var icon = $(this).attr('data-icon');
                    $('#award-icon').val(icon);

                    Swal.fire({
                        title: 'You selected',
                        icon: 'info',
                        html: '<i class="'+icon+' fa-3x"></i>',
                    })
                });


            }
        });
    });
    
</script>
@endpush
