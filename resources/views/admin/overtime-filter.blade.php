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
                <h4 class="card-header text-light bg-secondary">
                    <div class="row">
                        <div class="col-lg-8">\
                            zzzzzzz
                            Overtime Application List
                        </div>
                        <div class="col-lg-3 offset-1">
                            <form action="{{route('overtime.filter')}}" method="POST">
                                <div class="text-right">
                                    <div class="d-flex justify-content-between">
                                        @csrf

                                        <select name="filter" id="filter" class="form-control">
                                            <option value="PENDING">PENDING</option>
                                            <option value="ACCEPTED">ACCEPTED</option>
                                            <option value="REJECTED">REJECTED</option>
                                        </select>
                                        <button class="btn btn-success filter">FILTER</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </h4>
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
                                <td scope="col">{{{date("F jS, Y", strtotime($user->date))}}}</td>
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

        console.log(id + '   ' + empid);


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

        console.log(id + '   ' + empid);

        Swal.fire({
            title : 'Reject Overtime?',
            text : "Are you sure you want to reject " + name + "'s OT for " + date + "?",
            icon : "error",
            showConfirmButton: true,
            showCloseButton:true,
            showCancelButton:true,
            allowOutsideClick: false,
            confirmButtonText: 'Yes, reject overtime',
            confirmButtonColor: '#E3342F',
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

</script>
@endpush
