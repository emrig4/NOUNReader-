<?php

namespace App\Modules\Subscription\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Subscription\Models\Pricing;
use App\Modules\Subscription\Events\PaystackWebhookEvent;


class PricingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $basic = Pricing::where('slug', 'basic')->first();
        $standard = Pricing::where('slug', 'standard')->first();
        $pro = Pricing::where('slug', 'pro')->first();

        return view('admin.pricings.index', compact('basic', 'standard', 'pro'));
    }

    /**
     * Show the form to pay a pricing plan.
     * @return Renderable
     */
    public function pay(Request $request)
    {   
        $method = $request->query('method');
        $item = '';
        if($method === 'paystack'){
             return view('payment::paystackpay', ['item' => $item ]);
        }
       
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($slug){
        
        // event(new PaystackWebhookEvent('abc@gmail.com'));

        $pricing = Pricing::where('slug', $slug)->first();
        return view('admin.pricings.single', compact('pricing'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('pricing::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Pricing $pricing)
    {   
        $pricing->update( $request->all() );
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
