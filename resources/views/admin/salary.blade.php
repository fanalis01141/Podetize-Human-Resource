@extends('layouts.app')

@section('content-dashboard')

<style>
    .deduct{
        border: 1px solid red;
    }

    .inc{
        border: 1px solid green;
    }
</style>

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
            <div class="card">
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
    </script>
@endpush
