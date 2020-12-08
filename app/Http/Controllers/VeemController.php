<?php

namespace App\Http\Controllers;

use App\Veem;
use Illuminate\Http\Request;

class VeemController extends Controller
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
     * @param  \App\Veem  $veem
     * @return \Illuminate\Http\Response
     */
    public function show(Veem $veem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Veem  $veem
     * @return \Illuminate\Http\Response
     */
    public function edit(Veem $veem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Veem  $veem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Veem::where('user_id', $id)->update([
            'veem_email' => $request->veem_email,
            'complete_bank_account_name' => $request->complete_bank_account_name,
            'veem_mobile_number' => $request->veem_mobile_number
        ]);

         return redirect()->back()->with('success', 'Veem details updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Veem  $veem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Veem $veem)
    {
        //
    }
}
