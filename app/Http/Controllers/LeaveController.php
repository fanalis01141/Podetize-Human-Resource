<?php

namespace App\Http\Controllers;

use App\Leave;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leavers = DB::table('users')
                        ->join('leaves', 'users.id', '=', 'leaves.emp_id')
                        ->select('users.*', 'leaves.*')
                        ->where('users.active','1')
                        ->where('leaves.status', '!=' ,'DELETED')
                        ->orderBy('leaves.id', 'desc')
                        ->paginate(25);
        return view('admin.leave', compact('leavers'));
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
    public function store(Request $request){

        $user = User::where('id', $request->emp_id)->first();
        $diffYears = \Carbon\Carbon::now()->diffInYears($user->date_hired);

        if($diffYears == 0){

            Leave::create([
                'emp_id' => $request->emp_id,
                'emp_name' => $user->fname . ' ' . $user->lname,
                'type' => 'Marked as absent by admin.',
                'total_days' => 1,
                'dates' => date('Y-m-d'),
                'status' => 'APPROVED',
                'sick_leaves_left' => 0 ,
                'vacation_leaves_left' => 0 ,
            ]);

        }else{

            if ( ! Leave::where('emp_id', $request->emp_id)->exists() ){

                Leave::create([
                    'emp_id' => $request->emp_id,
                    'emp_name' => $user->fname . ' ' . $user->lname,
                    'type' => 'Marked as absent by '. Auth::user()->fname. ' ' . Auth::user()->lname,
                    'total_days' => 1,
                    'dates' => date('Y-m-d'),
                    'status' => 'APPROVED',
                    'sick_leaves_left' => $diffYears + 2,
                    'vacation_leaves_left' => $diffYears + 2,
                ]);

            }else{

                $user = Leave::where('emp_id', $request->emp_id)->orderBy('id', 'desc')->first();

                $remaining_sick = $user->sick_leaves_left;
                $remaining_vac = $user->vacation_leaves_left;

                Leave::create([
                    'emp_id' => $request->emp_id,
                    'emp_name' => $user->fname . ' ' . $user->lname,
                    'type' => 'Marked as absent by '. Auth::user()->fname. ' ' . Auth::user()->lname,
                    'total_days' => 1,
                    'dates' => date('Y-m-d'),
                    'status' => 'APPROVED',
                    'sick_leaves_left' => $remaining_sick,
                    'vacation_leaves_left' => $remaining_vac,
                ]);
            }
        }

        return 'Employee has been marked absent.';

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // $data = array();
        // $data['user'] = User::findOrFail($id);
        // $data['leave'] = Leave::where('emp_id',$id)->get();
        // return view('admin.leave', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function edit(Leave $leave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Leave $leave)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Leave  $leave
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $leave = Leave::where('emp_name', $request->name)->orderBy('id','desc')->first();


        if($request->type == 'Paid vacation leave' && $request->status == 'DELETED'){
            Leave::where('id', $leave->id)->update([
                'vacation_leaves_left' => $leave->vacation_leaves_left + 1,
            ]);

            Leave::where('id', $request->id)->update([
                'status' => $request->status,
            ]);

        }elseif($request->type == 'Paid sick leave' && $request->status == 'DELETED'){
            Leave::where('id', $leave->id)->update([
                'sick_leaves_left' => $leave->sick_leaves_left + 1,
            ]);       
            
            Leave::where('id', $request->id)->update([
                'status' => $request->status,
            ]);
        }else{
            Leave::where('id', $request->id)->update([
                'status' => $request->status,
            ]);
        }

    }

    public function acceptleave(Request $request){
        $leave = Leave::where('emp_name', $request->name)->orderBy('id','desc')->first();


        if($request->type == 'Paid vacation leave' && $request->status == 'REJECTED'){
            Leave::where('id', $leave->id)->update([
                'vacation_leaves_left' => $leave->vacation_leaves_left + 1,
            ]);

            Leave::where('id', $request->id)->update([
                'status' => $request->status,
            ]);

        }elseif($request->type == 'Paid sick leave' && $request->status == 'REJECTED'){
            Leave::where('id', $leave->id)->update([
                'sick_leaves_left' => $leave->sick_leaves_left + 1,
            ]);       
            
            Leave::where('id', $request->id)->update([
                'status' => $request->status,
            ]);
        }else{
            Leave::where('id', $request->id)->update([
                'status' => $request->status,
            ]);
        }


        return $request->name . "'s leave has been " . $request->status;
    }

    public function countLeave(Request $request){
        $leaves = count(Leave::where('status', 'PENDING')->get());
        return $leaves;
    }

    public function filterLeave(Request $request){
        $leaves = Leave::whereBetween('dates', [$request->from, $request->to])->orderBy('dates','desc')->get();

        // // $leaves = Leave::where('dates', $request->date)->get();
        // return $leaves;
        // // return $leaves;


        $all_leaves = [];
        foreach ($leaves as $key => $value){
            $all_leaves[$key]['id'] = $value->id;
            $all_leaves[$key]['name'] = $value->emp_name;
            $all_leaves[$key]['type'] = $value->type;
            $all_leaves[$key]['note'] = $value->note;

            $all_leaves[$key]['date'] = date("F d, Y", strtotime($value->dates));
            $all_leaves[$key]['status'] = $value->status;
        }    

        return $all_leaves;
    }

    public function leaveSearchByName(Request $request){
        $leaves = Leave::where('emp_name', 'like', '%'.$request->name.'%')->where('status','!=','DELETED')->orderBy('dates','desc')->get();

        // // $leaves = Leave::where('dates', $request->date)->get();
        // return $leaves;
        // // return $leaves;


        $all_leaves = [];
        foreach ($leaves as $key => $value){
            $all_leaves[$key]['id'] = $value->id;
            $all_leaves[$key]['name'] = $value->emp_name;
            $all_leaves[$key]['type'] = $value->type;
            $all_leaves[$key]['note'] = $value->note;

            $all_leaves[$key]['date'] = date("F d, Y", strtotime($value->dates));
            $all_leaves[$key]['status'] = $value->status;
        }    

        return $all_leaves;
    }



}
