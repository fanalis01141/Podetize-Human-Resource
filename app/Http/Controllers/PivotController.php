<?php

namespace App\Http\Controllers;

use App\Deduction;
use App\holiday;
use App\Item;
use App\User;
use App\Leave;
use App\Department;
use App\Overtime;
use App\Schedule;
use App\Salary;
use App\Icon;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\DB;
use OverflowException;
use App\Veem;
use App\Contact;
use Carbon\Carbon;
use App\Http\Controllers\Auth;
use Carbon\CarbonPeriod;
use App\Announcements;
use App\Deactivation;
use App\Document;
use App\Award;
use App\Certificate;
use PDF;

class PivotController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $showDep = Department::distinct()->get('department_name');
        $showPos = Department::all();
        $dis_dep = Department::select('department_name')->groupBy('department_name')->get();
        $dis_pos = Department::select('position')->groupBy('position')->get();

        // dd($dis_dep);
        $referrers = User::all();
        // $deductions = Deduction::where('emp_id', $id)->first();
        $contact = Contact::where('user_id', $id)->first();
        $veem  = Veem::where('user_id', $id)->first();
        $docu  = Document::where('emp_id', $id)->first();

        $rfi = Award::where('emp_id', $id)->where('type','rfi')->get();
        $commend = Award::where('emp_id', $id)->where('type','commend')->get();
        $certs = Certificate::where('emp_id', $id)->where('type','GIVEN')->get();

        // dd($commend);

        return view('admin.employee', compact('user','showDep','showPos', 'referrers', 'veem','contact','docu','dis_dep','dis_pos','rfi', 'commend','certs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        //
        return $request;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        User::where('id', $id)->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'username' => $request->username,
            'date_hired' => $request->date_hired,
            'department' => $request->new_department,
            'position' => $request->new_position,
            'emp_status' => $request->emp_status == 'FULL' ? 'Full time' : 'Part time',
            'referred_by' => $request->referred_by,
            'birth_date' => $request->birthday,
        ]);

        return redirect()->back()->with('success', 'Personal info updated.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::where('id', $id)->delete();
        Contact::where('user_id', $id)->delete();
        Item::where('emp_id', $id)->delete();
        Leave::where('emp_id', $id)->delete();
        Leave::where('emp_id', $id)->delete();
        Overtime::where('emp_id', $id)->delete();
        Schedule::where('emp_id', $id)->delete();
        Veem::where('user_id', $id)->delete();
        Salary::where('emp_id', $id)->delete();
        Document::where('emp_id', $id)->delete();


        return redirect()->route('homedashboard')->with('success', 'Employee has now been deleted from the system.');

        // return "Employee has now been deleted from the system.";
    }

    //edit holiday
    public function setHoliday(Request $request){
        $id = $request->id;
        $newName = $request->holiday_name;
        $newDate = $request->holiday_date;
        holiday::where('id',$id)->update(['holiday_name' => $newName, 'holiday_date' => $newDate]);
        return back()->with('success-holiday', 'Holiday Successfully Updated');
    }

    public function delHoliday(Request $request){
        $id = $request->id;
        holiday::where('id',$id)->delete();
        return back()->with('success-holiday', 'Holiday Successfully Deleted');
    }




    public function promote($id, Request $request){
        // return $request;
        $user = User::findOrFail($id);

        if($user->rate > $request->new_rate){
            return back()->withErrors('Please enter a higher rate for promotion');
        }


        $promotion = '';
        if($user->emp_status == 'TRAINEE'){
            $promotion = 'PROBATIONARY';
        }else{
            $promotion = 'REGULAR';
        }
        User::where('id', $user->id)->update(['emp_status' => $promotion,
            'weeks_of_training' => $request->new_training,
            'rate' => $request->new_rate
        ]);
        return redirect()->back()->with('success', 'Successfully promoted ' . $user->name . ' to : ' .$promotion);
    }

    public function terminate($id){
        $user = User::findOrFail($id);
        User::where('id',$id)->update([
            'active' => '0'
            ]);
        return redirect()->route('dashboard')->with('success','YOU HAVE TERMINATED ' . $user->name);
    }

    public function accountability($id){
        $data = array();
        $data['user'] = User::findOrFail($id);
        $data['items'] = Item::where('emp_id', $id)->where('quantity','!=','0')->get();
        // return $data['items'];
        return view ('admin.accountability', compact('data'));
    }

    public function editEmp($id, Request $request){
        return request()->all();
    }

    public function OTstatus(Request $request, $id){
        $user = User::findOrFail($id);

        Overtime::where('emp_id', $id)->update([
            'status' => strtoupper($request->status)
        ]);

        return redirect()->route('homedashboard')->with('success', 'YOU ' . strtoupper($request->status) . ' REQUESTED OVERTIME OF '  . $user->name);
    }

    public function showInventoryList(){

    }

    public function selfUpdate(Request $request){
        User::where('id', \Auth::user()->id)->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'birth_date' => $request->birthday,
            'username' => $request->username
        ]);
        return redirect()->back()->with('success', 'Your details has been updated');
    }

    public function profilePic(Request $request){
        $request->validate([
            'profilePic' => 'image|max:1999'
        ]);



        if($request->has('profilePic')){

            $filenamewithExtension = $request->file('profilePic')->getClientOriginalName();
            $filename = pathinfo($filenamewithExtension, PATHINFO_FILENAME);
            $extension = $request->file('profilePic')->getClientOriginalExtension();
            $filenametostore = $filename.'_'.time().'.'.$extension;

            $path = $request->file('profilePic')->storeAs('public/profilePic', $filenametostore);

            User::where('id', \Auth::user()->id)->update([
                'prof_pic' => $filenametostore,
            ]);
            
            return redirect()->back()->with('success', 'Your profile picture has been updated');
        }
    }

    public function checkWorkversary(Request $request){
        $date_hired = \Auth::user()->date_hired;
        $date_now = date('Y-m-d'); //-> date now

        $days_hired = Carbon::parse($date_hired)->diff($date_now)->format("%a");
        // return $days_hired;
        // if($days_hired >= 365){
        //     return "Eligible";
        // }else{
        //     return "Not eligible";
        // }
        // return ($days_hired);
        $diffYears = \Carbon\Carbon::now()->diffInYears($date_hired);
        return $diffYears;

    }

    public function fileLeave(Request $request){

        $diffYears = \Carbon\Carbon::now()->diffInYears(\Auth::user()->date_hired);
        $period = CarbonPeriod::create($request->from, $request->to);
        $total_days = Carbon::parse($request->from)->diff($request->to)->format("%a");


        foreach ($period as $date) {
            if( Leave::where('emp_id', \Auth::user()->id)->where('status', 'PENDING')->where('dates', $date->format('Y-m-d'))->exists() ){
                return redirect()->back()->with('warning', 'You already filed a pending leave for ' . date('l, F d Y'));
            }
        }

        if( $request->type == 'Paid sick leave' || $request->type == 'Paid vacation leave' ){
            $compared_days = $total_days + 1;

            // return $compared_days;
            $x = Leave::where('emp_id',\Auth::user()->id)->orderBy('id', 'desc')->first();

            /*
                If user is not existing in the leaves record,
            */
            if ($x == null){
                $remaining_sick = $diffYears + 3 - 1 ;
                $remaining_vac = $diffYears + 3 - 1 ;
            }else{
                $remaining_sick = $x->sick_leaves_left;
                $remaining_vac = $x->vacation_leaves_left;
            }

            if($request->type == 'Paid sick leave'){
                if($remaining_sick < $compared_days ){
                    return redirect()->back()->with('warning', 'You cannot file a paid leave more than your remaining paid sick leaves.');
                }
            }

            if($request->type == 'Paid vacation leave'){
                if($remaining_vac < $compared_days ){
                    return redirect()->back()->with('warning', 'You cannot file a paid leave more than your remaining paid vacation leaves.');
                }
            }
        }



        // check if greater than 1 year ang user
        if( $diffYears >= 1 ){
            $default = $diffYears + 2;
            // return $default;

            //check if request is paid leave
            if( $request->type == 'Paid sick leave' || $request->type == 'Paid vacation leave' ){

                //if user doesnt have a leave record
                if ( ! Leave::where('emp_id', \Auth::user()->id)->exists() ){

                    foreach ($period as $date) {

                        Leave::create([
                            'emp_id' => \Auth::user()->id,
                            'emp_name' => \Auth::user()->fname . ' ' . \Auth::user()->lname,
                            'type' => $request->type,
                            'total_days' => $total_days + 1,
                            'dates' => $date->format('Y-m-d'),
                            'status' => 'PENDING',
                            'sick_leaves_left' => $request->type == 'Paid sick leave' ?  $diffYears + 2 - $total_days - 1 :  $default ,
                            'vacation_leaves_left' => $request->type == 'Paid vacation leave' ?  $diffYears + 2 - $total_days - 1 :  $default,
                        ]);

                    }

                // if user IS EXISTING
                }else{


                    $user = Leave::where('emp_id', \Auth::user()->id)->orderBy('id', 'desc')->first();

                    $remaining_sick = $user->sick_leaves_left;
                    $remaining_vac = $user->vacation_leaves_left;

                    foreach ($period as $date) {

                        Leave::create([
                            'emp_id' => \Auth::user()->id,
                            'emp_name' => \Auth::user()->fname . ' ' . \Auth::user()->lname,
                            'type' => $request->type,
                            'total_days' => $total_days + 1,
                            'dates' => $date->format('Y-m-d'),
                            'status' => 'PENDING',
                            'note' => $request->note,
                            'sick_leaves_left' => $request->type == 'Paid sick leave' ?  $remaining_sick - $total_days - 1 :  $remaining_sick ,
                            'vacation_leaves_left' => $request->type == 'Paid vacation leave' ?  $remaining_vac - $total_days - 1 :  $remaining_vac,
                        ]);

                    }
                }

                
            }else{


                if ( ! Leave::where('emp_id', \Auth::user()->id)->exists() ){

                    foreach ($period as $date) {

                        Leave::create([
                            'emp_id' => \Auth::user()->id,
                            'emp_name' => \Auth::user()->fname . ' ' . \Auth::user()->lname,
                            'type' => $request->type,
                            'total_days' => $total_days + 1,
                            'dates' => $date->format('Y-m-d'),
                            'status' => 'PENDING',
                            'note' => $request->note,
                            'sick_leaves_left' => $request->type == 'Paid sick leave' ?  $diffYears + 2 - $total_days - 1 :  $default ,
                            'vacation_leaves_left' => $request->type == 'Paid vacation leave' ?  $diffYears + 2 - $total_days - 1 :  $default,
                        ]);

                    }

                }else{

                    $user = Leave::where('emp_id', \Auth::user()->id)->orderBy('id', 'desc')->first();

                    $remaining_sick = $user->sick_leaves_left;
                    $remaining_vac = $user->vacation_leaves_left;

                    foreach ($period as $date) {

                        Leave::create([
                            'emp_id' => \Auth::user()->id,
                            'emp_name' => \Auth::user()->fname . ' ' . \Auth::user()->lname,
                            'type' => $request->type,
                            'total_days' => $total_days + 1,
                            'dates' => $date->format('Y-m-d'),
                            'status' => 'PENDING',
                            'note' => $request->note,

                            'sick_leaves_left' => $request->type == 'Paid sick leave' ?  $remaining_sick - $total_days - 1 :  $remaining_sick ,
                            'vacation_leaves_left' => $request->type == 'Paid vacation leave' ?  $remaining_vac - $total_days - 1 :  $remaining_vac,
                        ]);

                    }

                }

            }

        }else{

                foreach ($period as $date) {

                    Leave::create([
                        'emp_id' => \Auth::user()->id,
                        'emp_name' => \Auth::user()->fname . ' ' . \Auth::user()->lname,
                        'type' => $request->type,
                        'total_days' => $total_days + 1,
                        'dates' => $date->format('Y-m-d'),
                        'status' => 'PENDING',
                        'note' => $request->note,
                        'sick_leaves_left' => 0 ,
                        'vacation_leaves_left' => 0,
                    ]);

                }

        }


        return redirect()->back()->with('success', 'Request for leave applied.');

    }

    public function workversaryUpdate(Request $request){
        
        $user = User::where('id', $request->workversary_id)->first();
        $new_workversary = Carbon::parse($user->workversary)->addYears(1);
        User::where('id', $request->workversary_id)->update([
            'workversary' => $new_workversary->format('Y-m-d')
        ]);

        if(Leave::where('emp_id', $request->workversary_id)->exists()){

            $leave = Leave::where('emp_id', $request->workversary_id)->orderBy('id', 'desc')->first();

            if($leave->sick_leaves_left == 0 && $leave->vacation_leaves_left == 0){
                Leave::where('emp_id', $request->workversary_id)->where('id', $leave->id)->update([
                    'sick_leaves_left' => 3,
                    'vacation_leaves_left' => 3,
                ]);
            }else{
                Leave::where('emp_id', $request->workversary_id)->where('id', $leave->id)->update([
                    'sick_leaves_left' => $leave->sick_leaves_left + 1,
                    'vacation_leaves_left' => $leave->vacation_leaves_left + 1,
                ]);
            }

        }

        Announcements::create([
            'title' => 'Happy workversary, ' . $user->fname. ' ' . $user->lname . '!',
            'content' => 'Today is his/her workversary, send him/her a message! ♥',
        ]);

        return redirect()->back()->with('success', 'Greeting has been sent, triggers has been done.');
    }

    public function deacEmployee(Request $request, $id){
        // return $request;
        User::where('id', $request->id)->update([
            'active' => '0'
        ]);

        Deactivation::create([
            'emp_id' => $request->id,
            'reason' => $request->reason,
        ]);

        return redirect()->route('homedashboard')->with('success', 'Employee has been deactivated and moved to archive.');
    }

    public function reacEmployee(Request $request, $id){
        // return $request;
        User::where('id', $request->id)->update([
            'active' => '1'
        ]);

        Deactivation::where('emp_id', $request->id)->delete();

        return redirect()->route('homedashboard')->with('success', 'Employee has been reactivated and removed from archive.');
    }

    public function employeeArchive(){
        // $users = User::where('active', 0)->paginate(20);
        $users = \DB::table('users')
                        ->join('deactivations', 'users.id', '=', 'deactivations.emp_id')
                        ->select('users.*', 'deactivations.*')
                        ->where('users.active','0')
                        ->paginate(20);
                        // dd($users);
        return view('admin.archive', compact('users'));
    }

    public function filterEmployeeList(Request $request){
        // return $request;
        if($request->position != 'ALL'){
            $users = User::where('active','1')->where('department', $request->department)->orderBy('date_hired' ,'asc')->get();
        }else{
            $users = User::where('active','1')->get();
        }

        $all_users = [];
        foreach ($users as $key => $value){
            $all_users[$key]['id'] = $value->id;
            $all_users[$key]['name'] = $value->fname . ' ' . $value->lname;
            $all_users[$key]['position'] = $value->position;
            $all_users[$key]['date_hired'] = date("F d, Y", strtotime($value->date_hired));
            $all_users[$key]['monthly_rate'] = $value->monthly_rate;

        }
        return $all_users;
    }

    public function searchByName(Request $request){
        $users = \DB::table('users')
                ->where('fname', 'like', '%'.$request->name.'%')
                ->where('active', 1)
                ->orderBy('date_hired' ,'asc')
                ->get();

        $all_users = [];
        foreach ($users as $key => $value){
            $all_users[$key]['id'] = $value->id;
            $all_users[$key]['name'] = $value->fname . ' ' . $value->lname;
            $all_users[$key]['position'] = $value->position;
            $all_users[$key]['date_hired'] = date("F d, Y", strtotime($value->date_hired));
            $all_users[$key]['monthly_rate'] = $value->monthly_rate;
        }
        return $all_users;    
    }

    public function alphabetEmployeeList(Request $request){
        // return $request;
        $users = User::where('active','1')->orderBy('fname' ,'asc')->get();

        $all_users = [];
        foreach ($users as $key => $value){
            $all_users[$key]['id'] = $value->id;
            $all_users[$key]['name'] = $value->fname . ' ' . $value->lname;
            $all_users[$key]['position'] = $value->position;
            $all_users[$key]['date_hired'] = date("F d, Y", strtotime($value->date_hired));
            $all_users[$key]['monthly_rate'] = $value->monthly_rate;

        }
        return $all_users;
    }

    public function employeeGreetBirthday(Request $request){
        
        request()->validate([
            'hbd_content' => 'required',
        'hbd_title' => 'required'
        ]);

        Announcements::create([
            'title' => $request->hbd_title,
            'content' => $request->hbd_content,
        ]);

        return redirect()->back()->with('success', 'Birthday greeting announced.');
    }

    public function awardsAndRfi(){
        $users = User::where('active','1')->orderBy('date_hired', 'asc')->paginate(100);
        $showDep = Department::distinct()->get('department_name');
        $showPos = Department::all();
        $commend_icons = Icon::where('category', 'commendation')->get();
        $rfi_icons = Icon::where('category', 'rfi')->get();
        $certs = Certificate::where('type','ADMIN')->get();

        return view('admin.awards', compact('users', 'showDep', 'showPos', 'commend_icons','rfi_icons','certs'));
    }

    public function employeeAttendance(){
        // dd('123');
        $position = Department::distinct()->get(['position']);
        $roster_today = \DB::table('users')
                        ->join('schedules', 'users.id', '=', 'schedules.emp_id')
                        ->select('users.*', 'schedules.*')
                        ->where(date('l'), 'ON')
                        ->where('users.active','1')
                        ->get();

        $leave_today = \DB::table('users')
                        ->join('leaves', 'users.id', '=', 'leaves.emp_id')
                        ->select('users.*', 'leaves.*')
                        ->where('dates', date('Y-m-d'))
                        ->where('leaves.status','=', 'APPROVED')
                        ->where('users.active','1')
                        ->get();


        $upcoming_ws = [];
        $upcoming_ws = $roster_today->map(function($r){
            $leave_today = \DB::table('users')
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

        $ots = \DB::table('users')
            ->join('overtimes', 'users.id', '=', 'overtimes.emp_id')
            ->select('users.*', 'overtimes.*')
            ->where('users.active','1')
            ->where('overtimes.status','PENDING')
            ->get();

        return view('employee.attendance', compact('position','attend_today','leave_today','ots'));
    }

    public function testPDF(Request $request){

        $certs = Certificate::where('id', $request->certid)->first();
        // dd($certs);
        $data = [
            'name' => $certs->emp_name,
            'date' => $certs->cert_date_awarded,
            'title' => $certs->cert_title,
            ];

            $pdf = PDF::loadView('employee.certs', $data)->setPaper('a4', 'landscape');
            return $pdf->stream('employee.certs');
    }

}
