@extends('layouts.app')

@section('content-dashboard')

    <div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
            @endif
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow">
                <h5 class="card-header bg-dark text-light">Deductions</h5>
                <div class="card-body">
                    <table class="table table-hover table-bordered table-striped">
                    <thead>
                        <tr>
                        <th scope="col">Employee</th>
                        <th scope="col">PHIC</th>
                        <th scope="col">SSS</th>
                        <th scope="col">PAG-IBIG</th>
                        <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['tables'] as $item)
                            <tr class="trdeduction">
                            <td class="td-deduct-name">{{$item->name}}<input type="hidden" class="deduct-id" name="" value="{{$item->id}}"></td>
                            <td class="td-deduct-phic">{{$item->phic}}</td>
                            <td class="td-deduct-pagibig">{{$item->sss}}</td>
                            <td class="td-deduct-sss">{{$item->pagibig}}</td>
                            <td><button class="btn btn-sm btn-primary btn-edit-duduction" id="btn-edit-duduction" data-target="#edit-deduction-modal" data-toggle="modal"><i class="fas fa-pencil-alt"></i> edit</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


{{-- edit modal --}}
    <div class="modal fade" id="edit-deduction-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{route("editDeduction")}}" method="POST">
                @csrf
                <div class="modal-dialog" role="document">
                    <div class="modal-content modal-lg">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-light " id="exampleModalLabel">Edit Deductions</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" class="ded-id">
                            <div class="text-center">
                                <h5>
                                <small class="text-muted">Edit deductions for:</small>
                                <span class="ded-name"></span>
                                </h5>
                            </div>
                        <hr>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label text-right">PHIC</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control edit-phic" name="phic" id="edit-phic" placeholder="Enter amount for PHIC">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label text-right">SSS</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control edit-sss" name="sss" id="edit-sss" placeholder="Enter amount for SSS">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label text-right">PAG-IBIG</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control edit-pagibig" name="pagibig" id="edit-pagibig" placeholder="Enter amount for PAG-IBIG">
                            </div>
                        </div>
                    </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">SAVE</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        {{-- end edit modal  --}}

</div>

@endsection
