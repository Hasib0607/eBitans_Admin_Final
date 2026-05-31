<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\RequiredInformation;
use App\Models\RequiredInformationForContent;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;

class RequiredInformationController extends Controller
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

        $request->validate(
            [
                'question_1' => 'required',
                'question_2' => 'required',
            ],
            [
                'question_1.required' => 'আপনার ব্যবসার ধরন এবং ক্রেতাদের ব্যপারে জানান।',
                'question_2.required' => 'আপনার ব্যবসায়িক যোগাযোগ নাম্বার দিন।',
            ]
          );

        $user=Auth::user()->id;
        $user_type=Auth::user()->type;
        if ($user_type=="admin") {
            $customer=Customer::where('uid', $user)->first();
            $store_id=$customer->active_store;
            $customer_id=$customer->id;
        } elseif ($user_type=='staff') {
            $staff=Staff::where('uid', Auth::user()->id)->first();
            $store_id=$staff->store_id;
            $customer_id=$staff->customer_id;
        }

        $RequiredFile = [];

        if ($request->question_5) {
            foreach ($request->question_5 as $key => $value) {
                $information = Auth::user()->id . time(). $key .'.'.$value->extension();
                $value->move(public_path('clientContent/RequiredInformation'), $information);
                $RequiredFile[] = $information;
            }

        }

$RequiredFile = json_encode($RequiredFile);

        $requiredInformation = RequiredInformation::firstOrNew(['store_id' =>  $store_id]);
//  dd($requiredInformation);
        $requiredInformation->client_id     = Auth::user()->id;
        $requiredInformation->store_id      = $store_id;
        $requiredInformation->question_1    = $request->question_1;
        $requiredInformation->question_2    = $request->question_2;
        $requiredInformation->question_3    = $request->question_3;
        $requiredInformation->question_4    = $request->question_4;
        $requiredInformation->question_5    = $RequiredFile;
        $requiredInformation->save();

        return back()->with('success', 'আপনার তথ্য গুলো সংরক্ষণ করা হয়েছে।');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function individualContentStore(Request $request)
    {

        $user=Auth::user()->id;
        $user_type=Auth::user()->type;
        if ($user_type=="admin") {
            $customer=Customer::where('uid', $user)->first();
            $store_id=$customer->active_store;
            $customer_id=$customer->id;
        } elseif ($user_type=='staff') {
            $staff=Staff::where('uid', Auth::user()->id)->first();
            $store_id=$staff->store_id;
            $customer_id=$staff->customer_id;
        }


        $RequiredInformation1 = [];
        $RequiredInformation2 = [];

        if ($request->question_9 != null) {
            foreach ($request->question_9 as $key => $value) {
                $information = Auth::user()->id . time(). $key .'.'.$value->extension();
                $value->move(public_path('clientContent/RequiredInformation/forContent'), $information);
                $RequiredInformation1[] = $information;
            }
        }
        $RequiredInformation1 = json_encode($RequiredInformation1);

        if ($request->question_10 != null) {
            foreach ($request->question_10 as $key => $value) {
                $information = Auth::user()->id . time(). $key .'.'.$value->extension();
                $value->move(public_path('clientContent/RequiredInformation/forContent'), $information);
                $RequiredInformation2[] = $information;
            }
        }
        $RequiredInformation2 = json_encode($RequiredInformation2);


        $requiredInformation = new RequiredInformationForContent();

        $requiredInformation->client_id     = Auth::user()->id;
        $requiredInformation->store_id      = $store_id;
        $requiredInformation->question_6    = $request->question_6;
        $requiredInformation->question_7    = $request->question_7;
        $requiredInformation->question_8    = $request->question_8;
        $requiredInformation->question_9    = $RequiredInformation1;
        $requiredInformation->question_10   = $RequiredInformation2;
        $requiredInformation->question_11    = $request->question_11;
        $requiredInformation->question_12    = $request->question_12;

        $requiredInformation->save();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RequiredInformation  $requiredInformation
     * @return \Illuminate\Http\Response
     */
    public function individualContentDelete($id)
    {
        //
        $data = RequiredInformationForContent::find($id);
        $data -> delete();

        return back()->with('success', 'আপনার তথ্যটি মুছে ফেলা হয়েছে।');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RequiredInformation  $requiredInformation
     * @return \Illuminate\Http\Response
     */
    public function show(RequiredInformation $requiredInformation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RequiredInformation  $requiredInformation
     * @return \Illuminate\Http\Response
     */
    public function edit(RequiredInformation $requiredInformation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RequiredInformation  $requiredInformation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RequiredInformation $requiredInformation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RequiredInformation  $requiredInformation
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequiredInformation $requiredInformation)
    {
        //
    }
}
