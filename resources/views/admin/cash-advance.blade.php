@extends('layouts.app')

@section('content-dashboard')

    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                <i class="fa fa-check-square" aria-hidden="true"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow">
                    <h5 class="card-header bg-dark text-white">Cash Advance Records:</h5>
                    <div class="card-body">
                        <table class="table table-hover table-bordered table-striped text-center">
                        <thead>
                            <tr class="text-secondary">
                                <th scope="col">Employee</th>
                                <th scope="col">Requested Cash</th>
                                <th scope="col">Deduction/month</th>
                                <th scope="col">Date Issued</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['tables'] as $x)
                            <tr>
                                <td>{{$x->name}}</td>
                                <td>{{$x->request}}</td>
                                <td>{{$x->ded_per_pay}}</td>
                                <td>{{date("M. d, Y", strtotime($x->date_issued))}}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit_ca"
                                    data-name="{{$x->name}}" data-request="{{$x->request}}" data-ded="{{$x->ded_per_pay}}" data-id="{{$x->emp_id}}">  
                                        <i class="fas fa-pencil-alt    "></i>
                                        Edit
                                    </button>

                                    <button class="btn btn-danger btn-sm " data-toggle="modal" data-target="#del_ca"
                                    data-name="{{$x->name}}" data-id="{{$x->emp_id}}">  
                                        <i class="fas fa-trash-alt    "></i>
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>

            </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-light">
                    New Cash Advance Request
                </div>
                <form action="{{route('ded.storeCA', 'accept')}}">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="employees">Choose Employee:</label>
                            <select class="form-control" name="employees" id="employees">
                                <option value="">--SELECT EMPLOYEE--</option>
                                @foreach ($data['users'] as $user)
                                    <option value="{{$user->id}}" name="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                            <label for="reason" class="mt-3">Reason for Cash Advance</label>
                            <textarea type="text" name="reason" id="reason" class="form-control" placeholder="Reason" style="height:75px" required>{{ old('reason') }}</textarea>
                            <label for="amount" class="mt-3">Amount Requested</label>
                            <input type="number" min="1" max="99999" class="form-control" name="request" placeholder="Amount Requested" required>
                            <label for="amount" class="mt-3">Deduction per Payroll</label>
                            <input type="number" min="1" max="99999" class="form-control" name="ded_per_pay" placeholder="Deduction per Payroll" required>
                            <label for="date_issued" class="mt-3">Date Issued for Request:</label>
                            <input type="date" name="date_issued" id="date_issued" class="form-control" req>

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Save Record</button>
                    </div>
                </form>
            </div>
        </div>

        </div>
    </div>

    <div class="modal fade" id="edit_ca" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{route('ded.editCA','edit')}}" method="POST">
            @csrf

            <div class="modal-dialog" role="document">
              <div class="modal-content modal-lg">
                <div class="modal-header text-light bg-primary">
                  <h5 class="modal-title" id="exampleModalLabel">PROMOTION</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
    
                <div class="modal-body text-center">
                    @csrf
                    {{-- @method('PATCH') --}}
                    <h5>
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i>
                            <p class="font-weight-bold mt-2">Please double check details before saving!</p>
                        </div>
                    </h5>
                    <hr>
                    <input type="text" name="myid" id="myid" hidden>
                    <a href="" class="badge badge-light" name="myname" id="myname"></a>
                    <div class="row text-center">    
                        <div class="col-md-6">
                            <label for="new_rate">Requested Cash:</label>
                            <input type="number" name="request" id="request" min="1" max="999999" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="new_rate">Deduction per Month:</label>
                            <input type="number" name="deduction" id="deduction" min="1" max="999999" class="form-control" required>
                        </div>
                    </div>
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="del_ca" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{route('ded.delCA','delete')}}" method="POST">
            @csrf

            <div class="modal-dialog" role="document">
                <div class="modal-content modal-lg">
                <div class="modal-header text-light bg-danger">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Record?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body text-center">
                    @csrf
                    {{-- @method('PATCH') --}}
                    <h5>
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation" aria-hidden="true"></i>
                                
                                <p class="font-weight-bold mt-2">Are you sure you want to remove this cash advance record of
                                    <p id="del_myname" class="font-weight-bold" name="name"></p>
                                </p>
                        </div>
                    </h5>
                    <hr>
                    <input type="text" name="myid" id="myid" hidden>
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    

@endsection
