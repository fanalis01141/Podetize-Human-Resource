<?php

namespace App\Http\Controllers;

use App\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
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
        return ($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request;
        if ($request->has('Monday')){
            Schedule::where('emp_id', $request->emp_id)->update([
                'Monday' => 'ON'
            ]);
        }else{
            Schedule::where('emp_id', $request->emp_id)->update([
                'Monday' => 'OFF'
            ]);
        }

        if ($request->has('Tuesday')){
            Schedule::where('emp_id', $request->emp_id)->update([
                'Tuesday' => 'ON'
            ]);
        }else{
            Schedule::where('emp_id', $request->emp_id)->update([
                'Tuesday' => 'OFF'
            ]);
        }

        if ($request->has('Wednesday')){
            Schedule::where('emp_id', $request->emp_id)->update([
                'Wednesday' => 'ON'
            ]);
        }else{
            Schedule::where('emp_id', $request->emp_id)->update([
                'Wednesday' => 'OFF'
            ]);
        }

        if ($request->has('Thursday')){
            Schedule::where('emp_id', $request->emp_id)->update([
                'Thursday' => 'ON'
            ]);
        }else{
            Schedule::where('emp_id', $request->emp_id)->update([
                'Thursday' => 'OFF'
            ]);
        }

        if ($request->has('Friday')){
            Schedule::where('emp_id', $request->emp_id)->update([
                'Friday' => 'ON'
            ]);
        }else{
            Schedule::where('emp_id', $request->emp_id)->update([
                'Friday' => 'OFF'
            ]);
        }

        if ($request->has('Saturday')){
            Schedule::where('emp_id', $request->emp_id)->update([
                'Saturday' => 'ON'
            ]);
        }else{
            Schedule::where('emp_id', $request->emp_id)->update([
                'Saturday' => 'OFF'
            ]);
        }

        if ($request->has('Sunday')){
            Schedule::where('emp_id', $request->emp_id)->update([
                'Sunday' => 'ON'
            ]);
        }else{
            Schedule::where('emp_id', $request->emp_id)->update([
                'Sunday' => 'OFF'
            ]);
        }

        return redirect()->back()->with('success', 'Time off has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        //
    }

    public function checkOff(Request $request){
        return Schedule::where('emp_id', $request->id)->first();
    }
}
