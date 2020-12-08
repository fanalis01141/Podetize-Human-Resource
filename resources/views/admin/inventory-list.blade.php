@extends('layouts.app')

@section('content-dashboard')
@if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow">
                    <h4 class="card-header text-light bg-secondary">
                        Inventory
                    </h4>
                    <div class="card-body">
                        <table class="table table-hover table-striped table-bordered text-center">
                            <thead>
                                <tr>
                                <th scope="col" class="text-secondary">Employee</th>
                                <th scope="col" class="text-secondary"></i>Date Hired</th>
                                <th scope="col" class="text-secondary"></i>Position</th>
                                <th scope="col" class="text-secondary"></i>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $item)
                                    <tr>
                                        <th scope="row">{{$item->fname . ' ' . $item->lname}}</th>
                                        <th>{{date("F d, Y", strtotime($item->date_hired))}}</th>
                                        <th>{{$item->position}}</th>

                                        <th>
                                        <button class="btn btn-primary viewItem" data-myID='{{$item->id}}' data-name='{{$item->fname . ' ' .$item->lname}}'>
                                            <i class="fas fa-eye"></i>&nbsp;View Items
                                        </button>
                                        <button class="btn btn-success addItem" data-myID='{{$item->id}}' data-name='{{$item->fname . ' ' .$item->lname}}'>
                                            <i class="fas fa-plus-circle"></i>&nbsp;Add Item
                                        </button>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newitem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                @csrf
                <div class="modal-header bg-primary ">
                    <h5 class="modal-title text-light" id="exampleModalLabel">New Accountability</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('item.store')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label for="item_name">Employee:</label>
                        <input disabled type="text" class="form-control mb-4" name="emp_name" id="emp_name" placeholder="Item Name" required>

                        <label for="item_name">Item:</label>
                        <input type="text" class="form-control mb-4" name="item_name" id="item_name" placeholder="Item Name" required>

                        <label for="item_name">Quantity: </label>
                        <input type="text" class="form-control mb-2" name="quantity" id="quantity" placeholder="Quantity" required>

                        <label for="item_name">Item Description: </label>
                        <textarea type="text" rows="7" class="form-control mb-2" name="desc" id="desc" placeholder="Item description" required></textarea>

                        <input type="text" name="emp_id" id="emp_id" value="" hidden>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="open-list" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Accountability</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover table-striped table-bordered text-center">
                        <thead>
                            <tr>
                            <th scope="col" class="text-secondary">Item Issued</th>
                            <th scope="col" class="text-secondary" style="width:300px;">Description</th>
                            <th scope="col" class="text-secondary">Date Issued</th>
                            <th scope="col" class="text-secondary">Action</th>
                            </tr>
                        </thead>
                        <tbody id='tbody'>

                        </tbody>
                    </table>
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


        $('.addItem').click(function(e){
            var id = $(this).attr('data-myID');
            var name = $(this).attr('data-name');

            $('#emp_name').val(name);
            $('#emp_id').val(id);
            $('#newitem').modal('show');
        });

        $(".viewItem").click(function(){
            var id = $(this).attr('data-myID');
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: "/get-inventory-list",
                type:'GET',
                data: {_token:_token, id:id},
                datatype:'json',
                cache: false,
                success: function(data) {
                    $('#tbody').empty();
                    $('#open-list').modal('show');
                    $.each(data, function(index, value) {
                        addRow(value.item_name, value.description, value.date_issued, value.emp_id, value.id);
                    });
                }
            });
        });

        function addRow(name, desc, date, empid, id){
            $('#tbody').append(
                '<tr>'+
                    '<th>'+name+'</th>'+
                    '<th>'+desc+'</th>'+
                    '<th>'+date+"</th>"+
                    "<th><button class='remove btn btn-danger' data-myID="+empid+" data-itemID="+id+"><i class='fas fa-times'></i></button></th>"+
                "</tr>"
            );

            $('.remove').click(function(){
                var id = $(this).attr('data-myID');
                var item_id = $(this).attr('data-itemID');
                Swal.fire({
                    title:'Are you sure you want to remove this item?',
                    text:'This action cannot be undone.',
                    icon:'error',
                    showConfirmButton: true,
                    showCloseButton:true,
                    showCancelButton:true,
                    allowOutsideClick: false,
                    confirmButtonText: 'Yes, remove item',
                    confirmButtonColor: '#E3342F',
                }).then(function(response){
                    if(response.isConfirmed){
                        $.ajax({
                            type:'DELETE',
                            url:'/item/'+id,
                            data : { "_token": "{{ csrf_token() }}" , "emp_id" : id, "item_id" : item_id} ,
                            success:function(data) {
                                location.reload();
                            }
                        });
                    }
                })
            });
        }

    </script>
@endpush
