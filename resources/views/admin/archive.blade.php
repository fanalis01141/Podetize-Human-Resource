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
                    <div class="col-lg-8">
                        ARCHIVED EMPLOYEES
                    </div>
                    <div class="col-lg-4 text-right">
                        {{-- <button class="btn btn-primary btn-sm shadow" data-toggle="modal" data-target="#addnewP"><i class="fa fa-plus"></i> New Employee</button> --}}

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
                                <th scope="col">Position</th>
                                <th scope="col">Date hired</th>
                                <th scope="col">Deactivation date</th>

                                <th scope="col">Reason for Deactivation</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td scope="col">{{$user->fname. ' ' . $user->lname}}</td>
                                <td scope="col">{{$user->position}}</td>
                                <td scope="col">{{{date("F d, Y", strtotime($user->date_hired))}}}</td>
                                <td scope="col">{{date("F d, Y", strtotime($user->created_at))}}</td>
                                <td scope="col">{{$user->reason}}</td>
                                <td class="">

                                    <div class="text-center">
                                        <a href="{{route('employee.show', $user->emp_id)}}" class="btn btn-success btn-sm">
                                            <i class="far fa-eye"></i>&nbsp;View Details</a>

                                        {{-- <button data-myID="{{$user->id}}"
                                            class="btn btn-primary btn-sm timeoff">
                                            <i class="far fa-clock"></i>&nbsp;Schedule</button>

                                        <a href="{{route('salary.show', $user->id)}}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-dollar-sign"></i>&nbsp;Salary Record</a> --}}
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
