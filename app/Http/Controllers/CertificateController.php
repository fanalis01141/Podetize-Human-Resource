<?php

namespace App\Http\Controllers;

use App\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd('123');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->type =='GIVEN'){

            $image = Certificate::where('cert_title', $request->certificate)->first();
            $filenametostore = $image->cert_image;
            // dd($request);

            Certificate::create([
                'emp_id' => $request->emp_id,
                'cert_title' => $request->certificate,
                'cert_image' => $filenametostore,
                'type' => 'GIVEN', //AWARDED BY ADMIN
                'cert_date_awarded' => $request->date_given,
                'emp_name' => $request->emp_name
            ]);

            return redirect()->back()->with('success', 'Certificate given to ' . $request->emp_name);

        }else{
            if($request->has('profilePic')){
    
                $filenamewithExtension = $request->file('profilePic')->getClientOriginalName();
                $filename = pathinfo($filenamewithExtension, PATHINFO_FILENAME);
                $extension = $request->file('profilePic')->getClientOriginalExtension();
                $filenametostore = $filename.'_'.time().'.'.$extension;
    
                $path = $request->file('profilePic')->storeAs('public/profilePic', $filenametostore);
    
                Certificate::create([
                    'emp_id' => '-',
                    'cert_title' => $request->name_of_cert,
                    'cert_image' => $filenametostore,
                    'type' => 'ADMIN', //ADDED BY ADMIN
                    'cert_date_awarded' => '-',
                    'emp_name' => '-'
                ]);
                
                return redirect()->back()->with('success', 'certificate added');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function show(Certificate $certificate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function edit(Certificate $certificate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Certificate $certificate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Certificate $certificate)
    {
        //
    }
}
