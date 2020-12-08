<?php

namespace App\Http\Controllers;

use App\Icon;
use Illuminate\Http\Request;

class IconController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
    public function store(Request $request)
    {
        $request->validate([
            'class' => 'required|unique:icons|string',
            'category' => 'required|string',
        ]);

        Icon::create([
            'class' => $request->class,
            'category' => $request->category
        ]);

        return redirect()->back()->with('success-icon', 'Icon added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Icon  $icon
     * @return \Illuminate\Http\Response
     */
    public function show(Icon $icon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Icon  $icon
     * @return \Illuminate\Http\Response
     */
    public function edit(Icon $icon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Icon  $icon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Icon $icon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Icon  $icon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Icon::where('class', $request->class)->delete();
        return "Icon deleted";
    }
}
