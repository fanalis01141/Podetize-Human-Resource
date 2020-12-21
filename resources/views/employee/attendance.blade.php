@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        <div class="col-md-6">
            <h3 id='display_date_only'>
                Displaying roster for {{  date('l d F Y') }}
            </h3>
            <div class="card mb-3 shadow">
                <h4 class="card-header text-light bg-secondary">ATTENDANCE</h4>
                <div class="card-body">
                        <div class="col-lg-9 mb-2">
                            <div class="input-group">
                                <h4 class="mr-3"><small class="text-muted">SELECT POSITION</small></h4>
                                <select class="form-control ml-3" name="position" id="position" required>
                                    <option value="ALL" selected>--ALL--</option>
                                    @foreach ($position as $p)
                                        <option value="{{$p->position}}">{{$p->position}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                        <div class="input-group">
                                <h4 class="mr-3"><small class="text-muted">SELECT DATE</small></h4>
                                <input type="date" name="date" id="date" class="form-control mb-3 ml-5" value="{{date('Y-m-d')}}">
                                <span class="input-group-btn">
                                <button class="btn btn-primary px-3 mx-3 filter get-ot" id="filter" onclick="charot()">Filter</button>
                                </span>
                            </div>
                        </div>
                        <table class="table table-hover table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-primary">Employee</th>
                                    <th scope="col" class="text-primary">Position</th>
                                    {{-- <th scope="col" class="text-primary">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody id='attendance_body'>
                                @foreach ($attend_today as $r)
                                    <tr>
                                        <td>{{$r['name']}}</td>
                                        <td>{{($r['position'])}}</td>
                                        {{-- <td><button class="btn btn-danger btn-sm absent" data-myID={{$r['id']}} data-name={{$r['name']}}>Mark as absent</button></td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                </div>
            </div>

        </div>

        <div class="col-md-6">

                <div class="card mb-3 shadow" style="margin-top:38px;">
                    <h4 class="card-header text-light bg-secondary">ON TIME OFF</h4>
                    <div class="card-body">
                        <table class="table table-hover table-bordered table-striped">
                            <thead>
                                <tr>
                                <th scope="col" class="text-primary">Employee</th>
                                <th scope="col" class="text-primary">Position</th>
                                <th scope="col" class="text-primary">Reason of absence</th>

                                </tr>
                            </thead>
                            <tbody id='leave_body'>
                                @foreach ($leave_today as $r)
                                    <tr>
                                        <td>{{$r->fname . ' ' . $r->lname}}</td>
                                        <td>{{($r->position)}}</td>
                                        <td>{{($r->type)}}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card mb-3 shadow" style="margin-top:38px;">
                    <h4 class="card-header text-light bg-secondary">OVERTIME</h4>
                    <div class="card-body">
                        <table class="table table-hover table-bordered table-striped">
                            <thead>
                                <tr>
                                <th scope="col" class="text-primary">Employee</th>
                                <th scope="col" class="text-primary">reason</th>
                                {{-- <th scope="col" class="text-primary">Reason of absence</th> --}}

                                </tr>
                            </thead>
                            <tbody id="ot-body">
                                @foreach ($ots as $r)
                                    <tr>
                                        <td>{{$r->fname . ' ' . $r->lname}}</td>
                                        <td>{{($r->task_or_eps)}}</td>
                                        {{-- <td>{{($r->type)}}</td> --}}

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
    </div>
</div>




@endsection

@push('scripts')

    <script>

    //this fetches roster today only
    $('.filter').click(function(){
        var date = $('#date').val();
        var position = $('#position').val()
        $.ajax({
            type:'POST',
            url:'/get-all-attendance',
            data : { "_token": "{{ csrf_token() }}" , "date" : date , "position" : position} ,
            success:function(data) {
                addARow(data);
            }
        });
    });

    //this fetches roster today only
    $('.get-ot').click(function(){
        var date = $('#date').val();
        $.ajax({
            type:'POST',
            url:'/get-all-ot',
            data : { "_token": "{{ csrf_token() }}" , "date" : date } ,
            success:function(data) {
                // addARow(data);
                console.log(data);
                $('#ot-body').empty();
                $.each(data, function(key, val){
                    $('#ot-body').append("<tr><td>"+val.fname + ' ' +val.lname+"</td><td>"+val.task_or_eps+"</td></tr>");
                });
            }
        });
    });


    //this fetches all off/leave only
    $('#filter').click(function(){
        var date = $('#date').val();
        var position = $('#position').val()
        $.ajax({
            type:'POST',
            url:'/get-all-off',
            data : { "_token": "{{ csrf_token() }}" , "date" : date , "position" : position} ,
            success:function(data) {
                $('#leave_body').empty();
                $.each(data, function(key, val){
                    $('#leave_body').append("<tr><td>"+val.emp_name+"</td><td>"+val.position+"</td><td>"+val.type+"</td></tr>")
                })
            }
        });
    });

    function charot(){
        var date = $('#date').val();
        $.ajax({
            type:'POST',
            url:'/parse-date-for-display',
            data : { "_token": "{{ csrf_token() }}" , "date" : date} ,
            success:function(data) {
                $('#display_date_only').html('Displaying roster for ' + data);
            }
        });
    }

    function addARow(data){
        $('#attendance_body').empty();
        $.each(data, function(key, val){
            $('#attendance_body').append("<tr><td>"+val.name+"</td><td>"+val.position+"</td></tr>")
        })
    }

    </script>

@endpush
