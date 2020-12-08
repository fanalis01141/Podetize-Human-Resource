@extends('layouts.app')

@section('content-dashboard')

<div class="container">
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-primary text-light d-flex justify-content-between">
                <h3>Announcements</h3>
                <button class="btn btn-secondary btn-sm" data-toggle="modal" data-target='#new_ann'>Add New Announcement</button>
            </div>

            <div id="accordion">
                @foreach($ann as $a)
                    <div class="card">
                        <div class="card-header " id="heading-{{$a->id}}">
                            <h5 class="mb-0 d-flex ">
                                <button class="btn btn-link" onclick="event.preventDefault()"
                                        data-toggle="collapse" data-target="#collapse-{{$a->id}}"
                                        aria-controls="collapse-{{$a->id}}">
                                    {{$a->title}}
                                </button>
                            </h5>
                        </div>

                        <div id="collapse-{{$a->id}}" class="collapse" aria-labelledby="heading-{{$a->id}}" data-parent="#accordion">
                            <div class="card-body">
                                {{$a->content}}
                            </div>
                            <hr>
                            <div class="text-right m-3">
                                <button class="btn btn-danger btn-del" data-myID="{{$a->id}}" data-title="{{$a->title}}" data-content="{{$a->content}}">Delete</button>
                                <button class="btn btn-success btn-edit" data-myID="{{$a->id}}" data-title="{{$a->title}}" data-content="{{$a->content}}">Edit this Announcement</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="text-center">
            {{$ann->links()}}
        </div>
    </div>
</div>


