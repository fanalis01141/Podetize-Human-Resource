@extends('layouts.app')

@section('content-dashboard')



<div class="container-fluid">
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
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

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header bg-dark text-white d-flex justify-content-between" style="background-color: #3490dc !important;">
                    <h5 style="padding-top: 5px">Leave Records:</h5>
                    <div>
                        From
                        <input type="date" name="filter-leave" id="filter-leave-from" value="{{date('Y-m-d')}}" style="margin-right: 50px; border-radius: 5px">
                        To
                        <input type="date" name="filter-leave" id="filter-leave-to" value="{{date('Y-m-d')}}" style="border-radius: 5px">
                        <button class="btn btn-success filter-leave">Filter</button>
                    </div>

                    <div style="padding-top: 2px">
                        Search by name
                        <input type="text" name="filter-leave" id="filter-leave-name" style="border-radius: 5px" placeholder="Type employee name">
                    </div>
                </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered table-striped">
                            <thead>
                                    <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Reason</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Comments</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>

                                    </tr>
                            </thead>
                            <tbody id='leaveBody'>
                                @foreach ($leavers as $x)
                                    <tr>
                                    {{-- <th scope="row">1</th> --}}
                                        <td>{{$x->emp_name}}</td>
                                        <td>{{$x->type}}</td>
                                        <td>{{date("F d, Y", strtotime($x->dates))}}</td>
                                        <td>{{$x->note == '' ? '---' : $x->note}}</td>

                                        <td>
                                            @if ($x->status == 'PENDING')
                                                <h4>
                                                    <span class="badge badge-warning text-dark">PENDING</span>
                                                </h4>
                                            @elseif ($x->status == 'APPROVED')
                                                <h4>
                                                    <span class="badge badge-success text-light">APPROVED</span>
                                                </h4>
                                            @else
                                                <h4>
                                                    <span class="badge badge-danger text-light">REJECTED</span>
                                                </h4>
                                            @endif
                                        </td>

                                        <td class="d-flex justify-content-center">
                                            @if ($x->status == 'PENDING')
                                                
                                                <div class="text-center">
                                                        <button class="btn btn-success btn-sm approve-leave"
                                                            data-name="{{$x->emp_name}}" data-empid="{{$x->emp_id}}" data-newstat="ACCEPTED"
                                                            data-id="{{$x->id}}" data-date="{{$x->dates}}" data-date_display="{{date("F d, Y", strtotime($x->dates))}}"
                                                            ><i class="far fa-check-circle text-light "></i></button>

                                                        <button class="btn btn-danger btn-sm reject-leave" data-newstat="REJECTED" data-empid="{{$x->emp_id}}"
                                                            data-name="{{$x->emp_name}}" data-id="{{$x->id}}" data-date="{{$x->dates}}" 
                                                            data-date_display="{{date("F d, Y", strtotime($x->dates))}}"
                                                            data-type="{{$x->type}}" style="margin-right:3px;">
                                                            <i class="far fa-times-circle text-light "></i></button>

                                                </div>
                                            @endif
                                                <button class="btn btn-secondary btn-sm delete-leave" data-newstat="REJECTED" data-empid="{{$x->emp_id}}"
                                                    data-name="{{$x->emp_name}}" data-id="{{$x->id}}" data-date="{{$x->dates}}" 
                                                    data-date_display="{{date("F d, Y", strtotime($x->dates))}}"
                                                    data-type="{{$x->type}}">
                                                    <i class="far fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
            <div class="text-center">
                {{$leavers->links()}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>

        $('.approve-leave').click(function(){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var date_display = $(this).attr('data-date_display');

            Swal.fire({
                title : 'Accept leave?',
                text : "Are you sure you want to ACCEPT " + name + "'s leave for " + date_display + "?",
                icon : "success",
                showConfirmButton: true,
                showCloseButton:true,
                showCancelButton:true,
                allowOutsideClick: false,
                confirmButtonText: 'Yes, approve leave'
            }).then(function(accept){
                if(accept.isConfirmed == true){
                    $.ajax({
                        type:'POST',
                        url:'/accept-leave',
                        data : { "_token": "{{ csrf_token() }}" , "id" : id , "name" : name , "status" : 'APPROVED'} ,
                        success:function(data) {
                            Swal.fire(
                            data,
                            '',
                            'success'
                            ).then(function(accept){
                                location.reload();
                            })
                        }
                    });
                }
            })
        });

        $('.reject-leave').click(function(){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var type = $(this).attr('data-type');
            var date_display = $(this).attr('data-date_display');
            Swal.fire({
                title : 'Reject leave?',
                text : "Are you sure you want to REJECT " + name + "'s leave for " + date_display + "?",
                icon : "warning",
                showConfirmButton: true,
                showCloseButton:true,
                showCancelButton:true,
                allowOutsideClick: false,
                confirmButtonText: 'Yes, reject leave'
            }).then(function(accept){
                if(accept.isConfirmed == true){
                    $.ajax({
                        type:'POST',
                        url:'/accept-leave',
                        data : { "_token": "{{ csrf_token() }}" , "id" : id , "name" : name , "status" : 'REJECTED', "type" : type} ,
                        success:function(data) {
                            // console.log(data);
                            Swal.fire(
                            data,
                            '',
                            'success'
                            ).then(function(accept){
                                location.reload();
                            })
                        }
                    });
                }
            })
        });

        $('.delete-leave').click(function(){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var type = $(this).attr('data-type');
            var date_display = $(this).attr('data-date_display');
            Swal.fire({
                title : 'Delete leave?',
                text : "Are you sure you want to DELETE " + name + "'s leave for " + date_display + "?",
                icon : "warning",
                showConfirmButton: true,
                showCloseButton:true,
                showCancelButton:true,
                allowOutsideClick: false,
                confirmButtonText: 'Yes, reject leave'
            }).then(function(accept){
                if(accept.isConfirmed == true){
                    $.ajax({
                        type:'DELETE',
                        url:'/leave/delete-leave',
                        data : { "_token": "{{ csrf_token() }}" , "id" : id , "name" : name , "status" : 'DELETED', "type" : type} ,
                        success:function(data) {
                            Swal.fire(
                            data,
                            '',
                            'success'
                            ).then(function(accept){
                                location.reload();
                            })
                        }
                    });
                }
            })
        });

        $('.filter-leave').click(function(){
            var from = $('#filter-leave-from').val();
            var to = $('#filter-leave-to').val();
            $.ajax({
                type:'POST',
                url:'/filter-leave',
                data : { "_token": "{{ csrf_token() }}" , "from" : from, "to" : to} ,
                success:function(data) {

                    $('#leaveBody').empty();
                    console.log(data);

                    $.each(data, function(key, val){

                        if (val.status == 'PENDING'){

                            btns = "<td>"+
                                "<button class='btn btn-success btn-sm approve-leave'"+
                                "data-name="+val.name+" data-empid="+val.id+" data-newstat='APPROVED'"+
                                "data-id="+val.id+" data-date="+val.date+" data-date_display="+val.date+">"+
                                "<i class='far fa-check-circle text-light'></i>"+
                                "</button>"+

                                "<button class='btn btn-danger btn-sm reject-leave'"+
                                "data-name="+val.name+" data-empid="+val.id+" data-newstat='REJECTED'"+
                                "data-id="+val.id+" data-date="+val.date+" data-date_display="+val.date+">"+
                                "<i class='far fa-times-circle text-light '></i>"+
                                "</button>"+

                                "<button class='btn btn-secondary btn-sm delete-leave'"+
                                "data-name="+val.name+" data-empid="+val.id+" data-newstat='DELETED'"+
                                "data-id="+val.id+" data-date="+val.date+" data-date_display="+val.date+">"+
                                "<i class='far fa-trash-alt text-light'></i>"+
                                "</button>"+

                                "</td>";

                        }else{
                            btns = "<td>"+
                                "<button class='btn btn-secondary btn-sm delete-leave'"+
                                "data-name="+val.name+" data-empid="+val.id+" data-newstat='DELETED'"+
                                "data-id="+val.id+" data-date="+val.date+" data-date_display="+val.date+">"+
                                "<i class='far fa-trash-alt text-light'></i>"+
                                "</button>"+

                                "</td>";
                        }

                        if(val.note == null){
                            note = "---";
                        }else{
                            note = val.note;
                        }

                        $('#leaveBody').append("<tr><td>"+val.name+"</td><td>"+val.type+"</td><td>"+val.date+"</td><td>"+note+"</td><td>"+val.status+"</td>"+
                            btns+
                            "</tr>")          
                    });

                    $('.approve-leave').click(function(){

                        var id = $(this).attr('data-id');
                        var name = $(this).attr('data-name');
                        var date_display = $(this).attr('data-date_display');

                        Swal.fire({
                            title : 'Accept leave?',
                            text : "Are you sure you want to ACCEPT " + name + "'s leave for " + date_display + "?",
                            icon : "success",
                            showConfirmButton: true,
                            showCloseButton:true,
                            showCancelButton:true,
                            allowOutsideClick: false,
                            confirmButtonText: 'Yes, approve leave'
                        }).then(function(accept){
                            if(accept.isConfirmed == true){
                                $.ajax({
                                    type:'POST',
                                    url:'/accept-leave',
                                    data : { "_token": "{{ csrf_token() }}" , "id" : id , "name" : name , "status" : 'APPROVED'} ,
                                    success:function(data) {
                                        Swal.fire(
                                        data,
                                        '',
                                        'success'
                                        ).then(function(accept){
                                            location.reload();
                                        })
                                    }
                                });
                            }
                        })
                    });

                    $('.reject-leave').click(function(){
                        var id = $(this).attr('data-id');
                        var name = $(this).attr('data-name');
                        var type = $(this).attr('data-type');
                        var date_display = $(this).attr('data-date_display');
                        Swal.fire({
                            title : 'Reject leave?',
                            text : "Are you sure you want to REJECT " + name + "'s leave for " + date_display + "?",
                            icon : "warning",
                            showConfirmButton: true,
                            showCloseButton:true,
                            showCancelButton:true,
                            allowOutsideClick: false,
                            confirmButtonText: 'Yes, reject leave'
                        }).then(function(accept){
                            if(accept.isConfirmed == true){
                                $.ajax({
                                    type:'POST',
                                    url:'/accept-leave',
                                    data : { "_token": "{{ csrf_token() }}" , "id" : id , "name" : name , "status" : 'REJECTED', "type" : type} ,
                                    success:function(data) {
                                        // console.log(data);
                                        Swal.fire(
                                        data,
                                        '',
                                        'success'
                                        ).then(function(accept){
                                            location.reload();
                                        })
                                    }
                                });
                            }
                        })
                    });

                    $('.delete-leave').click(function(){
                        var id = $(this).attr('data-id');
                        var name = $(this).attr('data-name');
                        var type = $(this).attr('data-type');
                        var date_display = $(this).attr('data-date_display');
                        Swal.fire({
                            title : 'Delete leave?',
                            text : "Are you sure you want to DELETE " + name + "'s leave for " + date_display + "?",
                            icon : "warning",
                            showConfirmButton: true,
                            showCloseButton:true,
                            showCancelButton:true,
                            allowOutsideClick: false,
                            confirmButtonText: 'Yes, reject leave'
                        }).then(function(accept){
                            if(accept.isConfirmed == true){
                                $.ajax({
                                    type:'DELETE',
                                    url:'/leave/delete-leave',
                                    data : { "_token": "{{ csrf_token() }}" , "id" : id , "name" : name , "status" : 'DELETED', "type" : type} ,
                                    success:function(data) {
                                        Swal.fire(
                                        data,
                                        '',
                                        'success'
                                        ).then(function(accept){
                                            location.reload();
                                        })
                                    }
                                });
                            }
                        })
                    });
                }
            });
        });

        $('#filter-leave-name').keyup(function(){
            function search(){
                $.ajax({
                    type:'POST',
                    url:'/leave-search-by-name',
                    data : { "_token": "{{ csrf_token() }}" , "name" : name} ,
                    success:function(data) {
                        console.log(data);
                        $('#leaveBody').empty();

                        $.each(data, function(key, val){

                            if (val.status == 'PENDING'){

                                btns = "<td>"+
                                    "<button class='btn btn-success btn-sm approve-leave'"+
                                    "data-name="+val.name+" data-empid="+val.id+" data-newstat='APPROVED'"+
                                    "data-id="+val.id+" data-date="+val.date+" data-date_display="+val.date+">"+
                                    "<i class='far fa-check-circle text-light'></i>"+
                                    "</button>"+

                                    "<button class='btn btn-danger btn-sm reject-leave'"+
                                    "data-name="+val.name+" data-empid="+val.id+" data-newstat='REJECTED'"+
                                    "data-id="+val.id+" data-date="+val.date+" data-date_display="+val.date+">"+
                                    "<i class='far fa-times-circle text-light '></i>"+
                                    "</button>"+

                                    "<button class='btn btn-secondary btn-sm delete-leave'"+
                                    "data-name="+val.name+" data-empid="+val.id+" data-newstat='DELETED'"+
                                    "data-id="+val.id+" data-date="+val.date+" data-date_display="+val.date+">"+
                                    "<i class='far fa-trash-alt text-light'></i>"+
                                    "</button>"+

                                    "</td>";

                            }else{
                                btns = "<td>"+
                                    "<button class='btn btn-secondary btn-sm delete-leave'"+
                                    "data-name="+val.name+" data-empid="+val.id+" data-newstat='DELETED'"+
                                    "data-id="+val.id+" data-date="+val.date+" data-date_display="+val.date+">"+
                                    "<i class='far fa-trash-alt text-light'></i>"+
                                    "</button>"+

                                    "</td>";
                            }
                            var note = "";
                            if(val.note == null){
                                note = "---";
                            }else{
                                note = val.note;
                            }

                            $('#leaveBody').append("<tr><td>"+val.name+"</td><td>"+val.type+"</td><td>"+val.date+"</td><td>"+note+"</td><td>"+val.status+"</td>"+
                                btns+
                                "</tr>")          
                        });

                        $('.approve-leave').click(function(){

                            var id = $(this).attr('data-id');
                            var name = $(this).attr('data-name');
                            var date_display = $(this).attr('data-date_display');

                            Swal.fire({
                                title : 'Accept leave?',
                                text : "Are you sure you want to ACCEPT " + name + "'s leave for " + date_display + "?",
                                icon : "success",
                                showConfirmButton: true,
                                showCloseButton:true,
                                showCancelButton:true,
                                allowOutsideClick: false,
                                confirmButtonText: 'Yes, approve leave'
                            }).then(function(accept){
                                if(accept.isConfirmed == true){
                                    $.ajax({
                                        type:'POST',
                                        url:'/accept-leave',
                                        data : { "_token": "{{ csrf_token() }}" , "id" : id , "name" : name , "status" : 'APPROVED'} ,
                                        success:function(data) {
                                            Swal.fire(
                                            data,
                                            '',
                                            'success'
                                            ).then(function(accept){
                                                location.reload();
                                            })
                                        }
                                    });
                                }
                            })
                        });

                        $('.reject-leave').click(function(){
                            var id = $(this).attr('data-id');
                            var name = $(this).attr('data-name');
                            var type = $(this).attr('data-type');
                            var date_display = $(this).attr('data-date_display');
                            Swal.fire({
                                title : 'Reject leave?',
                                text : "Are you sure you want to REJECT " + name + "'s leave for " + date_display + "?",
                                icon : "warning",
                                showConfirmButton: true,
                                showCloseButton:true,
                                showCancelButton:true,
                                allowOutsideClick: false,
                                confirmButtonText: 'Yes, reject leave'
                            }).then(function(accept){
                                if(accept.isConfirmed == true){
                                    $.ajax({
                                        type:'POST',
                                        url:'/accept-leave',
                                        data : { "_token": "{{ csrf_token() }}" , "id" : id , "name" : name , "status" : 'REJECTED', "type" : type} ,
                                        success:function(data) {
                                            // console.log(data);
                                            Swal.fire(
                                            data,
                                            '',
                                            'success'
                                            ).then(function(accept){
                                                location.reload();
                                            })
                                        }
                                    });
                                }
                            })
                        });
                        
                        $('.delete-leave').click(function(){
                            var id = $(this).attr('data-id');
                            var name = $(this).attr('data-name');
                            var type = $(this).attr('data-type');
                            var date_display = $(this).attr('data-date_display');
                            Swal.fire({
                                title : 'Delete leave?',
                                text : "Are you sure you want to DELETE " + name + "'s leave for " + date_display + "?",
                                icon : "warning",
                                showConfirmButton: true,
                                showCloseButton:true,
                                showCancelButton:true,
                                allowOutsideClick: false,
                                confirmButtonText: 'Yes, reject leave'
                            }).then(function(accept){
                                if(accept.isConfirmed == true){
                                    $.ajax({
                                        type:'DELETE',
                                        url:'/leave/delete-leave',
                                        data : { "_token": "{{ csrf_token() }}" , "id" : id , "name" : name , "status" : 'DELETED', "type" : type} ,
                                        success:function(data) {
                                            Swal.fire(
                                            data,
                                            '',
                                            'success'
                                            ).then(function(accept){
                                                location.reload();
                                            })
                                        }
                                    });
                                }
                            })
                        });
                    }
                });

                

            }
            var name = $('#filter-leave-name').val();
            setTimeout(function(){
                search();
            }, 1000);

        });

    </script>
@endpush
