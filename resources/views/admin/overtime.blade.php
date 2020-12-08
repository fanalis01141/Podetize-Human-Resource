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

            <div class="card shadow">
                <div class="card-header text-light bg-secondary">
                    <div class="row">
                        <div class="col-md-3">
                            <h4>Overtime Application List</h4>
                        </div>

                        <div class="col-md-9">
                            <div class="d-flex justify-content-end">

                                From 
                                <input type="date" name="" id="ot-from" value="{{date('Y-m-d')}}" style="margin-right: 30px;">

                                To
                                <input type="date" name="" id="ot-to" value="{{date('Y-m-d')}}" style="margin-right: 30px;">

                                <select name="filter" id="filter">
                                    <option value="PENDING">PENDING</option>
                                    <option value="ACCEPTED">ACCEPTED</option>
                                    <option value="REJECTED">REJECTED</option>
                                </select>
                                <button class="btn btn-success filter">FILTER</button>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-body">

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
                                <th scope="col">Department</th>
                                <th scope="col">Overtime date</th>
                                <th scope="col">Task / Episode</th>
                                <th scope="col">Hours</th>
                                <th scope="col">For Approval By:</th>
                                <th scope="col">Status:</th>


                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id='otbody'>
                            @foreach ($users as $user)
                            <tr>
                                <td scope="col">{{$user->complete_name}}</td>
                                <td scope="col">{{$user->department}}</td>
                                <td scope="col">{{{date("F d, Y", strtotime($user->date))}}}</td>
                                <td scope="col">{{$user->task_or_eps}}</td>
                                <td scope="col">{{$user->hours}}</td>
                                <td scope="col">{{$user->for_approval}}</td>
                                <td scope="col">
                                    @if ($user->status == 'PENDING')
                                        <h5><span class="badge badge-warning">PENDING</span></h5>
                                    @elseif ($user->status == 'ACCEPTED')
                                        <h5><span class="badge badge-success">ACCEPTED</span></h5>
                                    @else
                                        <h5><span class="badge badge-danger">REJECTED</span></h5>
                                    @endif
                                </td>

                                <td class="">
                                    <div class="text-center">
                                        <button class="btn btn-success btn-sm accept"
                                        data-name="{{$user->complete_name}}" data-empid="{{$user->emp_id}}" data-newstat="ACCEPTED"
                                        data-id="{{$user->id}}" data-date="{{$user->date}}">
                                                <i class="far fa-check-circle text-light "></i></button>

                                        <button class="btn btn-danger btn-sm reject" data-newstat="REJECTED" data-empid="{{$user->emp_id}}"
                                        data-name="{{$user->complete_name}}" data-id="{{$user->id}}" data-date="{{$user->date}}">
                                                <i class="far fa-times-circle text-light "></i></button>

                                        <button class="btn btn-secondary btn-sm delete" data-newstat="DELETED" data-empid="{{$user->emp_id}}"
                                            data-name="{{$user->complete_name}}" data-id="{{$user->id}}" data-date="{{$user->date}}">
                                                <i class="far fa-trash-alt"></i></i></button>
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


@endsection

