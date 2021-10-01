<?php

namespace App\Http\Controllers;

use App\Overtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;


class OvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $users = Overtime::where('status', 'PENDING')->paginate(50);
        $users = DB::table('users')
                        ->join('overtimes', 'users.id', '=', 'overtimes.emp_id')
                        ->select('users.*', 'overtimes.*')
                        ->where('users.active','1')
                        ->where('overtimes.status','PENDING')
                        ->orderBy('overtimes.id','desc')
                        ->paginate(50);
        return view('admin.overtime', compact('users'));
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
        // return ($request);
        $user_info = User::find($request->member_id);
        // return $user_info;
        $user = Overtime::where('status','PENDING')->where([
            'emp_id'=> $request->member_id,
            'date' => $request->date_of_ot
        ])->first();

        if($user != null || $user != ""){
            return redirect()->back()->withErrors('Overtime request existing for date: ' . date("F jS, Y", strtotime($request->date_of_ot)));
        }else{
            Overtime::create([
                'emp_id' => $request->member_id,
                'task_or_eps' => $request->task_or_eps,
                'department' => $user_info->department,
                'complete_name' => $user_info->fname . ' ' . $user_info->lname,
                'date' => $request->date_of_ot,
                'hours' => $request->hours,
                'for_approval' => $request->for_approval,
                'status' => 'PENDING'
            ]);
            return redirect()->back()->with('success', 'Overtime requested for date: ' . date("F jS, Y", strtotime($request->date_of_ot)));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // return $request;
        Overtime::where('id', $request->id)->where('emp_id', $request->emp_id)->delete();
    }

    public function accept(Request $request){
        Overtime::where('emp_id', $request->emp_id)->where('id', $request->id)->update([
            'status' => 'ACCEPTED',
        ]);

        return "accepted from server";
    }

    public function reject(Request $request){
        Overtime::where('emp_id', $request->emp_id)->where('id', $request->id)->update([
            'status' => 'REJECTED',
        ]);

        return "rejected from server";
    }

    public function filter(Request $request){
        // return $request;
        $users = Overtime::whereBetween('date', [$request->from, $request->to])->where('status', $request->filter)->get();
        // return redirect(('admin.overtime')->with( ['users' => ($users)] ));
        return $users;


    }
}
