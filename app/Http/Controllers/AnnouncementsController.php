<?php

namespace App\Http\Controllers;

use App\Announcements;
use App\User;
use Illuminate\Http\Request;
use App\Department;

class AnnouncementsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ann = Announcements::orderBy('id','desc')->paginate(10);

        $emps = User::where('active', 1)->get();
        $department = Department::distinct()->get('department_name');
        $position = Department::distinct()->get('position');

        return view('admin.announcement', compact('ann','emps','department','position'));
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
            'title' => 'required',
            'content' => 'required',
        ]);

        Announcements::create([
            'emp_id' => $request->emp_id,
            'department' => $request->department,
            'position' => $request->department != null ? null : $request->position,
            'title' => $request->title,
            'content' => $request->content,
        ]);


        return redirect()->back()->with('success', 'Announcement posted.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Announcements  $announcements
     * @return \Illuminate\Http\Response
     */
    public function show(Announcements $announcements)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Announcements  $announcements
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcements $announcements)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Announcements  $announcements
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request;

        Announcements::where('id', $request->edit_id)->update([
            'emp_id' => $request->emp_id,
            'department' => $request->department,
            'position' => $request->department != null ? null : $request->position,
            'title' => $request->edit_title,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Announcement has been edited.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Announcements  $announcements
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Announcements::where('id', $id)->delete();

        return "Announcement deleted";
    }
}
