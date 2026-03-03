<?php

namespace App\Modules\Subscription\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Digikraaft\Paystack\Paystack;
use Digikraaft\Paystack\Plan;
use Illuminate\Support\Facades\Http;




class PaystackPlanController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        Paystack::setApiKey(config('paystacksubscription.secret', env('PAYSTACK_SECRET')));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $planss = Plan::list()->data;
        $plans = collect($planss)->reject(function ($plan) {
            return $plan->is_deleted;
        });
        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
      
       $data = [
                    "name" => $request->name, 
                    "interval" => $request->interval,
                    "currency" => $request->currency,
                    "description" => $request->description,   
                    "amount" => (int) $request->amount
                ];

        $plan = Plan::create($data);
        return redirect()->back();


        // $plan = Http https://api.paystack.co/plan
        // $headers = [
        //     'Content-Type' =>  'application/json',
        //     'Authorization' =>  'Bearer sk_test_753dd1f3358d2561cbdb455a3e95198dbf9012ad'
        // ];

        // $data = [
        //             "name" => "Monthly Retainer", 
        //             "interval" => "monthly",
        //             "currency" => "NGN",
        //             "description" => "description",   
        //             "amount" => 500000
        //         ];

        // $res = Http::withHeaders($headers)->post('https://api.paystack.co/plan', $data );
        // dd($res);

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {   
        //retrieve plan code
        $plan = Plan::fetch($id)->data;
        return view('admin.plans.single', compact('plan'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {   
        $data = [
            "name" => $request->name, 
            "interval" => $request->interval,
            "currency" => $request->currency,
            "description" => $request->description,   
            "amount" => (int) $request->amount
        ];

        $plan = Plan::update($id, $data);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        
        $headers = [
            'Content-Type' =>  'application/json',
            'Authorization' =>  'Bearer sk_test_753dd1f3358d2561cbdb455a3e95198dbf9012ad'
        ];
        $res = Http::withHeaders($headers)->delete('https://api.paystack.co/plan/' . $id );
        return redirect()->back();
        
    }
}