<div class="modal fade" id="new_ann" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header bg-primary text-light">
        <h5 class="modal-title" id="exampleModalLabel">New Announcement</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{route('announcements.store')}}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <a href="#" class="btn btn-outline-primary btn_all" id="btn_all">For All</a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-outline-primary btn_emp_only" id="btn_emp_only">For Employee only</a>

                        <div class="emp_only" hidden>
                            <select name="emp_id" id="emp_id" class="form-control ">
                                <option value="" selected id='null_emp'>SELECT EMPLOYEE</option>
                                @foreach ($emps as $e)
                                    <option value="{{$e->id}}">{{$e->fname . ' ' . $e->lname}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="col-md-3">
                        <a href="#" class="btn btn-outline-primary btn_dep_only" id="btn_dep_only">Department only</a>

                        <div class="dep_only" hidden>
                            <select name="department" id="department" class="form-control" >
                                <option value="" selected id='null_dep'>SELECT DEPARTMENT</option>
                                @foreach ($department as $d)
                                    <option value="{{$d->department_name}}">{{$d->department_name}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="col-md-3">
                        <a href="#" class="btn btn-outline-primary btn_pos_only" id="btn_pos_only">Position only</a>

                        <div class="pos_only" hidden>
                            <select name="position" id="position" class="form-control">
                                <option value=""  id='null_pos' selected="selected">SELECT POSITION</option>
                                @foreach ($position as $p)
                                    <option value="{{$p->position}}">{{$p->position}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
                <hr>
                <label for="" class="mt-2"><h5>Announcement title</h5></label>
                <input type="text" name="title" id="" class="form-control mb-4" placeholder="Announcement title" required>
                <label for=""><h5>Announcement content</h5></label>
                <textarea name="content" id="" cols="30" rows="10" class="form-control" placeholder="Content..." required></textarea>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Announcement</button>
            </div>
        </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-ann" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header bg-primary text-light">
        <h5 class="modal-title" id="exampleModalLabel">Edit Announcement</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{route('announcements.update','123')}}" method="POST">
            @method('PATCH')
            @csrf
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <a href="#" class="btn btn-outline-primary btn_all" id="btn_all">For All</a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-outline-primary btn_emp_only" id="btn_emp_only">For Employee only</a>
                        <div class="emp_only" hidden>
                            <select name="emp_id" id="emp_id" class="form-control">
                                <option value="" selected id='null_emp'>SELECT EMPLOYEE</option>
                                @foreach ($emps as $e)
                                    <option value="{{$e->id}}">{{$e->fname . ' ' . $e->lname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-outline-primary btn_dep_only" id="btn_dep_only">Department only</a>

                        <div class="dep_only" hidden>
                            <select name="department" id="department" class="form-control" >
                                <option value="" selected id='null_dep'>SELECT DEPARTMENT</option>
                                @foreach ($department as $d)
                                    <option value="{{$d->department_name}}">{{$d->department_name}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="col-md-3">
                        <a href="#" class="btn btn-outline-primary btn_pos_only" id="btn_pos_only">Position only</a>

                        <div class="pos_only" hidden>
                            <select name="position" id="position" class="form-control">
                                <option value=""  id='null_pos' selected="selected">SELECT POSITION</option>
                                @foreach ($position as $p)
                                    <option value="{{$p->position}}">{{$p->position}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
                <hr>
                <label for="" class="mt-2"><h5>Announcement title</h5></label>
                <input type="text" name="edit_title" id="edit_title" class="form-control mb-4" placeholder="Announcement title" required>
                <label for=""><h5>Announcement content</h5></label>
                <textarea name="content" id="edit_content" cols="30" rows="10" class="form-control" placeholder="Content..." required></textarea>
                <input type="text" id='edit_id' name="edit_id">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Edit Announcement</button>
            </div>
        </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>
    $('.btn_emp_only').click(function(e){
        e.preventDefault();
        $('.pos_only').prop('hidden', true);
        $('.dep_only').prop('hidden', true);
        $('.emp_only').prop('hidden', false);

        $('#position').val('');
        $('#department').val('');

    });

    $('.btn_dep_only').click(function(e){
        e.preventDefault();
        $('.pos_only').prop('hidden', true);
        $('.dep_only').prop('hidden', false);
        $('.emp_only').prop('hidden', true);

        $('#emp_id').val('');
        $('#position').val(null);
    });

    $('.btn_pos_only').click(function(e){
        e.preventDefault();
        $('.pos_only').prop('hidden', false);
        $('.dep_only').prop('hidden', true);
        $('.emp_only').prop('hidden', true);

        $('#emp_id').val('');
        $('#department').val('');
    });

    $('.btn_all').click(function(e){
        e.preventDefault();
        $('.pos_only').prop('hidden', true);
        $('.dep_only').prop('hidden', true);
        $('.emp_only').prop('hidden', true);

        $('#employee').val('');
        $('#department').val('');
        $('#position').val('');
    });

    $('.btn-edit').click(function(){
        var id = $(this).attr('data-myID');
        var title = $(this).attr('data-title');
        var content = $(this).attr('data-content');



        console.log('ann id: ' + id);
        $('#edit_id').val(id);
        $('#edit_content').val(content);
        $('#edit_title').val(title);

        $('#edit-ann').modal();
    });

    $('.btn-del').click(function(){
        var id = $(this).attr('data-myID');
        var title = $(this).attr('data-title');

        Swal.fire({
            title : 'Delete announcement?',
            text : "Are you sure you want to remove " + title + "?",
            icon : "error",
            showConfirmButton: true,
            showCloseButton:true,
            showCancelButton:true,
            allowOutsideClick: false,
            confirmButtonText: 'Yes, delete announcement',
            confirmButtonColor: '#E3342F',
        }).then(function(accept){
            if(accept.isConfirmed == true){
                $.ajax({
                    type:'DELETE',
                    url:'/announcements/'+id,
                    data : { "_token": "{{ csrf_token() }}" , "id" : id,} ,
                    success:function(data) {
                        Swal.fire({
                            title : data,
                            showConfirmButton: true,
                        }).then(function(ok){
                            if(ok.isConfirmed == true){
                                location.reload();
                            }
                        })
                    }
                });
            }
        })
    });
</script>

@endpush
