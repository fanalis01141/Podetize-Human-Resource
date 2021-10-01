@extends('layouts.app')

@section('content-dashboard')


<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            <i class="far fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $err)
                <li>{{$err}}</li>
            @endforeach
        </div>
    @endif


    <div class="text-center">
        <h1>
            {{$user->fname. ' ' . $user->lname}}
        </h1>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card" id="modify-salary">
                <div class="card-header bg-primary text-light d-flex justify-content-between">
                    <h5>Current salary</h5>
                    <button class="btn btn-success" id='modbtn'>Modify Salary</button>
                </div>
                <table class="table table-hover table-striped table-bordered text-center">
                    <thead class="text-secondary">
                        <tr>
                            <th scope="col">Current Daily Rate</th>
                            <th scope="col">Current Biweekly Rate</th>
                            <th scope="col">Current Monthly Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="col">
                                ${{$user->daily_rate}}
                            </td>
                            <td scope="col">
                                ${{$user->bi_weekly_rate}}
                            </td>
                            <td scope="col">
                                ${{$user->monthly_rate}}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div id='add_panel' hidden>
                    <form action="{{route('salary.update', $user->id)}}" method="POST">
                        @method('PATCH')
                        @csrf
                        <div class="row text-center">
                            <div class="col-md-12">
                                <input type="text" class="form-control mt-3" name="new_daily" placeholder="Enter new daily rate">
                                <input type="text" class="form-control mt-3" name="new_bi" placeholder="Enter new biweekly rate">
                                <input type="text" class="form-control mt-3" name="new_monthly" placeholder="Enter new monthly rate">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <hr>
                                <input type="text" placeholder="Note / Comment" class="form-control" required name="note">
                            </div>
                        </div>
                        <div class="text-right m-3">
                            <button type="submit" class="btn btn-success">Save Salary Record</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card" hidden id="update-salary">
                <div class="card-header bg-primary text-light d-flex justify-content-between">
                    <h5>Update Salary</h5>
                    {{-- <button class="btn btn-success" id='modbtn'>Modify Salary</button> --}}
                </div>
                <table class="table table-hover table-striped table-bordered text-center">
                    <thead class="text-secondary">
                        <tr>
                            <th scope="col">Current Daily Rate</th>
                            <th scope="col">Current Biweekly Rate</th>
                            <th scope="col">Current Monthly Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="col">
                                ${{$user->daily_rate}}
                            </td>
                            <td scope="col">
                                ${{$user->bi_weekly_rate}}
                            </td>
                            <td scope="col">
                                ${{$user->monthly_rate}}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div id='edit_panel'>
                    <form action="{{route('salary.updateSalary', $user->id)}}" method="POST">
                        @method('POST')
                        @csrf
                        <div class="row text-center">
                            <div class="col-md-12">
                                <input type="text" class="form-control mt-3" name="update_id" id="update_id" hidden>
                                <input type="text" class="form-control mt-3" name="emp_id" id="emp_id" value="{{$user->id}}" hidden>
<hr>

                                <input type="text" class="form-control mt-3" name="update_daily" id="update_daily" placeholder="Enter updated daily rate">
                                <input type="text" class="form-control mt-3" name="update_bi" id="update_bi" placeholder="Enter updated biweekly rate">
                                <input type="text" class="form-control mt-3" name="update_monthly" id="update_monthly" placeholder="Enter updated monthly rate">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <hr>
                                <input type="text" placeholder="Note / Comment" class="form-control" required name="update_note" id="update_note">
                            </div>
                        </div>
                        <div class="text-right m-3">
                            <button type="submit" class="btn btn-success">Update Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-light"><h5>Salary History</h5></div>
                <div class="card-body">
                    <table class="table table-hover table-bordered text-center">
                        <thead class="text-secondary">
                            <tr>
                                <th scope="col">Daily Rate</th>
                                <th scope="col">Biweekly Rate</th>
                                <th scope="col">Monthly Rate</th>
                                <th scope="col">Note / Comment</th>
                                <th scope="col" class="text-center">Modified on</th>
                                <th scope="col" class="text-center">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salary as $s)
                                <tr class="font-weight-bold">

                                    <td scope="col">
                                        ${{$s->daily_rate}}
                                        <h5>
                                            <p class="badge badge-{{($s->status!='INCREASE') ? 'danger' : 'success'}}">
                                                ({{$s->add_or_ded_daily}})
                                            </p>
                                        </h5>
                                    </td>
                                    <td scope="col">
                                        ${{$s->bi_weekly_rate}}
                                        <h5>
                                            <p class="badge badge-{{($s->status!='INCREASE') ? 'danger' : 'success'}}">
                                                ({{$s->add_or_ded_biweekly}})
                                            </p>
                                        </h5>
                                    </td>
                                    <td scope="col d-flex justify-content-between">
                                        ${{$s->monthly_rate}}
                                        <h5>
                                            <p class="badge badge-{{($s->status!='INCREASE') ? 'danger' : 'success'}}">
                                                ({{$s->add_or_ded_monthly}})
                                            </p>
                                        </h5>
                                    </td>
                                    <td class="align-middle">
                                        {{$s->note}}
                                    </td>
                                    <td scope="col" class="align-middle">{{{date("F jS, Y", strtotime($s->created_at))}}}</td>
                                        {{-- {{$s->note}} --}}
                                    <td scope="col" class="align-middle">
                                        <button class="btn btn-primary btn-del-salary" data-id="{{$s->id}}" data-daily="{{$s->daily_rate}}" 
                                        data-bi_weekly="{{$s->bi_weekly_rate}}" data-monthly="{{$s->monthly_rate}}" data-note="{{$s->note}}">
                                            Edit this record</button>
                                    </td>

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
        $('#modbtn').click(function(){
            $('#add_panel').prop('hidden', false)
        });

        $('.btn-del-salary').click(function(){
            $('#update-salary').prop('hidden', false);
            $('#modify-salary').prop('hidden', true);


            var daily = $(this).attr('data-daily');
            var bi_weekly = $(this).attr('data-bi_weekly');
            var monthly = $(this).attr('data-monthly');
            var note = $(this).attr('data-note');
            var id = $(this).attr('data-id');

            $('#update_daily').val(daily);
            $('#update_bi').val(bi_weekly);
            $('#update_monthly').val(monthly);
            $('#update_note').val(note);
            $('#update_id').val(id);

        });
    </script>
@endpush
