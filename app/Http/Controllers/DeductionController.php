<?php

namespace App\Http\Controllers;

use App\Deduction;
use App\User;
use App\CashAdvance;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $data['users'] = User::where('active', 1)->get();
        $data['deduct'] = Deduction::all();
        $data['tables'] = DB::table('users')->select([
                        'users.name', 'users.id', 'deductions.phic', 'deductions.sss', 'deductions.pagibig' ,
                        ])->join('deductions','deductions.emp_id','=','users.id')->where('active', 1)
                        ->get();
        return view('admin.deductions', compact('data'));
        // return $data;
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

    public function editDeduction(Request $request)
    {
        $id = $request->id;
        $phic = $request->phic;
        $sss = $request->sss;
        $pagibig = $request->pagibig;
        Deduction::where('emp_id',$id)->update(['phic' => $phic, 'sss' => $sss, 'pag-ibig' => $pagibig]);
        return back()->with('success', 'Deductions Successfully Updated');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function edit(Deduction $deduction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deduction $deduction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deduction $deduction)
    {
        //
    }

    public function showCA(){
        $data = array();
        $data['users'] = User::where('active', 1)->get();
        $data['CA'] = CashAdvance::where('request','!=', 0)->get();
        $data['tables'] = DB::table('users')->select([
                        'users.name', 'cashadvances.request','cashadvances.emp_id', 'cashadvances.ded_per_pay', 'cashadvances.months_to_pay','cashadvances.date_issued'
                        ])->join('cashadvances','cashadvances.emp_id','=','users.id')->where('active', 1)
                        ->get();

        // return $data['tables'];

        return view('admin.cash-advance', compact('data'));
    }

    public function storeCA($id, Request $request){

        // if($request->get('request') > 100000){
        //     return redirect()->back()->withErrors($request->get('request') . ' as requested amount is invalid.');
        // }elseif($request->get(''))

        $user = User::findOrFail($request->employees);
        $check = CashAdvance::where('emp_id', $request->employees)->where('request','!=', '0')->get();
        if($check->isEmpty()){
            CashAdvance::create([
                'emp_id' => $request->get('employees'),
                'reason' => $request->get('reason'),
                'request' => $request->get('request'),
                'ded_per_pay' => $request->get('ded_per_pay'),
                'date_issued' => $request->get('date_issued'),
            ]);
            return redirect()->back()->with('success', 'Registered ' . $request->get('request') . ' Cash Advance to: ' . $user->name);
        }else{
            $record = CashAdvance::where('emp_id', $request->employees)->first();
            $new_amount = (int)$record->request + (int)$request->get('request');
            CashAdvance::where('emp_id', $request->employees)->update([
                'request' => $new_amount,
                'date_issued' => $request->get('date_issued'),
                'ded_per_pay' => $request->get('ded_per_pay'),
            ]);
            return redirect()->back()->with('success', 'Updated ' . $request->get('request') . ' Cash Advance to: ' . $user->name);

        }
    }

    public function editCA(Request $req, $id){
        // return $req;
        CashAdvance::where('emp_id',$req->myid)->update([
            'request' => $req->get('request'),
            'ded_per_pay' => $req->get('deduction')
        ]);
        return redirect()->back()->with('success','Edit succesful.');
    }

    public function deleteCA(Request $req){
        // return $req;
        CashAdvance::where('emp_id', $req->myid)->delete();
        $user = User::find($req->myid);
        // return $user;
        

        return redirect()->back()->with('success', 'Deleted cash advance record of ' . $user->name);

    }
}
