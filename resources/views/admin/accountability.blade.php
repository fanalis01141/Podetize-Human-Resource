@extends('layouts.app')

@section('content-dashboard')

    <div class="container-fluid">

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-8">

                        <h4><a href="{{route('dashboard')}}" class="btn btn-secondary mr-2"><i class="fas fa-arrow-left"></i> Back</a>ACCOUNTABILITY OF: {{strtoupper($data['user']->name)}}</h4>
                    </div>
                    <div class="col-lg-4 text-right">
                        <button class="btn btn-success" data-toggle="modal" data-target="#newitem">Add Item</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (!$data['items']->isEmpty())
                    <ul class="list-group col-md-6">
                        @foreach ($data['items'] as $item)

                            <li class="list-group-item">
                                <h3>
                                    {{$item->item_name}} x {{$item->quantity}}
                                    <button class="btn btn-danger float-right ml-3"
                                        data-myitemded="{{$item->item_name}}" data-myitemidded="{{$item->id}}" data-myuserid="{{$data['user']->id}}"
                                        data-myquantityded={{$item->quantity}}
                                        data-target="#minus" data-toggle="modal">
                                        <i class="fas fa-minus-circle"></i>
                                    </button>

                                    <button class="btn btn-success float-right"
                                        data-myitemadd="{{$item->item_name}}" data-myitemidadd="{{$item->id}}" data-myuserid="{{$data['user']->id}}"
                                        data-target="#add" data-toggle="modal">
                                        <i class="fas fa-plus-circle"></i>
                                    </button>
                                </h3>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="alert alert-info">{{$data['user']->name}} doesn't have any accountability</p>
                @endif


            </div>
        </div>

    </div>


    {{-- New Item Modal --}}
    <div class="modal fade" id="newitem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Accountability for {{$data['user']->name}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('item.store')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label for="item_name">Item:</label>
                        <input type="text" class="form-control" name="item_name" id="item_name" placeholder="Item Name" required>

                        <label for="item_name">Quantity: </label>
                        <input type="text" class="form-control" name="quantity" id="quantity" placeholder="Quantity" required>

                        <input type="text" name="emp_id" id="emp_id" value="{{$data['user']->id}}" hidden>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Subtract quantity --}}
    <div class="modal fade" id="minus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Deduct Quantity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('item.deduct', $data['user']->id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label for      ="item_name">Item:</label>
                        <input type="text" class="form-control" name="ded_item" id="ded_item" disabled readonly>

                        <label for="item_name">Quantity to Deduct: </label>
                        <input type="text" class="form-control" name="ded_quantity" id="ded_quantity" placeholder="Quantity" required>

                        <input type="text" name="ded_item_id" id="ded_item_id" hidden>
                        <input type="text" name="ded_user_id" id="ded_user_id" hidden value="{{$data['user']->id}}">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Quantity</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{route('item.add', $data['user']->id)}}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <label for="item_name">Item:</label>
                            <input type="text" class="form-control" name="add_item" id="add_item" disabled readonly>

                            <label for="item_name">Quantity to Add: </label>
                            <input type="text" class="form-control" name="add_quantity" id="add_quantity" placeholder="Quantity" required>

                            <input type="text" name="add_item_id" id="add_item_id" hidden>
                            <input type="text" name="add_user_id" id="add_user_id" hidden value="{{$data['user']->id}}">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Send message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

@endsection
