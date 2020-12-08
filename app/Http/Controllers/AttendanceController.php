<?php

namespace App\Http\Controllers;

use App\Attendance;
use Carbon\Carbon;
use DateTime;
use App\User;
use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Leave;
use App\Http\Controllers\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $position = Department::distinct()->get(['position']);
        $roster_today = DB::table('users')
                        ->join('schedules', 'users.id', '=', 'schedules.emp_id')
                        ->select('users.*', 'schedules.*')
                        ->where(date('l'), 'ON')
                        ->where('users.active','1')
                        ->get();

        $leave_today = DB::table('users')
                        ->join('leaves', 'users.id', '=', 'leaves.emp_id')
                        ->select('users.*', 'leaves.*')
                        ->where('dates', date('Y-m-d'))
                        ->where('leaves.status','=', 'APPROVED')
                        ->where('users.active','1')
                        ->get();


        $upcoming_ws = [];
        $upcoming_ws = $roster_today->map(function($r){
            $leave_today = DB::table('users')
                    ->join('leaves', 'users.id', '=', 'leaves.emp_id')
                    ->select('users.*', 'leaves.*')
                    ->where('dates', date('Y-m-d'))
                    ->where('leaves.status','=', 'APPROVED')
                    ->where('users.active','1')
                    ->get();

            if( ! $leave_today->contains('emp_id', $r->emp_id)){
                return [
                    'id' => $r->emp_id,
                    'name' => $r->fname . ' ' . $r->lname,
                    'position' => $r->position,
                ];
            }
        });

        $attend_today = $upcoming_ws->filter(function ($value) {
             return !is_null($value);
        });


        return view('admin.attendance', compact('position','attend_today','leave_today'));
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
        //hinde te man proceed
        $date = Carbon::now('GMT+8')->format('Y-m-d');
        $time = Carbon::now('GMT+8')->format('H:i');

        $checker = Attendance::where('attend_date', $date)->where('emp_id',$request->emp_id)->get();
        if($checker->isEmpty()){
            Attendance::create([
                'emp_id' => $request->emp_id,
                'time_in' => $time,
                'attend_date' => $date
            ]);
        }else{
            //check if last time in is greater than 3 minutes to avoid immediate timeout
            $check_timein = Attendance::select('time_in')->where('emp_id', $request->emp_id)->where('attend_date', $date)->first();
            $time2 = strtotime($check_timein->time_in);
            $time1 = strtotime($time);
            $interval  = abs($time2 - $time1);
            $difference   = round($interval / 60);

            if($difference > 3){
                $checkertimeout = Attendance::select('time_out')
                                ->where('emp_id', $request->emp_id)
                                ->where('attend_date', $date)
                                ->first();
                if(is_null($checkertimeout->time_out))
                {
                    Attendance::where('attend_date',$date)->where('emp_id', $request->emp_id)->update([
                        'time_out' => $time
                    ]);
                }
            }

            //get time in and out of user
            $timein = Attendance::select('time_in')->where('emp_id', $request->emp_id)->where('attend_date', $date)->first();
            $timeout = Attendance::select('time_out')->where('emp_id', $request->emp_id)->where('attend_date', $date)->first();
            $in = strtotime(($timein->time_in));
            $out = strtotime(($timeout->time_out));
            $interval  = abs($out - $in);
            $minutes   = round($interval / 60);

            //get per minute rate
            $user = User::where('id', $request->emp_id)->first();
            $per_minute = $user->rate / 8 / 60;
            $pay = $minutes * $per_minute;

            //overtime
            if ((int)$minutes >= 540) {
                $overtime = (int)$minutes - 540;
                $ot_pay = $overtime * $per_minute;

                //get OT if greather than 1 hour
                if(60 > $overtime){
                    Attendance::where('attend_date',$date)->where('emp_id', $request->emp_id)->update([
                        'overtime' => $overtime,
                        'undertime' => '0',
                        'pay_for_day' => $user->rate,
                        'otpay_for_day' => '0',
                        'ded_for_day' => '0'
                    ]);
                }else{
                    Attendance::where('attend_date',$date)->where('emp_id', $request->emp_id)->update([
                        'overtime' => $overtime,
                        'undertime' => '0',
                        'pay_for_day' => $pay,
                        'otpay_for_day' => $ot_pay,
                        'ded_for_day' => '0'
                    ]);
                }
            } else {
                //undertime
                $undertime = 540 - (int)$minutes;
                $deduction = $per_minute * $undertime;

                Attendance::where('attend_date',$date)->where('emp_id', $request->emp_id)->update([
                    'undertime' => $undertime,
                    'overtime' => '0',
                    'pay_for_day' => $pay,
                    'ded_for_day' => $deduction
                ]);
            }
        }


        return redirect()->back();
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }

    public function seekdate(Request $request){
        $users = User::all()->where('active', 1);


        $attendance = DB::table('users')->select([
            'users.name', 'users.id', 'attendances.time_in', 'attendances.time_out', 'attend_date', 'attendances.id as att_id'
            ])->join('attendances','attendances.emp_id','=','users.id')->where('attend_date', $request->date)
            ->where('users.name', $request->name)
            ->orderBy('attendances.id', 'DESC')->get();
        return view('admin.attendance', compact('attendance', 'users') );


        return redirect()->back()->with('attendance','users');
    }

    public function getAllOff(Request $request){
        $time = strtotime($request->date);
        $newDate = date('Y-m-d',$time);

        if($request->position == 'ALL'){
            $leave_today = DB::table('users')
                            ->join('leaves', 'users.id', '=', 'leaves.emp_id')
                            ->select('users.*', 'leaves.*')
                            ->where('dates', $newDate)
                            ->where('users.active','1')
                            ->get();
        }else{
            $leave_today = DB::table('users')
                            ->join('leaves', 'users.id', '=', 'leaves.emp_id')
                            ->select('users.*', 'leaves.*')
                            ->where('dates', $newDate)
                            ->where('users.position', $request->position)
                            ->where('users.active','1')
                            ->get();
        }
        return $leave_today;
    }

    public function getAllAttendance(Request $request){
        $time = strtotime($request->date);
        $newDate = date('Y-m-d', $time);
        $string_date = date('l', $time);
        $display_string = date('l d F Y', $time);

        if($request->position == 'ALL'){
            $roster_today = DB::table('users')
                            ->join('schedules', 'users.id', '=', 'schedules.emp_id')
                            ->select('users.*', 'schedules.*')
                            ->where($string_date, 'ON')
                            ->where('users.active','1')
                            ->get();
        }else{
            $roster_today = DB::table('users')
                            ->join('schedules', 'users.id', '=', 'schedules.emp_id')
                            ->select('users.*', 'schedules.*')
                            ->where($string_date, 'ON')
                            ->where('users.position', $request->position)
                            ->where('users.active','1')
                            ->get();
        }



        $leave_today = DB::table('users')
                        ->join('leaves', 'users.id', '=', 'leaves.emp_id')
                        ->select('users.*', 'leaves.*')
                        ->where('dates', $newDate)
                        ->get();

        $upcoming_ws = [];
        $upcoming_ws = $roster_today->map(function($r) use ($newDate, $display_string){
            $leave_today = DB::table('users')
                    ->join('leaves', 'users.id', '=', 'leaves.emp_id')
                    ->select('users.*', 'leaves.*')
                    ->where('users.active','1')
                    ->where('dates', $newDate)
                    ->get();

            if( ! $leave_today->contains('emp_id', $r->emp_id)){
                return [
                    'id' => $r->emp_id,
                    'name' => $r->fname . ' ' . $r->lname,
                    'position' => $r->position,
                ];
            }
        });

        $attend_today = $upcoming_ws->filter(function ($value) {
             return !is_null($value);
        });

        return $attend_today;
    }

    public function parseDateForDisplay(Request $request){
        $time = strtotime($request->date);
        $display_string = date('l d F Y', $time);
        return $display_string;
    }

    public function markAbsentByDate(Request $request){

        $user = User::where('id', $request->emp_id)->first();
        $diffYears = \Carbon\Carbon::now()->diffInYears($user->date_hired);

        if($diffYears == 0){

            Leave::create([
                'emp_id' => $request->emp_id,
                'emp_name' => $user->fname . ' ' . $user->lname,
                'type' => 'Marked as absent by ' . \Auth::user()->fname . ' ' . \Auth::user()->lname,
                'total_days' => 1,
                'dates' => $request->date,
                'status' => 'APPROVED',
                'sick_leaves_left' => 0 ,
                'vacation_leaves_left' => 0 ,
            ]);

        }else{

            if ( ! Leave::where('emp_id', $request->emp_id)->exists() ){

                Leave::create([
                    'emp_id' => $request->emp_id,
                    'emp_name' => $user->fname . ' ' . $user->lname,
                    'type' => 'Marked as absent by ' . \Auth::user()->fname . ' ' . \Auth::user()->lname,
                    'total_days' => 1,
                    'dates' => $request->date,
                    'status' => 'APPROVED',
                    'sick_leaves_left' => $diffYears + 2,
                    'vacation_leaves_left' => $diffYears + 2,
                ]);

            }else{

                $user = Leave::where('emp_id', $request->emp_id)->orderBy('id', 'desc')->first();
                // return $user;

                $remaining_sick = $user->sick_leaves_left;
                $remaining_vac = $user->vacation_leaves_left;

                Leave::create([
                    'emp_id' => $request->emp_id,
                    'emp_name' => $user->emp_name,
                    'type' => 'Marked as absent by ' . \Auth::user()->fname . ' ' . \Auth::user()->lname,
                    'total_days' => 1,
                    'dates' => $request->date,
                    'status' => 'APPROVED',
                    'sick_leaves_left' => $remaining_sick,
                    'vacation_leaves_left' => $remaining_vac,
                ]);
            }
        }

        return 'Employee has been marked absent.';

    }
}
