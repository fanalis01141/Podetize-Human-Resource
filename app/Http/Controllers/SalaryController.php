<?php

namespace App\Http\Controllers;

use App\Salary;
use Illuminate\Http\Request;
use App\User;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
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
    public function store(Request $request, $id)
    {
        return $id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $salary = Salary::where('emp_id', $id)->orderBy('created_at','desc')->get();
        return view ('admin.salary', compact('user','salary'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function edit(Salary $salary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

            //Old rate is bigger than new rate
        if($user->daily_rate > $request->new_daily &&
            $user->bi_weekly_rate > $request->new_bi &&
            $user->monthly_rate > $request->new_monthly){

            $add_or_ded_daily = ($user->daily_rate) - ($request->new_daily);
            $add_or_ded_biweekly = ($user->bi_weekly_rate) - ($request->new_bi);
            $add_or_ded_monthly = ($user->monthly_rate) - ($request->new_monthly);
            $status = 'DEDUCTION';
        }else{
            //New rate is bigger than old rate
            $add_or_ded_daily = ($request->new_daily) - ($user->daily_rate);
            $add_or_ded_biweekly = ($request->new_bi) - ($user->bi_weekly_rate);
            $add_or_ded_monthly =  ($request->new_monthly) - ($user->monthly_rate);
            $status = 'INCREASE';
        }


        User::where('id', $id)->update([
            'daily_rate' => $request->new_daily,
            'bi_weekly_rate' => $request->new_bi,
            'monthly_rate' => $request->new_monthly,
        ]);

        Salary::create([
            'emp_id' => $id,
            'daily_rate' => $request->new_daily,
            'bi_weekly_rate' => $request->new_bi,
            'monthly_rate' => $request->new_monthly,
            'add_or_ded_daily' => $status == 'INCREASE' ? "+". $add_or_ded_daily : "-". $add_or_ded_daily,
            'add_or_ded_biweekly' => $status == 'INCREASE' ? "+". $add_or_ded_biweekly : "-". $add_or_ded_biweekly,
            'add_or_ded_monthly' => $status == 'INCREASE' ? "+". $add_or_ded_monthly : "-". $add_or_ded_monthly,
            'status' => $status,
            'note' => $request->note,
        ]);
        return redirect()->back()->with('success', 'New salary has been recorded');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salary $salary)
    {
        // return $salary;
        $clean_  = preg_replace('/[^a-zA-Z0-9_ -]/s','', $salary->add_or_ded_biweekly);
        return $result;

    }

    public function updateSalary(Request $request)
    {
        // return $request;
        $user = User::find($request->emp_id);

            //Old rate is bigger than new rate
        if($user->daily_rate > $request->update_daily &&
            $user->bi_weekly_rate > $request->update_bi &&
            $user->monthly_rate > $request->update_monthly){

            $add_or_ded_daily = ($user->daily_rate) - ($request->update_daily);
            $add_or_ded_biweekly = ($user->bi_weekly_rate) - ($request->update_bi);
            $add_or_ded_monthly = ($user->monthly_rate) - ($request->update_monthly);
            $status = 'DEDUCTION';
        }else{
            //New rate is bigger than old rate
            $add_or_ded_daily = ($request->update_daily) - ($user->daily_rate);
            $add_or_ded_biweekly = ($request->update_bi) - ($user->bi_weekly_rate);
            $add_or_ded_monthly =  ($request->update_monthly) - ($user->monthly_rate);
            $status = 'INCREASE';
        }

        User::where('id', $request->emp_id)->update([
            'daily_rate' => $request->update_daily,
            'bi_weekly_rate' => $request->update_bi,
            'monthly_rate' => $request->update_monthly,
        ]);

        Salary::where('id', $request->update_id)->update([
            'daily_rate' => $request->update_daily,
            'bi_weekly_rate' => $request->update_bi,
            'monthly_rate' => $request->update_monthly,
            'add_or_ded_daily' => $status == 'INCREASE' ? "+". $add_or_ded_daily : "-". $add_or_ded_daily,
            'add_or_ded_biweekly' => $status == 'INCREASE' ? "+". $add_or_ded_biweekly : "-". $add_or_ded_biweekly,
            'add_or_ded_monthly' => $status == 'INCREASE' ? "+". $add_or_ded_monthly : "-". $add_or_ded_monthly,
            'status' => $status,
            'note' => $request->update_note,
        ]);
        
        return redirect()->back()->with('success', 'New salary has been updated');    
    
    }
}
