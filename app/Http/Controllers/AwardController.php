<?php

namespace App\Http\Controllers;

use App\Award;
use App\Announcements;
use Illuminate\Http\Request;
use App\User;

class AwardController extends Controller
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
        $request->validate([
            'award_icon' => 'required',
            'content' => 'required',
            'title' => 'required'
        ]); 

        $user = User::find($request->empid);

        if($request->award_status == 'commend'){
            Announcements::create([
                'title' => 'Commendation: '.$request->title,
                'content' => $request->content,
            ]);

            Award::create([
                'emp_id' => $request->empid,
                'award_title' => $request->title,
                'award_content' => $request->content,
                'icon' => $request->award_icon,
                'type' => $request->award_status
            ]);
        }else{
            Award::create([
                'emp_id' => $request->empid,
                'award_title' => $request->title,
                'award_content' => $request->content,
                'icon' => $request->award_icon,
                'type' => $request->award_status
            ]);

            Announcements::create([
                'emp_id' => $request->empid,
                'title' => 'RFI: ' . $request->title,
                'content' => $request->content,
            ]);
        }

        return back()->with("success", \Str::upper($request->award_status) . " given to " . $user->fname . ' ' . $user->lname);



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $cmd = Award::where('emp_id', $request->emp_id)->where('type','commend')->get();
        return $cmd;
    }


    public function showrfi(Request $request)
    {
        $rfi = Award::where('emp_id', $request->emp_id)->where('type','rfi')->get();
        return $rfi;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function edit(Award $award)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Award $award)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Award  $award
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Award::where('emp_id', $request->emp_id)->where('award_title', $request->title)->where('award_content', $request->content)->delete();
        return "Award removed.";
    }
}