@push('scripts')
<script>
    
    $('.accept').click(function(e){

        var id = $(this).attr('data-id');
        var empid = $(this).attr('data-empid');
        var date = $(this).attr('data-date');
        var name = $(this).attr('data-name');


        Swal.fire({
            title : 'Accept Overtime?',
            text : "Are you sure you want to accept " + name + "'s OT for " + date + "?",
            icon : "success",
            showConfirmButton: true,
            showCloseButton:true,
            showCancelButton:true,
            allowOutsideClick: false,
            confirmButtonText: 'Yes, accept overtime'
        }).then(function(accept){
            if(accept.isConfirmed == true){
                $.ajax({
                    type:'POST',
                    url:'/accept-ot',
                    data : { "_token": "{{ csrf_token() }}" , "id" : id, "emp_id" : empid} ,
                    success:function(data) {
                        location.reload();
                    }
                });
            }
        })
    });

    $('.reject').click(function(e){
        var id = $(this).attr('data-id');
        var empid = $(this).attr('data-empid');
        var date = $(this).attr('data-date');
        var name = $(this).attr('data-name');

        Swal.fire({
            title : 'Reject Overtime?',
            text : "Are you sure you want to reject " + name + "'s OT for " + date + "?",
            icon : "error",
            showConfirmButton: true,
            showCloseButton:true,
            showCancelButton:true,
            allowOutsideClick: false,
            confirmButtonText: 'Yes, reject overtime',
            confirmButtonColor: '#E3342F',p
        }).then(function(accept){
            if(accept.isConfirmed == true){
                $.ajax({
                    type:'POST',
                    url:'/reject-ot',
                    data : { "_token": "{{ csrf_token() }}" , "id" : id, "emp_id" : empid} ,
                    success:function(data) {
                        location.reload();
                    }
                });
            }
        })
    });

    $('.delete').click(function(e){
        var id = $(this).attr('data-id');
        var empid = $(this).attr('data-empid');
        var date = $(this).attr('data-date');
        var name = $(this).attr('data-name');

        Swal.fire({
            title : 'Delete Overtime?',
            text : "Are you sure you want to delete " + name + "'s OT for " + date + "? This action cannot be undone.",
            icon : "error",
            showConfirmButton: true,
            showCloseButton:true,
            showCancelButton:true,
            allowOutsideClick: false,
            confirmButtonText: 'Yes, delete overtime',
            confirmButtonColor: '#E3342F',
        }).then(function(accept){
            if(accept.isConfirmed == true){
                $.ajax({
                    type:'DELETE',
                    url:'/overtime/delete-ot',
                    data : { "_token": "{{ csrf_token() }}" , "id" : id, "emp_id" : empid} ,
                    success:function(data) {
                        location.reload();
                    }
                });
            }
        })
    });

    $('.filter').click(function(e){

        var filter = $('#filter').val();
        var from = $('#ot-from').val();
        var to = $('#ot-to').val();

        var badge = '';
        if(filter == 'ACCEPTED'){
            badge = '<h5><span class="badge badge-success">ACCEPTED</span></h5>';
        }else if(filter =='REJECTED'){
            badge = '<h5><span class="badge badge-danger">REJECTED</span></h5>';
        }

        $.ajax({
            type:'POST',
            url:'/filter-ot',
            data : { "_token": "{{ csrf_token() }}" , "filter" : filter, "from" : from , "to" : to} ,
            success:function(data) {

                $('#otbody').empty();
                $.each(data, function(key, val){

                    btns = "<td>"+
                                "<button class='btn btn-secondary btn-sm delete'"+
                                "data-name="+val.name+" data-empid="+val.emp_id+" data-newstat='DELETED'"+
                                "data-id="+val.id+" data-date="+val.date+" data-date="+val.date+">"+
                                "<i class='far fa-trash-alt text-light'></i>"+
                                "</button>"+

                                "</td>";


                    $('#otbody').append("<tr><td>"+val.complete_name+"</td><td>"+val.department+"</td><td>"+val.date+"</td><td>"+val.task_or_eps+"</td><td>"+val.hours+"</td><td>"+val.for_approval+"</td><td>"+badge+"</td>"+btns+"/tr>")
                })

                $('.delete').click(function(e){
                    var id = $(this).attr('data-id');
                    var empid = $(this).attr('data-empid');
                    var date = $(this).attr('data-date');
                    var name = $(this).attr('data-name');

                    Swal.fire({
                        title : 'Delete Overtime?',
                        text : "Are you sure you want to delete " + name + "'s OT for " + date + "? This action cannot be undone.",
                        icon : "error",
                        showConfirmButton: true,
                        showCloseButton:true,
                        showCancelButton:true,
                        allowOutsideClick: false,
                        confirmButtonText: 'Yes, delete overtime',
                        confirmButtonColor: '#E3342F',
                    }).then(function(accept){
                        if(accept.isConfirmed == true){
                            $.ajax({
                                type:'DELETE',
                                url:'/overtime/delete-ot',
                                data : { "_token": "{{ csrf_token() }}" , "id" : id, "emp_id" : empid} ,
                                success:function(data) {
                                    location.reload();
                                }
                            });
                        }
                    })
                });
            }
        });
        

    });

</script>
@endpush
