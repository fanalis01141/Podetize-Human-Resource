<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Department::create([
            'department_name' => $request->department_name,
            'position' => $request->position,
        ]);
        return back()->with('success', 'Added: ' . $request->department_name);
    }

    public function newPosition($department_name, Request $request){
        Department::create([
            'department_name' => $request->department_name,
            'position' => $request->new_position,
        ]);
        return back()->with('success', 'Added Position: ' . $request->new_position . ' to '. $request->department_name);
    }

    public function setPosition(Request $request){
        $id = $request->id;
        $newPos = $request->new_position;
        Department::where('id',$id)->update(['position' => $newPos]);
        return back()->with('success', 'Position Updated to: '. $newPos);
    }

    public function delPosition(Request $request){
        $id = $request->id;
        Department::where('id',$id)->delete();
        return back()->with('success', 'Position successfully deleted');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        //
    }
}
